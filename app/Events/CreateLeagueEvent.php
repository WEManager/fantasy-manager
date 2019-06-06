<?php

namespace App\Events;

use App\Tournament;
use App\TournamentGame;
use App\TournamentGroup;
use App\TournamentParticipant;
use App\TournamentStanding;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CreateLeagueEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param Tournament $tournament
     */
    public function __construct(Tournament $tournament)
    {
        switch ($tournament->type) {
            case 'league':

                // create one group for the league table
                $group = TournamentGroup::create([
                    'name' => $tournament->name,
                    'tournament_id' => $tournament->id,
                ]);

                // shuffle competitors
                $players = TournamentParticipant::where('tournament_id', $tournament->id)->pluck('user_id')->toArray();
                shuffle($players);

                // set competitors into league table
                foreach ($players as $player) {
                    TournamentStanding::create([
                        'user_id' => $player,
                        'group_id' => $group->id,
                    ]);
                }


                // create games schedule
                $this->generateGameSchedule($players, $group);


                break;
            case 'groups':

                // create x amount of groups and one league table each
                $groups = [];
                for ($i = 0; $i < $tournament->groups; $i++) {
                    $groups[] = TournamentGroup::create([
                        'name' => __('Group') . ' ' . ($i + 1),
                        'tournament_id' => $tournament->id,
                    ]);
                }

                // shuffle competitors
                $players = TournamentParticipant::where('tournament_id', $tournament->id)->pluck('user_id')->toArray();
                shuffle($players);

                // set competitors into the different groups
                $i = 0;
                foreach ($players as $player) {
                    if (isset($groups[$i])) {
                        TournamentStanding::create([
                            'user_id' => $player,
                            'group_id' => $groups[$i]->id,
                        ]);
                    }

                    // If group is the last one, start over from first group
                    if ((count($groups) - 1) === $i) {
                        $i = 0;
                    } else {
                        $i++;
                    }
                }


                // create games schedule
                foreach ($groups as $group) {
                    $players = TournamentStanding::where('group_id', $group->id)->pluck('user_id');
                    $this->generateGameSchedule($players, $group);
                }

                break;
            case 'playoffs':

                //

                break;
        }
    }

    public function generateGameSchedule($players, $group, $meetings = 2)
    {
        // Count the number of teams
        $amountOfParticipants = count($players);
        // If number of team is odd, add a ghost-team...
        if ($amountOfParticipants % 2 != 0) {
            $amountOfParticipants++;
            $ghost = $amountOfParticipants;
        }
        // Number of rounds
        $rounds = ($amountOfParticipants - 1) * $meetings;

        // Loop the rounds...
        for ($r = 1; $r <= $rounds; $r++) {
            // For each round loop teams / 2 ... it takes 2 to tango...
            for ($s = 1; $s <= $amountOfParticipants / 2; $s++) {
                // algorithm to take each team "backwards"
                $hometeam = ($s == 1) ? 1 : (($r + $s - 2) % ($amountOfParticipants - 1) + 2);
                // algorithm to prevent home and awayteam to be the same
                $awayteam = ($amountOfParticipants - 1 + $r - $s) % ($amountOfParticipants - 1) + 2;
                // let the venue change after each round... homegame... then awaygame..
                if ($r % 2) {
                    $swap = $hometeam;
                    $hometeam = $awayteam;
                    $awayteam = $swap;
                }
                // never print the ghost-team..
                if (!isset($ghost) || (($hometeam != $ghost) && ($awayteam != $ghost))) {
                    TournamentGame::create([
                        'group_id' => $group->id,
                        'first_player_id' => $players[($hometeam - 1)],
                        'second_player_id' => $players[($awayteam - 1)],
                    ]);
                }
            }
        }
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}

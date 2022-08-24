<?php

namespace App\Events;

use App\Models\Season;
use App\Models\Tournament;
use App\Models\TournamentGame;
use App\Models\TournamentGroup;
use App\Models\TournamentStanding;
use App\Models\TournamentParticipant;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

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
                $clubs = TournamentParticipant::where('tournament_id', $tournament->id)->pluck('club_id')->toArray();
                shuffle($clubs);

                // set competitors into league table
                foreach ($clubs as $club) {
                    TournamentStanding::create([
                        'club_id' => $club,
                        'group_id' => $group->id,
                    ]);
                }


                // create games schedule
                $this->generateGameSchedule($clubs, $group);


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
                $clubs = TournamentParticipant::where('tournament_id', $tournament->id)->pluck('club_id')->toArray();
                shuffle($clubs);

                // set competitors into the different groups
                $i = 0;
                foreach ($clubs as $club) {
                    if (isset($groups[$i])) {
                        TournamentStanding::create([
                            'club_id' => $club,
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
                    $clubs = TournamentStanding::where('group_id', $group->id)->pluck('club_id');
                    $this->generateGameSchedule($clubs, $group);
                }

                break;
            case 'playoffs':

                //

                break;
        }
    }

    private function generateGameSchedule($players, $group, $meetings = 2)
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

        // Count playing days
        $season = getCurrentSeason();

        $restingDaysBeforeStart = 1;
        $restingDaysAfterSeason = 7;

        $startDate = strtotime($season->start_time . ' +' . $restingDaysBeforeStart . ' days');
        $endDate = strtotime($season->end_time . ' -' . $restingDaysAfterSeason . ' days');

        $daysBetween = (int)round(($endDate - $startDate) / 86400);
        //$daysBetween = 47;

        $oneRoundEvery = $daysBetween / $rounds;

        // Loop the rounds...
        for ($r = 1; $r <= $rounds; $r++) {

            $daysAfterStart = round(($r - 1) * $oneRoundEvery);
            $roundDate = date('Y-m-d 19:00:00', strtotime(date('Y-m-d', $startDate) . ' +' . $daysAfterStart . ' days'));

            // If it is weekend, set other time
            if (date('N', strtotime($roundDate)) >= 6) {
                $roundDate = date('Y-m-d 16:00:00', strtotime(date('Y-m-d', $startDate) . ' +' . $daysAfterStart . ' days'));
            }

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
                // never add the ghost-team to database..
                if (!isset($ghost) || (($hometeam != $ghost) && ($awayteam != $ghost))) {
                    TournamentGame::create([
                        'group_id' => $group->id,
                        'hometeam_id' => $players[($hometeam - 1)],
                        'awayteam_id' => $players[($awayteam - 1)],
                        'hometeam_squad' => $group->tournament->team,
                        'awayteam_squad' => $group->tournament->team,
                        'type' => '1', // 90min only
                        'status' => '0', // Not started
                        'start_time' => $roundDate,
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

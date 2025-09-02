<?php

declare(strict_types=1);

namespace App\Events;

use App\Enums\FixtureStatus;
use App\Enums\FixtureType;
use App\Enums\TournamentType;
use App\Models\Club;
use App\Models\Fixture;
use App\Models\Tournament;
use App\Models\TournamentGroup;
use App\Models\TournamentStanding;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class CreateLeagueEvent
{
    use Dispatchable,
        InteractsWithSockets,
        SerializesModels;

    public function __construct(Tournament $tournament)
    {
        $clubs = $tournament
            ->clubsParticipants
            ->shuffle();

        switch ($tournament->type) {
            case TournamentType::LEAGUE:
                // create one group for the league table
                $group = TournamentGroup::create([
                    'name' => $tournament->name,
                    'tournament_id' => $tournament->id,
                ]);

                foreach ($clubs as $club) {
                    TournamentStanding::create([
                        'club_id' => $club->id,
                        'group_id' => $group->id,
                    ]);
                }

                $this->generateGameSchedule($clubs, $group);

                break;
            case TournamentType::GROUPS:
                // create x amount of groups and one league table each
                $groups = [];

                for ($i = 0; $i < $tournament->groups; $i++) {
                    $groups[] = TournamentGroup::create([
                        'name' => __('Group').' '.($i + 1),
                        'tournament_id' => $tournament->id,
                    ]);
                }

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
            case TournamentType::PLAYOFFS:
                break;
        }
    }

    /**
     * @param  Collection<int, Club>  $clubs
     */
    private function generateGameSchedule(
        Collection $clubs,
        TournamentGroup $group,
        int $meetings = 2
    ): void {
        // Count the number of teams
        $amountOfParticipants = $clubs->count();

        // If number of team is odd, add a ghost-team...
        if ($amountOfParticipants % 2 !== 0) {
            $amountOfParticipants++;
            $ghost = $amountOfParticipants;
        }

        // Number of rounds
        $rounds = ($amountOfParticipants - 1) * $meetings;

        // Count playing days
        $season = getCurrentSeason();

        $restingDaysBeforeStart = 1;
        $restingDaysAfterSeason = 7;

        $startDate = strtotime($season->start_time.' +'.$restingDaysBeforeStart.' days');
        $endDate = strtotime($season->end_time.' -'.$restingDaysAfterSeason.' days');

        $daysBetween = (int) round(($endDate - $startDate) / 86400);
        // $daysBetween = 47;

        $oneRoundEvery = $daysBetween / $rounds;

        // Loop the rounds...
        for ($r = 1; $r <= $rounds; $r++) {

            $daysAfterStart = round(($r - 1) * $oneRoundEvery);
            $roundDate = date('Y-m-d 19:00:00', strtotime(date('Y-m-d', $startDate).' +'.$daysAfterStart.' days'));

            // If it is weekend, set other time
            if (date('N', strtotime($roundDate)) >= 6) {
                $roundDate = date('Y-m-d 16:00:00', strtotime(date('Y-m-d', $startDate).' +'.$daysAfterStart.' days'));
            }

            // For each round loop teams / 2 ... it takes 2 to tango...
            for ($s = 1; $s <= $amountOfParticipants / 2; $s++) {
                // algorithm to take each team "backwards"
                $hometeam = ($s === 1) ? 1 : (($r + $s - 2) % ($amountOfParticipants - 1) + 2);

                // algorithm to prevent home and awayteam to be the same
                $awayteam = ($amountOfParticipants - 1 + $r - $s) % ($amountOfParticipants - 1) + 2;

                // let the venue change after each round... homegame... then awaygame..
                if ($r % 2) {
                    $swap = $hometeam;
                    $hometeam = $awayteam;
                    $awayteam = $swap;
                }

                // never add the ghost-team to database..
                if (! isset($ghost) || (($hometeam !== $ghost) && ($awayteam !== $ghost))) {
                    Fixture::create([
                        'group_id' => $group->id,
                        'hometeam_id' => $clubs[($hometeam - 1)]->id,
                        'awayteam_id' => $clubs[($awayteam - 1)]->id,
                        // 'hometeam_squad' => $group->tournament->team,
                        // 'awayteam_squad' => $group->tournament->team,
                        'type' => FixtureType::REGULAR_TIME_ONLY,
                        'status' => FixtureStatus::NOT_STARTED,
                        'start_time' => $roundDate,
                    ]);
                }
            }
        }
    }
}

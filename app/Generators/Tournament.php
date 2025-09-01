<?php

declare(strict_types=1);

namespace App\Generators;

use App\Console\Commands\GenerateClub;
use App\Enums\TournamentType;
use App\Models\Club;
use App\Events\CreateLeagueEvent;
use App\Models\Season;
use App\Models\Tournament as ModelsTournament;
use App\Models\TournamentQualification;

final class Tournament {
    public static function create(array $input): int {
        $props = [
            'type' => TournamentType::LEAGUE->value,
            'teams' => 16,
            'groups' => 1,
            'champions' => 0,
            'promoted' => 0,
            'qualify_up' => 0,
            'qualify_down' => 0,
            'relegated' => 0,
            'generate_teams' => true,
        ];

        foreach ($input as $key => $value) {
            $props[$key] = $value;
        }

        $tournament = ModelsTournament::create([
            'name' => $props['name'],
            'type' => TournamentType::from($props['type']),
            'participants' => $props['teams'],
            // 'recurring_every_of_year' => 1,
            'groups' => $props['groups'],
        ]);

        $season = Season::whereDate('start_time', '<=', date('Y-m-d'))->first();
        $season->tournaments()->attach($tournament->id);

        $qualification_date = date('Y-m-d H:i:s', strtotime($season->start_time));

        $teamsInGroup = $props['teams'] / $props['groups'];

        for ($i = 1; $i <= $teamsInGroup; $i++) {
            $status = 'ended';
            $seasonEnded = true;

            if ($i <= $props['champions']) {
                $status = 'champions';
            } elseif ($i <= $props['promoted']) {
                $status = 'promoted';
            } elseif ($i <= $props['promoted'] + $props['qualify_up']) {
                $status = 'qualify_up';
                $seasonEnded = false;
            } elseif ($i > $teamsInGroup - $props['relegated']) {
                $status = 'relegated';
            } elseif ($i > $teamsInGroup - $props['relegated'] - $props['qualify_down']) {
                $status = 'qualify_down';
                $seasonEnded = false;
            }

            TournamentQualification::create([
                'tournament_id' => $tournament->id,
                'season_id' => $season->id,
                'position' => $i,
                'qualified_for_id' => $tournament->id, // TODO
                'season_ended' => $seasonEnded, // TODO
                'status' => $status, // TODO
                'qualification_date' => $qualification_date, // TODO: Should be date efter last game of league
            ]);
            }

            // Only generate teams and schedule if set
            if ($props['generate_teams']) {

            for ($i = 0; $i < $props['teams']; $i++) {      
                $genereator = new GenerateClub();

                $genereator->createClub('sv');
            }

            $clubs = Club::orderBy('id', 'desc')
                ->take($props['teams'])
                ->pluck('id')
                ->toArray();

            $tournament->clubsParticipants()->attach($clubs, [
                'season_id' => $season->id,
            ]);

            event(new CreateLeagueEvent($tournament));
        }

        return $tournament->id;
    }
}

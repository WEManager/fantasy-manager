<?php

namespace App\Generators;

use App\Club;
use App\Events\CreateLeagueEvent;
use App\Season;
use App\TournamentParticipant;
use App\TournamentQualification;
use App\TournamentSeason;
use Illuminate\Support\Facades\Artisan;

class Tournament
{
    public static function create(array $input)
    {
        $props = [
            'type' => 'league',
            'teams' => 16,
            'team' => 'senior',
            'groups' => 1,
            'champions' => 0,
            'promoted' => 0,
            'qualify_up' => 0,
            'qualify_down' => 0,
            'relegated' => 0,
            'generate_teams' => true,
            'locale' => 'sv',
        ];

        foreach ($input as $key => $value) {
            $props[$key] = $value;
        }

        $tournament = \App\Tournament::create([
            'name' => $props['name'],
            'nationality' => nationalityBasedOnLocale($props['locale']),
            'type' => $props['type'],
            'participants' => $props['teams'], // amount of teams
            'recurring_every_of_year' => 1,
            'team' => $props['team'], // senior, u21 or u19
            'groups' => $props['groups'], // amount of groups
        ]);

        $season = Season::whereDate('start_time', '<=', date('Y-m-d'))->first();

        $tournamentSeason = TournamentSeason::create([
            'tournament_id' => $tournament->id,
            'season_id' => $season->id,
        ]);

        $qualification_date = date('Y-m-d H:i:s', strtotime($season->end_time . ' - 13 days'));

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
            Artisan::call('club:generate', ['amount' => $props['teams'], 'locale' => $props['locale']]);

            $clubs = Club::where('locale', nationalityBasedOnLocale($props['locale']))->orderBy('id', 'desc')->take($props['teams'])->pluck('id');

            foreach ($clubs as $club) {
                TournamentParticipant::create([
                    'club_id' => $club,
                    'season_id' => $season->id,
                    'tournament_id' => $tournament->id,
                ]);
            }

            event(new CreateLeagueEvent($tournament));
        }

        return $tournament->id;
    }
}

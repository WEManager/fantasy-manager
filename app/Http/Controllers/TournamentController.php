<?php

namespace App\Http\Controllers;

use App\Club;
use App\Events\CreateLeagueEvent;
use App\Tournament;
use App\TournamentGroup;
use App\TournamentParticipant;
use App\TournamentStanding;
use App\User;
use App\Http\Requests\StoreTournament;
use Illuminate\Support\Facades\Cache;

class TournamentController extends Controller
{
    public function index()
    {
        return view('tournaments.index', ['tournaments' => Tournament::all()]);
    }

    public function create()
    {
        $clubs = Club::doesnthave('tournament')->get();

        return view('tournaments.create', ['clubs' => $clubs]);
    }

    public function store(StoreTournament $tournament)
    {
        $createdTournament = Tournament::create([
            'name' => $tournament->name,
            'team' => $tournament->team,
            'type' => $tournament->competitionType,
            'groups' => $tournament->groups,
            'playoffs' => $tournament->playOffs,
            'participants' => count($tournament->selectedClubs),
            'recurring_every_of_year' => $tournament->recurringEveryOfYear,
            'proceeding_to_playoffs' => $tournament->proceedingToPlayoffs,
        ]);

        foreach ($tournament->selectedClubs as $club) {
            TournamentParticipant::create([
                'club_id' => $club,
                'tournament_id' => $createdTournament->id,
                'season_id' => $tournament->season,
            ]);
        }

        event(new CreateLeagueEvent($createdTournament));

        return redirect('show_tournament')->with(['tournament' => $createdTournament->slug]);
    }

    public function show($locale, Tournament $tournament)
    {
        if (!Cache::has('standings-' . $tournament->id)) {
            $groupIds = [];
            foreach ($tournament->tournamentGroups as $group) {
                $groupIds[] = $group->id;
            }

            $participatingClubs = [];
            $standings = TournamentStanding::whereIn('group_id', $groupIds)->orderBy('points', 'desc')->orderByRaw('(scored - conceded) desc')->get();

            foreach ($tournament->tournamentGroups as $group) {
                $table = [];
                foreach ($standings as $standing) {
                    if ($group->id === $standing->group_id) {
                        $table[] = $standing;
                        $participatingClubs[$standing->club->id] = $standing->club;
                    }
                }
                $group->standings = $table;
            }

            $view = ['tournament' => $tournament, 'participating_clubs' => $participatingClubs, 'position_status' => $tournament->qualifications->pluck('status')];

            Cache::put('standings-' . $tournament->id, $view, 120);
        } else {
            $view = Cache::get('standings-' . $tournament->id);
        }

        return view('tournaments.show')->with($view);
    }
}

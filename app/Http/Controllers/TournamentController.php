<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\Tournament;
use App\Models\TournamentStanding;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

use function Sodium\compare;

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

    // public function store(StoreTournament $tournament)
    // {
    //     $createdTournament = Tournament::create([
    //         'name' => $tournament->name,
    //         'team' => $tournament->team,
    //         'type' => $tournament->competitionType,
    //         'groups' => $tournament->groups,
    //         'playoffs' => $tournament->playOffs,
    //         'participants' => count($tournament->selectedClubs),
    //         'recurring_every_of_year' => $tournament->recurringEveryOfYear,
    //         'proceeding_to_playoffs' => $tournament->proceedingToPlayoffs,
    //     ]);

    //     foreach ($tournament->selectedClubs as $club) {
    //         TournamentParticipant::create([
    //             'club_id' => $club,
    //             'tournament_id' => $createdTournament->id,
    //             'season_id' => $tournament->season,
    //         ]);
    //     }

    //     event(new CreateLeagueEvent($createdTournament));

    //     return redirect('tournament.show')->with(['t' => $createdTournament->slug]);
    // }

    public function showOld(Tournament $tournament) {        
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

    public function show(Tournament $tournament) {
        $tournament->load(['clubsParticipants', 'groups.standings', 'qualifications']);

        return Inertia::render('Tournament/Show', compact('tournament'));
    }
}

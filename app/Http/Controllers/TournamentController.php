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
            'season' => $tournament->season,
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
            ]);
        }

        event(new CreateLeagueEvent($createdTournament));

        return redirect('show_tournament')->with(['createdTournament' => $createdTournament]);
    }

    public function show($locale, Tournament $tournament)
    {
        //$groups = TournamentGroup::where('tournament_id', $tournament->id)->get();

        //$groupIds = $groups->pluck('id')->toArray();
        $standings = TournamentStanding::whereIn('group_id', $tournament->tournamentGroups->pluck('id'))->orderBy('points', 'desc')->orderByRaw('(scored - conceded) desc')->get();

        foreach ($tournament->tournamentGroups as $group) {
            $table = [];
            foreach ($standings as $standing) {
                if ($group->id === $standing->group_id) {
                    $table[] = $standing;
                }
            }
            $group->standings = $table;
        }

        return view('tournaments.show')->with(['tournament' => $tournament]);
    }
}

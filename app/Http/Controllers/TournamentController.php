<?php

namespace App\Http\Controllers;

use App\Club;
use App\Events\CreateLeagueEvent;
use App\Tournament;
use App\TournamentParticipant;
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
        $clubs = Club::all();

        return view('tournaments.create', ['clubs' => $clubs]);
    }

    public function store(StoreTournament $tournament)
    {
        $createdTournament = Tournament::create([
            'name' => $tournament->name,
            'type' => $tournament->competitionType,
            'groups' => $tournament->groups,
            'playoffs' => $tournament->playOffs,
            'participants' => count($tournament->selectedClubs),
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

    public function show(Tournament $tournament)
    {
        return view('tournaments.show')->with(['tournament' => $tournament]);
    }
}

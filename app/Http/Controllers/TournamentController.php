<?php

namespace App\Http\Controllers;

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
        $players = User::all();

        return view('tournaments.create', ['players' => $players]);
    }

    public function store(StoreTournament $tournament)
    {
        $createdTournament = Tournament::create([
            'name' => $tournament->name,
            'type' => $tournament->competitionType,
            'groups' => $tournament->groups,
            'playoffs' => $tournament->playOffs,
            'participants' => count($tournament->selectedPlayers),
            'proceeding_to_playoffs' => $tournament->proceedingToPlayoffs,
        ]);

        foreach ($tournament->selectedPlayers as $player) {
            TournamentParticipant::create([
                'user_id' => $player,
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

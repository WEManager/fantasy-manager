<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\Tournament;
use Inertia\Inertia;

use function Sodium\compare;

class TournamentController extends Controller {
  public function index() {
    return view('tournaments.index', ['tournaments' => Tournament::all()]);
  }

  public function show(Tournament $tournament) {
    $tournament->load(['clubsParticipants', 'groups.standings', 'qualifications']);

    return Inertia::render('Tournament/Show', compact('tournament'));
  }
}

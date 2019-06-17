<?php

namespace App\Http\Controllers;

use App\Club;
use App\TournamentGame;
use Illuminate\Http\Request;

class ClubController extends Controller
{
    public function index()
    {
        $clubs = Club::paginate();

        return view('clubs.index')->with(compact('clubs'));
    }

    public function show(Club $club)
    {
        $games = TournamentGame::where('hometeam_id', $club->id)->orWhere('awayteam_id', $club->id)->paginate();

        return view('clubs.show')->with(['club' => $club, 'games' => $games]);
    }
}

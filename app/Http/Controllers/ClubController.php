<?php

namespace App\Http\Controllers;

use App\Club;
use App\TournamentGame;
use Illuminate\Http\Request;

class ClubController extends Controller
{
    public function index($locale)
    {
        $clubs = Club::paginate();

        return view('clubs.index')->with(['clubs' => $clubs]);
    }

    public function show($locale, Club $club)
    {
        return view('clubs.show')->with(['club' => $club]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\TournamentGame;
use Illuminate\Http\Request;

class ClubController extends Controller
{
    public function index()
    {
        $clubs = Club::paginate();

        return view('clubs.index')->with(['clubs' => $clubs]);
    }

    public function show(Club $club)
    {
        return view('clubs.show')->with(['club' => $club]);
    }

    public function edit(Club $club)
    {
        return view('clubs.edit')->with(['club' => $club]);
    }

    public function store()
    {
        return redirect()->back();
    }
}

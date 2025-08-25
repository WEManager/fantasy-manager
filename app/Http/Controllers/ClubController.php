<?php

namespace App\Http\Controllers;

use App\Models\Club;
use Inertia\Inertia;

class ClubController extends Controller
{
  public function index()
  {
    $response = Club::paginate(40);

    return Inertia::render('Club/Index', compact('response'));
  }

  public function show(Club $club)
  {
    $club->load([
      'homegames.group.tournament',
      'homegames.hometeam',
      'homegames.awayteam',
      'awaygames.group.tournament',
      'awaygames.hometeam',
      'awaygames.awayteam'
    ]);

    return Inertia::render('clubs/show/page', compact('club'));
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

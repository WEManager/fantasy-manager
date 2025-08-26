<?php

namespace App\Http\Controllers;

use App\Models\Club;
use Inertia\Inertia;

class SquadController extends Controller
{
  public function __invoke(Club $club)
  {
    $squad = request()->query('squad', 'senior');

    $type = getContractType($squad);

    $players = $club->players($type)
      ->select([
        'players.id',
        'players.nation_fifa_id',
        'players.full_name',
        'players.know_as',
        'players.age',
        'players.form',
      ])
      ->with(['nation:fifa_id,name'])
      ->get();

    // Ensure nation data is available
    $players->each(function ($player) {
      if (!$player->nation) {
        $player->nation = (object) ['name' => 'Unknown', 'fifa_id' => 0];
      }
    });

    // Load club with manager relationship
    $club->load('manager');

    if ($squad == 'senior') $squad = '';

    return Inertia::render('clubs/squad/page', compact('club', 'players', 'squad'));
  }
}

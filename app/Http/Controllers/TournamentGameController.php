<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\TournamentGame;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TournamentGameController extends Controller {
  public function showOld(TournamentGame $game) {
    $homeTeamLineup = $game->homeLineup;
    $awayTeamLineup = $game->awayLineup;

    $hometeam = [];
    for($i=1; $i < 12; $i++) {
      $hometeam[$homeTeamLineup->{'position_' . $i}] = Player::find($homeTeamLineup->{'player_' . $i});
    }

    $awayteam = [];
    for($i=1; $i < 12; $i++) {
      $awayteam[$awayTeamLineup->{'position_' . $i}] = Player::find($awayTeamLineup->{'player_' . $i});
    }

    return view('games.show')->with(compact('game', 'hometeam', 'awayteam'));
  }

  public function show(TournamentGame $game) {
    $game->load(['awayTeam', 'homeTeam', 'group']);

    for($i=1; $i < 12; $i++) {
      $game->homeLineup->{'player_' . $i} = Player::find($game->homeLineup->{'player_' . $i});

      $game->awayLineup->{'player_' . $i} = Player::find($game->awayLineup->{'player_' . $i});
    }

    return Inertia::render('Game/Show', compact('game'));
  }
}

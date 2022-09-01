<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\TournamentGame;
use Illuminate\Http\Request;

class TournamentGameController extends Controller {
  public function show(TournamentGame $game) {
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
}

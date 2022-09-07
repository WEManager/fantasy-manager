<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\TournamentGame;
use Inertia\Inertia;

class TournamentGameController extends Controller {
  public function show(TournamentGame $game) {
    $game->load(['awayTeam', 'homeTeam', 'group']);

    for($i=1; $i < 12; $i++) {
      $game->homeLineup->{'player_' . $i} = Player::find($game->homeLineup->{'player_' . $i});

      $game->awayLineup->{'player_' . $i} = Player::find($game->awayLineup->{'player_' . $i});
    }

    return Inertia::render('Game/Show', compact('game'));
  }
}

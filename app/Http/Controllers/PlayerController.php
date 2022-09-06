<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Inertia\Inertia;

class PlayerController extends Controller {
  public function index() {
    $response = Player::with('club')->paginate();

    return Inertia::render('Player/Index', compact('response'));
  }

  public function show(Player $player) {
    $player->append(['technical', 'mental', 'physical', 'goalkeeping']);

    return Inertia::render('Player/Show', compact('player'));
  }
}

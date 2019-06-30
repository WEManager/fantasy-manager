<?php

namespace App\Http\Controllers;

use App\TournamentGame;
use Illuminate\Http\Request;

class TournamentGameController extends Controller
{
    public function show($locale, TournamentGame $game)
    {
        if (\request()->wantsJson()) {
            $game->playerOneEvents;
            $game->playerTwoEvents;

            return $game;
        }

        return view('games.show')->with(['game' => $game]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\Tournament;
use Illuminate\Http\Request;

class PageController extends Controller {
    public function show($slug) {
        if ($tournament = Tournament::where('slug', $slug)->first()) {
            $tournamentController = new TournamentController();

            return $tournamentController->show($tournament);
        } elseif ($club = Club::with(['homegames', 'awaygames'])->where('slug', $slug)->first()) {
            $clubController = new ClubController();

            return $clubController->show($club);
        }
    }
}

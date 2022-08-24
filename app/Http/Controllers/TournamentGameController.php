<?php

namespace App\Http\Controllers;

use App\Models\Lineup;
use App\Models\Person;
use App\Models\TournamentGame;
use Illuminate\Http\Request;

class TournamentGameController extends Controller {
    public function show($locale, TournamentGame $game) {
        $homeTeamLineup = Lineup::where('club_id', $game->hometeam_id)
            ->where('team', $game->hometeam_squad)
            ->first();
        $awayTeamLineup = Lineup::where('club_id', $game->awayteam_id)
            ->where('team', $game->awayteam_squad)
            ->first();

        $hometeam = [];
        for($i=1;$i<12;$i++) {
            $hometeam[$homeTeamLineup->{'position_' . $i}] = Person::find($homeTeamLineup->{'player_' . $i});
        }

        $awayteam = [];
        for($i=1;$i<12;$i++) {
            $awayteam[$awayTeamLineup->{'position_' . $i}] = Person::find($awayTeamLineup->{'player_' . $i});
        }

        return view('games.show')->with(compact('game', 'hometeam', 'awayteam'));
    }
}

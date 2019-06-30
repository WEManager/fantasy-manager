<?php

namespace App\Http\Controllers;

use App\Lineup;
use App\Person;
use App\TournamentGame;
use Illuminate\Http\Request;

class TournamentGameController extends Controller
{
    public function show($locale, TournamentGame $game)
    {
        $hometeam = Lineup::where('club_id', $game->hometeam_id)->where('team', $game->hometeam_squad)->first();
        $awayteam = Lineup::where('club_id', $game->awayteam_id)->where('team', $game->awayteam_squad)->first();

        $homeLineup = [];
        for($i=1;$i<12;$i++) {
            $homeLineup[$hometeam->{'position_' . $i}] = Person::find($hometeam->{'player_' . $i});
        }

        $awayLineup = [];
        for($i=1;$i<12;$i++) {
            $awayLineup[$awayteam->{'position_' . $i}] = Person::find($awayteam->{'player_' . $i});
        }

        return view('games.show')->with(['game' => $game, 'hometeam' => $homeLineup, 'awayteam' => $awayLineup]);
    }
}

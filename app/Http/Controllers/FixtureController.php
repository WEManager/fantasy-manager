<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Fixture;
use App\Models\Player;
use Inertia\Inertia;
use Inertia\Response;

final class FixtureController extends Controller
{
    public function __invoke(Fixture $game): Response
    {
        $game->load(['group.tournament', 'hometeam', 'awayteam', 'gameHappenings']);

        $homeTeamLineup = $game->homeLineup;
        $awayTeamLineup = $game->awayLineup;

        $hometeam = [];
        for ($i = 1; $i < 12; $i++) {
            $hometeam[$homeTeamLineup->{'position_'.$i}] = Player::find($homeTeamLineup->{'player_'.$i});
        }

        $awayteam = [];
        for ($i = 1; $i < 12; $i++) {
            $awayteam[$awayTeamLineup->{'position_'.$i}] = Player::find($awayTeamLineup->{'player_'.$i});
        }

        return Inertia::render('games/show/page', compact('game', 'hometeam', 'awayteam'));
    }
}

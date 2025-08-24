<?php

namespace App\Http\Controllers;

use App\Models;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

class HomeController extends Controller
{
  /**
   * Handle the incoming request.
   */
  public function __invoke(Request $request)
  {
    $tournaments = Models\Tournament::with('clubsParticipants')
      ->get()
      ->map(function ($tournament) {
        // Determina a nacionalidade baseada no locale do primeiro clube participante
        $nationality = $tournament->clubsParticipants->first()?->locale ?? 'unknown';

        return [
          'id' => $tournament->id,
          'slug' => $tournament->slug,
          'name' => $tournament->name,
          'nationality' => $nationality,
          'status' => strtoupper($tournament->status ?? 'NOT_STARTED')
        ];
      })
      ->sortBy('nationality')
      ->values();
    $ongoingGames = $games = Models\TournamentGame::with(['group.tournament', 'hometeam', 'awayteam'])
      ->where('status', '1')
      ->limit(request('limit', 15))
      ->get()
      ->map(function ($game) {
        return [
          'id' => $game->id,
          'hometeam' => [
            'id' => $game->hometeam->id,
            'name' => $game->hometeam->name,
            'colors' => $game->hometeam->colors,
            'slug' => $game->hometeam->slug,
          ],
          'awayteam' => [
            'id' => $game->awayteam->id,
            'name' => $game->awayteam->name,
            'colors' => $game->awayteam->colors,
            'slug' => $game->awayteam->slug,
          ],
          'hometeam_score' => $game->hometeam_score,
          'awayteam_score' => $game->awayteam_score,
          'start_time' => $game->start_time,
          'status' => $game->status,
          'group' => [
            'id' => $game->group->id,
            'name' => $game->group->name,
            'tournament' => [
              'id' => $game->group->tournament->id,
              'name' => $game->group->tournament->name,
              'slug' => $game->group->tournament->slug,
              'nationality' => $game->group->tournament->clubsParticipants->first()?->locale ?? 'unknown',
            ],
          ],
          'gameStatus' => $game->gameStatus,
        ];
      });

    session()->flash('type', 'success');
    session()->flash('message', 'Welcome back!');

    return Inertia::render('home/page', [
      'clubs' => Inertia::defer(fn() => Models\Club::doesntHave('manager')->inRandomOrder()->take(100)->get()),
      'tournaments' => $tournaments,
      'ongoingGames' => $ongoingGames,
    ]);
  }
}

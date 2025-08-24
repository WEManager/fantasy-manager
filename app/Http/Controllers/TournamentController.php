<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\Tournament;
use App\Models\TournamentGame;
use App\Models\TournamentStanding;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

use function Sodium\compare;

class TournamentController extends Controller
{
  public function index()
  {
    return view('tournaments.index', ['tournaments' => Tournament::all()]);
  }

  public function create()
  {
    $clubs = Club::doesnthave('tournament')->get();

    return view('tournaments.create', ['clubs' => $clubs]);
  }

  // public function store(StoreTournament $tournament)
  // {
  //     $createdTournament = Tournament::create([
  //         'name' => $tournament->name,
  //         'team' => $tournament->team,
  //         'type' => $tournament->competitionType,
  //         'groups' => $tournament->groups,
  //         'playoffs' => $tournament->playOffs,
  //         'participants' => count($tournament->selectedClubs),
  //         'recurring_every_of_year' => $tournament->recurringEveryOfYear,
  //         'proceeding_to_playoffs' => $tournament->proceedingToPlayoffs,
  //     ]);

  //     foreach ($tournament->selectedClubs as $club) {
  //         TournamentParticipant::create([
  //             'club_id' => $club,
  //             'tournament_id' => $createdTournament->id,
  //             'season_id' => $tournament->season,
  //         ]);
  //     }

  //     event(new CreateLeagueEvent($createdTournament));

  //     return redirect('tournament.show')->with(['t' => $createdTournament->slug]);
  // }

  public function showOld(Tournament $tournament)
  {
    if (!Cache::has('standings-' . $tournament->id)) {
      $groupIds = [];
      foreach ($tournament->tournamentGroups as $group) {
        $groupIds[] = $group->id;
      }

      $participatingClubs = [];
      $standings = TournamentStanding::whereIn('group_id', $groupIds)->orderBy('points', 'desc')->orderByRaw('(scored - conceded) desc')->get();

      foreach ($tournament->tournamentGroups as $group) {
        $table = [];
        foreach ($standings as $standing) {
          if ($group->id === $standing->group_id) {
            $table[] = $standing;
            $participatingClubs[$standing->club->id] = $standing->club;
          }
        }
        $group->standings = $table;
      }

      $view = ['tournament' => $tournament, 'participating_clubs' => $participatingClubs, 'position_status' => $tournament->qualifications->pluck('status')];

      Cache::put('standings-' . $tournament->id, $view, 120);
    } else {
      $view = Cache::get('standings-' . $tournament->id);
    }

    return view('tournaments.show')->with($view);
  }

  public function show(Tournament $tournament)
  {
    $tournament->load('clubsParticipants');

    return Inertia::render('Tournament/Show', compact('tournament'));
  }

  public function byNation()
  {
    try {
      $tournaments = Tournament::with('clubsParticipants')
        ->get()
        ->map(function ($tournament) {
          // Determina a nacionalidade baseada no locale do primeiro clube participante
          $nationality = $tournament->clubsParticipants->first()?->locale ?? 'unknown';

          return [
            'id' => $tournament->id,
            'slug' => $tournament->slug,
            'name' => $tournament->name,
            'nationality' => $nationality,
            'status' => $tournament->status
          ];
        })
        ->sortBy('nationality')
        ->values();

      return response()->json(['tournaments' => $tournaments]);
    } catch (\Exception $e) {
      return response()->json(['error' => $e->getMessage()], 500);
    }
  }

  public function ongoingGames()
  {
    try {
      $games = TournamentGame::with(['group.tournament', 'hometeam', 'awayteam'])
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

      return response()->json(['games' => $games]);
    } catch (\Exception $e) {
      return response()->json(['error' => $e->getMessage()], 500);
    }
  }
}

<?php

namespace App\Http\Controllers;

use App\Actions\CreateTournamentAction;
use App\Http\Requests\CreateTournamentRequest;
use App\Models\Club;
use App\Models\Tournament;
use App\Models\TournamentGame;
use App\Models\TournamentGroup;
use App\Models\TournamentParticipant;
use App\Models\TournamentStanding;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

class TournamentController extends Controller
{
  public function index()
  {
    return Inertia::render('tournaments/index/page', ['tournaments' => Tournament::all()]);
  }

  public function create()
  {
    $clubs = Club::doesnthave('tournament')->get();

    return Inertia::render('tournaments/create/page', ['clubs' => $clubs]);
  }

  public function store(CreateTournamentRequest $request, CreateTournamentAction $action)
  {
    $request->validated();
    $createdTournament = $action->handle($request);

    return redirect()
      ->route('tournaments.show', $createdTournament->slug)
      ->with('success', 'Torneio criado com sucesso!');
  }

  public function show(Tournament $tournament)
  {
    $date = request()->query('date', date('Y-m-d'));
    $groupId = request()->query('groupId');

    if (!Cache::has('standings-' . $tournament->id)) {
      $groupIds = [];
      foreach ($tournament->tournamentGroups as $group) {
        $groupIds[] = $group->id;
      }

      $participatingClubs = [];
      $standings = TournamentStanding::whereIn('group_id', $groupIds)->orderBy('points', 'desc')->orderByRaw('(scored - conceded) desc')->get();

      $tournamentGroups = [];
      foreach ($tournament->tournamentGroups as $group) {
        $table = [];
        foreach ($standings as $standing) {
          if ($group->id === $standing->group_id) {
            $table[] = $standing;
            $participatingClubs[$standing->club->id] = $standing->club;
          }
        }
        $tournamentGroups[] = [
          'id' => $group->id,
          'name' => $group->name,
          'standings' => $table,
        ];
      }

      $tournamentData = [
        'id' => $tournament->id,
        'name' => $tournament->name,
        'slug' => $tournament->slug,
        'type' => $tournament->type,
        'groups' => $tournament->groups,
        'playoffs' => $tournament->playoffs,
        'proceeding_to_playoffs' => $tournament->proceeding_to_playoffs,
        'created_at' => $tournament->created_at,
        'updated_at' => $tournament->updated_at,
        'status' => $tournament->status,
        'start_date' => $tournament->start_date,
        'end_date' => $tournament->end_date,
        'tournamentGroups' => $tournamentGroups,
      ];

      $view = [
        'tournament' => $tournamentData,
        'participating_clubs' => $participatingClubs,
        'position_status' => $tournament->qualifications->pluck('status')
      ];

      Cache::put('standings-' . $tournament->id, $view, 120);
    } else {
      $view = Cache::get('standings-' . $tournament->id);
    }

    // Busca jogos para a data e grupo selecionados
    $fixtures = [];
    if ($groupId) {
      $fixtures = TournamentGame::where('group_id', $groupId)
        ->whereDate('start_time', $date)
        ->with(['hometeam', 'awayteam'])
        ->get()
        ->map(function ($fixture) {
          return [
            'id' => $fixture->id,
            'hometeam_id' => $fixture->hometeam_id,
            'awayteam_id' => $fixture->awayteam_id,
            'hometeam_score' => $fixture->hometeam_score,
            'awayteam_score' => $fixture->awayteam_score,
            'start_time' => $fixture->start_time,
            'status' => $fixture->status,
            'gameStatus' => $fixture->gameStatus,
            'hometeam' => [
              'id' => $fixture->hometeam->id,
              'name' => $fixture->hometeam->name,
              'slug' => $fixture->hometeam->slug,
            ],
            'awayteam' => [
              'id' => $fixture->awayteam->id,
              'name' => $fixture->awayteam->name,
              'slug' => $fixture->awayteam->slug,
            ],
          ];
        });
    }

    $view['fixtures'] = $fixtures;
    $view['selectedDate'] = $date;

    return Inertia::render('tournaments/show/page', $view);
  }
}

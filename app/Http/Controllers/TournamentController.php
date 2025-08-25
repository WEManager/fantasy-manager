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

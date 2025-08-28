<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

final class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        // Cache dos torneios otimizado (5 minutos)
        $tournaments = Cache::remember('home-tournaments-optimized', 300, function () {
            return DB::table('tournaments as t')
                ->leftJoin('tournament_groups as grp', 'grp.tournament_id', '=', 't.id')
                ->leftJoin('tournament_games  as tg', 'tg.group_id', '=', 'grp.id')
                ->select([
                    't.id', 't.slug', 't.name',
                    DB::raw("COALESCE((
                        SELECT c.locale
                        FROM tournament_participants tp
                        JOIN clubs c ON c.id = tp.club_id
                        WHERE tp.tournament_id = t.id
                        LIMIT 1
                    ), 'unknown') as nationality"),
                    DB::raw('MIN(tg.start_time) as first_start'),
                    DB::raw('MAX(tg.start_time) as last_start'),
                ])
                ->groupBy('t.id', 't.slug', 't.name')
                ->orderBy('nationality')
                ->orderBy('t.name')
                ->get()
                ->map(function ($t) {
                    $first = $t->first_start ? Carbon::parse($t->first_start) : null;
                    $last = $t->last_start ? Carbon::parse($t->last_start) : null;

                    $status = 'NOT_DECIDED';
                    if ($first) {
                        if ($first->isFuture()) {
                            $status = 'NOT_STARTED';
                        } elseif ($last && $last->copy()->addMinutes(106)->isPast()) {
                            $status = 'ENDED';
                        } else {
                            $status = 'ACTIVE';
                        }
                    }

                    return [
                        'id' => $t->id,
                        'slug' => $t->slug,
                        'name' => $t->name,
                        'nationality' => $t->nationality ?? 'unknown',
                        'status' => $status,
                    ];
                });
        });

        // Jogos em andamento SEM cache para dados sempre atualizados
        $limit = (int) request('limit', 15);

        $ongoingGames = DB::table('tournament_games as tg')
            ->join('tournament_groups as grp', 'tg.group_id', '=', 'grp.id')
            ->join('tournaments as t', 'grp.tournament_id', '=', 't.id')
            ->join('clubs as ht', 'tg.hometeam_id', '=', 'ht.id')
            ->join('clubs as at', 'tg.awayteam_id', '=', 'at.id')
            // LEFT em participantes, mas agregando para não duplicar linhas do jogo
            ->leftJoin('tournament_participants as tp', 't.id', '=', 'tp.tournament_id')
            ->leftJoin('clubs as cp', 'tp.club_id', '=', 'cp.id')
            ->where('tg.status', '1')
            ->groupBy([
                'tg.id', 'tg.hometeam_id', 'tg.awayteam_id', 'tg.hometeam_score', 'tg.awayteam_score', 'tg.start_time', 'tg.status',
                'grp.id', 'grp.name',
                't.id', 't.name', 't.slug',
                'ht.id', 'ht.name', 'ht.colors', 'ht.slug',
                'at.id', 'at.name', 'at.colors', 'at.slug',
            ])
            ->select([
                'tg.id',
                'tg.hometeam_id',
                'tg.awayteam_id',
                'tg.hometeam_score',
                'tg.awayteam_score',
                'tg.start_time',
                'tg.status',
                'grp.id as group_id',
                'grp.name as group_name',
                't.id as tournament_id',
                't.name as tournament_name',
                't.slug as tournament_slug',
                'ht.id as hometeam_id',
                'ht.name as hometeam_name',
                'ht.colors as hometeam_colors',
                'ht.slug as hometeam_slug',
                'at.id as awayteam_id',
                'at.name as awayteam_name',
                'at.colors as awayteam_colors',
                'at.slug as awayteam_slug',
                DB::raw("COALESCE(MIN(cp.locale), 'unknown') as nationality"),
            ])
            ->limit($limit)
            ->get()
            ->map(function ($g) {
                return [
                    'id' => $g->id,
                    'hometeam' => [
                        'id' => $g->hometeam_id,
                        'name' => $g->hometeam_name,
                        'colors' => json_decode($g->hometeam_colors, true),
                        'slug' => $g->hometeam_slug,
                    ],
                    'awayteam' => [
                        'id' => $g->awayteam_id,
                        'name' => $g->awayteam_name,
                        'colors' => json_decode($g->awayteam_colors, true),
                        'slug' => $g->awayteam_slug,
                    ],
                    'hometeam_score' => $g->hometeam_score,
                    'awayteam_score' => $g->awayteam_score,
                    'start_time' => $g->start_time,
                    'status' => $g->status,
                    'group' => [
                        'id' => $g->group_id,
                        'name' => $g->group_name,
                        'tournament' => [
                            'id' => $g->tournament_id,
                            'name' => $g->tournament_name,
                            'slug' => $g->tournament_slug,
                            'nationality' => $g->nationality,
                        ],
                    ],
                    'gameStatus' => $this->getGameStatus($g->status, $g->start_time),
                ];
            });

        session()->flash('type', 'success');
        session()->flash('message', 'Welcome back!');

        return Inertia::render('home/page', [
            // Cache dos clubes disponíveis (10 minutos)
            'clubs' => Inertia::defer(fn () => Cache::remember('home-available-clubs', 600, function () {
                return Models\Club::select('id', 'name', 'colors', 'slug', 'locale')
                    ->whereNotExists(function ($q) {
                        $q->select(DB::raw(1))
                            ->from('manager_contracts')
                            ->whereColumn('manager_contracts.club_id', 'clubs.id');
                    })
                    ->inRandomOrder()
                    ->take(100)
                    ->get();
            })),
            'tournaments' => $tournaments,
            'ongoingGames' => $ongoingGames->values(),
        ]);
    }

    /**
     * Get game status based on status and start time
     */
    private function getGameStatus($status, $startTime)
    {
        switch ($status) {
            case '0':
                if (date('Y-m-d') === date('Y-m-d', strtotime($startTime))) {
                    return 'About to start';
                }
                if (date('Y') !== date('Y', strtotime($startTime))) {
                    return date('j/n H:i Y', strtotime($startTime));
                }

                return date('j/n H:i', strtotime($startTime));
            case '1':
                $minutes = (int) round((time() - strtotime($startTime)) / 60);
                if ($minutes > 45 && $minutes < 60) {
                    return 'Halftime';
                }
                if ($minutes >= 60) {
                    return '<i class="material-icons">timelapse</i> '.($minutes - 15)."'";
                }

                return '<i class="material-icons">timelapse</i> '.$minutes."'";
            case '2': return 'Ended';
            case '3': return 'Waiting for second half';
            case '4': return 'Waiting for extra time';
            case '5': return 'Waiting for penalties';
            case '6': return 'Cancelled';
            case '7': return 'Postponed';
            case '8': return 'Not decided';
            default:  return 'Unknown';
        }
    }
}

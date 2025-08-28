<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\CreateTournamentAction;
use App\Http\Requests\CreateTournamentRequest;
use App\Models\Club;
use App\Models\Tournament;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

final class TournamentController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('tournaments/index/page', [
            'tournaments' => Tournament::all()
        ]);
    }

    public function create(): Response
    {
        $clubs = Club::doesntHave('tournament')->get();

        return Inertia::render('tournaments/create/page', ['clubs' => $clubs]);
    }

    public function store(CreateTournamentRequest $request, CreateTournamentAction $action): RedirectResponse
    {
        $request->validated();
        $createdTournament = $action->handle($request);

        return redirect()
            ->route('tournaments.show', $createdTournament->slug)
            ->with('success', 'Torneio criado com sucesso!');
    }

    public function show(Tournament $tournament): Response
    {
        /** @var string $date */
        $date = (string) request()->query('date', date('Y-m-d'));

        /** @var null|string $groupId */
        $groupId = request()->query('groupId');

        // Carrega só grupos (mínimo necessário)
        $tournament->load(['tournamentGroups:id,tournament_id,name']);
        $groupIds = $tournament->tournamentGroups->pluck('id')->all();

        // 1) Bounds MIN/MAX dos jogos (1 query)
        $bounds = DB::table('tournament_games')
            ->whereIn('group_id', $groupIds)
            ->selectRaw('MIN(start_time) as first_start, MAX(start_time) as last_start')
            ->first();

        $first = $bounds->first_start ? Carbon::parse($bounds->first_start) : null;
        $last  = $bounds->last_start  ? Carbon::parse($bounds->last_start)  : null;

        // 2) Status calculado (sem accessor)
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

        // 3) Standings + Club via JOIN (1 query)
        /** @var \Illuminate\Support\Collection<int,object{
         *   id:int, group_id:int, club_id:int, scored:int, conceded:int, points:int,
         *   club_name:string, club_slug:string
         * }> $standings
         */
        $standings = DB::table('tournament_standings as s')
            ->join('clubs as c', 'c.id', '=', 's.club_id')
            ->whereIn('s.group_id', $groupIds)
            ->select([
                's.id',
                's.group_id',
                's.club_id',
                's.scored',
                's.conceded',
                's.points',
                'c.name as club_name',
                'c.slug as club_slug',
            ])
            ->orderBy('s.points', 'desc')
            ->orderByRaw('(s.scored - s.conceded) desc')
            ->get();

        // 4) Tabelas por grupo (memória)
        $tournamentGroups = $groups->map(
            /** @return array{id:int,name:string,standings:array<int,array<string,mixed>>} */
            function (TournamentGroup $g) use ($standings): array {
                $rows = $standings->where('group_id', $g->id)->map(
                    /** @param object{
                     *   id:int, group_id:int, club_id:int, scored:int, conceded:int, points:int,
                     *   club_name:string, club_slug:string
                     * } $s
                     * @return array<string,mixed>
                     */
                    function (object $s): array {
                        return [
                            'id'        => $s->id,
                            'group_id'  => $s->group_id,
                            'club_id'   => $s->club_id,
                            'scored'    => $s->scored,
                            'conceded'  => $s->conceded,
                            'points'    => $s->points,
                            'club'      => [
                                'id'   => $s->club_id,
                                'name' => $s->club_name,
                                'slug' => $s->club_slug,
                            ],
                        ];
                    }
                )->values()->all();

                return [
                    'id'        => $g->id,
                    'name'      => $g->name,
                    'standings' => $rows,
                ];
            }
        )->values()->all();

        // 5) Clubs participantes (derivado, sem query)
        $participatingClubs = $standings->unique('club_id')->values()->map(fn ($s) => [
            'id' => $s->club_id,
            'name' => $s->club_name,
            'slug' => $s->club_slug,
        ]);

        // 6) Status das qualifications (1 query)
        $positionStatus = DB::table('tournament_qualifications')
            ->where('tournament_id', $tournament->id)
            ->pluck('status');

        // 7) Fixtures por dia/grupo via JOINs (1 query quando houver groupId)
        $fixtures = collect();
        if ($groupId) {
            $fixtures = DB::table('tournament_games as tg')
                ->join('clubs as ht', 'ht.id', '=', 'tg.hometeam_id')
                ->join('clubs as at', 'at.id', '=', 'tg.awayteam_id')
                ->where('tg.group_id', $groupId)
                ->whereDate('tg.start_time', $date)
                ->select([
                    'tg.id', 'tg.group_id', 'tg.hometeam_id', 'tg.awayteam_id',
                    'tg.hometeam_score', 'tg.awayteam_score', 'tg.start_time', 'tg.status',
                    'ht.name as hometeam_name', 'ht.slug as hometeam_slug',
                    'at.name as awayteam_name', 'at.slug as awayteam_slug',
                ])
                ->get()
                ->map(function ($f) {
                    return [
                        'id' => $f->id,
                        'hometeam_id' => $f->hometeam_id,
                        'awayteam_id' => $f->awayteam_id,
                        'hometeam_score' => $f->hometeam_score,
                        'awayteam_score' => $f->awayteam_score,
                        'start_time' => $f->start_time,
                        'status' => $f->status,
                        'gameStatus' => $this->formatGameStatus((string) $f->status, (string) $f->start_time),
                        'hometeam' => ['id' => $f->hometeam_id, 'name' => $f->hometeam_name, 'slug' => $f->hometeam_slug],
                        'awayteam' => ['id' => $f->awayteam_id, 'name' => $f->awayteam_name, 'slug' => $f->awayteam_slug],
                    ];
                })->values();
        }

        // Payload final (array)
        $tournamentArr = [
            'id' => $tournament->id,
            'name' => $tournament->name,
            'slug' => $tournament->slug,
            'type' => $tournament->type,
            'groups' => $tournament->groups,
            'playoffs' => $tournament->playoffs,
            'proceeding_to_playoffs' => $tournament->proceeding_to_playoffs,
            'created_at' => $tournament->created_at,
            'updated_at' => $tournament->updated_at,
            'status' => $status,
            'start_date' => $first?->toDateString(),
            'end_date' => $last?->copy()->addMinutes(106)->toDateString(),
            'tournamentGroups' => $tournamentGroups,
        ];

        return Inertia::render('tournaments/show/page', [
            'tournament' => $tournamentArr,
            'participating_clubs' => $participatingClubs,
            'position_status' => $positionStatus,
            'fixtures' => $fixtures,
            'selectedDate' => $date,
        ]);
    }

    // Helper local para não tocar DB
    private function formatGameStatus(string $status, string $startTime): string
    {
        $dt = Carbon::parse($startTime);

        switch ($status) {
            case '0':
                if ($dt->isSameDay(Carbon::today())) {
                    return 'About to start';
                }
                if ($dt->year !== Carbon::now()->year) {
                    return $dt->format('j/n H:i Y');
                }
                return $dt->format('j/n H:i');

            case '1':
                $minutes = (int) floor($dt->diffInMinutes(Carbon::now()));
                if ($minutes > 45 && $minutes < 60) {
                    return 'Halftime';
                }
                if ($minutes >= 60) {
                    return '<i class="material-icons">timelapse</i> ' . ($minutes - 15) . "'";
                }
                return '<i class="material-icons">timelapse</i> ' . $minutes . "'";

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

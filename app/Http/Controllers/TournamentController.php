<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\FixtureStatus;
use App\Models\Tournament;
use App\Models\TournamentGroup;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

final class TournamentController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('tournaments/index/page', [
            'tournaments' => Tournament::all(),
        ]);
    }

    public function show(Request $request, Tournament $tournament): Response
    {
        /** @var string $date */
        $date = (string) $request->query('date', now()->toDateString());

        /** @var null|string $groupId */
        $groupId = $request->query('groupId');

        // Carrega só grupos (mínimo necessário)
        $tournament->load(['tournamentGroups:id,tournament_id,name']);

        /** @var \Illuminate\Support\Collection<int,int> $groupIds */
        $groupIds = $tournament->tournamentGroups->pluck('id');

        if ($groupIds->isEmpty()) {
            return Inertia::render('tournaments/show/page', [
                'tournament' => [
                    'id' => $tournament->id,
                    'name' => $tournament->name,
                    'slug' => $tournament->slug,
                    'type' => $tournament->type,
                    'groups' => $tournament->groups,
                    'playoffs' => $tournament->playoffs,
                    'proceeding_to_playoffs' => $tournament->proceeding_to_playoffs,
                    'created_at' => $tournament->created_at,
                    'updated_at' => $tournament->updated_at,
                    'status' => FixtureStatus::NOT_DECIDED->value,
                    'start_date' => null,
                    'end_date' => null,
                    'tournamentGroups' => [],
                ],
                'participating_clubs' => [],
                'position_status' => [],
                'fixtures' => [],
                'selectedDate' => $date,
            ]);
        }

        /** @var null|object{first_start: null|string, last_start: null|string} $bounds */
        $bounds = DB::table('fixtures')
            ->whereIn('group_id', $groupIds->all())
            ->selectRaw('MIN(start_time) as first_start, MAX(start_time) as last_start')
            ->first();

        $first = $bounds && $bounds->first_start ? Carbon::parse($bounds->first_start) : null;
        $last = $bounds && $bounds->last_start ? Carbon::parse($bounds->last_start) : null;

        // 2) Status calculado (sem accessor)
        $status = FixtureStatus::NOT_DECIDED;
        if ($first) {
            if ($first->isFuture()) {
                $status = 'NOT_STARTED';
            } elseif ($last && $last->copy()->addMinutes(106)->isPast()) {
                $status = FixtureStatus::ENDED;
            } else {
                $status = FixtureStatus::ACTIVE;
            }
        }

        /** @var \Illuminate\Support\Collection<int,object{
         *   id:int, group_id:int, club_id:int, scored:int, conceded:int, points:int,
         *   club_name:string, club_slug:string
         * }> $standings
         */
        $standings = DB::table('tournament_standings as s')
            ->join('clubs as c', 'c.id', '=', 's.club_id')
            ->whereIn('s.group_id', $groupIds->all())
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
            ->orderByDesc('s.points')
            ->orderByRaw('(s.scored - s.conceded) desc')
            ->get();

        /** @var \Illuminate\Database\Eloquent\Collection<int,TournamentGroup> $groups */
        $groups = $tournament->tournamentGroups;

        $tournamentGroups = $groups->map(
            /** @return array{id:int,name:string,standings:array<int,array<string,mixed>>} */
            function (TournamentGroup $g) use ($standings): array {
                $rows = $standings
                    ->where('group_id', $g->id)
                    ->map(
                        /** @param object{
                         *   id:int, group_id:int, club_id:int, scored:int, conceded:int, points:int,
                         *   club_name:string, club_slug:string
                         * } $s
                         * @return array<string,mixed>
                         */
                        function (object $s): array {
                            return [
                                'id' => $s->id,
                                'group_id' => $s->group_id,
                                'club_id' => $s->club_id,
                                'scored' => $s->scored,
                                'conceded' => $s->conceded,
                                'points' => $s->points,
                                'club' => [
                                    'id' => $s->club_id,
                                    'name' => $s->club_name,
                                    'slug' => $s->club_slug,
                                ],
                            ];
                        }
                    )
                    ->values()
                    ->all();

                return [
                    'id' => $g->id,
                    'name' => $g->name,
                    'standings' => $rows,
                ];
            }
        )->values()->all();

        $participatingClubs = $standings
            ->unique('club_id')
            ->values()
            ->map(fn (object $s) => [
                'id' => $s->club_id,
                'name' => $s->club_name,
                'slug' => $s->club_slug,
            ])
            ->all();

        $positionStatus = DB::table('tournament_qualifications')
            ->where('tournament_id', $tournament->id)
            ->pluck('status')
            ->all();

        $fixtures = [];
        if ($groupId) {
            /** @var \Illuminate\Support\Collection<int,object> $fixturesCol */
            $fixturesCol = DB::table('fixtures as fixture')
                ->join('clubs as ht', 'ht.id', '=', 'fixture.hometeam_id')
                ->join('clubs as at', 'at.id', '=', 'fixture.awayteam_id')
                ->where('fixture.group_id', (int) $groupId)
                ->whereDate('fixture.start_time', $date)
                ->select([
                    'fixture.id', 'fixture.group_id', 'fixture.hometeam_id', 'fixture.awayteam_id',
                    'fixture.hometeam_score', 'fixture.awayteam_score', 'fixture.start_time', 'fixture.status',
                    'ht.name as hometeam_name', 'ht.slug as hometeam_slug',
                    'at.name as awayteam_name', 'at.slug as awayteam_slug',
                ])
                ->get();

            $fixtures = $fixturesCol->map(function (object $f): array {
                $start = (string) $f->start_time;
                $status = (string) $f->status;

                return [
                    'id' => (int) $f->id,
                    'group_id' => (int) $f->group_id,
                    'hometeam_id' => (int) $f->hometeam_id,
                    'awayteam_id' => (int) $f->awayteam_id,
                    'hometeam_score' => (int) $f->hometeam_score,
                    'awayteam_score' => (int) $f->awayteam_score,
                    'start_time' => $start,
                    'status' => $status,
                    'gameStatus' => $this->formatGameStatus($status, $start),
                    'hometeam' => [
                        'id' => (int) $f->hometeam_id,
                        'name' => (string) $f->hometeam_name,
                        'slug' => (string) $f->hometeam_slug,
                    ],
                    'awayteam' => [
                        'id' => (int) $f->awayteam_id,
                        'name' => (string) $f->awayteam_name,
                        'slug' => (string) $f->awayteam_slug,
                    ],
                ];
            })->values()->all();
        }

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

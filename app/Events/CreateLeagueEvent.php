<?php

declare(strict_types=1);

namespace App\Events;

use App\Enums\TournamentType;
use App\Models\Club;
use App\Models\Tournament;
use App\Models\TournamentGroup;
use App\Models\TournamentStanding;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

final class CreateLeagueEvent
{
    use Dispatchable,
        InteractsWithSockets,
        SerializesModels;

    public function __construct(Tournament $tournament)
    {
        $clubs = $tournament
            ->clubsParticipants
            ->shuffle();

        switch ($tournament->type) {
            case TournamentType::LEAGUE:
                // create one group for the league table
                $group = TournamentGroup::create([
                    'name' => $tournament->name,
                    'tournament_id' => $tournament->id,
                ]);

                foreach ($clubs as $club) {
                    TournamentStanding::create([
                        'club_id' => $club->id,
                        'group_id' => $group->id,
                    ]);
                }

                $this->generateGameSchedule($clubs, $group);

                break;
            case TournamentType::GROUPS:
                // create x amount of groups and one league table each
                $groups = [];

                for ($i = 0; $i < $tournament->groups; $i++) {
                    $groups[] = TournamentGroup::create([
                        'name' => 'Group '.($i + 1),
                        'tournament_id' => $tournament->id,
                    ]);
                }

                // set competitors into the different groups
                $i = 0;
                foreach ($clubs as $club) {
                    if (isset($groups[$i])) {
                        TournamentStanding::create([
                            'club_id' => $club,
                            'group_id' => $groups[$i]->id,
                        ]);
                    }

                    // If group is the last one, start over from first group
                    if ((count($groups) - 1) === $i) {
                        $i = 0;
                    } else {
                        $i++;
                    }
                }

                // create games schedule
                foreach ($groups as $group) {
                    $clubs = TournamentStanding::where('group_id', $group->id)->pluck('club_id');

                    $this->generateGameSchedule($clubs, $group);
                }

                break;
            case TournamentType::PLAYOFFS:
                // Para playoffs, criamos grupos iniciais e depois eliminatórias
                $groups = [];

                // Criar grupos iniciais
                for ($i = 0; $i < $tournament->groups; $i++) {
                    $groups[] = TournamentGroup::create([
                        'name' => 'Group '.($i + 1),
                        'tournament_id' => $tournament->id,
                    ]);
                }

                // Distribuir clubes entre os grupos
                $i = 0;
                foreach ($clubs as $club) {
                    if (isset($groups[$i])) {
                        TournamentStanding::create([
                            'club_id' => $club->id,
                            'group_id' => $groups[$i]->id,
                        ]);
                    }

                    // Se é o último grupo, volta para o primeiro
                    if ((count($groups) - 1) === $i) {
                        $i = 0;
                    } else {
                        $i++;
                    }
                }

                // Criar jogos da fase de grupos
                foreach ($groups as $group) {
                    $groupClubs = TournamentStanding::where('group_id', $group->id)
                        ->with('club')
                        ->get()
                        ->pluck('club');

                    $this->generateGameSchedule($groupClubs, $group, 1); // 1 jogo por confronto na fase de grupos
                }

                // Criar grupo para playoffs (eliminatórias)
                // As fixtures de playoffs serão criadas dinamicamente quando necessário
                $playoffsGroup = TournamentGroup::create([
                    'name' => 'Playoffs',
                    'tournament_id' => $tournament->id,
                ]);

                break;
        }
    }

    /**
     * @param  \Illuminate\Support\Collection<int, Club>  $clubs
     */
    private function generateGameSchedule(
        \Illuminate\Support\Collection $clubs,
        TournamentGroup $group,
        int $meetings = 2
    ): void {
        // Reindexa para 0..n-1 e garante ordem estável
        $teams = $clubs->values();

        // Ghost team (null) para n ímpar
        if ($teams->count() % 2 !== 0) {
            $teams->push(null); // null == bye
        }

        $n = $teams->count();                 // total de “slots” (inclui ghost, se houver)
        $roundsPerMeeting = $n - 1;           // algoritmo clássico: n-1 rodadas por turno
        $totalRounds = $roundsPerMeeting * $meetings;

        // Datas (NÃO muta Season). Define janela e step de rodadas.
        $season = getCurrentSeason(); // Carbon nos campos
        $start = (clone $season->start_time)->addDays(1)->startOfDay(); // 1 dia de descanso
        $end = (clone $season->end_time)->subDays(7)->endOfDay();     // 7 dias antes de encerrar

        $totalDays = max(0, $start->diffInDays($end));
        // Passo mínimo de 1 dia entre rodadas (se janela curta)
        $stepDays = max(1, (int) floor(($totalDays) / max(1, $totalRounds - 1)));

        // Horários
        $weekdayKickoff = [19, 0]; // 19:00
        $weekendKickoff = [16, 0]; // 16:00

        $rows = [];

        // Gera todas as rodadas (turno + returno + extras conforme $meetings)
        for ($m = 0; $m < $meetings; $m++) {
            for ($r = 0; $r < $roundsPerMeeting; $r++) {

                // Data da rodada r no meeting m
                /** @var Carbon $roundDate */
                $roundDate = (clone $start)->addDays(($m * $roundsPerMeeting + $r) * $stepDays);
                [$h, $i] = $roundDate->isWeekend() ? $weekendKickoff : $weekdayKickoff;
                $roundDate->setTime($h, $i, 0, 0);

                // Circle method:
                // Índice fixo 0; os demais giram
                // Para cada “mesa” i, define par (home, away)
                for ($iMatch = 0; $iMatch < $n / 2; $iMatch++) {
                    // Home
                    $homeIndex = ($iMatch === 0)
                        ? 0
                        : ((($r + $iMatch) % ($n - 1)) + 1);

                    // Away
                    $awayIndex = ((($n - 1 - $iMatch + $r) % ($n - 1)) + 1);

                    $home = $teams[$homeIndex] ?? null; // null = ghost
                    $away = $teams[$awayIndex] ?? null;

                    // Pula jogos com ghost
                    if ($home === null || $away === null) {
                        continue;
                    }

                    // Balanceia mando:
                    // Turno 0 mantém padrão; demais invertidos para “returno”
                    $isReturnLeg = ($m % 2) === 1;
                    if ($isReturnLeg) {
                        [$home, $away] = [$away, $home];
                    }

                    // Opcional: inverter mando em rodadas ímpares para evitar sequência grande de casa/fora
                    if ($r % 2 === 1) {
                        [$home, $away] = [$away, $home];
                    }

                    $rows[] = [
                        'group_id' => $group->getKey(),
                        'hometeam_id' => $home->getKey(),
                        'awayteam_id' => $away->getKey(),
                        'type' => \App\Enums\FixtureType::REGULAR_TIME_ONLY,
                        'status' => \App\Enums\FixtureStatus::NOT_STARTED,
                        'start_time' => $roundDate->copy(), // Carbon
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        // Persistência: insert em lote (rápido).
        // Se precisar ser idempotente, troque por upsert usando (group_id, hometeam_id, awayteam_id, start_time) como unique.
        DB::transaction(fn () => \App\Models\Fixture::insert($rows));
    }
}

<?php

declare(strict_types=1);

namespace App\Generators;

use App\Enums\TournamentType;
use App\Events\CreateLeagueEvent;
use App\Models\Club;
use App\Models\Season;
use App\Models\Tournament as ModelsTournament;
use App\Models\TournamentQualification;
use Illuminate\Support\Facades\DB;
use Webmozart\Assert\InvalidArgumentException;

final class Tournament
{
    /**
     * @param array{
     *   name:string,
     *   type?:int|TournamentType,
     *   teams?:int,
     *   groups?:int,
     *   champions?:int,
     *   promoted?:int,
     *   qualify_up?:int,
     *   qualify_down?:int,
     *   relegated?:int,
     *   generate_teams?:bool
     * } $input
     */
    public static function create(array $input): int
    {
        /** @var array{
         *   name:string,
         *   type:int|TournamentType,
         *   teams:int,
         *   groups:int,
         *   champions:int,
         *   promoted:int,
         *   qualify_up:int,
         *   qualify_down:int,
         *   relegated:int,
         *   generate_teams:bool
         * } $props
         */
        $props = array_replace([
            'type' => TournamentType::LEAGUE->value,
            'teams' => 16,
            'groups' => 1,
            'champions' => 0,
            'promoted' => 0,
            'qualify_up' => 0,
            'qualify_down' => 0,
            'relegated' => 0,
            'generate_teams' => true,
        ], $input);

        $typeEnum = $props['type'] instanceof TournamentType
            ? $props['type']
            : TournamentType::from((int) $props['type']);

        $teams = max(2, (int) $props['teams']);
        $groups = max(1, (int) $props['groups']);

        if ($teams % $groups !== 0) {
            throw new InvalidArgumentException('teams must be divisible by groups.');
        }

        $perGroup = intdiv($teams, $groups);
        $top = max(0, (int) $props['champions']);
        $promo = max(0, (int) $props['promoted']);
        $qUp = max(0, (int) $props['qualify_up']);
        $qDown = max(0, (int) $props['qualify_down']);
        $releg = max(0, (int) $props['relegated']);

        if ($top > $perGroup) {
            throw new InvalidArgumentException('champions exceeds teams per group.');
        }
        if ($promo > $perGroup) {
            throw new InvalidArgumentException('promoted exceeds teams per group.');
        }
        if ($qUp + $top + $promo > $perGroup) {
            throw new InvalidArgumentException('upper slots exceed teams per group.');
        }
        if ($releg > $perGroup) {
            throw new InvalidArgumentException('relegated exceeds teams per group.');
        }
        if ($qDown + $releg > $perGroup) {
            throw new InvalidArgumentException('lower slots exceed teams per group.');
        }

        /** @var int $tournamentId */
        $tournamentId = DB::transaction(function () use ($props, $typeEnum, $teams, $groups, $perGroup, $top, $promo, $qUp, $qDown, $releg) {
            /** @var Season|null $season */
            $season = Season::query()
                ->whereDate('start_time', '<=', now())
                ->orderByDesc('start_time')
                ->first();

            if (! $season) {
                throw new InvalidArgumentException('No active season found.');
            }

            // Cria torneio
            $tournament = ModelsTournament::create([
                'name' => (string) $props['name'],
                'type' => $typeEnum,      // ideal: cast enum no Model
                'participants' => $teams,
                'groups' => $groups,
            ]);

            // Liga season
            $season->tournaments()->attach($tournament->id);

            // Qualifications (por posição no grupo)
            for ($i = 1; $i <= $perGroup; $i++) {
                $status = 'ended';
                $seasonEnded = true;

                if ($i <= $top) {
                    $status = 'champions';
                } elseif ($i <= $promo) {
                    $status = 'promoted';
                } elseif ($i <= ($promo + $qUp)) {
                    $status = 'qualify_up';
                    $seasonEnded = false;
                } elseif ($i > $perGroup - $releg) {
                    $status = 'relegated';
                } elseif ($i > $perGroup - ($releg + $qDown)) {
                    $status = 'qualify_down';
                    $seasonEnded = false;
                }

                TournamentQualification::create([
                    'tournament_id' => $tournament->id,
                    'season_id' => $season->id,
                    'position' => $i,
                    'qualified_for_id' => $tournament->id, // TODO: ajuste quando existir torneio alvo
                    'season_ended' => $seasonEnded,
                    'status' => $status,
                    'qualification_date' => $season->start_time,
                ]);
            }

            // Times + tabela (opcional)
            if ((bool) $props['generate_teams']) {
                // Geração de clubes — substitua por Service/Action própria (não use Console Command aqui).
                self::generateClubs($teams);

                $clubs = Club::query()
                    ->orderByDesc('id')
                    ->limit($teams)
                    ->pluck('id')
                    ->all();

                $tournament->clubsParticipants()->attach($clubs, [
                    'season_id' => $season->id,
                ]);

                event(new CreateLeagueEvent($tournament));
            }

            return (int) $tournament->id;
        });

        return $tournamentId;
    }

    /** Gera N clubes “placeholder”. Troque por um serviço real. */
    private static function generateClubs(int $count): void
    {
        for ($i = 0; $i < $count; $i++) {
            Club::query()->create([
                'name' => 'Club '.uniqid(),
                'slug' => 'club-'.uniqid(),
                'colors' => ['#111111', '#ffffff'],
            ]);
        }
    }
}

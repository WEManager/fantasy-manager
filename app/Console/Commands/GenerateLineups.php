<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Club;
use App\Models\Contract;
use App\Models\Lineup;
use App\Models\Player;
use Illuminate\Console\Command;

final class GenerateLineups extends Command
{
    protected $signature = 'lineups:generate';

    protected $description = 'Generate lineups for all clubs';

    public function handle(): void
    {
        $clubs = Club::all();

        foreach ($clubs as $club) {
            $this->info("Gerando lineup para {$club->name}...");

            // Buscar jogadores ativos do clube
            $players = Contract::where('club_id', $club->id)
                ->where('from', '<=', now())
                ->where('until', '>=', now())
                ->with('player')
                ->get()
                ->pluck('player')
                ->filter();

            if ($players->count() < 11) {
                $this->warn("Clube {$club->name} tem apenas {$players->count()} jogadores. Pulando...");

                continue;
            }

            // Verificar se já existe lineup
            $existingLineup = Lineup::where('club_id', $club->id)->first();
            if ($existingLineup) {
                $this->info("Lineup já existe para {$club->name}. Pulando...");

                continue;
            }

            // Criar lineup com posicionamento correto
            $lineup = $this->createOptimalLineup($club, $players);
            $lineup->save();

            $this->info("Lineup criado para {$club->name} com 11 jogadores posicionados corretamente.");
        }

        $this->info('Geração de lineups concluída!');
    }

    /**
     * @param \Illuminate\Support\Collection<int, Player> $players
     */
    private function createOptimalLineup(Club $club, \Illuminate\Support\Collection $players): Lineup
    {
        $lineup = new Lineup();
        $lineup->club_id = $club->id;

        // Formação 4-4-2
        $lineupPositions = [
            'GK', 'LD', 'CD', 'CRD', 'RD',
            'LM', 'CM', 'CRM', 'RM',
            'CF', 'RF',
        ];

        $assignedPlayers = [];
        $usedPlayerIds = [];

        // Primeiro, tenta colocar jogadores em suas posições preferidas
        foreach ($lineupPositions as $position) {
            $bestPlayer = null;
            $bestScore = -1;

            foreach ($players as $player) {
                if (in_array($player->id, $usedPlayerIds)) {
                    continue;
                }

                $score = $this->calculatePositionScore($player, $position);
                if ($score > $bestScore) {
                    $bestScore = $score;
                    $bestPlayer = $player;
                }
            }

            if ($bestPlayer) {
                $assignedPlayers[$position] = $bestPlayer;
                $usedPlayerIds[] = $bestPlayer->id;
            }
        }

        // Preenche posições restantes com jogadores não utilizados
        $remainingPlayers = $players->filter(function ($player) use ($usedPlayerIds) {
            return ! in_array($player->id, $usedPlayerIds);
        });

        $positionIndex = 1;
        foreach ($lineupPositions as $position) {
            if (isset($assignedPlayers[$position])) {
                $lineup->{'position_'.$positionIndex} = $position;
                $lineup->{'player_'.$positionIndex} = $assignedPlayers[$position]->id;
            } else {
                // Se não encontrou jogador ideal, pega qualquer um disponível
                $player = $remainingPlayers->first();
                if ($player) {
                    $lineup->{'position_'.$positionIndex} = $position;
                    $lineup->{'player_'.$positionIndex} = $player->id;
                    $remainingPlayers = $remainingPlayers->skip(1);
                }
            }
            $positionIndex++;
        }

        // Adiciona substitutos (próximos 6 jogadores)
        $substituteIndex = 1;
        foreach ($remainingPlayers->take(6) as $substitute) {
            $lineup->{'substitute_'.$substituteIndex} = $substitute->id;
            $substituteIndex++;
        }

        return $lineup;
    }

    private function calculatePositionScore(Player $player, string $position): int
    {
        $score = 0;

        // Mapeamento de posições FIFA para posições do sistema
        $fifaToSystemMapping = [
            'gk' => 'GK',
            'cb' => 'CD',
            'lcb' => 'CLD',
            'rcb' => 'CRD',
            'lb' => 'LD',
            'rb' => 'RD',
            'lwb' => 'LD',
            'rwb' => 'RD',
            'cdm' => 'CM',
            'cm' => 'CM',
            'lcm' => 'CLM',
            'rcm' => 'CRM',
            'lm' => 'LM',
            'rm' => 'RM',
            'cam' => 'CM',
            'cf' => 'CF',
            'lcf' => 'CLF',
            'rcf' => 'CRF',
            'lf' => 'LF',
            'rf' => 'RF',
            'lw' => 'LF',
            'rw' => 'RF',
            'st' => 'CF',
        ];

        // Converte posição preferida do jogador para o sistema
        $playerPreferredSystem = $fifaToSystemMapping[mb_strtolower($player->preferred_position)] ?? $player->preferred_position;

        // Pontuação máxima se a posição é a preferida
        if ($playerPreferredSystem === $position) {
            $score += 100;
        }

        // Pontuação baseada nas posições que o jogador pode jogar
        if ($player->positions !== null) {
            foreach ($player->positions as $playerPosition) {
                $playerSystemPosition = $fifaToSystemMapping[mb_strtolower($playerPosition)] ?? $playerPosition;
                if ($playerSystemPosition === $position) {
                    $score += 50;
                    break;
                }
            }
        }

        // Pontuação baseada na proximidade da posição
        $positionGroups = [
            'GK' => ['GK'],
            'defense' => ['LD', 'CLD', 'CD', 'CRD', 'RD'],
            'midfield' => ['LM', 'CLM', 'CM', 'CRM', 'RM'],
            'attack' => ['LF', 'CLF', 'CF', 'CRF', 'RF'],
        ];

        $playerGroup = null;
        $positionGroup = null;

        foreach ($positionGroups as $group => $positions) {
            if (in_array($playerPreferredSystem, $positions)) {
                $playerGroup = $group;
            }
            if (in_array($position, $positions)) {
                $positionGroup = $group;
            }
        }

        if ($playerGroup === $positionGroup) {
            $score += 25;
        }

        // Penalidade severa para goleiros em posições de campo
        if ($playerPreferredSystem === 'GK' && $position !== 'GK') {
            $score = -1000;
        }

        // Penalidade severa para jogadores de campo como goleiro
        if ($playerPreferredSystem !== 'GK' && $position === 'GK') {
            $score = -1000;
        }

        return $score;
    }
}

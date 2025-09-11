<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\TournamentType;
use App\Generators\Tournament;
use App\Models\Club;
use App\Models\Contract;
use App\Models\Player;
use App\Models\Season;
use Database\Seeders\NationSeeder;
use Database\Seeders\WemanagerClubsSeeder;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

final class ExampleSetup extends Command
{
    protected $signature = 'example:setup';

    protected $description = 'Create an example database';

    public function handle(): int
    {
        try {
            $this->info('Iniciando configuração do banco de dados...');

            return DB::transaction(function () {
                $this->info('Importando nações...');
                app(NationSeeder::class)->run();

                $this->info('Verificando clubes...');
                if (Club::count() === 0) {
                    $this->info('Importando clubes...');
                    app(WemanagerClubsSeeder::class)->run();
                } else {
                    $this->info('Clubes já existem, pulando importação...');
                }

                $this->info('Verificando jogadores...');
                if (Player::count() === 0) {
                    $this->info('Importando jogadores...');
                    Artisan::call('import:players');
                } else {
                    $this->info('Jogadores já existem, pulando importação...');
                }

                $this->info('Criando contratos entre jogadores e clubes...');
                $this->createPlayerContracts();

                $this->info('Gerando lineups para todos os clubes...');
                Artisan::call('lineups:generate');

                $this->info('Criando temporada...');
                Season::create([
                    'start_time' => now(),
                    'end_time' => now()->addDays(30),
                ]);

                $this->info('Criando Liga WEManager...');
                Tournament::create([
                    'name' => 'Liga WEManager',
                    'teams' => 8,
                    'champions' => 1,
                    'relegated' => 2,
                ]);

                $this->info('Criando Copa WEM...');
                Tournament::create([
                    'name' => 'Copa WEM',
                    'teams' => 8,
                    'type' => TournamentType::PLAYOFFS,
                    'groups' => 2,
                    'promoted' => 2,
                ]);

                $this->info('Configuração concluída com sucesso!');

                return self::SUCCESS;
            });
        } catch (Exception $e) {
            $this->error('Erro durante a configuração: '.$e->getMessage());
            $this->error('Stack trace: '.$e->getTraceAsString());

            return self::FAILURE;
        }
    }

    private function createPlayerContracts(): void
    {
        $clubs = Club::all();
        $players = Player::all();

        if ($clubs->isEmpty() || $players->isEmpty()) {
            $this->warn('Não há clubes ou jogadores para criar contratos.');

            return;
        }

        // Posições necessárias para cada clube (mapeadas do FIFA para o sistema)
        $requiredPositions = [
            'GK', 'GK', // 2 goleiros (titular + reserva)
            'LD', 'CD', 'CD', 'RD', // 4 defensores
            'LM', 'CM', 'CM', 'RM', // 4 meio-campistas
            'CF', 'CF', // 2 atacantes
        ];

        $totalClubs = $clubs->count();
        $totalPlayers = $players->count();
        $requiredPlayersPerClub = count($requiredPositions);

        // Verifica se há jogadores suficientes
        if ($totalPlayers < ($totalClubs * $requiredPlayersPerClub)) {
            $required = $totalClubs * $requiredPlayersPerClub;
            $this->warn("Não há jogadores suficientes. Necessário: {$required}, Disponível: {$totalPlayers}");

            return;
        }

        // Mapeamento de posições FIFA para posições do sistema
        $fifaToSystemMapping = [
            'gk' => 'GK',
            'cb' => 'CD',
            'lcb' => 'CD',
            'rcb' => 'CD',
            'lb' => 'LD',
            'rb' => 'RD',
            'lwb' => 'LD',
            'rwb' => 'RD',
            'cdm' => 'CM',
            'cm' => 'CM',
            'lcm' => 'CM',
            'rcm' => 'CM',
            'lm' => 'LM',
            'rm' => 'RM',
            'cam' => 'CM',
            'cf' => 'CF',
            'lcf' => 'CF',
            'rcf' => 'CF',
            'lf' => 'CF',
            'rf' => 'CF',
            'lw' => 'CF',
            'rw' => 'CF',
            'st' => 'CF',
        ];

        // Agrupa jogadores por posição preferida (convertida para o sistema)
        $playersByPosition = [];
        foreach ($players as $player) {
            $fifaPosition = mb_strtolower($player->preferred_position);
            $systemPosition = $fifaToSystemMapping[$fifaPosition] ?? $player->preferred_position;

            if (! isset($playersByPosition[$systemPosition])) {
                $playersByPosition[$systemPosition] = [];
            }
            $playersByPosition[$systemPosition][] = $player;
        }

        // Cria os contratos para cada clube
        $playerIndex = 0;
        foreach ($clubs as $club) {
            $this->info("Criando contratos para {$club->name}...");

            $assignedPlayers = [];

            // Primeiro, garante pelo menos um jogador de cada posição necessária
            foreach ($requiredPositions as $position) {
                if (isset($playersByPosition[$position]) && ! empty($playersByPosition[$position])) {
                    // Pega o primeiro jogador disponível desta posição
                    $player = array_shift($playersByPosition[$position]);
                    $assignedPlayers[] = $player;
                } else {
                    // Se não há jogador específico desta posição, pega qualquer jogador disponível
                    if ($playerIndex < $totalPlayers) {
                        $player = $players[$playerIndex];
                        $assignedPlayers[] = $player;
                        $playerIndex++;
                    }
                }
            }

            // Adiciona jogadores extras aleatórios se necessário (17-19 por clube)
            $minPlayersPerClub = 17;
            $maxPlayersPerClub = 19;
            $targetPlayers = rand($minPlayersPerClub, $maxPlayersPerClub);

            while (count($assignedPlayers) < $targetPlayers && $playerIndex < $totalPlayers) {
                $player = $players[$playerIndex];
                $assignedPlayers[] = $player;
                $playerIndex++;
            }

            // Cria os contratos para os jogadores atribuídos
            foreach ($assignedPlayers as $player) {
                Contract::create([
                    'club_id' => $club->id,
                    'player_id' => $player->id,
                    'wage' => rand(1000, 10000), // Salário aleatório entre 1000-10000
                    'from' => now()->subDays(30), // Contrato começou há 30 dias
                    'until' => now()->addYears(3), // Contrato de 3 anos
                ]);
            }

            $this->info("Contratos criados para {$club->name}: ".count($assignedPlayers).' jogadores');
        }

        $this->info("Contratos criados: {$totalPlayers} jogadores distribuídos entre {$totalClubs} clubes");
    }
}

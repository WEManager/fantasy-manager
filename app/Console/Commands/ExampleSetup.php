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

                $this->info('Criando Liga WEManager B...');
                Tournament::create([
                    'name' => 'Liga WEManager B',
                    'teams' => 8,
                    'promoted' => 2,
                    'qualify_down' => 2,
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

        // Distribui jogadores entre os clubes (17-19 por clube)
        $playersPerClub = [];
        $totalPlayers = $players->count();
        $totalClubs = $clubs->count();

        // Calcula quantos jogadores por clube (entre 17 e 19)
        $minPlayersPerClub = 17;
        $maxPlayersPerClub = 19;

        foreach ($clubs as $club) {
            $playersPerClub[$club->id] = rand($minPlayersPerClub, $maxPlayersPerClub);
        }

        // Ajusta para garantir que todos os jogadores sejam distribuídos
        $totalAssigned = array_sum($playersPerClub);
        if ($totalAssigned < $totalPlayers) {
            // Adiciona jogadores extras aos primeiros clubes
            $remaining = $totalPlayers - $totalAssigned;
            $clubIndex = 0;
            while ($remaining > 0 && $clubIndex < $totalClubs) {
                $clubId = $clubs[$clubIndex]->id;
                $playersPerClub[$clubId]++;
                $remaining--;
                $clubIndex++;
            }
        } elseif ($totalAssigned > $totalPlayers) {
            // Remove jogadores extras dos últimos clubes
            $excess = $totalAssigned - $totalPlayers;
            $clubIndex = $totalClubs - 1;
            while ($excess > 0 && $clubIndex >= 0) {
                $clubId = $clubs[$clubIndex]->id;
                if ($playersPerClub[$clubId] > $minPlayersPerClub) {
                    $playersPerClub[$clubId]--;
                    $excess--;
                }
                $clubIndex--;
            }
        }

        // Cria os contratos
        $playerIndex = 0;
        foreach ($clubs as $club) {
            $clubPlayerCount = $playersPerClub[$club->id];

            for ($i = 0; $i < $clubPlayerCount && $playerIndex < $totalPlayers; $i++) {
                $player = $players[$playerIndex];

                Contract::create([
                    'club_id' => $club->id,
                    'player_id' => $player->id,
                    'wage' => rand(1000, 10000), // Salário aleatório entre 1000-10000
                    'type' => $i < 3 ? 'key' : 'regular', // Primeiros 3 jogadores são 'key', resto 'regular'
                    'from' => now()->subDays(30), // Contrato começou há 30 dias
                    'until' => now()->addYears(3), // Contrato de 3 anos
                ]);

                $playerIndex++;
            }
        }

        $this->info("Contratos criados: {$totalPlayers} jogadores distribuídos entre {$totalClubs} clubes");
    }
}

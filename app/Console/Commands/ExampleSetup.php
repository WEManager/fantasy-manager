<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\TournamentType;
use App\Generators\Tournament;
use App\Models\Season;
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
                Artisan::call('import:nations');

                $this->info('Importando jogadores...');
                Artisan::call('import:players');

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
                    'teams' => 16,
                    'type' => TournamentType::PLAYOFFS,
                    'groups' => 4,
                    'promoted' => 1,
                    'qualify_up' => 1,
                    'qualify_down' => 1,
                    'relegated' => 1,
                    'generate_teams' => false,
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
}

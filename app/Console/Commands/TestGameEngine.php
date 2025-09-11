<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Engines\CommentsEngine;
use App\Engines\GameEngine;
use App\Models\Club;
use App\Models\Fixture;
use App\Models\Lineup;
use App\Models\Player;
use Exception;
use Illuminate\Console\Command;

final class TestGameEngine extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:game-engine {--fixture-id= : ID do fixture para testar} {--create-test-data : Criar dados de teste}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testa o funcionamento dos engines do jogo';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🎮 Testando Game Engines...');
        $this->newLine();

        // Teste 1: CommentsEngine
        $this->testCommentsEngine();

        // Teste 2: GameEngine básico
        $this->testGameEngineBasic();

        // Teste 3: GameEngine com dados reais (se disponível)
        if ($this->option('fixture-id')) {
            $this->testGameEngineWithFixture((int) $this->option('fixture-id'));
        } elseif ($this->option('create-test-data')) {
            $this->createTestDataAndRun();
        } else {
            $this->info('💡 Dica: Use --fixture-id=ID para testar com um fixture específico');
            $this->info('💡 Ou use --create-test-data para criar dados de teste');
        }

        $this->newLine();
        $this->info('✅ Testes concluídos!');
    }

    private function testCommentsEngine(): void
    {
        $this->info('📝 Testando CommentsEngine...');

        try {
            $comments = [
                'Gol' => CommentsEngine::goal(),
                'Chute' => CommentsEngine::shoots(),
                'Bola fora' => CommentsEngine::ball_out_of_play(),
                'Ataque' => CommentsEngine::is_attacking(),
                'Contra-ataque' => CommentsEngine::counter_attack('hometeam'),
            ];

            foreach ($comments as $type => $comment) {
                $this->line("  {$type}: {$comment}");
            }

            $this->info('✅ CommentsEngine funcionando!');
        } catch (Exception $e) {
            $this->error("❌ Erro no CommentsEngine: {$e->getMessage()}");
        }

        $this->newLine();
    }

    private function testGameEngineBasic(): void
    {
        $this->info('⚽ Testando GameEngine básico...');

        try {
            $engine = new GameEngine();

            $this->line("  Times: {$engine->hometeamName} vs {$engine->awayteamName}");
            $this->line("  Placar: {$engine->score['hometeam']}-{$engine->score['awayteam']}");
            $this->line("  Chances: {$engine->chances['hometeam']}% - {$engine->chances['awayteam']}%");
            $this->line('  Eventos gerados: '.count($engine->events));

            if (! empty($engine->events)) {
                $this->line("  Primeiro evento: {$engine->events[0]['time']}min - {$engine->events[0]['event']}");
            }

            $this->info('✅ GameEngine básico funcionando!');
        } catch (Exception $e) {
            $this->error("❌ Erro no GameEngine: {$e->getMessage()}");
        }

        $this->newLine();
    }

    private function testGameEngineWithFixture(int $fixtureId): void
    {
        $this->info("🏟️ Testando GameEngine com Fixture ID: {$fixtureId}");

        try {
            $fixture = Fixture::with(['hometeam', 'awayteam'])->find($fixtureId);

            if (! $fixture) {
                $this->error("❌ Fixture com ID {$fixtureId} não encontrado!");

                return;
            }

            $this->line("  Fixture: {$fixture->hometeam->name} vs {$fixture->awayteam->name}");

            $engine = new GameEngine($fixtureId);

            $this->line("  Placar: {$engine->score['hometeam']}-{$engine->score['awayteam']}");
            $this->line("  Chances: {$engine->chances['hometeam']}% - {$engine->chances['awayteam']}%");
            $this->line('  Eventos gerados: '.count($engine->events));

            // Mostrar alguns eventos
            if (! empty($engine->events)) {
                $this->line('  Primeiros 3 eventos:');
                for ($i = 0; $i < min(3, count($engine->events)); $i++) {
                    $event = $engine->events[$i];
                    $this->line("    {$event['time']}min - {$event['event']}");
                }
            }

            $this->info('✅ GameEngine com fixture funcionando!');
        } catch (Exception $e) {
            $this->error("❌ Erro no GameEngine com fixture: {$e->getMessage()}");
        }

        $this->newLine();
    }

    private function createTestDataAndRun(): void
    {
        $this->info('🔧 Criando dados de teste...');

        try {
            // Verificar se já existem clubs
            $clubs = Club::take(2)->get();

            if ($clubs->count() < 2) {
                $this->error('❌ É necessário ter pelo menos 2 clubs no banco de dados!');
                $this->info('💡 Execute: php artisan db:seed --class=WemanagerClubsSeeder');

                return;
            }

            // Criar um fixture de teste
            $fixture = Fixture::create([
                'hometeam_id' => $clubs[0]->id,
                'awayteam_id' => $clubs[1]->id,
                'group_id' => 1, // Usar o primeiro grupo disponível
                'start_time' => now()->addHour(),
                'status' => '0', // Scheduled
            ]);

            $this->line("  Fixture criado: {$clubs[0]->name} vs {$clubs[1]->name} (ID: {$fixture->id})");

            // Verificar se existem lineups
            $homeLineup = Lineup::where('club_id', $clubs[0]->id)->first();
            $awayLineup = Lineup::where('club_id', $clubs[1]->id)->first();

            if (! $homeLineup || ! $awayLineup) {
                $this->warn('⚠️ Lineups não encontrados. Criando lineups básicos...');

                // Criar lineups básicos se não existirem
                if (! $homeLineup) {
                    $this->createBasicLineup($clubs[0]);
                }
                if (! $awayLineup) {
                    $this->createBasicLineup($clubs[1]);
                }
            }

            // Testar o engine com o fixture criado
            $this->testGameEngineWithFixture($fixture->id);

        } catch (Exception $e) {
            $this->error("❌ Erro ao criar dados de teste: {$e->getMessage()}");
        }
    }

    private function createBasicLineup(Club $club): void
    {
        $players = Player::whereHas('contracts', function ($query) use ($club) {
            $query->where('club_id', $club->id);
        })->take(11)->get();

        if ($players->count() < 11) {
            $this->warn("⚠️ Club {$club->name} não tem jogadores suficientes para lineup");

            return;
        }

        $lineup = Lineup::create([
            'club_id' => $club->id,
            'player_1' => $players[0]->id,
            'position_1' => 'GK',
            'player_2' => $players[1]->id,
            'position_2' => 'LD',
            'player_3' => $players[2]->id,
            'position_3' => 'CD',
            'player_4' => $players[3]->id,
            'position_4' => 'RD',
            'player_5' => $players[4]->id,
            'position_5' => 'LM',
            'player_6' => $players[5]->id,
            'position_6' => 'CM',
            'player_7' => $players[6]->id,
            'position_7' => 'RM',
            'player_8' => $players[7]->id,
            'position_8' => 'LF',
            'player_9' => $players[8]->id,
            'position_9' => 'CF',
            'player_10' => $players[9]->id,
            'position_10' => 'RF',
            'player_11' => $players[10]->id,
            'position_11' => 'CLD',
        ]);

        $this->line("  Lineup criado para {$club->name}");
    }
}

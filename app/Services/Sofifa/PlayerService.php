<?php

declare(strict_types=1);

namespace App\Services\Sofifa;

use App\Models\Player;
use App\Services\Sofifa\Dto\PlayerPayload;
use Illuminate\Support\Facades\Log;
use Throwable;

final class PlayerService
{
    /**
     * Cria ou atualiza um jogador no banco de dados
     * O fifa_player_id é o ID único do jogador no jogo EA FC (Sofifa)
     */
    private function createOrUpdatePlayer(PlayerPayload $playerPayload): void
    {
        Player::updateOrCreate(
            ['id' => $playerPayload->id],
            [
                'full_name' => $playerPayload->fullName,
                'know_as' => $playerPayload->knowAs,
                'fifa_player_id' => (string) $playerPayload->id, // ID do site EA FC (Sofifa)
                'birth_date' => $playerPayload->birthDate ? \Carbon\Carbon::createFromTimestamp($playerPayload->birthDate) : null,
                'weak_foot' => $playerPayload->weakFoot,
                'skill_moves' => $playerPayload->skillMoves,
                'preferred_foot' => $playerPayload->preferredFoot,
                'length' => $playerPayload->length,
                'weight' => $playerPayload->weight,
                'nation_id' => $playerPayload->nationId,
                'preferred_position' => $playerPayload->preferredPosition,
                'positions' => json_encode($playerPayload->positions),
                'specialities' => json_encode($playerPayload->specialities),
                'play_styles' => json_encode($playerPayload->playStyles),
                'stats' => $playerPayload->stats,
            ]
        );
    }

    /**
     * Processa o arquivo CSV de jogadores do Sofifa
     * @return array<array-key, array<string, mixed>>
     */
    public function processCsvFile(): array
    {
        $csvPath = storage_path('app/sofifa/players.csv');
        
        if (!file_exists($csvPath)) {
            Log::error('CSV file not found', ['path' => $csvPath]);
            return [];
        }

        $processedPlayers = [];
        $playersProcessed = 0;
        
        try {
            Log::info('Starting CSV processing');
            
            $handle = fopen($csvPath, 'r');
            if (!$handle) {
                Log::error('Failed to open CSV file');
                return [];
            }

            // Pular o cabeçalho
            $headers = fgetcsv($handle);
            if (!$headers) {
                Log::error('Failed to read CSV headers');
                fclose($handle);
                return [];
            }

            while (($row = fgetcsv($handle)) !== false) {
                $playerData = array_combine($headers, $row);
                
                if (!$playerData) {
                    continue;
                }

                try {
                    $processedPlayer = $this->processCsvRow($playerData);
                    
                    if ($processedPlayer) {
                        $processedPlayers[] = $processedPlayer;
                        $playersProcessed++;
                        
                        Log::info('Player processed from CSV', [
                            'playerId' => $playerData['player_id'] ?? 'Unknown',
                            'name' => $playerData['full_name'] ?? 'Unknown',
                            'processed' => $playersProcessed
                        ]);
                    }
                } catch (Throwable $e) {
                    Log::error('Failed to process CSV row', [
                        'playerId' => $playerData['player_id'] ?? 'Unknown',
                        'error' => $e->getMessage()
                    ]);
                }
            }

            fclose($handle);
            Log::info('CSV processing completed', ['totalProcessed' => $playersProcessed]);
            return $processedPlayers;

        } catch (Throwable $e) {
            Log::error('Error during CSV processing: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Processa uma linha do CSV
     * @param array<string, string> $row
     * @return array<string, mixed>
     */
    private function processCsvRow(array $row): array
    {
        // Extrair dados básicos
        $playerId = (int) $row['player_id'];
        $fullName = trim($row['full_name']);
        $knowAs = trim($row['name']);
        $height = (int) $row['height_cm'];
        $weight = (int) $row['weight_kg'];
        $dob = $row['birthdate'];
        $positions = array_filter(array_map('trim', explode(',', $row['positions'])));
        $preferredFoot = strtolower($row['preferred_foot']);
        $weakFoot = (int) $row['weak_foot'];
        $skillMoves = (int) $row['skill_moves'];
        $specialities = array_filter(array_map('trim', explode(',', $row['specialities'])));
        $playStyles = array_filter(array_map('trim', explode(',', $row['play_styles'])));
        $countryId = (int) $row['nation_id'];

        // Processar data de nascimento
        $birthDate = null;
        if ($dob) {
            $birthDate = strtotime($dob);
        }

        // Extrair estatísticas (todas soltas, sem agrupamento)
        $stats = [
            'crossing' => (int) $row['crossing'],
            'finishing' => (int) $row['finishing'],
            'heading_accuracy' => (int) $row['heading_accuracy'],
            'short_passing' => (int) $row['short_passing'],
            'volleys' => (int) $row['volleys'],
            'dribbling' => (int) $row['dribbling'],
            'curve' => (int) $row['curve'],
            'fk_accuracy' => (int) $row['fk_accuracy'],
            'long_passing' => (int) $row['long_passing'],
            'ball_control' => (int) $row['ball_control'],
            'acceleration' => (int) $row['acceleration'],
            'sprint_speed' => (int) $row['sprint_speed'],
            'agility' => (int) $row['agility'],
            'reactions' => (int) $row['reactions'],
            'balance' => (int) $row['balance'],
            'shot_power' => (int) $row['shot_power'],
            'jumping' => (int) $row['jumping'],
            'stamina' => (int) $row['stamina'],
            'strength' => (int) $row['strength'],
            'long_shots' => (int) $row['long_shots'],
            'aggression' => (int) $row['aggression'],
            'interceptions' => (int) $row['interceptions'],
            'att_position' => (int) $row['positioning'],
            'vision' => (int) $row['vision'],
            'penalties' => (int) $row['penalties'],
            'composure' => (int) $row['composure'],
            'defensive_awareness' => (int) $row['defensive_awareness'],
            'standing_tackle' => (int) $row['standing_tackle'],
            'sliding_tackle' => (int) $row['sliding_tackle'],
            'gk_diving' => (int) $row['gk_diving'],
            'gk_handling' => (int) $row['gk_handling'],
            'gk_kicking' => (int) $row['gk_kicking'],
            'gk_positioning' => (int) $row['gk_positioning'],
            'gk_reflexes' => (int) $row['gk_reflexes'],
        ];

        // Determinar posição preferida
        $preferredPosition = $row['best_position'];

        return [
            'id' => $playerId,
            'full_name' => $fullName,
            'know_as' => $knowAs,
            'nation_id' => $countryId,
            'birth_date' => $birthDate,
            'length' => $height,
            'weight' => $weight,
            'preferred_position' => $preferredPosition,
            'positions' => $positions,
            'preferred_foot' => $preferredFoot,
            'skill_moves' => $skillMoves,
            'weak_foot' => $weakFoot,
            'specialities' => $specialities,
            'play_styles' => $playStyles,
            'stats' => $stats,
        ];
    }

    /**
     * Testa o processamento do CSV sem salvar no banco
     * @return array<array-key, array<string, mixed>>
     */
    public function testCsvProcessing(int $maxPlayers = 3): array
    {
        try {
            Log::info('Starting CSV test processing', ['maxPlayers' => $maxPlayers]);
            
            $players = $this->processCsvFile();
            
            if (empty($players)) {
                Log::warning('No players found in CSV');
                return [];
            }

            // Limitar o número de jogadores para teste
            $testPlayers = array_slice($players, 0, $maxPlayers);
            
            Log::info('CSV test processing completed', ['processed' => count($testPlayers)]);
            return $testPlayers;

        } catch (Throwable $e) {
            Log::error('Error during CSV test processing: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Importa jogadores do CSV para o banco de dados
     */
    public function importFromCsv(): bool
    {
        try {
            Log::info('Starting CSV import');
            
            $players = $this->processCsvFile();
            
            if (empty($players)) {
                Log::warning('No players found in CSV');
                return false;
            }

            $imported = 0;
            foreach ($players as $playerData) {
                try {
                    $playerPayload = new PlayerPayload(
                        id: $playerData['id'],
                        fullName: $playerData['full_name'],
                        knowAs: $playerData['know_as'],
                        nationId: $playerData['nation_id'],
                        birthDate: $playerData['birth_date'] ?? 0,
                        length: $playerData['length'],
                        weight: $playerData['weight'],
                        preferredPosition: $playerData['preferred_position'],
                        positions: $playerData['positions'],
                        preferredFoot: $playerData['preferred_foot'],
                        skillMoves: $playerData['skill_moves'],
                        weakFoot: $playerData['weak_foot'],
                        specialities: $playerData['specialities'],
                        playStyles: $playerData['play_styles'],
                        stats: $playerData['stats'],
                    );

                    $this->createOrUpdatePlayer($playerPayload);
                    $imported++;

                    Log::info('Player imported from CSV', [
                        'playerId' => $playerData['id'],
                        'name' => $playerData['full_name'],
                        'imported' => $imported
                    ]);

                } catch (Throwable $e) {
                    Log::error('Failed to import player from CSV', [
                        'playerId' => $playerData['id'] ?? 'Unknown',
                        'error' => $e->getMessage()
                    ]);
                }
            }

            Log::info('CSV import completed', ['totalImported' => $imported]);
            return $imported > 0;

        } catch (Throwable $e) {
            Log::error('Error during CSV import: ' . $e->getMessage());
            return false;
        }
    }


}

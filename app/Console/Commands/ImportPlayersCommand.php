<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\Sofifa\PlayerService;
use Illuminate\Console\Command;

final class ImportPlayersCommand extends Command
{
    protected $signature = 'import:players';

    protected $description = 'Import players data from CSV file';

    public function __construct(private readonly PlayerService $playerService)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->info('Starting player import from CSV...');

        // Validar estrutura do CSV primeiro
        if (! $this->validateCsvStructure()) {
            $this->error('CSV structure validation failed. Please check the file.');

            return self::FAILURE;
        }

        $existingCount = \App\Models\Player::count();
        if ($existingCount > 0) {
            if (! $this->confirm("Found {$existingCount} existing players. Do you want to continue?")) {
                $this->info('Import cancelled.');

                return self::SUCCESS;
            }
        }

        $success = $this->playerService->importFromCsv();

        if ($success) {
            $this->info('Players imported successfully from CSV!');

            return self::SUCCESS;
        }
        $this->error('Failed to import players from CSV.');

        return self::FAILURE;

    }

    /**
     * Valida a estrutura do arquivo CSV
     */
    private function validateCsvStructure(): bool
    {
        $csvPath = storage_path('app/sofifa/players.csv');

        if (! file_exists($csvPath)) {
            $this->error("CSV file not found at: {$csvPath}");

            return false;
        }

        $handle = fopen($csvPath, 'r');
        if (! $handle) {
            $this->error('Failed to open CSV file');

            return false;
        }

        // Ler cabeçalhos
        $headers = fgetcsv($handle);
        fclose($handle);

        if (! $headers) {
            $this->error('Failed to read CSV headers');

            return false;
        }

        // Colunas obrigatórias que devem estar presentes
        $requiredColumns = [
            'player_id',
            'full_name',
            'name',
            'image',
            'height_cm',
            'weight_kg',
            'birthdate',
            'positions',
            'preferred_foot',
            'weak_foot',
            'skill_moves',
            'specialities',
            'nation_id',
            'play_styles',
            // Estatísticas
            'crossing', 'finishing', 'heading_accuracy', 'short_passing', 'volleys',
            'dribbling', 'curve', 'fk_accuracy', 'long_passing', 'ball_control',
            'acceleration', 'sprint_speed', 'agility', 'reactions', 'balance',
            'shot_power', 'jumping', 'stamina', 'strength', 'long_shots',
            'aggression', 'interceptions', 'positioning', 'vision', 'penalties', 'composure',
            'defensive_awareness', 'standing_tackle', 'sliding_tackle',
            'gk_diving', 'gk_handling', 'gk_kicking', 'gk_positioning', 'gk_reflexes',
        ];

        $missingColumns = [];
        foreach ($requiredColumns as $column) {
            if (! in_array($column, $headers)) {
                $missingColumns[] = $column;
            }
        }

        if (! empty($missingColumns)) {
            $this->error('Missing required columns in CSV:');
            foreach ($missingColumns as $column) {
                $this->error("  - {$column}");
            }

            return false;
        }

        // Verificar se há colunas extras que podem ser úteis
        $optionalColumns = [
            'best_position',
            'international_reputation',
            'version',
        ];

        $foundOptionalColumns = [];
        foreach ($optionalColumns as $column) {
            if (in_array($column, $headers)) {
                $foundOptionalColumns[] = $column;
            }
        }

        $this->info('CSV structure validation passed!');
        $this->info('Found '.count($headers).' columns total');
        $this->info('Required columns: '.count($requiredColumns));

        if (! empty($foundOptionalColumns)) {
            $this->info('Optional columns found: '.implode(', ', $foundOptionalColumns));
        }

        // Mostrar algumas estatísticas do arquivo
        $this->showCsvStatistics($csvPath);

        return true;
    }

    /**
     * Mostra estatísticas do arquivo CSV
     */
    private function showCsvStatistics(string $csvPath): void
    {
        $handle = fopen($csvPath, 'r');
        if (! $handle) {
            return;
        }

        $lineCount = 0;
        $sampleData = [];
        $headers = [];

        while (($row = fgetcsv($handle)) !== false && $lineCount < 5) {
            if ($lineCount === 0) {
                $headers = $row;
            } else {
                $sampleData[] = array_combine($headers, $row);
            }
            $lineCount++;
        }

        fclose($handle);

        if (! empty($sampleData)) {
            $this->info('Sample data from first player:');
            $sample = $sampleData[0];
            $this->info("  Player ID: {$sample['player_id']}");
            $this->info("  Name: {$sample['full_name']}");
            $this->info("  Position: {$sample['positions']}");
            $this->info("  Nation ID: {$sample['nation_id']}");
            $this->info("  Height: {$sample['height_cm']}cm");
            $this->info("  Weight: {$sample['weight_kg']}kg");
        }
    }
}

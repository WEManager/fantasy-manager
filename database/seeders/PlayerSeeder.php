<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Services\Sofifa\PlayerService;
use Illuminate\Database\Seeder;

final class PlayerSeeder extends Seeder
{
    public function __construct(private readonly PlayerService $playerService) {}

    public function run(): void
    {
        $this->command->info('Starting player import from CSV...');

        $this->command->info('Will import players from CSV');

        $ok = $this->playerService->importFromCsv();

        if ($ok) {
            $this->command->info('Players seeded successfully from CSV import');
        } else {
            $this->command->error('Failed to seed players from CSV import');
        }
    }
}

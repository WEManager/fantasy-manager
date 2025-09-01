<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Services\Sofifa\NationService;
use Illuminate\Database\Seeder;

final class NationSeeder extends Seeder
{
    public function __construct(private readonly NationService $nationService) {}

    public function run(): void
    {
        $this->command->info('Importing nations from file...');
        
        $ok = $this->nationService->updateFromFile('sofifa/nation.json', 500);

        if ($ok) {
            $this->command->info('Nations seeded successfully from file');
        } else {
            $this->command->error('Failed to seed nations from file');
        }
    }
}

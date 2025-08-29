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
        $ok = $this->nationService->updateFromApi();

        if ($ok) {
            $this->command->info('Nations seeded successfully from API');
        } else {
            $this->command->error('Failed to seed nations from API');
        }
    }
}

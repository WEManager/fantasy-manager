<?php

declare(strict_types=1);

use Database\Seeders\NationSeeder;
use Database\Seeders\PlayerSeeder;
use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(NationSeeder::class);
        $this->call(PlayerSeeder::class);
    }
}

<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Club;
use Illuminate\Database\Seeder;

final class WemanagerClubsSeeder extends Seeder
{
    public function run(): void
    {
        /** @var array<int, array<string, string|array<string>>> $clubs */
        $clubs = json_decode(file_get_contents(database_path('datas/wemanager_clubs.json')), true);

        foreach ($clubs as $club) {
            Club::create([
                'name' => $club['name'],
                'colors' => $club['colors'],
            ]);
        }
    }
}

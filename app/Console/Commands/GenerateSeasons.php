<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Season;
use Illuminate\Console\Command;

final class GenerateSeasons extends Command
{
    protected $signature = 'season:generate';

    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {
        if (! $lastSeason = Season::latest()->first()) {
            Season::create(['start_time' => now(), 'end_time' => now()->addMonths(3)]);
        } else {
            Season::create([
                'start_time' => $lastSeason->end_time->copy()->addDay(),
                'end_time' => $lastSeason->end_time->copy()->addDay()->addMonths(3),
            ]);
        }
    }
}

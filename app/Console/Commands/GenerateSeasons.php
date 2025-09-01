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

    public function handle():void
    {
        if (! $lastSeason = Season::latest()->first()) {
            Season::create(['start_time' => date('Y-m-d'), 'end_time' => date('Y-m-d 23:59:59', strtotime('+3 months'))]);
        } else {
            Season::create(['start_time' => date('Y-m-d', strtotime($lastSeason->end_time . ' +1 day')), 'end_time' => date('Y-m-d 23:59:59', strtotime($lastSeason->end_time . ' +3 months')) ]);
        }
    }
}

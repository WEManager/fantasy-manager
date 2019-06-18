<?php

namespace App\Console\Commands;

use App\Season;
use Illuminate\Console\Command;

class GenerateSeasons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'season:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (!$lastSeason = Season::latest()->first()) {
            Season::create(['start_time' => date('Y-m-d'), 'end_time' => date('Y-m-d 23:59:59', strtotime('+3 months'))]);
        } else {
            Season::create(['start_time' => date('Y-m-d', strtotime($lastSeason->end_time . ' +1 day')), 'end_time' => date('Y-m-d 23:59:59', strtotime($lastSeason->end_time . ' +3 months')) ]);
        }
    }
}

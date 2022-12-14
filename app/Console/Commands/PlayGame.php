<?php

namespace App\Console\Commands;

use App\Engines\MatchEngine;
use App\Models\GameEvent;
use App\Models\TournamentGame;
use Illuminate\Console\Command;

class PlayGame extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'games:play';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Play games';

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
        // We need to get the entire event objects since we cannot delete them otherwise
        $events = GameEvent::current()->get();

        $ids = $events->pluck('id')->toArray();

        foreach ($events as $event) {
            $event->play();
        }

        // Delete all events that has been played at once
        GameEvent::whereIn('id', $ids)->delete();

        $games = TournamentGame::where('start_time', '<=', now())
            ->where('status', '0')
            ->orWhere('status', '1')
            ->where('start_time', '<=', ago('106 minutes'))
            ->get();

        foreach ($games as $game) {
            new MatchEngine($game);
        }
    }
}

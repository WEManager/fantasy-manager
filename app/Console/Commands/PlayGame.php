<?php

namespace App\Console\Commands;

use App\Engines\MatchEngine;
use App\GameEvent;
use App\TournamentGame;
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
        $games = TournamentGame::AboutToStart()->get();
        foreach ($games as $game) {
            new MatchEngine($game);
        }

        // We need to get the entire event objects since we cannot delete them otherwise
        $events = GameEvent::current()->get();
        foreach ($events as $event) {
            $event->play();
        }
        // Delete all events that has been played at once
        $ids = $events->pluck('id')->toArray();
        GameEvent::whereIn('id', $ids)->delete();

        $games = TournamentGame::TimeForHalftime()->get();
        foreach ($games as $game) {
            new MatchEngine($game);
        }

        $games = TournamentGame::AboutToEnd()->get();
        foreach ($games as $game) {
            new MatchEngine($game);
        }
    }
}

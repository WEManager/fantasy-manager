<?php

namespace App\Console\Commands;

use App\Engines\MatchEngine;
use App\TournamentGame;
use Illuminate\Console\Command;

class PlayGame extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'game:play {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Play game by id';

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
        $game = TournamentGame::find($this->argument('id'));
        new MatchEngine($game);
    }
}

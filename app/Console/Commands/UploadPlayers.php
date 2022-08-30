<?php

namespace App\Console\Commands;

use App\Models\Player;
use Illuminate\Console\Command;

class UploadPlayers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'upload:players';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upload .JSON file with players.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $players = json_decode(file_get_contents(storage_path() . "/players.json"), true);
        
        foreach ($players as $key => $player) {
            Player::create($player);
        }
    }
}

<?php

namespace App\Console\Commands;

use App\Generators\Player;
use App\Person;
use Illuminate\Console\Command;

class GeneratePlayer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'player:generate {type=regular} {club_id=null} {locale=sv}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new player';

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
        Person::create([
            'firstname' => Player::firstname($this->argument('locale')),
            'lastname' => Player::lastname($this->argument('locale')),
            'nationality' => Player::nationality($this->argument('locale')),
            'age' => Player::age($this->argument('type')),
        ]);

    }
}

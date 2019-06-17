<?php

namespace App\Console\Commands;

use App\Club;
use App\Generators\Player;
use App\Person;
use App\PlayerContract;
use Illuminate\Console\Command;
use App\Generators\Club as ClubGenerator;

class GenerateClub extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'club:generate {locale=sv}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new club';

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
        $clubGenerator = new ClubGenerator();
        $name = $clubGenerator->name($this->argument('locale'));

        $club = Club::create(['name' => $name, 'locale' => nationalityBasedOnLocale($this->argument('locale'))]);

        // Let us create a U19 team
        $this->createPlayers($club, 'U19');

        // Let us create a U21 team
        $this->createPlayers($club, 'U21');

        // Let us create am A-team
        $this->createPlayers($club, 'regular');

    }

    /**
     * @param $club
     */
    protected function createPlayers($club, $type): void
    {
        for ($i = 0; $i < 20; $i++) {

            $person = Person::create([
                'firstname' => Player::firstname($this->argument('locale')),
                'lastname' => Player::lastname($this->argument('locale')),
                'nationality' => Player::nationality($this->argument('locale')),
                'age' => Player::age($type),
            ]);

            if ($type == 'U19') {
                $contractType = 'youth';
            } elseif ($type == 'U21') {
                $contractType = 'reserve';
            } else {
                $contractType = 'regular';
            }

            $contractLength = [3, 6, 9];
            shuffle($contractLength);

            PlayerContract::create([
                'club_id' => $club->id,
                'person_id' => $person->id,
                'wage' => rand(100, 999),
                'type' => $contractType,
                'from' => date('Y-m-d H:i:s', strtotime('-1 day')),
                'until' => date('Y-m-d H:i:s', strtotime('+' . $contractLength[0] . ' months')),
            ]);
        }
    }
}

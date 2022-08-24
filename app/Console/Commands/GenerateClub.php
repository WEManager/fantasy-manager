<?php

namespace App\Console\Commands;

use App\Models\Arena;
use App\Models\Club;
use App\Models\Lineup;
use App\Models\Person;
use App\Models\PlayerContract;
use App\Generators\Player;
use Illuminate\Console\Command;
use App\Generators\Club as ClubGenerator;
use App\Generators\Arena as ArenaGenerator;
use Illuminate\Support\Facades\DB;

class GenerateClub extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'club:generate {amount=1} {locale=sv}';

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
        for ($i = 0; $i < $this->argument('amount'); $i++) {
            $clubGenerator = new ClubGenerator();
            $name = $clubGenerator->name($this->argument('locale'));
            $colors = $clubGenerator->colors();

            $arenaGenerator = new ArenaGenerator();
            $arenaName = $arenaGenerator->name($this->argument('locale'), $clubGenerator->town);

            $arena = Arena::create(['name' => $arenaName, 'town' => $clubGenerator->town]);
            $club = Club::create(['name' => $name, 'colors' => $colors, 'locale' => nationalityBasedOnLocale($this->argument('locale'))]);

            // Let us create a U19 team
            $players = $this->createPlayers($club, 'U19');
            Lineup::create($this->setupLineup($players, $club, 'u19'));

            // Let us create a U21 team
            $players = $this->createPlayers($club, 'U21');
            Lineup::create($this->setupLineup($players, $club, 'u21'));

            // Let us create am A-team
            $players = $this->createPlayers($club, 'regular');
            Lineup::create($this->setupLineup($players, $club, 'senior'));

            $arenaData = [
                [
                    'club_id' => $club->id,
                    'arena_id' => $arena->id,
                    'team' => 'u19',
                ],
                [
                    'club_id' => $club->id,
                    'arena_id' => $arena->id,
                    'team' => 'u21',
                ],
                [
                    'club_id' => $club->id,
                    'arena_id' => $arena->id,
                    'team' => 'senior',
                ],
            ];
            DB::table('club_arenas')->insert($arenaData);
        }
    }

    protected function setupLineup($players, $club, $team)
    {
        $lineup = [
            'club_id' => $club->id,
            'team' => $team,
            'position_1' => 'GK',
            'player_1' => $players[0],
            'position_2' => 'LD',
            'player_2' => $players[1],
            'position_3' => 'CLD',
            'player_3' => $players[2],
            'position_4' => 'CRD',
            'player_4' => $players[3],
            'position_5' => 'RD',
            'player_5' => $players[4],
            'position_6' => 'LM',
            'player_6' => $players[5],
            'position_7' => 'CLM',
            'player_7' => $players[6],
            'position_8' => 'CRM',
            'player_8' => $players[7],
            'position_9' => 'RM',
            'player_9' => $players[8],
            'position_10' => 'CLF',
            'player_10' => $players[9],
            'position_11' => 'CRF',
            'player_11' => $players[10],

            'substitute_1' => $players[11],
            'substitute_2' => $players[12],
            'substitute_3' => $players[13],
            'substitute_4' => $players[14],
            'substitute_5' => $players[15],
            'substitute_6' => $players[16],
        ];

        return $lineup;
    }

    /**
     * @param $club
     */
    protected function createPlayers($club, $type): array
    {
        $players = [];
        for ($i = 0; $i < 20; $i++) {

            $person = Person::create([
                'firstname' => Player::firstname($this->argument('locale')),
                'lastname' => Player::lastname($this->argument('locale')),
                'nationality' => Player::nationality($this->argument('locale')),
                'age' => Player::age($type),
            ]);
            $players[] = $person->id;

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

        return $players;
    }
}

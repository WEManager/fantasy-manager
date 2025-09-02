<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Generators\Arena as ArenaGenerator;
use App\Generators\Club as ClubGenerator;
use App\Models\Club;
use App\Models\Contract;
use App\Models\Lineup;
use App\Models\Player;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

final class GenerateClub extends Command
{
    protected $signature = 'club:generate {amount=1} {locale=sv}';

    protected $description = 'Generate a new club';

    public function handle(): void
    {
        for ($i = 0; $i < $this->argument('amount'); $i++) {
            $this->createClub($this->argument('locale'));
        }
    }

    public function createClub(string $locale): void
    {
        $clubGenerator = new ClubGenerator();

        $name = $clubGenerator->name($locale);
        $colors = ClubGenerator::colors();

        $club = Club::create([
            'name' => $name,
            'colors' => $colors,
        ]);

        $players = Player::all()->random(17)->pluck('id');
        for ($i = 0; $i < 17; $i++) {
            $this->makeContract($players[$i], $club);
        }

        Lineup::create($this->setupLineup($players, $club));

        $arenaGenerator = new ArenaGenerator();
        $arenaName = $arenaGenerator->name($locale, $clubGenerator->town);

        $club->arena()->create(['name' => $arenaName, 'town' => $clubGenerator->town]);
    }

    /**
     * @param  Collection<int, Player>  $players
     * @return array<string, mixed>
     */
    protected function setupLineup(Collection $players, Club $club): array
    {
        return [
            'club_id' => $club->id,
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
    }

    private function makeContract(int $player, Club $club): void
    {
        $contractLengths = [3, 6, 9];

        Contract::create([
            'club_id' => $club->id,
            'player_id' => $player,
            'wage' => rand(100, 999),
            'from' => date('Y-m-d H:i:s', strtotime('-1 day')),
            'until' => date('Y-m-d H:i:s', strtotime('+'.$contractLengths[array_rand($contractLengths)].' months')),
        ]);
    }
}

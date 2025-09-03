<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use App\Models\Player;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\LineupController
 */
final class LineupControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    private $club;

    private $manager;

    private $players;

    private $lineup;

    protected function setUp(): void
    {
        parent::setUp();

        $this->create_lineup();
    }

    /**
     * @test
     */
    public function edit_returns_an_ok_response()
    {
        $response = $this->get(route('edit_lineup', ['locale' => 'en', $this->club]));

        // User is not logged in
        $response->assertStatus(403);

        $loggedIn = $this->actingAs($this->manager)->get(route('edit_lineup', ['locale' => 'en', $this->club]));
        $loggedIn->assertOk();
    }

    /**
     * @test
     */
    public function update_returns_an_ok_response()
    {

        $response = $this->post(route('update_lineup', ['locale' => 'en', 'lineup' => $this->lineup]), []);
        $response->assertStatus(403);

        $lineup = $this->setLineup(true);

        $response = $this->actingAs($this->manager)->post(route('update_lineup', ['locale' => 'en', 'lineup' => $this->lineup]), $lineup);
        $response->assertOk();
    }

    private function create_lineup()
    {
        $this->manager = factory(User::class)->create();
        $this->club = factory(\App\Models\Club::class)->create();
        factory(\App\ManagerContract::class)->create([
            'user_id' => $this->manager->id, 'club_id' => $this->club->id,
        ]);

        $this->players = factory(Player::class)->times(17)->create();

        $lineupData = $this->setLineup();

        $lineupData['club_id'] = $this->club->id;

        $this->lineup = factory(\App\Models\Lineup::class)->create($lineupData);
    }

    private function setLineup(bool $shuffle = false): array
    {
        $positions = getPositionsExceptGoalkeeper();
        shuffle($positions);

        $playerIds = [];
        foreach ($this->players as $player) {
            $playerIds[] = $player->id;
        }

        if ($shuffle) {
            shuffle($playerIds);
        }

        $lineupData = [];
        for ($i = 0; $i < count($playerIds); $i++) {
            if ($i === 0) {
                $position = 'GK';
            }
            if ($i + 1 < 12) {
                if ($i !== 0) {
                    $position = $positions[$i];
                }
                $positionKey = 'position_'.($i + 1);
                $playerKey = 'player_'.($i + 1);

            } else {
                $playerKey = 'substitute_'.($i - 10);
            }
            $lineupData[$playerKey] = $playerIds[$i];
            if (isset($positionKey)) {
                $lineupData[$positionKey] = $position;
            }
        }

        return $lineupData;
    }
}

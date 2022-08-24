<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;
use App\Models\TournamentGame;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @see \App\Http\Controllers\TournamentGameController
 */
class TournamentGameControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function show_returns_an_ok_response()
    {
        Artisan::call('make:tournament', ['name' => 'Test', 'teams' => 4]);

        $tournament = TournamentGame::find(1);

        $response = $this->get(route('show_game', ['locale' => 'en', 'game' => $tournament->id]));
        $response->assertOk();
        $response->assertSee($tournament->name);
    }

}

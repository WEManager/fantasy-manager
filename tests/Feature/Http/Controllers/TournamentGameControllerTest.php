<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use App\Models\Fixture;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\TournamentGameController
 */
final class TournamentGameControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function show_returns_an_ok_response()
    {
        Artisan::call('make:tournament', ['name' => 'Test', 'teams' => 4]);

        $tournament = Fixture::find(1);

        $response = $this->get(route('show_game', ['locale' => 'en', 'game' => $tournament->id]));
        $response->assertOk();
        $response->assertSee($tournament->name);
    }
}

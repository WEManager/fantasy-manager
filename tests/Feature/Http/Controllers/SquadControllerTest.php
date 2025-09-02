<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\SquadController
 */
final class SquadControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function show_returns_an_ok_response()
    {
        $club = factory(\App\Models\Club::class)->create();

        $response = $this->get(route('show_club_players', [$club]));

        $response->assertOk();
        $response->assertSee($club->name);
    }
}

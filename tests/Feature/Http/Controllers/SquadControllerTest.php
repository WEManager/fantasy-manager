<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @see \App\Http\Controllers\SquadController
 */
class SquadControllerTest extends TestCase
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

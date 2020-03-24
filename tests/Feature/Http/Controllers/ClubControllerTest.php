<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\ClubController
 */
class ClubControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function index_returns_an_ok_response()
    {
        //$this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $club = factory(\App\Club::class)->create();

        $this->get(route('list_clubs', ['locale' => 'en']))
            ->assertSee($club->name);
    }

    /**
     * @test
     */
    public function show_returns_an_ok_response()
    {
        $club = factory(\App\Club::class)->create();

        $this->assertDatabaseHas('clubs', ['name' => $club->name]);

        $this->get(route('show_club', ['locale' => 'en', 'club' => $club]))
            ->assertOk()
            ->assertSee($club->name);

    }

    // test cases...
}

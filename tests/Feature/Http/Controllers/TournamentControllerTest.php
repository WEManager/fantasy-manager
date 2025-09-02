<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use App\Models\Club;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\TournamentController
 */
final class TournamentControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function create_returns_an_ok_response()
    {
        $response = $this->getJson(route('tournaments.create'));

        $response->assertOk();
        $response->assertViewIs('tournaments.create');
        $response->assertViewHas('clubs');
    }

    /**
     * @test
     */
    public function index_returns_an_ok_response()
    {
        $response = $this->get(route('list_tournaments', ['locale' => 'en']));

        $response->assertOk();
        $response->assertViewIs('tournaments.index');
        $response->assertViewHas('tournaments');
    }

    /**
     * @test
     */
    public function show_returns_an_ok_response()
    {
        $tournament = factory(\App\Models\Tournament::class)->create();

        $response = $this->get(route('tournament.show', [$tournament]));

        $response->assertOk();
        $response->assertViewIs('tournaments.show');
    }

    /**
     * @test
     */
    public function store_returns_an_ok_response()
    {
        $tournament = factory(\App\Models\Tournament::class)->make()->toArray();

        $response = $this->post(route('store_tournament', ['locale' => 'en']), $tournament);
        $response->assertRedirect('en/login');

        $clubs = factory(Club::class)->times(4)->create();
        $tournament['selectedClubs'] = [];
        foreach ($clubs as $club) {
            $tournament['selectedClubs'][] = $club->id;
        }
        $tournament['participants'] = 4;
        $tournament['recurringEveryOfYear'] = $tournament['recurring_every_of_year'];
        $tournament['competitionType'] = $tournament['type'];
        $tournament['playOffs'] = $tournament['playoffs'];
        $tournament['season'] = 1;

        $user = factory(User::class)->create();
        $post = $this->actingAs($user)->post(route('store_tournament', ['locale' => 'en']), $tournament);
        echo $post->exception;

        unset($tournament['selectedClubs']);
        unset($tournament['recurringEveryOfYear']);
        unset($tournament['competitionType']);
        unset($tournament['playOffs']);
        $this->assertDatabaseHas('tournaments', ['name' => $tournament['name']]);
    }

    // test cases...
}

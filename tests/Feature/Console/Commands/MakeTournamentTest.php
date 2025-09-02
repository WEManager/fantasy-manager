<?php

declare(strict_types=1);

namespace Tests\Feature\Console\Commands;

use App\Models\Tournament;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

/**
 * @see \App\Console\Commands\MakeTournament
 */
final class MakeTournamentTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function it_runs_successfully()
    {
        $this->assertDatabaseMissing('tournaments', ['id' => 1]);

        $tournament = factory(Tournament::class)->make();

        $this->artisan('make:tournament', [
            'name' => $tournament->name,
            'type' => $tournament->type,
            'teams' => $tournament->participants,
            'groups' => $tournament->groups,
            'locale' => $tournament->nationality,
        ])
            ->assertExitCode(0)
            ->run();

        $this->assertDatabaseHas('tournaments', ['id' => 1]);
    }
}

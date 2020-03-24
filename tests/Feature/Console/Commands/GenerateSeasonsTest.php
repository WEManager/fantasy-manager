<?php

namespace Tests\Feature\Console\Commands;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @see \App\Console\Commands\GenerateSeasons
 */
class GenerateSeasonsTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function it_runs_successfully()
    {
        // The database is already seeded with season 1
        $this->assertDatabaseMissing('seasons', ['id' => 2]);

        $this->artisan('season:generate')
            ->assertExitCode(0)
            ->run();

        $this->assertDatabaseHas('seasons', ['id' => 2]);
    }
}


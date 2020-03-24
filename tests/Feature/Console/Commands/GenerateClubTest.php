<?php

namespace Tests\Feature\Console\Commands;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @see \App\Console\Commands\GenerateClub
 */
class GenerateClubTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function it_runs_successfully()
    {
        $this->assertDatabaseMissing('clubs', ['id' => 1]);

        $this->artisan('club:generate')
            ->assertExitCode(0)
            ->run();

        $this->assertDatabaseHas('clubs', ['id' => 1]);
    }
}


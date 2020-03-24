<?php

namespace Tests\Feature\Console\Commands;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @see \App\Console\Commands\GeneratePlayer
 */
class GeneratePlayerTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function it_runs_successfully()
    {
        $this->assertDatabaseMissing('people', ['id' => 1]);

        $this->artisan('player:generate')
            ->assertExitCode(0)
            ->run();

        $this->assertDatabaseHas('people', ['id' => 1]);
    }
}


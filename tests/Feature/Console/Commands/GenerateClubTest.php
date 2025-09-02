<?php

declare(strict_types=1);

namespace Tests\Feature\Console\Commands;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

/**
 * @see \App\Console\Commands\GenerateClub
 */
final class GenerateClubTest extends TestCase
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

<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

/**
 * @see \App\Http\Requests\StoreTournament
 */
final class StoreTournamentTest extends TestCase
{
    use DatabaseMigrations;

    /** @var \App\Http\Requests\StoreTournament */
    private $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = new \App\Http\Requests\StoreTournament();
    }

    /**
     * @test
     */
    public function authorize()
    {
        $actual = $this->subject->authorize();
        $this->assertFalse($actual);

        $user = factory(User::class)->create();
        $actual = $this->actingAs($user)->subject->authorize();
        $this->assertTrue($actual);
    }

    /**
     * @test
     */
    public function rules()
    {
        $actual = $this->subject->rules();

        $this->assertEquals([
            'name' => 'required',
            'team' => 'required',
            'groups' => 'integer',
            'playOffs' => 'required|boolean',
            'competitionType' => 'required',
            'selectedClubs' => 'required',
            'recurringEveryOfYear' => 'required|integer',
            'proceedingToPlayoffs' => 'integer',
        ], $actual);
    }
}

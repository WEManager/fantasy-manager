<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

/**
 * @see \App\Http\Requests\StorePlayerUser
 */
final class StorePlayerUserTest extends TestCase
{
    use DatabaseMigrations;

    /** @var \App\Http\Requests\StorePlayerUser */
    private $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = new \App\Http\Requests\StorePlayerUser();
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
            'email' => 'required|email',
        ], $actual);
    }

    // test cases...
}

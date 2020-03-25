<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @see \App\Http\Controllers\FederationController
 */
class FederationControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function create_returns_an_ok_response()
    {
        $response = $this->get(route('create_federation', ['locale' => 'en']));

        $response->assertOk();
        $response->assertViewIs('federations.create');
    }

    // test cases...
}

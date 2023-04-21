<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class LoginTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_that_true_is_true(): void
    {
        $response = $this->json('POST', '/api/v1/admin/login', [
            'email'        => 'admin@buckhill.co.uk',
            'password'     => 'admin'
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                         'data' => true // check if data is returned
                 ]);
    }
}

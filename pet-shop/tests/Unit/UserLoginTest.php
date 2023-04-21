<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class UserLoginTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_that_true_is_true(): void
    {
        $response = $this->json('POST', '/api/v1/admin/login', [
            'email'        => 'christine.elia@hotmail.com',
            'password'     => '123456789'
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                         'data' => true // check if data is returned
                 ]);
    }
}

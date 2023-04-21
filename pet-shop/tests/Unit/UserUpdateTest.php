<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class UserUpdateTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_that_true_is_true(): void
    {
         // Arrange
         $data = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'avatar' => '1234-5678-9012-3456',
            'address' => '123 Main St.',
            'phone_number' => '555-1234',
            'marketing' => '1'
        ];

        $response = $this->put('/api/v1/user/edit', $data);

        $response->assertStatus(401);
    }
}

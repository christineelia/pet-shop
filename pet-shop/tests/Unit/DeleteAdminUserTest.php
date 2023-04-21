<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class DeleteAdminUserTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_that_true_is_true(): void
    {
        $response = $this->json('GET', '/api/v1/admin/user-delete/12121');

        $response->assertStatus(405);
    }
}

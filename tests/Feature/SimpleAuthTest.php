<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SimpleAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_access_login_endpoint(): void
    {
        $response = $this->postJson('/api/auth/login', [
            'username' => 'testuser',
            'password' => 'password123'
        ]);

        // Should return validation error or invalid credentials
        $this->assertTrue($response->status() === 400 || $response->status() === 401);
    }

    public function test_login_requires_username_and_password(): void
    {
        $response = $this->postJson('/api/auth/login', []);

        $response->assertStatus(400);
    }
}

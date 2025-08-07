<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test users
        User::create([
            'username' => 'testadmin',
            'password_hash' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        User::create([
            'username' => 'testuser',
            'password_hash' => Hash::make('user123'),
            'role' => 'user',
        ]);
    }

    /** @test */
    public function it_can_login_with_valid_credentials()
    {
        $response = $this->postJson('/api/auth/login', [
            'username' => 'testadmin',
            'password' => 'admin123',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Login successful',
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'access_token',
                    'token_type',
                    'expires_in',
                    'user' => [
                        'id',
                        'username',
                        'role',
                    ]
                ]
            ]);

        $this->assertEquals('bearer', $response->json('data.token_type'));
        $this->assertEquals('testadmin', $response->json('data.user.username'));
        $this->assertEquals('admin', $response->json('data.user.role'));
    }

    /** @test */
    public function it_cannot_login_with_invalid_credentials()
    {
        $response = $this->postJson('/api/auth/login', [
            'username' => 'testadmin',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Invalid credentials',
            ]);
    }

    /** @test */
    public function it_requires_username_and_password_for_login()
    {
        $response = $this->postJson('/api/auth/login', []);

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => 'Validation failed',
            ])
            ->assertJsonValidationErrors(['username', 'password']);
    }

    /** @test */
    public function it_can_get_authenticated_user_info()
    {
        $user = User::where('username', 'testuser')->first();
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/auth/me');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'username' => 'testuser',
                        'role' => 'user',
                    ]
                ]
            ]);
    }

    /** @test */
    public function it_can_logout_successfully()
    {
        $user = User::where('username', 'testuser')->first();
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/auth/logout');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Successfully logged out',
            ]);
    }

    /** @test */
    public function it_can_refresh_token()
    {
        $user = User::where('username', 'testuser')->first();
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/auth/refresh');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonStructure([
                'success',
                'data' => [
                    'access_token',
                    'token_type',
                    'expires_in',
                ]
            ]);

        $this->assertEquals('bearer', $response->json('data.token_type'));
    }

    /** @test */
    public function it_requires_authentication_for_protected_routes()
    {
        $response = $this->getJson('/api/auth/me');
        $response->assertStatus(401);

        $response = $this->postJson('/api/auth/logout');
        $response->assertStatus(401);

        $response = $this->postJson('/api/auth/refresh');
        $response->assertStatus(401);
    }
}

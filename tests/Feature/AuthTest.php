<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_returns_token()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Alice',
            'email' => 'alice@example.test',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['user', 'access_token']);

        $this->assertDatabaseHas('users', ['email' => 'alice@example.test']);
    }

    public function test_login_returns_token()
    {
        $user = User::factory()->create(['password' => 'password']);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['access_token']);
    }

    public function test_protected_route_requires_auth()
    {
        $this->getJson('/api/me')->assertStatus(401);
    }

    public function test_me_returns_user_with_token()
    {
        $user = User::factory()->create();
        $token = auth('api')->login($user);

        $this->withHeaders(['Authorization' => 'Bearer ' . $token])
             ->getJson('/api/me')
             ->assertStatus(200)
             ->assertJson(['email' => $user->email]);
    }
}

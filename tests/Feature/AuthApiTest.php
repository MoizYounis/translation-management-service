<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_correct_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'status' => true,
                'message' => 'Login successfully.',
            ])
            ->assertJsonPath('data.user.email', 'test@example.com')
            ->assertJsonStructure([
                'data' => [
                    'user' => ['id', 'name', 'email'],
                    'token',
                ]
            ]);

        $this->assertIsString($response->json('data.token'));
    }

    public function test_user_cannot_login_with_wrong_password()
    {
        $user = User::factory()->create([
            'email' => 'wrong@example.com',
            'password' => bcrypt('correctpass'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'wrong@example.com',
            'password' => 'wrongpass',
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'status' => false,
                'message' => 'Password does not match.',
            ]);
    }


    public function test_user_can_logout()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJsonFragment([
                'status' => true,
                'message' => 'Logout successfully.',
            ]);
    }
}

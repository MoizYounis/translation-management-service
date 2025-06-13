<?php

namespace Tests\Unit;

use App\Exceptions\CustomException;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthServiceTest extends TestCase
{
    use RefreshDatabase;

    protected AuthService $authService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authService = new AuthService();
    }

    public function test_it_returns_user_on_valid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'unit@example.com',
            'password' => bcrypt('12345678'),
        ]);

        $result = $this->authService->login([
            'email' => 'unit@example.com',
            'password' => '12345678',
        ]);

        $this->assertEquals($user->id, $result->id);
    }

    public function test_it_throws_exception_on_invalid_email()
    {
        $this->expectException(CustomException::class);
        $this->expectExceptionMessage(__('lang.messages.email_exception'));

        $this->authService->login([
            'email' => 'notfound@example.com',
            'password' => 'any',
        ]);
    }

    public function test_it_throws_exception_on_invalid_password()
    {
        User::factory()->create([
            'email' => 'unit2@example.com',
            'password' => bcrypt('correctpassword'),
        ]);

        $this->expectException(CustomException::class);
        $this->expectExceptionMessage(__('lang.messages.password_exception'));

        $this->authService->login([
            'email' => 'unit2@example.com',
            'password' => 'wrongpassword',
        ]);
    }

    public function test_it_can_logout_user_and_delete_tokens()
    {
        $user = User::factory()->create();
        $user->createToken('test-token');

        $this->authService->logout($user);

        $this->assertCount(0, $user->tokens);
    }
}

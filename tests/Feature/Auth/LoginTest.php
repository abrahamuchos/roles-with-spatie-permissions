<?php

namespace Tests\Feature\Auth;

use App\Enums\RolesEnum;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create([
            'email' => 'test@example.com',
        ]);
    }

    public function test_user_can_login()
    {
        $credentials = [
            'email' => $this->user->email,
            'password' => 'password',
        ];

        $response = $this->postJson("{$this->apiBase}/login", $credentials);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'user' => [
                    'id',
                    'name',
                    'email',
                    'roles',
                ],
                'token',
                'tokenType',
                'expiresAt',
            ]
        ]);
    }


    public function test_user_cannot_login_with_invalid_password()
    {
        $credentials = [
            'email' => $this->user->email,
            'password' => 'wrong-password',
        ];

        $response = $this->postJson("{$this->apiBase}/login", $credentials);

        $response->assertStatus(401);
    }

    public function test_user_cannot_login_with_invalid_email()
    {
        $credentials = [
            'email' => 'email-not-found@example.com',
            'password' => 'wrong-password',
        ];

        $response = $this->postJson("{$this->apiBase}/login", $credentials);

        $response->assertStatus(422);
    }

    public function test_email_is_required()
    {
        $credentials = [
            'email' => null,
            'password' => 'wrong-password',
        ];

        $response = $this->postJson("{$this->apiBase}/login", $credentials);

        $response->assertStatus(422);
    }


}

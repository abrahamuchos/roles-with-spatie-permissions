<?php

namespace Tests\Feature\User;

use App\Enums\RolesEnum;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CreateUserTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin, $user, $guest;
    protected array $data;

    public function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create();
        $this->user = User::factory()->create();
        $this->guest = User::factory()->create();

        $this->admin->assignRole(RolesEnum::ADMIN);
        $this->user->assignRole(RolesEnum::USER);
        $this->guest->assignRole(RolesEnum::GUEST);

        $this->data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => RolesEnum::USER->value,
        ];
    }

    public function test_unauthenticated_user_cannot_create_user()
    {
        $response = $this->postJson("$this->apiBase/users", $this->data);

        $response->assertUnauthorized();
    }

    public function test_admin_can_create_user()
    {
        Sanctum::actingAs($this->admin);

        $response = $this->postJson("$this->apiBase/users", $this->data);

        $response->assertCreated();
    }

    public function test_user_cannot_create_user()
    {
        Sanctum::actingAs($this->user);

        $response = $this->postJson("$this->apiBase/users", $this->data);

        $response->assertForbidden();
    }

    public function test_guest_cannot_create_user()
    {
        Sanctum::actingAs($this->guest);

        $response = $this->postJson("$this->apiBase/users", $this->data);

        $response->assertForbidden();
    }

}

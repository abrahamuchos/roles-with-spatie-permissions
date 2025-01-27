<?php

namespace Tests\Feature\User;

use App\Enums\RolesEnum;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DeleteUserTest extends TestCase
{
    use RefreshDatabase;

    protected User $superAdmin, $admin, $user, $guest;
    protected array $data;

    public function setUp(): void
    {
        parent::setUp();
        $this->superAdmin = User::factory()->create();
        $this->admin = User::factory()->create();
        $this->user = User::factory()->create();
        $this->guest = User::factory()->create();

        $this->superAdmin->assignRole(RolesEnum::SUPER_ADMIN);
        $this->admin->assignRole(RolesEnum::ADMIN);
        $this->user->assignRole(RolesEnum::USER);
        $this->guest->assignRole(RolesEnum::GUEST);

        $this->data = [
            'name' => 'John Doe'
        ];
    }

    public function test_unauthenticated_user_cannot_edit_user()
    {
        $response = $this->deleteJson("$this->apiBase/users/{$this->user->id}", $this->data);

        $response->assertUnauthorized();
    }

    public function test_super_admin_can_edit_user()
    {
        Sanctum::actingAs($this->superAdmin);

        $response = $this->deleteJson("$this->apiBase/users/{$this->user->id}", $this->data);

        $response->assertStatus(200);
    }

    public function test_admin_cannot_edit_user()
    {
        Sanctum::actingAs($this->admin);

        $response = $this->deleteJson("$this->apiBase/users/{$this->user->id}", $this->data);

        $response->assertForbidden();
    }

    public function test_user_cannot_delete_user()
    {
        Sanctum::actingAs($this->user);

        $response = $this->deleteJson("$this->apiBase/users/{$this->user->id}", $this->data);

        $response->assertForbidden();
    }

    public function test_guest_cannot_delete_user()
    {
        Sanctum::actingAs($this->guest);

        $response = $this->deleteJson("$this->apiBase/users/{$this->user->id}", $this->data);

        $response->assertForbidden();
    }
}

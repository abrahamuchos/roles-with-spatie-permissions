<?php

namespace Tests\Feature\User;

use App\Enums\RolesEnum;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ListUserTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin, $user, $guest;

    public function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create();
        $this->user = User::factory()->create();
        $this->guest = User::factory()->create();

        User::factory(10)->create();

        $this->admin->assignRole(RolesEnum::ADMIN);
        $this->user->assignRole(RolesEnum::USER);
        $this->guest->assignRole(RolesEnum::GUEST);
    }

    public function test_unauthenticated_user_cannot_list_users()
    {
        $response = $this->getJson("$this->apiBase/users");

        $response->assertStatus(401);
    }

    public function test_admin_role_can_list_users()
    {
        Sanctum::actingAs($this->admin);

        $response = $this->getJson("$this->apiBase/users");

        $response->assertStatus(200);
        $response->assertJsonCount(13, 'data');
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'email',
                ]
            ]
        ]);
    }

    public function test_user_role_cannot_list_users()
    {
        Sanctum::actingAs($this->user);

        $response = $this->getJson("$this->apiBase/users");

        $response->assertStatus(200);
        $response->assertJsonCount(13, 'data');
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'email',
                ]
            ]
        ]);
    }

    public function test_guest_role_cannot_list_users()
    {
        Sanctum::actingAs($this->guest);

        $response = $this->getJson("$this->apiBase/users");

        $response->assertStatus(403);
    }

}

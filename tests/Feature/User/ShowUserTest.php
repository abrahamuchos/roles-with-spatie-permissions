<?php

namespace Tests\Feature\User;

use App\Enums\RolesEnum;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ShowUserTest extends TestCase
{
    use RefreshDatabase;

    protected User $user, $admin, $guest;

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

    public function test_unauthenticated_user_cannot_access_show_user()
    {
        $response = $this->getJson("$this->apiBase/users/{$this->user->id}");

        $response->assertUnauthorized();
    }

    public function test_admin_can_access_show_any_user()
    {
        Sanctum::actingAs($this->admin);

        $response = $this->getJson("$this->apiBase/users/{$this->user->id}");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email',
            ]
        ]);
    }

    public function test_user_can_access_show_own_user()
    {
        Sanctum::actingAs($this->user);

        $response = $this->getJson("$this->apiBase/users/{$this->user->id}");

        $response->assertStatus(200);
        $response->assertJsonPath('data.id', $this->user->id);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email',
            ]
        ]);
    }

    public function test_user_cannot_access_show_other_user()
    {
        Sanctum::actingAs($this->user);

        $response = $this->getJson("$this->apiBase/users/{$this->admin->id}");

        $response->assertStatus(403);
    }

    public function test_guest_user_cannot_access_show_user()
    {
        Sanctum::actingAs($this->guest);

        $response = $this->getJson("$this->apiBase/users/{$this->user->id}");

        $response->assertStatus(403);
    }


}

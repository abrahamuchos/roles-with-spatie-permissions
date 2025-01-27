<?php

namespace Tests\Feature\Post;

use App\Enums\RolesEnum;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DeletePostTest extends TestCase
{
    use RefreshDatabase;

    protected User $user, $admin, $guest;
    protected Post $post;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->admin = User::factory()->create();
        $this->guest = User::factory()->create();
        $this->post = Post::factory()->create([
            'user_id' => $this->user->id
        ]);

        $this->user->assignRole(RolesEnum::USER);
        $this->admin->assignRole(RolesEnum::ADMIN);
        $this->guest->assignRole(RolesEnum::GUEST);
    }

    public function test_unauthenticated_user_cannot_delete_post()
    {
        $response = $this->deleteJson("$this->apiBase/posts/{$this->post->id}");

        $response->assertStatus(401);
    }

    public function test_admin_role_can_delete_post()
    {
        Sanctum::actingAs($this->admin);

        $response = $this->deleteJson("$this->apiBase/posts/{$this->post->id}");

        $response->assertStatus(204);
    }

    public function test_user_role_can_delete_post()
    {
        Sanctum::actingAs($this->user);

        $response = $this->deleteJson("$this->apiBase/posts/{$this->post->id}");

        $response->assertStatus(204);
    }

    public function test_guest_role_cannot_delete_post()
    {
        Sanctum::actingAs($this->guest);

        $response = $this->deleteJson("$this->apiBase/posts/{$this->post->id}");

        $response->assertStatus(403);
    }

    public function test_user_role_cannot_delete_other_user_post()
    {
        Sanctum::actingAs($this->user);

        $post = Post::factory()->create([
            'user_id' => $this->admin->id
        ]);

        $response = $this->deleteJson("$this->apiBase/posts/{$post->id}");

        $response->assertStatus(403);
    }
}

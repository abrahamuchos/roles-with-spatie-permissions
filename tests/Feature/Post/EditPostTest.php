<?php

namespace Tests\Feature\Post;

use App\Enums\RolesEnum;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class EditPostTest extends TestCase
{
    use RefreshDatabase;

    protected User $user, $admin, $guest;
    protected Post $post;
    protected array $data;

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

        $this->data = [
            'title' => 'New Title',
            'content' => 'New Content'
        ];
    }

    public function test_unauthenticated_user_cannot_edit_post()
    {
        $response = $this->patchJson("$this->apiBase/posts/{$this->post->id}", $this->data);

        $response->assertStatus(401);
    }

    public function test_guest_cannot_edit_post()
    {
        Sanctum::actingAs($this->guest);

        $response = $this->patchJson("$this->apiBase/posts/{$this->post->id}", $this->data);

        $response->assertStatus(403);
    }

    public function test_admin_can_edit_post()
    {
        Sanctum::actingAs($this->admin);

        $response = $this->patchJson("$this->apiBase/posts/{$this->post->id}", $this->data);

        $response->assertStatus(200);
    }

    public function test_user_can_edit_own_post()
    {
        Sanctum::actingAs($this->user);

        $response = $this->patchJson("$this->apiBase/posts/{$this->post->id}", $this->data);

        $response->assertStatus(200);
    }

    //Others test cases about form validation
}

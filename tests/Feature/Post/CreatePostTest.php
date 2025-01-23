<?php

namespace Tests\Feature\Post;

use App\Enums\RolesEnum;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CreatePostTest extends TestCase
{
    use RefreshDatabase;

    protected User $user, $admin, $guest;
    protected array $data;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()
            ->create([
                'email' => 'user.test@mail.com'
            ]);
        $this->admin = User::factory()
            ->create([
                'email' => 'admin.test@mail.com'
            ]);
        $this->guest = User::factory()
            ->create([
                'email' => 'guest.test@mail.com'
            ]);


        $this->user->assignRole(RolesEnum::USER);
        $this->admin->assignRole(RolesEnum::ADMIN);
        $this->guest->assignRole(RolesEnum::GUEST);

        $this->data = [
            'user_id' => $this->user->id,
            'title' => 'Post title',
            'content' => 'Post content',
            'slug' =>\Str::slug('post title'),
            'image' => 'image.jpg',
        ];
    }

    public function test_unauthenticated_user_cannot_create_post()
    {
        $response = $this->postJson("$this->apiBase/posts", $this->data);

        $response->assertStatus(401);

    }

    public function test_admin_role_can_create_post()
    {
        Sanctum::actingAs($this->admin);

        $response = $this->postJson("$this->apiBase/posts", $this->data);

        $response->assertStatus(201);
    }

    public function test_user_role_can_create_post()
    {
        Sanctum::actingAs($this->user);

        $response = $this->postJson("$this->apiBase/posts", $this->data);

        $response->assertStatus(201);
    }

    public function test_guest_role_cannot_create_post()
    {
        Sanctum::actingAs($this->guest);

        $response = $this->postJson("$this->apiBase/posts", $this->data);

        $response->assertStatus(403);
    }

    //Others test cases about form validation

}

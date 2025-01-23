<?php

namespace Tests\Feature\Post;

use App\Enums\RolesEnum;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ListPostsTest extends TestCase
{
    use RefreshDatabase;

    protected User $user, $otherUser;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()
            ->has(Post::factory()->count(3))
            ->create([
                'email' => 'user.test@mail.com'
            ]);
        $this->otherUser = User::factory()
            ->has(Post::factory()->count(3))
            ->create([
                'email' => 'other.user@mail.com'
            ]);

        $this->user->assignRole(RolesEnum::USER);
        $this->otherUser->assignRole(RolesEnum::USER);
    }

    public function test_user_role_can_list_own_posts()
    {
        Sanctum::actingAs($this->user);

        $response = $this->getJson("$this->apiBase/posts");

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'content',
                    'createdAt',
                    'updatedAt',
                ]
            ]
        ]);
    }

    public function test_guest_role_can_list_all_posts()
    {
        $guest = User::factory()->create([
            'email' => 'guest@mail.com'
        ]);
        $guest->assignRole(RolesEnum::GUEST);
        Sanctum::actingAs($guest);

        $response = $this->getJson("$this->apiBase/posts");

        $response->assertStatus(200);
        $response->assertJsonCount(6, 'data');
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'content',
                    'createdAt',
                    'updatedAt',
                ]
            ]
        ]);
    }

    public function test_admin_role_can_list_all_posts()
    {
        $admin = User::factory()->create([
            'email' => 'admin@mail.com'
        ]);
        $admin->assignRole(RolesEnum::ADMIN);
        Sanctum::actingAs($admin);

        $response = $this->getJson("$this->apiBase/posts");

        $response->assertStatus(200);
        $response->assertJsonCount(6, 'data');
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'content',
                    'createdAt',
                    'updatedAt',
                ]
            ]
        ]);
    }


}

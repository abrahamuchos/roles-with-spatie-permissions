<?php

namespace Database\Seeders;

use App\Enums\RolesEnum;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //SuperAdmin User
        $superAdminUser = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'superadmin@mail.com',
        ]);
        $superAdminUser->assignRole(RolesEnum::SUPER_ADMIN);

        //Admin User
        $adminUser = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@mail.com'
        ]);
        $adminUser->assignRole(RolesEnum::ADMIN);

        //User
        $users = User::factory(10)
            ->has(Post::factory(5), 'posts')
            ->create();
        $users->each(fn($user) => $user->assignRole(RolesEnum::USER));
    }
}

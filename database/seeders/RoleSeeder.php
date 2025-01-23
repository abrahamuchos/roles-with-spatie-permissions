<?php

namespace Database\Seeders;

use App\Enums\PermissionsEnum;
use App\Enums\RolesEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Super Admin Role
        Role::create(['name' => RolesEnum::SUPER_ADMIN]);


        //Admin Role
        $adminRole = Role::create(['name' => RolesEnum::ADMIN]);
        $adminPermissions = Permission::query()
            ->where('name', '!=', PermissionsEnum::DELETE_USER)
            ->pluck('name');
        $adminRole->syncPermissions($adminPermissions);

        //User
        $userRole = Role::create(['name' => RolesEnum::USER]);
        $userRole->syncPermissions([
            PermissionsEnum::VIEW_USER,
            PermissionsEnum::EDIT_USER,
            PermissionsEnum::CREATE_POSTS,
            PermissionsEnum::VIEW_POSTS,
            PermissionsEnum::EDIT_POSTS,
            PermissionsEnum::DELETE_POSTS,
        ]);

        //Guest user
        $guestUser = Role::create(['name' => RolesEnum::GUEST]);
        $guestUser->syncPermissions([
            PermissionsEnum::VIEW_POSTS
        ]);
    }
}

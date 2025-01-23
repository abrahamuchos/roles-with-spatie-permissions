<?php

namespace Database\Seeders;

use App\Enums\PermissionsEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // User PermissionsEnum
        Permission::create(['name' => PermissionsEnum::VIEW_USER]);
        Permission::create(['name' => PermissionsEnum::CREATE_USER]);
        Permission::create(['name' => PermissionsEnum::EDIT_USER]);
        Permission::create(['name' => PermissionsEnum::DELETE_USER]);

        //Posts PermissionsEnum
        Permission::create(['name' => PermissionsEnum::VIEW_POSTS]);
        Permission::create(['name' => PermissionsEnum::CREATE_POSTS]);
        Permission::create(['name' => PermissionsEnum::EDIT_POSTS]);
        Permission::create(['name' => PermissionsEnum::DELETE_POSTS]);
    }
}

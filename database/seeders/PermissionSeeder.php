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

        //Books PermissionsEnum
        Permission::create(['name' => PermissionsEnum::VIEW_BOOKS]);
        Permission::create(['name' => PermissionsEnum::CREATE_BOOKS]);
        Permission::create(['name' => PermissionsEnum::EDIT_BOOKS]);
        Permission::create(['name' => PermissionsEnum::DELETE_BOOKS]);
    }
}

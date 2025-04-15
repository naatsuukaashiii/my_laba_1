<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['name' => 'Get user list', 'code' => 'get-list-users'],
            ['name' => 'Read user', 'code' => 'read-user'],
            ['name' => 'Create user', 'code' => 'create-user'],
            ['name' => 'Update user', 'code' => 'update-user'],
            ['name' => 'Delete user', 'code' => 'delete-user'],

            ['name' => 'Get role list', 'code' => 'get-list-roles'],
            ['name' => 'Read role', 'code' => 'read-role'],
            ['name' => 'Create role', 'code' => 'create-role'],
            ['name' => 'Update role', 'code' => 'update-role'],
            ['name' => 'Delete role', 'code' => 'delete-role'],

            ['name' => 'Get permission list', 'code' => 'get-list-permissions'],
            ['name' => 'Read permission', 'code' => 'read-permission'],
            ['name' => 'Create permission', 'code' => 'create-permission'],
            ['name' => 'Update permission', 'code' => 'update-permission'],
            ['name' => 'Delete permission', 'code' => 'delete-permission'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
}

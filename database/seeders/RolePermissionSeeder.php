<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Role::where('code', 'admin')->first();
        $user = Role::where('code', 'user')->first();
        $guest = Role::where('code', 'guest')->first();

        $admin->permissions()->attach(Permission::all());

        $userPermissions = Permission::whereIn('code', [
            'get-list-users',
            'read-user',
            'update-user',
        ])->get();
        $user->permissions()->attach($userPermissions);

        $guestPermissions = Permission::where('code', 'get-list-users')->get();
        $guest->permissions()->attach($guestPermissions);
    }
}

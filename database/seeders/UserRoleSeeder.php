<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Role;

class UserRoleSeeder extends Seeder
{
    public function run()
    {

        $adminRole = Role::where('code', 'admin')->first();
        $guestRole = Role::where('code', 'guest')->first();

        $adminUser = User::where('email', 'adminnim@example.com')->first();
        $regularUser = User::where('email', 'testtest@example.com')->first();

        if ($adminRole && $adminUser) {

            DB::table('user_role')->updateOrInsert(
                ['user_id' => $adminUser->id, 'role_id' => $adminRole->id],
                [
                    'deleted_at' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        if ($guestRole && $regularUser) {
            DB::table('user_role')->updateOrInsert(
                ['user_id' => $regularUser->id, 'role_id' => $guestRole->id],
                [
                    'deleted_at' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}

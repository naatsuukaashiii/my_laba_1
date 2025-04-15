<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,

        ]);

        User::create([
            'username' => 'TestUser',
            'email' => 'testtest@example.com',
            'password' => Hash::make('Password123!!'),
            'birthday' => '2002-10-10'
        ]);

        User::create([
            'username' => 'Adminnim',
            'email' => 'adminnim@example.com',
            'password' => Hash::make('Password123!!'),
            'birthday' => '2002-10-10'
        ]);
        $this->call([RoleSeeder::class ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Auth\Role;
use App\Models\Auth\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $managerRole = Role::firstOrCreate(
            ['name' => 'manager'],
            ['display_name' => 'Manager', 'description' => 'Manager role']
        );

        $adminRole = Role::firstOrCreate(
            ['name' => 'admin'],
            ['display_name' => 'Admin', 'description' => 'Admin role']
        );

        $admin = User::firstOrCreate(
            ['email' => 'admin@frostpos.com'],
            [
                'name' => 'Admin',
                'password' => 'password',
                'store' => 1,
            ]
        );

        $admin->roles()->syncWithoutDetaching([$managerRole->id, $adminRole->id]);
    }
}

<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin',
                'email' => 'admin@frostpos.com',
                'password' => bcrypt('password'),
                'store' => '1',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ],
        ]);

        DB::table('roles')->insert([
            [
                'name' => 'manager',
                'display_name' => 'Manager',
                'description' => ' ',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime()
            ],
            [
                'name' => 'admin',
                'display_name' => 'Admin',
                'description' => ' ',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime()
            ]
        ]);

        DB::table('role_user')->insert([
            [
                'user_id' => '1',
                'role_id' => '1'
            ],
            [
                'user_id' => '1',
                'role_id' => '2'
            ]
        ]);
    }
}

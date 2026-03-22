<?php

namespace Tests\Feature;

use App\Models\Auth\Role;
use App\Models\Auth\User;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\UserTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_seeder_calls_user_table_seeder()
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseHas('users', [
            'email' => 'admin@frostpos.com',
            'name' => 'Admin',
        ]);
    }

    public function test_user_table_seeder_creates_default_admin_with_roles()
    {
        $this->seed(UserTableSeeder::class);

        $admin = User::where('email', 'admin@frostpos.com')->first();

        $this->assertNotNull($admin);
        $this->assertTrue($admin->hasRole('admin'));
        $this->assertTrue($admin->hasRole('manager'));
    }

    public function test_user_table_seeder_is_idempotent()
    {
        $this->seed(UserTableSeeder::class);
        $this->seed(UserTableSeeder::class);

        $this->assertEquals(1, User::where('email', 'admin@frostpos.com')->count());
        $this->assertEquals(1, Role::where('name', 'admin')->count());
        $this->assertEquals(1, Role::where('name', 'manager')->count());
    }

    public function test_seeded_admin_password_is_hashed_via_cast()
    {
        $this->seed(UserTableSeeder::class);

        $admin = User::where('email', 'admin@frostpos.com')->first();

        $this->assertNotEquals('password', $admin->password);
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('password', $admin->password));
    }
}

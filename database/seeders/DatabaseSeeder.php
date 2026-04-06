<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Model::unguard();

        $this->call([
            UserTableSeeder::class,
            ComprehensiveSeeder::class,
        ]);

        Model::reguard();
    }
}

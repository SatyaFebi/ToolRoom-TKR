<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);

        User::factory()->create([
            'name' => 'user 1',
            'email' => 'user1@gmail.com',
            'password' => bcrypt('12345678'),
            'role_id' => 1
        ]);

        User::factory()->create([
            'name' => 'user 2',
            'email' => 'user2@gmail.com',
            'password' => bcrypt('12345678'),
            'role_id' => 2
        ]);

                User::factory()->create([
            'name' => 'user 3',
            'email' => 'user3@gmail.com',
            'password' => bcrypt('12345678'),
            'role_id' => 3
        ]);
                User::factory()->create([
            'name' => 'user 4',
            'email' => 'user4@gmail.com',
            'password' => bcrypt('12345678'),
            'role_id' => 4
        ]);

    }
}

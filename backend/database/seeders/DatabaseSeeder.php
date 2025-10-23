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
            'name' => 'aldy',
            'email' => 'dy@mail.com',
            'password' => bcrypt('12345678'),
            'role_id' => 1
        ]);

        User::factory()->create([
            'name' => 'satya',
            'email' => 'bangsatya@gmail.com',
            'password' => bcrypt('12345678'),
            'role_id' => 1
        ]);

    }
}

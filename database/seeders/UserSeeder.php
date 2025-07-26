<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'erfan',
            'email' => 'erfan@example.com',
            'password' => bcrypt('password'),
            'mobile' => '09398179644',
        ]);
    }
}

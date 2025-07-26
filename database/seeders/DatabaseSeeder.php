<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            DoctorRoleSeeder::class,
        ]);

        DB::table('categories')->insert([
            [
                'name' => 'ورزشی',
                'created_at' => now(),
                'updated_at' => now(),
                'status' => 1,
            ],
            [
                'name' => 'سیاسی',
                'created_at' => now(),
                'updated_at' => now(),
                'status' => 1,
            ],
        ]);
    }
}

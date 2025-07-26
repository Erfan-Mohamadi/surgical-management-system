<?php

namespace Database\Seeders;

use App\Models\DoctorRole;
use Illuminate\Database\Seeder;

class DoctorRoleSeeder extends Seeder
{
    public function run(): void
    {
        DoctorRole::create([
            'title' => 'جراح',
            'required' => false,
            'quota' => 60,
            'status' => true,
        ]);

        DoctorRole::create([
            'title' => 'بیهوشی',
            'required' => false,
            'quota' => 30,
            'status' => true,
        ]);

        DoctorRole::create([
            'title' => 'مشاور',
            'required' => false,
            'quota' => 10,
            'status' => true,
        ]);
    }
}

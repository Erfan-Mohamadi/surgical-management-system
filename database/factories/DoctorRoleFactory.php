<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DoctorRoleFactory extends Factory
{
    public function definition()
    {
        return [
            'title' => $this->faker->unique()->jobTitle,
            'required' => $this->faker->boolean,
            'quota' => $this->faker->numberBetween(0, 100),
            'status' => $this->faker->boolean(true),
        ];
    }
}

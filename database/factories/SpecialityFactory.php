<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SpecialityFactory extends Factory
{
    public function definition()
    {
        return [
            'title' => $this->faker->unique()->word,
            'status' => $this->faker->boolean(90), // 90% chance of being active
        ];
    }
}

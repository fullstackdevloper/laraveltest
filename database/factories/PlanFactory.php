<?php

namespace Database\Factories;

use App\Models\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlanFactory extends Factory
{
    protected $model = Plan::class;

    public function definition()
    {
        return [
            'name' => $this->faker->unique()->word,
            'amount' => $this->faker->randomFloat(2, 10, 100),
            'interval' => $this->faker->randomElement(['monthly', 'yearly']),
        ];
    }
}


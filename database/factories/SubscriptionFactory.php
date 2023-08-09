<?php

namespace Database\Factories;

use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class SubscriptionFactory extends Factory
{
    protected $model = Subscription::class;

    public function definition()
    {
        return [
            'user_id' => function () {
                return User::factory()->create()->id;
            },
            'plan_id' => function () {
                return Plan::factory()->create()->id;
            },
            'start_date' => now(),
            'end_date' => now()->addMonth(),
            'subscription_id' => $this->faker->unique()->randomNumber(6),
            'amount' => $this->faker->randomFloat(2, 10, 100),
            'transaction_id' => $this->faker->uuid
        ];
    }

    // Define a state for expired subscriptions
    public function expired()
    {
        return $this->state(function (array $attributes) {
            return [
                'end_date' => now()->subDay(),
            ];
        });
    }
}

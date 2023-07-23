<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriptionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'renewed_at' => now()->format('Y-m-d'),
            'expired_at' => now()->addMonth()->format('Y-m-d'),
        ];
    }
}

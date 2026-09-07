<?php

namespace Database\Factories;

use App\Enums\SubscriptionStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subscription>
 */
class SubscriptionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'creator_id' => \App\Models\User::factory(),
            'value_cents' => fake()->randomElement([990, 1990, 2990]),
            'status' => SubscriptionStatus::Active->value,
            'starts_at' => now(),
            'ends_at' => now()->addMonth(),
            'gateway_transaction_id' => 'sandbox_'.fake()->uuid(),
        ];
    }

    public function active(): static
    {
        return $this->state(fn () => ['status' => SubscriptionStatus::Active->value]);
    }

    public function pending(): static
    {
        return $this->state(fn () => ['status' => SubscriptionStatus::Pending->value]);
    }
}
<?php

namespace Database\Factories;

use App\Enums\VerificationStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CreatorProfile>
 */
class CreatorProfileFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'display_name' => fake()->name(),
            'tagline' => fake()->sentence(6),
            'subscription_price_cents' => fake()->randomElement([990, 1990, 2990]),
            'verification_status' => VerificationStatus::Approved->value,
            'is_featured' => fake()->boolean(15),
            'subscriber_count' => fake()->numberBetween(0, 5000),
        ];
    }

    public function approved(): static
    {
        return $this->state(fn () => ['verification_status' => VerificationStatus::Approved->value]);
    }

    public function pending(): static
    {
        return $this->state(fn () => ['verification_status' => VerificationStatus::Pending->value]);
    }
}
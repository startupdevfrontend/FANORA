<?php

namespace Database\Factories;

use App\Enums\PostVisibility;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'body' => fake()->paragraphs(rand(1, 3), true),
            'visibility' => fake()->randomElement([PostVisibility::Public->value, PostVisibility::SubscribersOnly->value]),
            'is_premium_paid' => false,
            'status' => 'published',
            'published_at' => now(),
        ];
    }

    public function public(): static
    {
        return $this->state(fn () => ['visibility' => PostVisibility::Public->value]);
    }

    public function exclusive(): static
    {
        return $this->state(fn () => ['visibility' => PostVisibility::SubscribersOnly->value]);
    }
}
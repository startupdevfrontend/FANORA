<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profile>
 */
class ProfileFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'avatar_path' => null,
            'cover_path' => null,
            'bio' => fake()->sentence(12),
            'location' => fake()->city(),
            'website' => null,
            'is_private' => false,
        ];
    }
}
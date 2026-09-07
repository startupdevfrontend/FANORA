<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PostMedia>
 */
class PostMediaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'post_id' => Post::factory(),
            'media_type' => 'image',
            'file_path' => 'posts/demo/'.fake()->uuid().'.png',
            'thumbnail_path' => null,
            'original_name' => fake()->word().'.png',
            'mime_type' => 'image/png',
            'size_bytes' => fake()->numberBetween(10000, 2000000),
            'sort_order' => 0,
        ];
    }
}
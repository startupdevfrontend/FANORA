<?php

namespace Database\Factories;

use App\Enums\ReportReason;
use App\Enums\ReportStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Report>
 */
class ReportFactory extends Factory
{
    public function definition(): array
    {
        return [
            'reporter_id' => \App\Models\User::factory(),
            'reportable_type' => \App\Models\Post::class,
            'reportable_id' => \App\Models\Post::factory(),
            'reason' => fake()->randomElement(ReportReason::cases())->value,
            'description' => fake()->paragraph(),
            'status' => ReportStatus::Pending->value,
        ];
    }
}
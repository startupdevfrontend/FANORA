<?php

namespace Database\Factories;

use App\Enums\VerificationStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CreatorVerification>
 */
class CreatorVerificationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'status' => VerificationStatus::Pending->value,
            'document_type' => 'rg',
            'document_path' => null,
            'submitted_at' => now(),
        ];
    }
}
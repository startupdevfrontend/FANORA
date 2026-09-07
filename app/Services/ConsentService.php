<?php

namespace App\Services;

use App\Enums\ConsentType;
use App\Models\Consent;
use App\Models\User;

class ConsentService
{
    public function record(User $user, ConsentType $type, ?string $version = '1.0'): Consent
    {
        return Consent::updateOrCreate(
            ['user_id' => $user->id, 'type' => $type->value],
            [
                'source_ip' => request()->ip(),
                'version' => $version,
                'approved_at' => now(),
            ]
        );
    }

    public function recordMany(User $user, array $types): void
    {
        foreach ($types as $type) {
            $this->record($user, $type);
        }
    }

    public function hasConsent(User $user, ConsentType $type): bool
    {
        return $user->consents()->where('type', $type->value)->exists();
    }
}
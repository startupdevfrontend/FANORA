<?php

namespace App\Policies;

use App\Models\CreatorProfile;
use App\Models\User;

class CreatorProfilePolicy
{
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, CreatorProfile $creatorProfile): bool
    {
        return $creatorProfile->user->isActive();
    }

    public function update(?User $user, CreatorProfile $creatorProfile): bool
    {
        return $user !== null && $user->isActive() && $creatorProfile->user_id === $user->id;
    }

    public function verify(?User $user): bool
    {
        return $user !== null && $user->isActive();
    }
}
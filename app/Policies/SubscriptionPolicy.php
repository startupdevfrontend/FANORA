<?php

namespace App\Policies;

use App\Models\Subscription;
use App\Models\User;

class SubscriptionPolicy
{
    public function create(?User $user, ?User $creator = null): bool
    {
        if ($user === null || ! $user->isActive()) {
            return false;
        }

        if ($creator !== null) {
            if ($creator->id === $user->id) {
                return false;
            }

            return $creator->isVerifiedCreator();
        }

        return true;
    }

    public function cancel(?User $user, Subscription $subscription): bool
    {
        if ($user === null || ! $user->isActive()) {
            return false;
        }

        return $subscription->user_id === $user->id || $user->isAdmin();
    }
}
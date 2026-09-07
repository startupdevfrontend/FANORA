<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function manage(?User $user, ?User $target = null): bool
    {
        return $user !== null && $user->isAdmin();
    }

    public function activate(?User $user, ?User $target = null): bool
    {
        return $this->manage($user, $target);
    }

    public function block(?User $user, ?User $target = null): bool
    {
        if ($user === null || $target === null || ! $user->isActive()) {
            return false;
        }

        return $user->id !== $target->id;
    }

    public function export(?User $user, ?User $target = null): bool
    {
        return $user !== null && $target !== null && $user->id === $target->id;
    }
}
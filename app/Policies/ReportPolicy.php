<?php

namespace App\Policies;

use App\Models\Report;
use App\Models\User;

class ReportPolicy
{
    public function create(?User $user): bool
    {
        return $user !== null && $user->isActive();
    }

    public function review(?User $user, ?Report $report = null): bool
    {
        return $user !== null && $user->isAdmin();
    }
}
<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Support\Facades\Request;

class AuditService
{
    /** @param array<string, mixed>|null $old */
    /** @param array<string, mixed>|null $new */
    public function log(
        ?User $actor,
        string $action,
        object $entity,
        ?array $old = null,
        ?array $new = null,
    ): AuditLog {
        return AuditLog::create([
            'user_id' => $actor?->id,
            'action' => $action,
            'entity_type' => get_class($entity),
            'entity_id' => $entity->getKey(),
            'old_values' => $old,
            'new_values' => $new,
            'ip_address' => request()->ip() ?? Request::ip(),
            'user_agent' => substr((string) request()->userAgent(), 0, 500),
        ]);
    }

    public function logRaw(string $action, string $entityType, ?int $entityId, ?array $old = null, ?array $new = null): AuditLog
    {
        return AuditLog::create([
            'user_id' => optional(auth()->user())->id,
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'old_values' => $old,
            'new_values' => $new,
            'ip_address' => request()->ip() ?? Request::ip(),
            'user_agent' => substr((string) request()->userAgent(), 0, 500),
        ]);
    }
}
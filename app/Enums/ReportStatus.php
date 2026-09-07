<?php

namespace App\Enums;

enum ReportStatus: string
{
    case Pending = 'pending';
    case Reviewing = 'reviewing';
    case Resolved = 'resolved';
    case Rejected = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pendente',
            self::Reviewing => 'Em análise',
            self::Resolved => 'Resolvida',
            self::Rejected => 'Rejeitada',
        };
    }
}
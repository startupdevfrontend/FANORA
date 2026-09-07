<?php

namespace App\Enums;

enum PostVisibility: string
{
    case Public = 'public';
    case SubscribersOnly = 'subscribers_only';

    public function label(): string
    {
        return match ($this) {
            self::Public => 'Público',
            self::SubscribersOnly => 'Exclusivo para assinantes',
        };
    }
}
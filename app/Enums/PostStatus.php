<?php

namespace App\Enums;

enum PostStatus: string
{
    case Published = 'published';
    case Hidden = 'hidden';

    public function label(): string
    {
        return match ($this) {
            self::Published => 'Publicada',
            self::Hidden => 'Oculta',
        };
    }
}

enum MediaType: string
{
    case Image = 'image';
    case Video = 'video';
}
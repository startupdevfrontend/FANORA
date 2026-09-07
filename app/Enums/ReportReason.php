<?php

namespace App\Enums;

enum ReportReason: string
{
    case IllegalContent = 'illegal_content';
    case Spam = 'spam';
    case Fraud = 'fraud';
    case Harassment = 'harassment';
    case Copyright = 'copyright';
    case Minor = 'minor';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::IllegalContent => 'Conteúdo ilegal',
            self::Spam => 'Spam',
            self::Fraud => 'Fraude',
            self::Harassment => 'Assédio',
            self::Copyright => 'Violação de direitos autorais',
            self::Minor => 'Conteúdo envolvendo menor',
            self::Other => 'Outro',
        };
    }
}
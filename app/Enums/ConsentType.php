<?php

namespace App\Enums;

enum ConsentType: string
{
    case Terms = 'terms';
    case Privacy = 'privacy';
    case ContentPolicy = 'content_policy';
    case Cookies = 'cookies';
    case Age = 'age';

    public function label(): string
    {
        return match ($this) {
            self::Terms => 'Termos de Uso',
            self::Privacy => 'Política de Privacidade',
            self::ContentPolicy => 'Política de Conteúdo',
            self::Cookies => 'Política de Cookies',
            self::Age => 'Confirmação de maioridade',
        };
    }
}
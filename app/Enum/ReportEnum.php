<?php

namespace App\Enum;

enum ReportEnum: string
{
    case SPAM = 'spam';
    case INAPPROPRIATE = 'INAPPROPRIATE';
    case SEXUAL = 'sexual';
    case OTHER = 'other';

    public function report(): string
    {
        return match ($this) {
            self::SPAM => 'spam',
            self::INAPPROPRIATE => 'inappropriate',
            self::SEXUAL => 'sexual',
            self::OTHER => 'other',
        };
    }

    public static function all(): array
    {
        return [
            self::SPAM,
            self::INAPPROPRIATE,
            self::SEXUAL,
            self::OTHER,
        ];
    }
}

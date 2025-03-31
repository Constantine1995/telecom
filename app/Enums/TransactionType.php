<?php

namespace App\Enums;

enum TransactionType: string
{
    case DEPOSIT = 'deposit';
    case WITHDRAWAL = 'withdrawal';

    public function label(): string
    {
        return match ($this) {
            self::DEPOSIT => 'Пополнение',
            self::WITHDRAWAL => 'Списание',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}

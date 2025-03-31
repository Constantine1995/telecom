<?php

namespace App\Enums;

enum MonthlyPaymentStatus: string
{
    case PAID = 'paid';
    case UNPAID = 'unpaid';

    public function label(): string
    {
        return match ($this) {
            self::PAID => 'Оплачен',
            self::UNPAID => 'Не оплачен',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
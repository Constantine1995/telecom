<?php

namespace App\Enums;

enum PropertyType: string
{
    case HOUSE = 'house';
    case APARTMENT = 'apartment';

    public function label(): string
    {
        return match ($this) {
            self::HOUSE => 'Дом',
            self::APARTMENT => 'Квартира',
        };
    }
}

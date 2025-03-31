<?php

namespace App\Enums;

enum ContractStatus: string
{
    case ACTIVE = 'active';
    case TERMINATED = 'terminated';
    case PENDING_ACTIVATION = 'pending_activation';
    case SUSPENDED = 'suspended';
    case EXPIRED = 'expired';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'Активный',
            self::TERMINATED => 'Расторгнутый',
            self::PENDING_ACTIVATION => 'Ожидает активации',
            self::SUSPENDED => 'Заморожен',
            self::EXPIRED => 'Истёк',
            self::CANCELLED => 'Аннулирован',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

}

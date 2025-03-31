<?php

namespace App\Enums;

enum DeviceStatusType: string
{
    case ACTIVE = 'active';
    case UNPAID = 'unpaid';
    case DISABLED = 'disabled';
    case AWAITING_ACTIVATION = 'awaiting_activation';
    case CONNECTION_ERROR = 'connection_error';
    case RESERVED = 'reserved';
    case FAULTY = 'faulty';

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'Работает',
            self::UNPAID => 'Не оплачен',
            self::DISABLED => 'Отключен',
            self::AWAITING_ACTIVATION => 'Ожидает активации',
            self::CONNECTION_ERROR => 'Ошибка подключения',
            self::RESERVED => 'Зарезервирован',
            self::FAULTY => 'Неисправен',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}

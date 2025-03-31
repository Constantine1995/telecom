<?php

namespace App\Enums;

enum ConnectRequestStatus: string
{
    case NEW = 'new';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case PENDING = 'pending';
    case CANCELED = 'canceled';

    public function label(): string
    {
        return match ($this) {
            self::NEW => 'Новый',
            self::IN_PROGRESS => 'В работе',
            self::COMPLETED => 'Выполнено',
            self::PENDING => 'Ожидает уточнения',
            self::CANCELED => 'Отменено',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}

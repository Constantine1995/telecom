<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case PENDING = 'pending'; // Платеж создан, ожидает действий пользователя
    case WAITING_FOR_CAPTURE = 'waiting_for_capture'; // Платеж ожидает подтверждения
    case SUCCEEDED = 'succeeded'; // Платеж успешно завершен, деньги списаны
    case CANCELED = 'canceled'; // Платеж отменен
    case REFUNDED = 'refunded'; // Платеж возвращен

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Ожидает оплаты',
            self::WAITING_FOR_CAPTURE => 'Ожидает подтверждения платежа',
            self::SUCCEEDED => 'Завершен',
            self::CANCELED => 'Отменен',
            self::REFUNDED => 'Возвращен',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}

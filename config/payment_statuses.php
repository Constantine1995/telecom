<?php

use App\Enums\PaymentStatus;

return [
    'messages' => [
        PaymentStatus::SUCCEEDED->value => [
            'type' => 'success',
            'message' => 'Пополнение выполнено успешно!',
        ],
        PaymentStatus::WAITING_FOR_CAPTURE->value => [
            'type' => 'info',
            'message' => 'Платеж ожидает подтверждения. Баланс будет пополнен после завершения обработки.',
        ],
        PaymentStatus::CANCELED->value => [
            'type' => 'error',
            'message' => 'Платеж был отменен. Пожалуйста, попробуйте снова.',
        ],
        PaymentStatus::PENDING->value => [
            'type' => 'info',
            'message' => 'Платеж обрабатывается. Пожалуйста, подождите.',
        ],
    ],
    'default' => [
        'type' => 'error',
        'message' => 'Неизвестный статус платежа. Пожалуйста, обратитесь в поддержку.',
    ],
];
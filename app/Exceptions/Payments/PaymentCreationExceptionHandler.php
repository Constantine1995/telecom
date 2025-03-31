<?php

namespace App\Exceptions\Payments;

use RuntimeException;

class PaymentCreationExceptionHandler extends RuntimeException
{
    public function __construct(string $message = 'Не удалось создать платеж', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
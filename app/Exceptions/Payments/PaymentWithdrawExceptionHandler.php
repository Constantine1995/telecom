<?php

namespace App\Exceptions\Payments;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class PaymentWithdrawExceptionHandler
{
    public function handle(\Throwable $e, array $context = []): RedirectResponse
    {
        return match (true) {
            $e instanceof \InvalidArgumentException => $this->handleInvalidArgument($e, $context),
            default => $this->handleGeneric($e, $context),
        };
    }

    private function handleInvalidArgument(\InvalidArgumentException $e, array $context): RedirectResponse
    {
        Log::warning('Ошибка валидации', array_merge([
            'withdraw_error' => $e->getMessage(),
        ], $context));

        $sessionKey = isset($context['contract_id']) ? 'withdraw_error_' . $context['contract_id'] : 'withdraw_error';
        return redirect()->back()->with($sessionKey, $e->getMessage());
    }

    private function handleGeneric(\Throwable $e, array $context): RedirectResponse
    {
        Log::emergency('Ошибка при списании средств', array_merge([
            'withdraw_error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ], $context));

        $sessionKey = isset($context['contract_id']) ? 'withdraw_error_' . $context['contract_id'] : 'withdraw_error';
        return redirect()->back()->with($sessionKey, 'Произошла ошибка системы');
    }

}
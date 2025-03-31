<?php

namespace App\Exceptions\Payments;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use YooKassa\Common\Exceptions\ApiException;
use YooKassa\Common\Exceptions\BadApiRequestException;
use YooKassa\Common\Exceptions\ForbiddenException;
use YooKassa\Common\Exceptions\TooManyRequestsException;

class PaymentExceptionHandler
{
    public function handle(\Throwable $e, array $context = [], string $redirectRoute = 'payments.index'): RedirectResponse
    {
        return match (true) {
            $e instanceof PaymentCreationExceptionHandler => $this->handlePaymentCreation($e, $context, $redirectRoute),
            $e instanceof \InvalidArgumentException => $this->handleInvalidArgument($e, $context, $redirectRoute),
            $e instanceof ModelNotFoundException => $this->handleModelNotFound($e, $context, $redirectRoute),
            $e instanceof BadApiRequestException => $this->handleBadApiRequest($e, $redirectRoute),
            $e instanceof ForbiddenException => $this->handleForbidden($e, $redirectRoute),
            $e instanceof TooManyRequestsException => $this->handleTooManyRequests($e, $redirectRoute),
            $e instanceof ApiException => $this->handleApiException($e, $redirectRoute),
            default => $this->handleGeneric($e, $context, $redirectRoute),
        };
    }

    private function handlePaymentCreation(PaymentCreationExceptionHandler $e, array $context, string $redirectRoute): RedirectResponse
    {
        Log::error('Ошибка создания платежа', array_merge([
            'payment_error' => $e->getMessage(),
        ], $context));
        return redirect()->route($redirectRoute)->with('payment_error', $e->getMessage());
    }

    private function handleInvalidArgument(\InvalidArgumentException $e, array $context, string $redirectRoute): RedirectResponse
    {
        Log::warning('Ошибка валидации платежа', array_merge([
            'payment_error' => $e->getMessage(),
        ], $context));
        return redirect()->route($redirectRoute)->with('payment_error', 'Неверная сумма платежа');
    }

    private function handleModelNotFound(ModelNotFoundException $e, array $context, string $redirectRoute): RedirectResponse
    {
        Log::error('Транзакция не найдена', array_merge([
            'payment_error' => $e->getMessage(),
        ], $context));
        return redirect()->route($redirectRoute)->with('payment_error', 'Транзакция не найдена. Пожалуйста, попробуйте снова.');
    }

    private function handleBadApiRequest(BadApiRequestException $e, string $redirectRoute): RedirectResponse
    {
        Log::error('Ошибка API YooKassa: Неверный запрос', [
            'payment_error' => $e->getMessage(),
            'code' => $e->getCode(),
        ]);
        return redirect()->route($redirectRoute)->with('payment_error', 'Ошибка обработки платежа');
    }

    private function handleForbidden(ForbiddenException $e, string $redirectRoute): RedirectResponse
    {
        Log::error('Ошибка API YooKassa: Доступ запрещен', [
            'payment_error' => $e->getMessage(),
        ]);
        return redirect()->route($redirectRoute)->with('payment_error', 'Ошибка авторизации платежа');
    }

    private function handleTooManyRequests(TooManyRequestsException $e, string $redirectRoute): RedirectResponse
    {
        Log::warning('Превышен лимит запросов к YooKassa', [
            'payment_error' => $e->getMessage(),
        ]);
        return redirect()->route($redirectRoute)->with('payment_error', 'Слишком много попыток, попробуйте позже');
    }

    private function handleApiException(ApiException $e, string $redirectRoute): RedirectResponse
    {
        Log::error('Ошибка API YooKassa', [
            'payment_error' => $e->getMessage(),
            'code' => $e->getCode(),
        ]);
        return redirect()->route($redirectRoute)->with('payment_error', 'Ошибка при создании платежа');
    }

    private function handleGeneric(\Throwable $e, array $context, string $redirectRoute): RedirectResponse
    {
        Log::emergency('Необработанная ошибка при обработке платежа', array_merge([
            'payment_error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ], $context));
        return redirect()->route($redirectRoute)->with('payment_error', 'Произошла ошибка системы');
    }
}
<?php

namespace App\Services;

use App\Enums\MonthlyPaymentStatus;
use App\Enums\PaymentStatus;
use App\Enums\TransactionType;
use App\Exceptions\Payments\PaymentCreationExceptionHandler;
use App\Exceptions\Payments\PaymentExceptionHandler;
use App\Jobs\UpdateContractPaymentStatus;
use App\Models\Contract;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use YooKassa\Client;
use YooKassa\Request\Payments\CreatePaymentResponse;

class PaymentService
{
    protected $client;
    protected $exceptionHandler;

    public function __construct(Client $client, PaymentExceptionHandler $exceptionHandler)
    {
        $this->client = $client;
        $this->exceptionHandler = $exceptionHandler;
    }

    // Создание платежа через YooKassa
    public function createPayment(User $user, float $amount): RedirectResponse
    {
        try {
            $payment = $this->client->createPayment(
                [
                    'amount' => [
                        'value' => $amount,
                        'currency' => config('services.yookassa.currency', 'RUB'),
                    ],
                    'capture' => false,
                    'confirmation' => [
                        'type' => 'redirect',
                        'return_url' => route('payments.callback'),
                    ],
                    'description' => 'Пополнение баланса',
                ],
                uniqid('', true)
            );

            // Проверяем, что платеж успешно создан
            if (!$payment instanceof CreatePaymentResponse) {
                throw new PaymentCreationExceptionHandler();
            }

            session(['payment_id' => $payment->getId()]);

            // Создает транзакцию со статусом PENDING
            Transaction::create([
                'user_id' => $user->id,
                'contract_id' => null,
                'payment_id' => $payment->getId(),
                'amount' => $amount,
                'payment_status' => PaymentStatus::PENDING->value,
                'transaction_type' => TransactionType::DEPOSIT->value,
            ]);

            $confirmationUrl = $payment->getConfirmation()?->getConfirmationUrl();

            if (!$confirmationUrl) {
                throw new PaymentCreationExceptionHandler('Не получен URL для подтверждения платежа');
            }

            return redirect($confirmationUrl);
        } catch (\Throwable $e) {
            return $this->exceptionHandler->handle($e, [
                'user_id' => $user->id,
                'amount' => $amount,
            ]);
        }
    }

    // Списание с баланса
    public function withdraw(User $user, Contract $contract): void
    {
        $amount = $contract->tariff->price;
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Сумма списания должна быть положительной');
        }

        if ($user->balance->amount < $amount) {
            throw new \InvalidArgumentException('Недостаточно средств на балансе');
        }

        DB::transaction(function () use ($contract, $user, $amount) {
            $user->balance->decrement('amount', $amount);

            Transaction::create([
                'user_id' => $user->id,
                'contract_id' => $contract->id,
                'payment_id' => null,
                'amount' => $amount,
                'payment_status' => PaymentStatus::SUCCEEDED->value,
                'transaction_type' => TransactionType::WITHDRAWAL->value,
            ]);

            $contract->payment_status = MonthlyPaymentStatus::PAID->value;
            $contract->save();

            // запуск Job, который через 10 секунд сбросит статус интернета на 'Не оплачен'
            UpdateContractPaymentStatus::dispatch($contract->id)->delay(10);

            Log::info('Withdrawal completed', [
                'user_id' => $user->id,
                'amount' => $amount,
            ]);
        }, 3);
    }

    public function getClient(): Client
    {
        return $this->client;
    }

}
<?php

namespace App\Http\Controllers;

use App\Enums\MonthlyPaymentStatus;
use App\Enums\PaymentStatus;
use App\Exceptions\Payments\PaymentExceptionHandler;
use App\Exceptions\Payments\PaymentWithdrawExceptionHandler;
use App\Http\Requests\Payment\CreatePaymentRequest;
use App\Models\Contract;
use App\Models\Transaction;
use App\Services\PaymentService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class PaymentController extends Controller
{
    use AuthorizesRequests;

    protected $paymentService;
    protected $paymentWithdrawExceptionHandler;
    protected $paymentExceptionHandler;

    public function __construct(PaymentService                  $paymentService,
                                PaymentWithdrawExceptionHandler $paymentWithdrawExceptionHandler,
                                PaymentExceptionHandler         $paymentExceptionHandler,)
    {
        $this->paymentService = $paymentService;
        $this->paymentWithdrawExceptionHandler = $paymentWithdrawExceptionHandler;
        $this->paymentExceptionHandler = $paymentExceptionHandler;
    }

    public function index(): View
    {
        $user = auth()->user();
        $contracts = $user->contracts;
        return view('payment.index', compact('contracts'));
    }

    public function store(CreatePaymentRequest $request): RedirectResponse
    {
        $amount = (float)$request->validated()['amount'];
        return $this->paymentService->createPayment(auth()->user(), $amount);
    }

    // YooKassa отправляет POST запрос на URL вебхука указанного в HTTP-уведомления
    public function handleWebhook(Request $request): JsonResponse
    {

        Log::info('Webhook received', ['data' => $request->all()]);
        try {
            // Получаем данные из запроса
            $payload = $request->getContent();
            $data = json_decode($payload, true);

            Log::info('Webhook received', ['data' => $data]);

            // Проверяем, что это событие от YooKassa
            if (!isset($data['event']) || !isset($data['object'])) {
                Log::error('Invalid webhook payload', ['data' => $data]);
                return response()->json(['status' => 'error'], 400);
            }

            // Получаем объект платежа и его данные
            $payment = $data['object'];
            $paymentId = $payment['id'];
            $status = $payment['status'];

            // Получаем транзакцию в базе данных
            $transaction = Transaction::where('payment_id', $paymentId)->first();

            if (!$transaction) {
                Log::error('Transaction not found', ['payment_id' => $paymentId]);
                return response()->json(['status' => 'error'], 404);
            }

            // Логируем текущий статус транзакции и платежа для отладки
            Log::info('Processing webhook', [
                'payment_id' => $paymentId,
                'current_transaction_status' => $transaction->payment_status,
                'new_payment_status' => $status,
            ]);

            // Если статус уже обработан пропускаем обработку
            if ($transaction->payment_status === PaymentStatus::SUCCEEDED->value) {
                Log::info('Payment already processed', ['payment_id' => $paymentId]);
                return response()->json(['status' => 'success'], 200);
            }

            $transaction->update([
                'payment_status' => PaymentStatus::from($status)->value,
            ]);

            // Обработка статуса waiting_for_capture
            if ($status === PaymentStatus::WAITING_FOR_CAPTURE->value) {
                Log::info('Payment is waiting for capture', ['payment_id' => $paymentId]);

                try {
                    $capturedPayment = $this->paymentService->getClient()->capturePayment(
                        [
                            'amount' => [
                                'value' => $payment['amount']['value'],
                                'currency' => $payment['amount']['currency'],
                            ],
                        ],
                        $paymentId,
                        uniqid('', true)
                    );

                    $transaction->update([
                        'payment_status' => PaymentStatus::from($capturedPayment->getStatus())->value,
                    ]);
                } catch (\Exception $e) {
                    Log::error('Capture failed', [
                        'payment_id' => $paymentId,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                    return response()->json(['status' => 'error'], 500);
                }
            }

            if ($status === PaymentStatus::SUCCEEDED->value) {
                $user = $transaction->user;
                $user->balance->increment('amount', $payment['amount']['value']);

                Log::info('Balance updated directly', [
                    'user_id' => $user->id,
                    'amount' => $payment['amount']['value'],
                ]);
            }

            // Отправляем success ответ для YooKassa
            return response()->json(['status' => 'success'], 200);
        } catch (\Exception $e) {
            Log::error('Webhook processing failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['status' => 'error'], 500);
        }
    }


    // Срабатывает на экране Successfully при нажатии на кнопку back
    public function callback(PaymentService $paymentService): RedirectResponse
    {
        $paymentId = session('payment_id');

        if (!$paymentId) {
            return redirect()->route('payments.index')
                ->with('payment_error', 'Идентификатор платежа отсутствует. Пожалуйста, попробуйте снова.');
        }

        try {
            // Получаем информацию о платеже через API YooKassa
            $payment = $paymentService->getClient()->getPaymentInfo($paymentId);

            // Сбрасываем session
            session()->forget('payment_id');

            // Получаем текущий статус платежа
            $status = $payment->getStatus();

            // Логируем текущий статус для отладки
            Log::info('Payment callback processed', [
                'payment_id' => $paymentId,
                'status' => $status,
            ]);

            // Получаем маппинг статусов и значение по умолчанию из конфигурации
            $statusMessages = config('payment_statuses.messages');
            $defaultMessage = config('payment_statuses.default');

            // Проверяем, есть ли обработчик для текущего статуса
            $statusMessage = $statusMessages[$status] ?? $defaultMessage;

            // Определяем тип сообщения и метод перенаправления
            $redirect = redirect()->route('payments.index');

            if ($statusMessage['type'] === 'success') {
                return $redirect->with('payment_status', $statusMessage['message']);
            } elseif ($statusMessage['type'] === 'info') {
                return $redirect->with('payment_info', $statusMessage['message']);
            } else {
                return $redirect->with('payment_error', $statusMessage['message']);
            }
        } catch (\Exception $e) {
            return $this->paymentExceptionHandler->handle($e, [
                'payment_id' => $paymentId,
            ]);
        }
    }

    // Списание с баланса
    public function withdraw(Contract $contract): RedirectResponse
    {
        $user = auth()->user();
        try {
            if ($contract->payment_status->value === MonthlyPaymentStatus::PAID->value) {
                throw new \InvalidArgumentException('Вы уже оплатили за этот месяц (DEV: Через 10 секунд статус сбросится на UNPAID)');
            }

            $this->authorize('view', $contract);
            $this->paymentService->withdraw($user, $contract);
            return redirect()->route('payments.index')->with('withdraw_status_' . $contract->id, 'Списание средств успешно выполнено!');
        } catch (\Throwable $e) {
            return $this->paymentWithdrawExceptionHandler->handle($e, [
                'contract_id' => $contract->id,
            ]);
        }
    }
}

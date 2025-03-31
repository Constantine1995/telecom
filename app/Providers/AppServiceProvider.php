<?php

namespace App\Providers;

use App\Exceptions\Payments\PaymentExceptionHandler;
use App\Models\Contract;
use App\Policies\ContractPolicy;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use YooKassa\Client;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Регистрируем политику доступа для класса Contract, связывая ее с ContractPolicy
        Gate::policy(Contract::class, ContractPolicy::class);

        // Биндим класс Client с настройкой авторизации
        $this->app->bind(Client::class, function () {
            $client = new Client();
            $client->setAuth(
                config('services.yookassa.shop_id'),
                config('services.yookassa.secret_key')
            );
            return $client;
        });

        // Биндим класс PaymentService к контейнеру с зависимостями
        $this->app->bind(PaymentService::class, function ($app) {
            // Создаем новый экземпляр PaymentService, передавая ему Client и PaymentExceptionHandler
            return new PaymentService($app->make(Client::class), $app->make(PaymentExceptionHandler::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

    }
}

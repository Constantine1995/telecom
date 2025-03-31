@extends('layouts.app')

@section('content')
    <div class="flex flex-col font-sans text-left gap-5 mt-12 mb-12 px-52 w-full">
        <p class="text-xl"><strong>Ваш баланс:</strong> {{ auth()->user()->balance?->formattedAmount ?? '0.00 ₽' }}</p>
        <!-- Форма для пополнения -->
        <form action="{{ route('payments.store') }}" method="POST" class="flex flex-col gap-3">
            @csrf
            <x-text-input
                    name="amount"
                    type="number"
                    step="0.1" min="1"
                    required
                    placeholder="Сумма пополнения"
                    class="mt-1 block w-fit"/>
            <x-primary-button class="w-fit">Пополнить</x-primary-button>
        </form>

        <!-- Успешное сообщение -->
        @if (session('payment_status'))
            <x-alert-success>
                {{ session('payment_status') }}
            </x-alert-success>
        @endif

        <!-- Информационное сообщение -->
        @if (session('payment_info'))
            <x-alert-info>
                {{ session('payment_info') }}
            </x-alert-info>
        @endif

        <!-- Сообщение об ошибке -->
        @if (session('payment_error'))
            <x-alert-danger>
                {{ session('payment_error') }}
            </x-alert-danger>
        @endif

        <br>

        @foreach($contracts as $contract)
            <div class="flex flex-col gap-4 text-left">

                <p><strong>Договор:</strong> №{{ $contract->contract_number }}</p>

                <div class="space-y-2">
                    <h3 class="mb-2"><strong>Тариф:</strong></h3>
                    <ul class="list-disc pl-5 space-y-1.5">
                        <li class="text-gray-700">
                            <span><strong>Название:</strong></span>
                            {{ $contract->tariff->name ?? 'Не указано' }}
                        </li>
                        <li class="text-gray-700">
                            <span><strong>Скорость:</strong></span>
                            {{ $contract->tariff->formattedSpeed ?? '—' }}
                        </li>
                        <li class="text-gray-700">
                            <span><strong>Стоимость:</strong></span>
                            {{ $contract->tariff->formattedPrice ?? '—' }}
                        </li>
                        <li class="text-gray-700">
                            <span><strong>Статус:</strong></span>
                            <span class="font-bold"
                                  style="color: {{ $contract->getContractStatusColor() }}">
                            {{ $contract->contract_status->label() ?? 'Не указано' }}
                            </span>
                        </li>
                        <li class="text-gray-700">
                            <span><strong>Статус оплаты:</strong></span>
                            {{ $contract->payment_status->label() ?? 'Не указано' }}
                        </li>

                    </ul>
                </div>

                @if($contract->getContractStatus())
                    <!-- Форма для списания -->
                    <form action="{{ route('withdraw', $contract) }}" method="POST"
                          class="flex flex-col gap-3">
                        @csrf
                        <x-primary-button class="w-fit">Оплатить</x-primary-button>
                    </form>

                    <!-- Успешное сообщение -->
                    @if (session('withdraw_status_' . $contract->id))
                        <x-alert-success>
                            {{ session('withdraw_status_' . $contract->id) }}
                        </x-alert-success>
                    @endif

                    <!-- Сообщение об ошибке -->
                    @if (session('withdraw_error_' . $contract->id))
                        <x-alert-danger>
                            {{ session('withdraw_error_' . $contract->id) }}
                        </x-alert-danger>
                    @endif
                @endif
                <hr class="border-t border-gray-300">

            </div>
        @endforeach
    </div>

@endsection

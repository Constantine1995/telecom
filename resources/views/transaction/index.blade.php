@extends('layouts.app')

@section('content')
    <div class="flex flex-col font-sans text-left gap-5 mt-12 mb-12 px-4 sm:px-8 md:px-16 lg:px-52 w-full">
        <div class="flex flex-col gap-8 mb-6 text-start">
            <h1 class="text-left text-xl font-bold text-gray-800">
                История ваших транзакций
            </h1>
            <div class="overflow-x-auto">

                <!-- Все транзакции пользователя -->
                <div class="overflow-x-auto">
                    <livewire:user-transaction-expandable-section :user="auth()->user()"/>
                </div>

                <!-- Транзакции договоров -->
                @foreach($contracts as $contract)
                    <div class="mt-4 mb-4">
                        <livewire:contract-transaction-expandable-section :contract="$contract"/>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
@endsection
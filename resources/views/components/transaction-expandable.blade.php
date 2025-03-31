<div class="border border-gray-300 rounded-md p-4 overflow-hidden max-w-full">
    <button wire:click="toggle" class="w-full flex justify-between items-center bg-gray-100 px-4 py-2 rounded-md">
        <p class="font-bold"> {{ $title }}</p>
        <svg class="w-5 h-5 transform transition-transform duration-300"
             :class="{'rotate-180': {{ $isExpanded ? 'true' : 'false' }}}"
             xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>

    @if($isExpanded)
        <div class="flex flex-col items-center mt-4">
            <!-- Таблица -->
            <div class="w-full overflow-x-auto">
                <table class="w-full shadow-md rounded-lg">
                    <!-- Заголовки таблицы -->
                    <thead class="bg-gray-200 hidden md:table-header-group">
                    <tr>
                        <th class="p-3 px-6 text-center font-semibold">Дата платежа</th>
                        <th class="p-3 px-6 text-center font-semibold">Цена</th>
                        <th class="p-3 px-6 text-center font-semibold">Тип транзакции</th>
                        <th class="p-3 px-6 text-center font-semibold">Статус платежа</th>
                    </tr>
                    </thead>

                    <!-- Тело таблицы -->
                    <tbody>
                    @forelse($transactions as $transaction)
                        <tr class="border-b border-gray-100 hover:bg-gray-50 md:table-row flex flex-col gap-2 md:gap-0 p-4 md:p-0">
                            <!-- Дата платежа -->
                            <td class="p-3 px-6 flex justify-between md:table-cell  md:text-center">
                                <span class="font-semibold md:hidden">Дата платежа:</span>
                                <span>{{ $transaction->formattedCreateDate }}</span>
                            </td>
                            <!-- Цена -->
                            <td class="p-3 px-6 flex justify-between md:table-cell  md:text-center">
                                <span class="font-semibold md:hidden">Цена:</span>
                                <span>{{ $transaction->amount }} ₽</span>
                            </td>
                            <!-- Тип транзакции -->
                            <td class="p-3 px-6 flex justify-between md:table-cell  md:text-center">
                                <span class="font-semibold md:hidden">Тип транзакции:</span>
                                <span>{{ $transaction->transaction_type->label() }}</span>
                            </td>
                            <!-- Статус платежа -->
                            <td class="p-3 px-6 flex justify-between md:table-cell  md:text-center">
                                <span class="font-semibold md:hidden">Статус платежа:</span>
                                <span>{{ $transaction->payment_status->label() }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-4 text-center text-gray-500">Транзакций пока нет.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $transactions->links('components.custom-pagination') }}
            </div>
        </div>
    @endif
</div>
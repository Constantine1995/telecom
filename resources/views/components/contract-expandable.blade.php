<div class="border border-gray-300 rounded-md p-4 overflow-hidden max-w-full">

    <button wire:click="toggle" class="w-full flex justify-between items-center bg-gray-100 px-4 py-2 rounded-md">
        <p class="font-bold"> Договор №{{ $contract->contract_number }}</p>
        <svg class="w-5 h-5 transform transition-transform duration-300"
             :class="{'rotate-180': {{ $isExpanded ? 'true' : 'false' }}}"
             xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>

    @if($isExpanded)
        <div class="flex flex-col max-w-full py-4 px-4">

            <p> Статус Договора: <span class="font-bold"
                                       style="color: {{ $contract->getContractStatusColor() }}">{{ $contract->contract_status->label() }} </span>
            </p>
            <p> Номер телефона: <span class="font-bold">{{ $contract->connectRequest->phone }} </span></p>
            <p> Адрес: <span class="font-bold">{{ $contract->address->getFullAddress() }} </span></p>
            <p> Дата подключения: <span class="font-bold">{{ $contract->formatted_date_connection }} </span></p>

            @if(!$contract->getAvailableDateDisconnection())
                <p> Дата отключения: <span
                            class="font-bold">{{ $contract->formatted_date_disconnection}} </span></p>
            @endif
        </div>

        <div class="flex flex-row mt-8 max-w-full">
            <div class="grid grid-cols-1 sm:grid-cols-1 lg:grid-cols-2 gap-6">
                @includeWhen(isset($contract), 'base-info.tariff.item', ['contract' => $contract])
                @includeWhen(isset($contract), 'base-info.device.item', ['contract' => $contract])
            </div>
        </div>

        <div class="px-4 mt-6">
            @if ($contract->getContractStatus())
                <a href="{{ route('pdf.index', ['id' => $contract->id]) }}"
                   class="underline text-sm text-blue-500 hover:text-blue-700">Скачать Договор в PDF</a>
            @else
                <p class="font-bold text-red-500 text-sm">Скачать договор можно только при его активном статусе!</p>
            @endif

        </div>
    @endif
</div>

<div class="bg-white p-6 rounded-3xl border border-gray-300 w-full max-w-md">
    <div class="w-full max-w-full mx-auto space-y-6">
        <div class="-my-6 -mx-6 mb-6 ">
            <div wire:key="tariff-{{ $contract->tariff->id }}"
                 class="px-6 py-4 bg-gradient-to-br from-green-400 to-cyan-400 rounded-t-3xl">
                <h1 class="text-3xl font-bold text-white">
                    Тариф "{{ $contract->tariff->name ?? '–'}}"
                </h1>
            </div>
            <hr class="border-t-1 border-gray-300">
        </div>

        <div class="grid grid-cols-2 gap-2">
            <div class="bg-gray-50 p-2 rounded-lg text-center">
                <div class="font-bold text-gray-500">Тип</div>
                <div class="text-gray-800">{{ $contract->tariff->connection_type->label() ?? '–'}}</div>
            </div>
            <div class="bg-gray-50 p-2 rounded-lg text-center">
                <div class="font-bold text-gray-500">Скорость</div>
                <div class="text-gray-800"> {{ $contract->tariff->formatted_speed }} </div>
            </div>
        </div>

        <hr class="-mx-6 border-t-1 border-gray-300">

        <div class="flex flex-col gap-4">
            <div class="flex flex-row justify-between gap-4 items-center">
                <div class="text-xl font-bold text-gray-800">
                    {{ $contract->tariff->formatted_price }}
                </div>
                <div class="inline-block px-4 py-1 rounded-full text-sm"
                     style="color: {{ $contract->tariff->status_text_color }} !important;
                   background-color: {{ $contract->tariff->status_background_color }} !important;">
                    {{ $contract->tariff->active_text ?? '–' }}
                </div>
            </div>
            <div class="flex justify-end">
                <x-primary-button
                        onclick="Livewire.dispatch('openTariffModal', {{ json_encode(['contractId' => $contract->id]) }})"
                        class="min-w-24 max-w-32"
                >
                    Изменить
                </x-primary-button>
            </div>
        </div>

    </div>
</div>

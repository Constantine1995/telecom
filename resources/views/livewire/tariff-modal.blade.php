<div x-data>

    @if($isOpen)
        <div class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50"
             x-data
             @click="if ($event.target === $el) $wire.closeTariffModal()">
            <div class="flex flex-col w-[370px] text-gray-900 p-5 rounded-lg shadow-lg bg-white"
                 style="height: auto; overflow-y: inherit;
                 @click.stop">

                <div class="flex flex-row justify-between w-full">
                    <h2 class="text-2xl font-bold">Выберите тариф</h2>
                    <button wire:click="closeTariffModal" class="bg-gray-400 text-white rounded px-3">
                        X
                    </button>
                </div>

                <div class="flex flex-col gap-4 mt-4">
                    <p class="text-sm font-sans">Тарифы с типом подключения "{{ $contract->tariff->connection_type->label() }}"</p>

                    <div>
                        <x-dropdown-input wire:model.live="tariff_id" name="tariff_id" class="block w-full">
                            <option value="">Выберите регион</option>
                            @foreach($this->tariffs as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </x-dropdown-input>
                        <x-input-error :messages="$errors->get('tariff_id')" class="mt-2"/>
                    </div>

                    <x-primary-button wire:click="sendRequest"
                                      class="justify-center mt-4 px-4 py-2 bg-gray-600 text-white rounded">
                        Сохранить
                    </x-primary-button>

                </div>
            </div>
        </div>
    @endif

</div>

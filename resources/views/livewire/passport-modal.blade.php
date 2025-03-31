
<div>

    @if($isOpen)
        <div class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50">
            <div class="flex flex-col w-[370px] text-gray-900 p-5 rounded-lg shadow-lg bg-white"
                 style="height: auto; overflow-y: inherit;">

                <div class="flex flex-row justify-end w-full">
                    <button wire:click="closePassportModal" class="bg-gray-400 text-white rounded px-3 py-1">
                        X
                    </button>
                </div>

                <p class="p-8 font-sans">Для заказа услуги <span class="font-bold">{{$tariff['name']}}</span> необходимо заполнить паспорт в профиле</p>

                <x-primary-link href="{{ route('passport.create') }}" class="justify-center">
                    Заполнить
                </x-primary-link>

            </div>
        </div>
    @endif

</div>

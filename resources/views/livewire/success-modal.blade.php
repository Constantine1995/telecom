<div>

    @if($isOpen)
        <div class="fixed inset-0 flex text-center items-center justify-center bg-gray-900 bg-opacity-50">
            <div class="flex flex-col w-[370px] text-gray-900 p-5 rounded-lg shadow-lg bg-white"
                 style="height: auto; overflow-y: inherit;">

                <div class="flex justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                         stroke="#4CAF50" class="size-14">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                    </svg>
                </div>

                <p class="p-8 font-bold">{{ $title }}</p>

                <x-primary-button wire:click="closeSuccessModal" class="justify-center">
                    ОК
                </x-primary-button>

            </div>
        </div>
    @endif

</div>

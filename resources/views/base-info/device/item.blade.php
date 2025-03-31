<div class="flex flex-row gap-4 items-end max-w-2xl mx-auto border border-gray-300 rounded-2xl overflow-hidden"
     style="background-color: rgb(248, 249, 250);">
    <div class="items-center py-4 rounded-2xl w-full">

        <div class="flex flex-col gap-6">
            <div class="flex flex-col text-left gap-2 px-4">
                <p class="font-bold">Название маршрутизатора:</p>
                <p>{{ $contract->device->name ?? 'Отсутствует' }}</p>
            </div>

            <hr class="border-t border-gray-300">
            <div class="flex flex-col text-left gap-2 px-4">
                <p class="font-bold">IP-address:</p>
                <p>{{ $contract->device->ip_address ?? 'Отсутствует' }}</p>
            </div>

            <hr class="border-t border-gray-300">
            <div class="flex flex-col text-left gap-2 px-4">
                <p class="font-bold">MAC-address:</p>
                <p>{{ $contract->device->mac_address ?? 'Отсутствует' }}</p>
            </div>
        </div>
    </div>

    <div class="w-32 h-32 md:w-40 md:h-40 lg:w-48 lg:h-48 flex-shrink-0">
        <img src="{{ Vite::asset('resources/assets/images/router-banner.png') }}"
             alt="router"
             class="w-full h-full object-contain">
    </div>
</div>
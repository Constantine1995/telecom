<div class="flex flex-col gap-4">
    <p class="font-bold"> Номер телефона: </p>
    <p>{{ $connectRequest->phone }}</p>

    <hr class="border-gray-300">

    <p class="font-bold"> Адрес: </p>
    <p>{{ $connectRequest->address->getFullAddress() ?? 'Отсутствует' }}</p>

    <hr class="border-gray-300">

    <p class="font-bold"> Статус заявления: </p>
    <p>{{ $connectRequest->connect_request_status_type->label() }}</p>

    <hr class="border-gray-300">

    <p class="font-bold"> Договор: </p>
    <p>№ {{ $connectRequest->contract->contract_number ?? 'Отсутствует' }}</p>
</div>
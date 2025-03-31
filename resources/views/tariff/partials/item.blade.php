<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Тарифы</title>
</head>

<body class="flex items-center justify-center min-h-screen bg-gray-100">

<div class="flex flex-col w-full max-w-[370px] p-6 bg-white rounded-2xl shadow-lg shadow-gray-200/50  gap-4"
     style="color: {{ $tariff['tag']['text-color'] }};">

    <div class="flex flex-col items-start justify-between gap-2 h-14 mb-8">
        <h2 class="text-2xl font-bold text-gray-900"> {{$tariff['name']}} </h2>

        <div class="px-3 py-1 text-sm font-semibold rounded-full flex items-center gap-1"
             style="background-color: {{ $tariff['tag']['bg-color'] }};">
            {{ $tariff['tag']['name'] }}
        </div>
    </div>

    <div class="flex items-center gap-3 border-t border-b py-3">
        <span class="text-purple-600">
            <svg xmlns="http://www.w3.org/2000/svg" width="29" height="29" viewBox="0 0 29 29" fill="none">
                <g id="Icons">
                    <g id="Vector">
                        <path
                            d="M21.8024 28.1801H6.86904C4.44238 28.1801 2.22905 26.3134 1.82905 23.9134L0.0557299 13.3001C-0.22427 11.6467 0.575736 9.52675 1.89574 8.47342L11.1357 1.07335C12.9224 -0.36665 15.7357 -0.353304 17.5357 1.0867L26.7757 8.47342C28.0824 9.52675 28.8824 11.6467 28.6157 13.3001L26.8424 23.9134C26.4424 26.2734 24.1891 28.1801 21.8024 28.1801ZM14.3224 2.01996C13.6157 2.01996 12.9091 2.23342 12.3891 2.64676L3.14903 10.0467C2.38903 10.66 1.86904 12.0201 2.02904 12.9801L3.80239 23.5934C4.04239 24.9934 5.44238 26.1801 6.86904 26.1801H21.8024C23.2291 26.1801 24.629 24.9934 24.869 23.58L26.6424 12.9667C26.8024 12.0067 26.2691 10.6333 25.5224 10.0333L16.2824 2.64676C15.749 2.23342 15.0424 2.01996 14.3224 2.01996Z"
                            fill="#7700FF"/>
                        <path
                            d="M17.1633 18.2735C16.91 18.2735 16.67 18.1802 16.47 17.9936C15.1767 16.7536 13.4967 16.7536 12.19 17.9936C11.79 18.3802 11.1633 18.3669 10.7766 17.9669C10.39 17.5669 10.4033 16.9401 10.8033 16.5535C12.87 14.5668 15.7633 14.5668 17.8433 16.5535C18.2433 16.9401 18.2566 17.5669 17.87 17.9669C17.6966 18.1669 17.43 18.2735 17.1633 18.2735Z"
                            fill="#7700FF"/>
                        <path
                            d="M19.9898 15.447C19.7364 15.447 19.4831 15.3537 19.2964 15.167C18.6698 14.5537 17.9631 14.0602 17.2164 13.7002C15.3498 12.8069 13.3098 12.8069 11.4564 13.7002C10.7098 14.0602 10.0164 14.5537 9.37643 15.167C8.98976 15.5537 8.34977 15.5537 7.9631 15.1537C7.57643 14.7537 7.58978 14.1269 7.97645 13.7403C8.76311 12.9669 9.6431 12.3536 10.5898 11.9003C13.0031 10.7403 15.6698 10.7403 18.0697 11.9003C19.0164 12.3536 19.8964 12.9669 20.6831 13.7403C21.0831 14.1269 21.0831 14.7537 20.6964 15.1537C20.5098 15.3404 20.2564 15.447 19.9898 15.447Z"
                            fill="#7700FF"/>
                        <path
                            d="M14.3367 21.4336C13.99 21.4336 13.6567 21.3002 13.39 21.0469C12.87 20.5269 12.87 19.687 13.39 19.167C13.91 18.647 14.7633 18.647 15.2833 19.167C15.8033 19.687 15.8033 20.5269 15.2833 21.0469C15.0166 21.3002 14.6833 21.4336 14.3367 21.4336Z"
                            fill="#7700FF"/>
                    </g>
                </g>
            </svg>
        </span>

        <div class="flex flex-col w-full">
            <p class="text-gray-500 font-medium text-left">Домашний интернет</p>
            <p class="font-bold text-gray-900 text-left">до {{$tariff['speed']}} Мбит/с</p>
        </div>
        <div class="bg-purple-600 rounded-full p-1">
            <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 21 20" fill="none">
                <g id="Icons=Tick Square">
                    <path id="Stroke 3" d="M4 11.1864L9.18859 16.3728L19.5614 6" stroke="#ffffff" stroke-width="2"
                          stroke-linecap="round"
                          stroke-linejoin="round"/>
                </g>
            </svg>
        </div>

    </div>

    <div class="flex flex-col gap-2 items-start">
        <span
            class="text-gray-900 font-semibold">  {{$tariff['connection_type']->value === \App\Enums\PropertyType::HOUSE->value ? 'Частный дом': 'Многоквартирный дом'}}  </span>
        <div class="flex justify-between w-full text-gray-900  font-semibold">
            <span>Подключение</span>
            <span class=" text-gray-500 text-md font-medium">{{$tariff['connection_price']}} руб.</span>
        </div>
    </div>

    <div class="flex items-center justify-between gap-5 mt-4">
        <p class="text-2xl font-bold text-gray-900">{{$tariff['price']}} <span
                class="text-lg font-medium">руб./мес</span></p>

        <x-primary-button
            onclick="Livewire.dispatch('{{ $hasPassport ? 'openRequestModal' : 'openPassportModal' }}', {{ json_encode(['tariffId' => $tariff->id]) }})">
            + Заказать
        </x-primary-button>

    </div>
</div>

</body>
</html>

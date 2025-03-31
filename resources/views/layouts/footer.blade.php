@php
    $phoneItems = [
        [
            'number' => '+7 (999) 999 99 99',
            'description' => 'Техподдержка',
            'nowrap' => true,
            'multiline' => false
        ],
        [
            'number' => '+7 (999) 999 99 99',
            'description' => 'По территории РФ',
            'nowrap' => true,
            'multiline' => false
        ],
        [
            'number' => '5555',
            'description' => "Бесплатно для абонентов\nТелеком",
            'nowrap' => false,
            'multiline' => true
        ]
    ];
@endphp

<footer class="text-center text-sm text-black bg-gray-100 pt-8">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-t-2xl p-6 sm:p-8 lg:p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
                <!-- Левый блок с номерами -->
                <div class="text-left">
                    <x-phone-numbers :items="$phoneItems"/>
                </div>

                <!-- Правый блок Telegram -->
                <div class="flex md:justify-end">
                    <div class="bg-purple-600 text-white p-6 rounded-2xl w-full max-w-xs">
                        <h2 class="font-bold text-lg">Напишите нам</h2>
                        <p class="text-white/90 mt-2 text-sm">Свяжитесь с нами в любой удобной для вас форме</p>
                        <div class="mt-4">
                            <a href="#" class="flex items-center justify-center gap-2 border border-white p-3 rounded-xl text-white hover:bg-white/10 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                     stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5"/>
                                </svg>
                                <span>Telegram</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <p class="text-gray-400 pt-6 sm:pt-8 text-left text-xs sm:text-sm">
                Компания занимает лидирующие позиции на рынке услуг высокоскоростного доступа в интернет и платного
                телевидения, а также мобильной связи. Количество клиентов услуг доступа в интернет с использованием
                оптических технологий превышает 12,6 млн. «Телеком» входит в топ-3 мобильных операторов страны с более
                чем 48 млн абонентов.<br>
                <br>ПАО «Телеком» обрабатывает пользовательские данные при работе сайта в соответствии с Политикой
                конфиденциальности. Также Вы можете ознакомиться с Политикой обработки персональных данных.
            </p>
        </div>
    </div>
</footer>
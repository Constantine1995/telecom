<nav class="bg-white border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-10">
            <!-- Навигационные ссылки -->
            <div class="hidden sm:flex sm:items-center sm:space-x-8 sm:-my-px sm:ms-10">
                <x-nav-link :href="route('main')" :active="request()->routeIs('main')">
                    Главная
                </x-nav-link>

                <x-nav-link :href="route('tariff.index')" :active="request()->routeIs('tariff.index')">
                    Тарифы
                </x-nav-link>

                @auth
                    @if (auth()->user()->hasPassport())
                        <x-nav-link :href="route('base-info.index')"
                                    :active="request()->routeIs('base-info.index')">
                            Общая информация
                        </x-nav-link>
                    @endif

                    <x-nav-link :href="route('transaction.index')" :active="request()->routeIs('transaction.index')">
                        Транзакции
                    </x-nav-link>

                    <x-nav-link :href="route('payments.index')" :active="request()->routeIs('payments.index')">
                        Оплата
                    </x-nav-link>
                @endauth

                <x-nav-link :href="route('contact.index')" :active="request()->routeIs('contact.index')">
                    Контакты
                </x-nav-link>
            </div>

            <!-- меню для маленьких экранов-->
            <div class="sm:hidden">
                <button id="menu-toggle" class="text-gray-900 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Мобильное меню -->
        <div id="mobile-menu" class="sm:hidden hidden">
            <div class="flex flex-col space-y-4 pt-4 pb-2">
                <x-nav-link :href="route('main')" :active="request()->routeIs('main')">
                    Главная
                </x-nav-link>

                <x-nav-link :href="route('tariff.index')" :active="request()->routeIs('tariff.index')">
                    Тарифы
                </x-nav-link>

                @auth
                    @if (auth()->user()->hasPassport())
                        <x-nav-link :href="route('base-info.index')"
                                    :active="request()->routeIs('base-info.index')">
                            Общая информация
                        </x-nav-link>
                    @endif

                    <x-nav-link :href="route('transaction.index')" :active="request()->routeIs('transaction.index')">
                        Транзакции
                    </x-nav-link>

                    <x-nav-link :href="route('payments.index')" :active="request()->routeIs('payments.index')">
                        Оплата
                    </x-nav-link>
                @endauth

                <x-nav-link :href="route('contact.index')" :active="request()->routeIs('contact.index')">
                    Контакты
                </x-nav-link>
            </div>
        </div>
    </div>
</nav>

<script>
    document.getElementById('menu-toggle').addEventListener('click', function () {
        const mobileMenu = document.getElementById('mobile-menu');
        mobileMenu.classList.toggle('hidden');
    });
</script>
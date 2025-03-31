<header class=" items-end gap-2 py-5 px-5 bg-white">
    <div class="flex">

        <div class="flex items-center justify-center">
            <a href="/" class="flex items-center gap-2">
                <x-application-logo class="w-10 h-10 fill-current"/>
                <span class="text-gray-900 font-semibold text-lg">Телеком</span>
            </a>
        </div>

        @if (Route::has('login'))
            <nav class="flex flex-1 justify-end gap-5">
                @auth

                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <x-dropdown align="right" width="48" >
                            <x-slot name="trigger">
                                <x-primary-button>
                                    <div>{{ Auth::user()->name }}</div>
                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </x-primary-button>
                            </x-slot>

                            <x-slot name="content">
                                @auth
                                    @php
                                        $user = auth()->user();
                                        $passportRoute = $user->passport
                                            ? route('passport.edit', $user->passport)
                                            : route('passport.create');
                                    @endphp

                                    <x-dropdown-link :href="$passportRoute">
                                        Личная информация
                                    </x-dropdown-link>
                                @endauth
                                
                                <x-dropdown-link :href="route('profile.edit')">
                                    Профиль
                                </x-dropdown-link>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf

                                    <x-dropdown-link :href="route('logout')"
                                                     onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                        Выйти
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>

                @else
                    <x-primary-link href="{{ route('login') }}">
                        Логин
                    </x-primary-link>

                    @if (Route::has('register'))
                        <x-primary-link href="{{ route('register') }}">
                            Регистрация
                        </x-primary-link>
                    @endif
                @endauth
            </nav>
        @endif
    </div>
</header>

@extends('layouts.app')
@section('content')
    <x-guest-layout>
        <div class="mb-4 text-sm text-gray-600">
            Укажите свой адрес электронной почты, и мы отправим вам ссылку для сброса пароля.'
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')"/>

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div>
                <x-input-label for="email" value="Email"/>
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                              required autofocus/>
                <x-input-error :messages="$errors->get('email')" class="mt-2"/>
            </div>

            <div class="flex items-center justify-center mt-4">
                <x-primary-button>
                    Сброс
                </x-primary-button>
            </div>
        </form>
    </x-guest-layout>
@endsection

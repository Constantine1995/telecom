@extends('layouts.app')
@section('content')
<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        Пожалуйста, подтвердите свой пароль, прежде чем продолжить.
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div>
            <x-input-label for="password" value="Пароль" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex justify-center mt-4">
            <x-primary-button>
                Подтвердить
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
@endsection

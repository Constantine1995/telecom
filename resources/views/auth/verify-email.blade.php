@extends('layouts.app')
@section('content')
<x-guest-layout>
    <div class="mb-8 text-base text-gray-600">
        Мы отправили письмо на электронную почту, перейдите по ссылку, чтобы подтвердить адрес электронной почты.
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600">
            На адрес электронной почты, указанный при регистрации, была отправлена новая ссылка для проверки.
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button>
                    Отправить повторно
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="underline text-base text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Выйти
            </button>
        </form>
    </div>
</x-guest-layout>
@endsection

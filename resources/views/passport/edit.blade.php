@extends('layouts.app')
@section('content')
    <div class="py-12">
        <div class="flex flex-col gap-8">

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="w-[500px]">
                    <form method="POST" action="{{ route('passport.update', $passport) }}"
                          enctype="multipart/form-data">
                        @csrf
                        @method('put')
                        @include('passport.form')

                        <x-primary-button class="mt-4"> Обновить</x-primary-button>

                        @if (session('status') === 'passport-updated')
                            <div x-data="{ show: true }"
                                 x-show="show"
                                 x-init="window.scrollTo({ top: 0, behavior: 'smooth' }); setTimeout(() => show = false, 2000)"
                                 class="fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg">
                                Сохранено!
                            </div>
                        @endif

                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection

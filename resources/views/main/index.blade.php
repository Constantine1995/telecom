@extends('layouts.app')

@section('content')
    <div class="flex flex-col font-sans text-center gap-5 mt-12 mb-12">

        <h1 class="text-left text-3xl font-bold text-gray-800">
            3 причины, чтобы подключиться к Телеком
        </h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 w-full">
            @foreach($banners as $banner)
                @include('main.partials.banner.item', ['banner' => $banner])
            @endforeach
        </div>

        @guest
            <div class="mt-6">
                <x-secondary-link href="{{ route('tariff.index') }}"> Подключиться</x-secondary-link>
            </div>
        @endauth

    </div>

@endsection

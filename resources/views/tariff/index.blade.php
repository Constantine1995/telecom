@extends('layouts.app')

@section('content')

    <div class="flex flex-col font-sans text-left gap-5 mt-12 mb-12 px-4">

        <h1 class="text-3xl font-bold text-gray-800">
            Тарифы
        </h1>

        @if($tariffForHouse->isNotEmpty())
            <h2 class="text-2xl font-bold">Интернет в частном секторе</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5 w-full">
                @foreach($tariffForHouse as $tariff)
                    @include('tariff.partials.item', ['tariff' => $tariff])
                @endforeach
            </div>
        @endif

        @if($tariffForApartment->isNotEmpty())
            <h2 class="text-2xl font-bold mt-6">Интернет в многоквартирных домах</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5 w-full">
                @foreach($tariffForApartment as $tariff)
                    @include('tariff.partials.item', ['tariff' => $tariff])
                @endforeach
            </div>
        @endif

    </div>

@endsection

@extends('layouts.app')
@section('content')

    <div class="flex flex-col font-sans text-left gap-5 mt-12 mb-12 px-52 w-full">
        <div class="flex flex-col gap-8 mb-6 text-start">

            <h2 class="font-bold text-2xl"> Личные данные </h2>
            <div class="flex flex-col items-start text-center">
                <p class="font-sans"> ФИО </p>
                <p class="font-bold"> {{ $passport->fullName() }} </p>
            </div>

            @php
                $connectRequests = $contracts->pluck('connectRequest')->filter();
                 $filteredRequests = $connectRequests->reject(
                 fn($request) => $request->connect_request_status_type->value === \App\Enums\ConnectRequestStatus::COMPLETED->value);
            @endphp

            @if ($filteredRequests->isNotEmpty())
                <div class="flex flex-col gap-2">
                    <h2 class="font-bold text-2xl">
                        {{ $filteredRequests->count() === 1 ? 'Заявление' : 'Заявления' }} на подключения
                    </h2>
                    <div>
                        <div class="grid grid-cols-1 sm:grid-cols-1 lg:grid-cols-3 gap-8 w-full">
                            @foreach($filteredRequests as $connectRequest)
                                <div class="flex justify-center">
                                    <div class="min-w-[200px] max-w-[400px] border border-gray-300 rounded-lg bg-gray-100 p-6 shadow-md">
                                        @include('base-info.connect-request.item', ['connectRequest' => $connectRequest])
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            @foreach($contracts as $contract)
                @include('base-info.contract.item', ['contract' => $contract])
            @endforeach
        </div>

@endsection

@extends('layouts.app')
@section('content')
    <div class="py-12">
        <div class="flex flex-col gap-8">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="w-[500px]">
                    <form method="POST" action="{{ route('passport.store') }}" enctype="multipart/form-data">
                        @csrf
                        @include('passport.form')
                        <x-primary-button class="mt-4"> Создать</x-primary-button>
                    </form>
                </div>
            </div>
        </div>
@endsection

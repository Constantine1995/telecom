<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Телеком') }}</title>

    @vite('resources/css/app.css')
    @livewireStyles
</head>

<body class="font-sans antialiased flex flex-col min-h-screen">
@include('layouts.header')
@include('layouts.navigation')

<main class="flex-1 text-center bg-gray-100 flex items-center justify-center border-t">
    @yield('content')
</main>

@include('layouts.footer')

@livewire('order-modal')
@livewire('passport-modal')
@livewire('tariff-modal')
@livewire('success-modal')

@livewireScripts
</body>
</html>

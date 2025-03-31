<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body class="flex items-center justify-center min-h-screen bg-gray-100">

<div class="h-[450px] w-[330px] flex flex-col justify-between p-6 rounded-3xl text-white relative"
     style="color: {{ $banner['text-color'] }};
     background-color: {{$banner['bg-color']}};">

    <div
            class="absolute top-4 left-4 w-10 h-10 flex items-center justify-center border rounded-full text-lg font-bold"
            style="border-color: {{ $banner['text-color'] }};">
        {{$banner['order']}}
    </div>

    <div class="flex justify-center mb-4">
        <img src="{{ $banner->iconUrl() }}" class="w-40 h-40 object-contain" alt="Баннер">
    </div>

    <h2 class="flex justify-start text-2xl font-bold mb-2">{{$banner['title']}}</h2>

    <p class="text-left text-sm font-medium leading-relaxed">
        {{$banner['description']}}
    </p>

</div>

</body>
</html>

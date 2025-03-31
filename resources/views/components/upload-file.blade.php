@props(['current' => null])

@if ($current)
    <div class="mb-2">
        <img src="{{ $current }}" alt=""
             class="w-32 h-32 object-cover rounded">
    </div>
@endif

<input {{ $attributes->merge([
    'type' => 'file',
    'class' => 'block w-full text-sm text-gray-500
                file:mr-4 file:py-2 file:px-4
                file:rounded-full file:border-0
                file:text-sm file:font-semibold
                file:bg-white file:text-orange-400
                hover:file:bg-orange-100'
]) }}>
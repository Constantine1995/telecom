@props(['disabled' => false])

<select @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 focus:border-orange-500 focus:ring-orange-400 rounded-md shadow-sm']) }}>
    {{ $slot }}
</select>

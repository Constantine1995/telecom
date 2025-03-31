<a {{ $attributes->merge(['class' => 'flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-orange-500 to-orange-400 text-white font-semibold rounded-2xl shadow-md hover:from-orange-600 hover:to-orange-500 transition-all']) }}>
    @isset($icon)
        {{ $icon }}
    @endisset
    {{ $slot }}
</a>

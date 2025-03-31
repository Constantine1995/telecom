@props(['items'])

<div class="font-sans space-y-4 sm:space-y-2 max-w-full sm:max-w-xs">
    @foreach($items as $item)
        <div class="space-y-2 sm:space-y-1">
            <div class="text-lg sm:text-base font-semibold tracking-tight
                {{ $item['nowrap'] ? 'whitespace-nowrap' : '' }}">
                {{ $item['number'] }}
            </div>
            <div class="text-base sm:text-sm text-gray-600
                {{ $item['multiline'] ? 'leading-tight' : '' }}">
                {!! nl2br(e($item['description'])) !!}
            </div>
            <hr class="border-t border-gray-200 hidden sm:block">
        </div>
    @endforeach
</div>
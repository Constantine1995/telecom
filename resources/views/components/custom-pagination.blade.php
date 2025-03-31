@if ($paginator->lastPage() > 1)
    <div class="flex items-center justify-center gap-2 mt-4">
        @if ($paginator->onFirstPage())
            <button disabled class="px-3 py-2 bg-gray-200 text-gray-500 rounded-md cursor-not-allowed">Назад</button>
        @else
            <button wire:click="previousPage" rel="prev" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md transition-colors">Назад</button>
        @endif

        @foreach ($elements as $element)
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="px-3 py-2 bg-orange-500 text-white rounded-md">{{ $page }}</span>
                    @else
                        <button wire:click="gotoPage({{ $page }})" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md transition-colors">{{ $page }}</button>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <button wire:click="nextPage" rel="next" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md transition-colors">Вперёд</button>
        @else
            <button disabled class="px-3 py-2 bg-gray-200 text-gray-500 rounded-md cursor-not-allowed">Вперёд</button>
        @endif
    </div>
@endif
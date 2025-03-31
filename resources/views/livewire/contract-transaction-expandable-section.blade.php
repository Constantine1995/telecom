<x-transaction-expandable :is-expanded="$isExpanded" :transactions="$transactions">
    <x-slot:title>
        Договор №{{ $contract->contract_number }}
    </x-slot>
</x-transaction-expandable>

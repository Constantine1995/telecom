<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

class ContractTransactionExpandableSection extends Component
{
    use WithPagination;

    public bool $isExpanded = false;
    public mixed $contract;

    public function mount($contract)
    {
        $this->contract = $contract;
    }

    public function toggle()
    {
        $this->isExpanded = !$this->isExpanded;
    }

    public function render()
    {
        $transactions = $this->contract->transactions()
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view('livewire.contract-transaction-expandable-section', [
            'transactions' => $transactions,
        ]);
    }
}

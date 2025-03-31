<?php

namespace App\Livewire;

use Livewire\Component;

class ContractExpandableSection extends Component
{
    public bool $isExpanded = false;
    public mixed $contract;

    protected $listeners = ['contractUpdated'];

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
        return view('livewire.contract-expandable-section');
    }
}

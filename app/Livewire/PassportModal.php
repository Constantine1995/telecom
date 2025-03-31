<?php

namespace App\Livewire;

use App\Models\Tariff;
use Livewire\Component;

class PassportModal extends Component
{
    public bool $isOpen = false;
    public ?Tariff $tariff = null;

    protected $listeners = ['openPassportModal'];

    public function openPassportModal($tariffId)
    {
        if ($this->isOpen) return;

        $this->reset();
        $this->isOpen = true;
        $this->tariff = Tariff::findOrFail($tariffId);
    }

    public function closePassportModal()
    {
        $this->isOpen = false;
    }

    public function render()
    {
        return view('livewire.passport-modal');
    }
}

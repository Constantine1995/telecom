<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class SuccessModal extends Component
{

    public bool $isOpen = false;
    public string $title = 'Успешно';

    #[On('open-success-modal')]
    public function openSuccessModal(string $title = 'Успешно')
    {
        $this->title = $title;
        $this->isOpen = true;
    }

    public function closeSuccessModal()
    {
        $this->isOpen = false;
    }

    public function render()
    {
        return view('livewire.success-modal');
    }
}

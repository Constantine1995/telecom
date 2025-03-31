<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserTransactionExpandableSection extends Component
{
    use WithPagination;

    public bool $isExpanded = false;
    public User $user;

    public function toggle()
    {
        $this->isExpanded = !$this->isExpanded;
    }

    public function render()
    {
        $transactions = $this->user->transactions()
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view('livewire.user-transaction-expandable-section', [
            'transactions' => $transactions,
        ]);
    }
}

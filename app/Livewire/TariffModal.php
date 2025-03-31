<?php

namespace App\Livewire;

use App\Models\Contract;
use App\Models\Tariff;
use Livewire\Attributes\Computed;
use Livewire\Component;

class TariffModal extends Component
{
    public bool $isOpen = false;
    public $tariff_id;
    public Contract $contract;

    protected $listeners = ['openTariffModal'];

    public function openTariffModal(int $contractId)
    {
        if ($this->isOpen) return;
        $this->isOpen = true;
        $this->contract = Contract::findOrFail($contractId);
        $this->tariff_id = $this->contract->tariff_id;
    }

    public function closeTariffModal()
    {
        $this->isOpen = false;
    }

    #[Computed]
    public function tariffs()
    {
        // Получаем тип подключения из тарифа, связанного с контрактом
        $connection_type = $this->contract->tariff->connection_type;
        // Возвращаем список тарифов с таким же типом подключения, извлекая только имя и ID
        return Tariff::where('connection_type', $connection_type)->pluck('name', 'id');
    }

    public function sendRequest()
    {
        if (!$this->contract) {
            return;
        }
        // Обновляем и шлём событие
        $this->contract->update(['tariff_id' => $this->tariff_id]);
        $this->dispatch('contractUpdated', contractId: $this->contract->id);

        $this->closeTariffModal();
    }

    public function render()
    {
        return view('livewire.tariff-modal');
    }
}

<?php

namespace App\Livewire;

use App\Enums\ConnectRequestStatus;
use App\Enums\ContractStatus;
use App\Enums\PropertyType;
use App\Models\Address;
use App\Models\City;
use App\Models\ConnectRequest;
use App\Models\Contract;
use App\Models\Region;
use App\Models\Tariff;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class OrderModal extends Component
{
    public bool $isOpen = false;
    public ?Tariff $tariff = null;

    public $regions;
    public $cities;

    public $name;
    public $phone;
    public $region;
    public $city;
    public $street;

    public ?string $houseNumber = null;
    public ?string $apartmentNumber = null;

    protected $listeners = ['openRequestModal'];

    public function openRequestModal(int $tariffId)
    {
        if ($this->isOpen) return;

        // Сбрасываем все свойства объекта к начальным значениям
        $this->reset();
        $this->isOpen = true;
        $this->tariff = Tariff::findOrFail($tariffId);

        // Определяем тип недвижимости (квартира или дом) на основе типа подключения тарифа
        $this->house = (
        $this->tariff->connection_type === PropertyType::APARTMENT->value
            ? PropertyType::APARTMENT
            : PropertyType::HOUSE
        )->value;
        $this->regions = Region::all();
        $this->cities = collect();
        $this->city = null;
    }

    public function closeRequestModal()
    {
        $this->isOpen = false;
    }

    public function updatedRegion($value)
    {
        $this->cities = City::where('region_id', $value)->get();
        $this->city = $this->cities->first()->id ?? null;
    }

    public function sendRequest()
    {
        $this->validate($this->getValidationRules());

        DB::transaction(function () {
            // Создание адрес
            $address = Address::create([
                'region_id' => $this->region,
                'city_id' => $this->city,
                'property_type' => $this->tariff->connection_type,
                'street' => $this->street,
                'house_number' => $this->houseNumber,
                'apartment_number' => $this->apartmentNumber,
            ]);

            // Создание заявления
            $connectRequest = ConnectRequest::create([
                'user_id' => auth()->id(),
                'address_id' => $address->id,
                'name' => $this->name,
                'phone' => $this->phone,
                'connect_request_status_type' => ConnectRequestStatus::NEW->value,
            ]);

            // Создание договора
            $contract = Contract::create([
                'user_id' => auth()->id(),
                'tariff_id' => $this->tariff->id,
                'contract_status' => ContractStatus::PENDING_ACTIVATION->value,
                'address_id' => $address->id,
                'connect_request_id' => $connectRequest->id,
                'contract_number' => Contract::generateContractNumber(),
                'date_connection' => now(),
            ]);

            $contract->save();
        });

        $this->isOpen = false;
        $this->dispatch('open-success-modal', title: 'Заявление создано успешно!');
    }

    public function changeHouse()
    {
        $this->apartmentNumber = null;
    }

    protected function getValidationRules()
    {
        $rules = [
            'name' => ['required', 'string', 'max:50'],
            'phone' => ['required', 'regex:/^\+7\(\d{3}\) \d{3}-\d{2}-\d{2}$/'],
            'region' => 'required',
            'city' => 'required',
            'street' => ['required', 'string', 'max:100'],
            'houseNumber' => ['required', 'string', 'max:10'],
        ];

        $rules['apartmentNumber'] = $this->tariff->connection_type === PropertyType::APARTMENT->value ? 'required|string|max:10' : 'nullable|string|max:10';

        return $rules;
    }

    public function messages(): array
    {
        return [
            'phone.regex' => 'Введите корректный номер телефона в формате +7(999) 999-99-99',
        ];
    }

    public function render()
    {
        return view('livewire.order-modal', [
            'Houses' => PropertyType::cases(),
        ]);
    }

}

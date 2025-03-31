<?php

namespace App\Livewire;

use App\Enums\PropertyType;
use App\Models\City;
use App\Models\Passport;
use App\Models\Region;
use Livewire\Attributes\Computed;
use Livewire\Component;

class AddressSelector extends Component
{
    public ?Passport $passport = null;

    public $region_id;
    public $city_id;

    public string $property_type = '';
    public string $house_number = '';
    public string $street = '';
    public ?string $apartment_number = null;

    protected $queryString = [
        'main_photo',
        'registration_photo',
        'property_type',
        'region_id',
        'city_id',
        'house_number',
        'apartment_number',
    ];

    public function mount(?Passport $passport)
    {
        $this->passport = $passport;

        // Проверяем, существует ли паспорт и связанный с ним адрес
        if ($this->passport && $this->passport->address) {
            // Заполняем поля данными из адреса паспорта
            $this->fill([
                'region_id' => $this->passport->address->region_id,
                'city_id' => $this->passport->address->city_id,
                'property_type' => $this->passport->address->property_type->value ?? PropertyType::APARTMENT->value,
                'house_number' => $this->passport->address->house_number,
                'apartment_number' => $this->passport->address->apartment_number,
                'street' => $this->passport->address->street,
            ]);
        }

        // Если тип недвижимости не задан, устанавливаем значение из паспорта или по умолчанию - квартира
        if (blank($this->property_type)) {
            $this->property_type = $passport->property_type->value ?? PropertyType::APARTMENT->value;
        }
    }

    public function updatedRegionId($value)
    {
        $this->city_id = null;
    }

    #[Computed]
    public function regions()
    {
        return Region::pluck('name', 'id');
    }

    #[Computed]
    public function cities()
    {
        return $this->region_id ? City::where('region_id', $this->region_id)->pluck('name', 'id') : [];
    }

    public function render()
    {
        return view('livewire.address-selector');
    }
}

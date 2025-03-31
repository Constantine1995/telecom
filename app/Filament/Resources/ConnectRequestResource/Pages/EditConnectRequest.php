<?php

namespace App\Filament\Resources\ConnectRequestResource\Pages;

use App\Enums\PropertyType;
use App\Filament\Resources\ConnectRequestResource;
use App\Models\Address;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditConnectRequest extends EditRecord
{
    protected static string $resource = ConnectRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Загружаем связанные данные
        $this->record->load('address.region.cities');

        if (isset($this->record->address)) {
            $addressData = [];

            $address = $this->record->address->toArray();
            foreach ($address as $key => $value) {
                $data["address.$key"] = $value;
            }

            // Обрабатываем данные, начинающиеся с "address."
            foreach ($data as $key => $value) {
                if (str_starts_with($key, 'address.')) {
                    // Извлекаем чистый ключ, убирая префикс "address."
                    $addressKey = str_replace('address.', '', $key);
                    $addressData[$addressKey] = $value;

                    unset($data[$key]);
                }
            }

            $data['address'] = $addressData;
            $address = $this->record->address;

            // Устанавливаем конкретные поля адреса, если они существуют
            foreach (['property_type', 'street', 'house_number', 'apartment_number'] as $key) {
                $data["address.$key"] = $address->$key ?? null;
            }
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Извлекаем данные адреса из общего массива
        $addressData = $data['address'] ?? [];

        // Очистка поля apartment_number, если выбран "Дом"
        if (isset($addressData['property_type']) && $addressData['property_type'] === PropertyType::HOUSE->value) {
            $addressData['apartment_number'] = null; // Очищаем поле в массиве адреса
        }

        // Если есть существующий адрес, обновляем его
        if (!empty($addressData) && $this->record->address) {
            $this->record->address()->update($addressData);
        } elseif (!empty($addressData)) {
            // Если адреса нет, создаем новый
            $address = Address::create($addressData);
            $data['address_id'] = $address->id;
        }

        unset($data['region_id'], $data['city_id'], $data['address']);

        return $data;
    }

}

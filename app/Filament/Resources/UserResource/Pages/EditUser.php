<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Enums\PropertyType;
use App\Filament\Resources\UserResource;
use App\Models\Address;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Подгружаем связанные данные
        $this->record->load('passport.address.region.cities');

        // Если паспорт существует, добавляем его данные в массив формы
        if ($this->record->passport) {
            $data['passport'] = $this->record->passport->toArray();

            // Если адрес существует, добавляем его данные в массив формы
            if ($this->record->passport->address) {
                $data['address'] = $this->record->passport->address->toArray();

                $data['region'] = $this->record->passport->address->region?->toArray();
                $data['city'] = $this->record->passport->address->city?->toArray();
            }
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        try {
            DB::transaction(function () use (&$data) {
                // Извлекаем данные адреса и паспорта
                $addressData = $data['address'] ?? [];
                $passportData = $data['passport'] ?? [];

                // Очистка поля apartment_number, если выбран "Дом"
                if (isset($addressData['property_type']) && $addressData['property_type'] === PropertyType::HOUSE->value) {
                    $addressData['apartment_number'] = null;
                }

                // Обновляем или создаём адрес
                if (!empty($addressData)) {
                    $address = Address::updateOrCreate(
                        ['id' => $this->record->passport?->address_id],
                        $addressData
                    );
                    $passportData['address_id'] = $address->id;
                }

                // Обновляем или создаём паспорт
                if (!empty($passportData)) {
                    $this->record->passport()->updateOrCreate([], $passportData);
                }

                // Удаляем данные, которые не относятся к основной модели User
                unset($data['passport'], $data['address'], $data['region'], $data['city']);
            });
        } catch (QueryException $e) {
            throw new \Exception('Ошибка при обновлении связанных данных: ' . $e->getMessage());
        }

        return $data;
    }
}
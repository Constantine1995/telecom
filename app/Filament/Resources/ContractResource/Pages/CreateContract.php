<?php

namespace App\Filament\Resources\ContractResource\Pages;

use App\Filament\Resources\ContractResource;
use App\Models\Address;
use App\Models\Contract;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;

class CreateContract extends CreateRecord
{
    protected static string $resource = ContractResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        DB::transaction(function () use (&$data) {
            $data['contract_number'] = Contract::generateContractNumber();

            // Создаём адрес
            $address = Address::create([
                'region_id' => $data['address']['region_id'],
                'city_id' => $data['address']['city_id'],
                'property_type' => $data['address']['property_type'],
                'street' => $data['address']['street'],
                'house_number' => $data['address']['house_number'],
                'apartment_number' => $data['address']['apartment_number'] ?? null,
            ]);

            // Добавляем ID счёта и адреса в данные для контракта
            $data['address_id'] = $address->id;
        });

        unset($data['region_id'], $data['city_id'], $data['address']);

        return $data;
    }
}

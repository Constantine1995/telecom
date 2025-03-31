<?php

namespace App\Filament\Resources\ConnectRequestResource\Pages;

use App\Enums\ContractStatus;
use App\Filament\Resources\ConnectRequestResource;
use App\Models\Address;
use App\Models\Contract;
use App\Models\Tariff;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class CreateConnectRequest extends CreateRecord
{
    protected static string $resource = ConnectRequestResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $hasPassport = auth()->user()->hasPassport();
        if (!$hasPassport) {
            throw ValidationException::withMessages([
                'statement' => ['У вас не заполнен паспорт. Пожалуйста, добавьте данные паспорта перед подачей заявления.'],
            ]);
        }

        DB::transaction(function () use (&$data) {
            // Создание адреса
            $address = Address::create([
                'region_id' => $data['address']['region_id'],
                'city_id' => $data['address']['city_id'],
                'property_type' => $data['address']['property_type'],
                'street' => $data['address']['street'],
                'house_number' => $data['address']['house_number'],
                'apartment_number' => $data['address']['apartment_number'] ?? null,
            ]);

            // Добавляем ID адреса в данные
            $data['address_id'] = $address->id;
        });

        unset($data['region_id'], $data['city_id'], $data['address']);
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        $record = $this->record;
        $address = Address::findOrFail($record->address_id);

        $tariffId = Tariff::where('connection_type', $address->property_type->value)->value('id');

        try {
            $contract = Contract::create([
                'user_id' => $record->user_id,
                'tariff_id' => $tariffId,
                'contract_status' => ContractStatus::PENDING_ACTIVATION->value,
                'address_id' => $record->address_id,
                'connect_request_id' => $record->id,
                'contract_number' => Contract::generateContractNumber(),
                'date_connection' => now(),
            ]);

            return route('filament.admin.resources.contracts.edit', ['record' => $contract->id]);
        } catch (\Exception $e) {
            Log::error('Ошибка при создании договора: ' . $e->getMessage());

            Notification::make()
                ->title('Ошибка')
                ->body('Ошибка при создании договора: ')
                ->danger()
                ->send();

            throw ValidationException::withMessages([
                'form' => ['Ошибка при создании договора: ' . $e->getMessage()],
            ]);
        }
    }
}

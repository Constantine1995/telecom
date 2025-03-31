<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\Address;
use App\Models\Balance;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function afterCreate(): void
    {
        try {
            // Запускаем транзакцию БД для обеспечения целостности данных
            DB::transaction(function () {
                // Получаем текущее состояние формы после заполнения
                $formState = $this->form->getState();

                if (isset($formState['address'])) {
                    // Создаем новую запись адреса в базе данных
                    $address = Address::create($formState['address']);

                    // Привязываем ID созданного адреса к данным паспорта
                    $formState['passport']['address_id'] = $address->id;
                }

                // Создаем запись паспорта для текущей модели, используя данные из формы
                $this->record->passport()->create($formState['passport']);

                // Создаем запись Balance для нового пользователя
                Balance::create([
                    'user_id' => $this->record->id,
                    'amount' => 0.00,
                ]);
            });
        } catch (QueryException $e) {
            throw new \Exception('Ошибка при создании связанных данных: ' . $e->getMessage());
        }
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Удаляем данные, которые не относятся к основной модели User
        unset($data['address'], $data['passport']);
        return $data;
    }
}
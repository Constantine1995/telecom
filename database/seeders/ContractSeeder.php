<?php

namespace Database\Seeders;

use App\Enums\ContractStatus;
use App\Models\Address;
use App\Models\ConnectRequest;
use App\Models\Contract;
use App\Models\Device;
use App\Models\Tariff;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ContractSeeder extends Seeder
{
    public function run(): void
    {
        // Проверяем наличие пользователя "admin" или создаем его
        $user = User::where('name', 'admin')->first() ?? User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@example.com',
        ]);

        $address = Address::first();
        $tariff = Tariff::where('connection_type', $address->property_type)->first();

        $contract = [
            'user_id' => $user->id,
            'address_id' => $address->id,
            'device_id' => Device::first()->id,
            'connect_request_id' => ConnectRequest::first()->id,
            'tariff_id' => $tariff->id,
            'contract_status' => ContractStatus::PENDING_ACTIVATION->value,
            'contract_number' => $this->generateUniqueContractNumber(),
            'date_connection' => Carbon::now(),
            'date_disconnection' => null,
        ];

        Contract::firstOrCreate($contract);
    }

    private function generateUniqueContractNumber(): int
    {
        do {
            $number = rand(100000, 999999);
        } while (Contract::where('contract_number', $number)->exists());

        return $number;
    }
}

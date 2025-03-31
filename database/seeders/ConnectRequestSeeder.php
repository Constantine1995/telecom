<?php

namespace Database\Seeders;

use App\Enums\ConnectRequestStatus;
use App\Models\Address;
use App\Models\ConnectRequest;
use App\Models\User;
use Illuminate\Database\Seeder;

class ConnectRequestSeeder extends Seeder
{
    public function run(): void
    {
        // Проверяем наличие пользователя "admin" или создаем его
        $user = User::where('name', 'admin')->first() ?? User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@example.com',
        ]);

        $address = Address::inRandomOrder()->first() ?? Address::factory()->create();

        // Создаем запись
        ConnectRequest::firstOrCreate([
            'user_id' => $user->id,
            'address_id' => $address->id,
            'connect_request_status_type' => ConnectRequestStatus::NEW->value,
            'name' => 'admin',
            'phone' => '79999999999',
        ]);

        ConnectRequest::factory()
            ->count(5)
            ->create();
    }
}

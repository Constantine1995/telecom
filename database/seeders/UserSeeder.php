<?php

namespace Database\Seeders;

use App\Models\Balance;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Создаем администратора только один раз
        $adminData = [
            'name' => 'admin',
            'email' => 'admin@mail.ru',
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
            'remember_token' => Str::random(10),
        ];

        $admin = User::firstOrCreate(
            ['email' => $adminData['email']],
            $adminData
        );

        // Создаем баланс для администратора
        Balance::firstOrCreate(
            ['user_id' => $admin->id],
            ['amount' => 0.00]
        );

        // Создаем пользователей с помощью фабрики
        User::factory()
            ->count(5)
            ->create()
            ->each(function ($user) {
                Balance::firstOrCreate(
                    ['user_id' => $user->id],
                    ['amount' => 0.00]
                );
            });
    }
}

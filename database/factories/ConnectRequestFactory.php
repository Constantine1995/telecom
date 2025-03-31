<?php

namespace Database\Factories;

use App\Enums\ConnectRequestStatus;
use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConnectRequestFactory extends Factory
{
    public function definition(): array
    {
        // Берем случайного существующего пользователя или создаем нового
        $user = User::inRandomOrder()->first() ?? User::factory()->create();

        $address = Address::inRandomOrder()->first() ?? Address::factory()->create();

        return [
            'user_id' => $user->id,
            'address_id' => $address->id,
            'connect_request_status_type' => $this->faker->randomElement([
                ConnectRequestStatus::NEW->value,
                ConnectRequestStatus::IN_PROGRESS->value,
                ConnectRequestStatus::COMPLETED->value,
                ConnectRequestStatus::PENDING->value,
                ConnectRequestStatus::CANCELED->value,
            ]),
            'name' => $this->faker->name,
            'phone' => $this->faker->numerify('79#########'),
        ];
    }

    public function withStatus(string $status): static
    {
        return $this->state([
            'connect_request_status_type' => $status,
        ]);
    }
}
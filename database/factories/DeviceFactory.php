<?php

namespace Database\Factories;

use App\Enums\DeviceStatusType;
use Illuminate\Database\Eloquent\Factories\Factory;

class DeviceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'device_status' => $this->faker->randomElement([
                DeviceStatusType::ACTIVE->value,
                DeviceStatusType::CONNECTION_ERROR->value,
                DeviceStatusType::UNPAID->value,
                DeviceStatusType::DISABLED->value,
            ]),
            'name' => $this->faker->randomElement([
                'TP LINK Archer C64',
                'TP LINK Archer AX23',
                'Xiaomi Mi WiFi 4A',
                'ASUS RT AX55',
                'TP LINK  Archer BE230',
                'DSR-1000N',
                'Mercusys MR70X',
                'TP-LINK Archer C6U',
                'TP-LINK Deco S7',
                'TP-LINK Deco S8',
                'TP-LINK Archer AX12',
                'Xiaomi Router AC1200',
                'HUAWEI B311−221',
                'Xiaomi Mesh System AX3000',
            ]),
            'ip_address' => $this->faker->ipv4,
            'mac_address' => $this->faker->macAddress,
            'date_connection' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
        ];
    }

    public function withStatus(string $status): static
    {
        return $this->state([
            'device_status' => $status,
        ]);
    }
}
<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CityFactory extends Factory
{
    public function definition(): array
    {
        return [
            'region_id' => null,
            'name' => $this->faker->unique()->city,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    // Метод для установки region_id
    public function forRegion($region): static
    {
        return $this->state([
            'region_id' => $region->id,
        ]);
    }
}
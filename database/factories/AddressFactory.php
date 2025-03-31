<?php

namespace Database\Factories;

use App\Enums\PropertyType;
use App\Models\City;
use App\Models\Region;
use Illuminate\Database\Eloquent\Factories\Factory;


class AddressFactory extends Factory
{
    public function definition(): array
    {
        // Получаем случайный существующий регион и город
        $region = Region::inRandomOrder()->first() ?? Region::factory()->create();
        $city = City::where('region_id', $region->id)->inRandomOrder()->first() ?? City::factory()->forRegion($region)->create();

        return [
            'region_id' => $region->id,
            'city_id' => $city->id,
            'property_type' => $this->faker->randomElement([PropertyType::HOUSE->value, PropertyType::APARTMENT->value]),
            'street' => $this->faker->unique()->streetName,
            'house_number' => $this->faker->buildingNumber,
            'apartment_number' => $this->faker->numberBetween(1, 100),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
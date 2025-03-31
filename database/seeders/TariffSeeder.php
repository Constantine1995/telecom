<?php

namespace Database\Seeders;

use App\Enums\PropertyType;
use App\Models\Tag;
use App\Models\Tariff;
use Illuminate\Database\Seeder;

class TariffSeeder extends Seeder
{
    public function run(): void
    {
        $tariffs = [
            [
                'name' => 'Мир 100+',
                'tag_id' => Tag::where('name', 'Хит')->value('id'),
                'price' => 500,
                'connection_price' => 4000,
                'connection_type' => PropertyType::HOUSE->value,
                'speed' => 100,
                'active' => true,
            ],
            [
                'name' => 'Мир 200+',
                'tag_id' => Tag::where('name', 'Оптимальный')->value('id'),
                'price' => 700,
                'connection_price' => 6000,
                'connection_type' => PropertyType::APARTMENT->value,
                'speed' => 200,
                'active' => true,
            ],
            [
                'name' => 'Мир 500+',
                'tag_id' => Tag::where('name', 'Максимальный')->value('id'),
                'price' => 1000,
                'connection_price' => 4000,
                'connection_type' => PropertyType::HOUSE->value,
                'speed' => 500,
                'active' => true,
            ],
        ];

        foreach ($tariffs as $tariff) {
            Tariff::firstOrCreate($tariff);
        }
    }
}
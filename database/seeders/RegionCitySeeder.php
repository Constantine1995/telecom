<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Region;
use Illuminate\Database\Seeder;

class RegionCitySeeder extends Seeder
{
    public function run(): void
    {
        Region::factory()
            ->count(5)
            ->create()
            ->each(function ($region) {
                City::factory()
                    ->count(3)
                    ->forRegion($region) // Привязываем города к текущему региону
                    ->create();
            });

    }
}

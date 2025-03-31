<?php

namespace Database\Seeders;

use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            RegionCitySeeder::class,
            AddressSeeder::class,
            TagSeeder::class,
            TariffSeeder::class,
            BannerSeeder::class,
            DeviceSeeder::class,
            ConnectRequestSeeder::class,
            ContractSeeder::class,
        ]);
    }
}

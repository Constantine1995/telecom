<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        $banners = [
            [
                'title' => 'Остатки не сгорают',
                'description' => 'При своевременной оплате абонентской платы, неиспользованные остатки по тарифу текущего месяца и ранее перенесённые остатки прошлых периодов – переносятся на новый оплаченный период',
                'bg-color' => '#FF4F12',
                'text-color' => '#FFFFFF',
                'icon' => 'fire.png',
                'order' => 1,
            ],
            [
                'title' => 'Бесперебойный высокоскоростной интернет',
                'description' => 'Благодаря оптоволокну интернет стабильно работает и не зависит от погоды и электричества.',
                'bg-color' => '#6A0DAD',
                'text-color' => '#FFFFFF',
                'icon' => 'rocket.png',
                'order' => 2,
            ],
            [
                'title' => 'Быстрое подключение без вреда для ремонта',
                'description' => 'Привезем в течение 2-х рабочих дней после оформления заявки на подключение. Работы занимают до 2-х часов. Наши монтажники работают аккуратно и убирают за собой.',
                'bg-color' => '#FFFFFF',
                'text-color' => '#000000',
                'icon' => 'null.png',
                'order' => 3,
            ],
        ];

        foreach ($banners as $banner) {
            $sourcePath = resource_path("assets/images/banners/{$banner['icon']}");
            $destinationPath = "banners/{$banner['icon']}"; // Сохраняем в storage/app/public/banners/

            // Проверяем, существует ли файл в storage, если нет - копируем
            if (File::exists($sourcePath) && !Storage::disk('public')->exists($destinationPath)) {
                Storage::disk('public')->put($destinationPath, File::get($sourcePath));
            }

            Banner::firstOrCreate(
                ['title' => $banner['title']],
                [
                    'description' => $banner['description'],
                    'bg-color' => $banner['bg-color'],
                    'text-color' => $banner['text-color'],
                    'icon' => $destinationPath,
                    'order' => $banner['order'],
                ]
            );
        }
    }
}

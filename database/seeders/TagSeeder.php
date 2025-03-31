<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            'Хит' => ['#FFFFFF', '#7700FF'],
            'Оптимальный' => ['#FFFFFF', '#0BB477'],
            'Максимальный' => ['#FFFFFF', '#FF4F12'],
        ];

        foreach ($tags as $tagName => $colors) {
            Tag::firstOrCreate([
                'name' => $tagName,
                'text-color' => $colors[0],
                'bg-color' => $colors[1],
            ]);
        }
    }
}

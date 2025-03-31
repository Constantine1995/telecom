<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Banner extends Model
{
    protected $fillable = [
        'title',
        'description',
        'bg-color',
        'text-color',
        'icon',
        'order',
    ];

    protected $casts = [
        'icon' => 'string',
    ];

    protected static function boot()
    {
        parent::boot();

        static::updating(function ($banner) {
            // Проверяем, что старое значение существует и не равно null
            if ($banner->isDirty('icon') && !is_null($banner->getOriginal('icon'))) {
                // Удаляем старое изображение, если оно существует
                if (Storage::disk('public')->exists($banner->getOriginal('icon'))) {
                    Storage::disk('public')->delete($banner->getOriginal('icon'));
                }
            }
        });

        static::deleting(function ($banner) {
            // Проверяем, что файл существует перед удалением
            if ($banner->icon && Storage::disk('public')->exists($banner->icon)) {
                Storage::disk('public')->delete($banner->icon);
            }
        });
    }

    public function iconUrl(): string
    {
        return asset('storage/' . $this->icon);
    }

}

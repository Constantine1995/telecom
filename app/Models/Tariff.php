<?php

namespace App\Models;

use App\Enums\PropertyType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Tariff extends Model
{
    protected $fillable = [
        'name',
        'tag_id',
        'price',
        'connection_price',
        'connection_type',
        'speed',
        'active',
    ];

    protected $casts = ['connection_type' => PropertyType::class];

    public function contract(): HasOne
    {
        return $this->hasOne(Contract::class);
    }

    public function tag(): BelongsTo
    {
        return $this->belongsTo(Tag::class);
    }

    public function activeText(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->attributes['active'] ? 'Активный' : 'Неактивный'
        );
    }

    public function statusTextColor(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->attributes['active'] ? '#065f46' : '#7f1d1d'
        );
    }

    public function statusBackgroundColor(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->attributes['active'] ? '#d1fae5' : '#fee2e2'
        );
    }

    protected function formattedSpeed(): Attribute
    {
        return Attribute::make(
            get: fn() => isset($this->attributes['speed']) ? "До {$this->attributes['speed']} Мбит/c" : "Не указано"
        );
    }

    protected function formattedPrice(): Attribute
    {
        return Attribute::make(
            get: fn() => isset($this->attributes['price']) ? "$this->price руб./месяц" : "Не указано"
        );
    }

}

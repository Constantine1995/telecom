<?php

namespace App\Models;

use App\Enums\PropertyType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'region_id',
        'city_id',
        'property_type',
        'street',
        'house_number',
        'apartment_number',
    ];

    protected $casts = [
        'property_type' => PropertyType::class,
    ];

    public function connectRequest(): HasMany
    {
        return $this->hasMany(ConnectRequest::class);
    }

    public function passport() : HasOne
    {
        return $this->hasOne(Passport::class);
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function getFullAddress(): string
    {
        // Формируем базовую строку адреса с регионом, городом, улицей и номером дома
        $address = "обл. {$this->region->name}, г. {$this->city->name}, ул. {$this->street}, д. {$this->house_number}";

        // Проверяем, является ли тип недвижимости квартирой и указан ли номер квартиры
        if ($this->property_type->value === PropertyType::APARTMENT->value && $this->apartment_number) {
            // Если да, добавляем номер квартиры к адресу
            $address .= ", кв. {$this->apartment_number}";
        }

        return $address;
    }

}

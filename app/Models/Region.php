<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Region extends Model
{
    use HasFactory;

    protected $fillable = [
        'city_id',
        'name',
    ];

    public function addresses() : HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function cities() : HasMany
    {
        return $this->hasMany(City::class);
    }

}

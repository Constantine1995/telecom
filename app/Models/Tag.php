<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Tag extends Model
{
    protected $fillable = [
        'name',
        'bg-color',
        'text-color',
    ];

    public function tariff() : HasOne
    {
        return $this->hasOne(Tariff::class);
    }
}

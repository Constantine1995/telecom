<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{
    protected $fillable = ['user_id', 'amount'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected function formattedAmount(): Attribute
    {
        return Attribute::make(
            get: fn() => number_format($this->amount, 2, '.', ' ') . ' ₽'
        );
    }

}

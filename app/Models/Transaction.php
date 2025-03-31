<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use App\Enums\TransactionType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Transaction extends Model
{
    protected $fillable = ['user_id', 'contract_id', 'payment_id', 'amount', 'payment_status', 'transaction_type'];

    protected $casts = [
        'payment_status' => PaymentStatus::class,
        'transaction_type' => TransactionType::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    protected function formattedCreateDate(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->created_at ? Carbon::parse($this->created_at)->format('d-m-y (H:i)') : null
        );
    }

}

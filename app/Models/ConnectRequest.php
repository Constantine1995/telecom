<?php

namespace App\Models;

use App\Enums\ConnectRequestStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ConnectRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'address_id',
        'connect_request_status_type',
        'name',
        'phone',
    ];

    protected $casts = [
        'connect_request_status_type' => ConnectRequestStatus::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function contract(): HasOne
    {
        return $this->hasOne(Contract::class);
    }

    protected function phone(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => preg_match('/^7\d{10}$/', $value)
                ? '+7 (' . substr($value, 1, 3) . ') ' . substr($value, 4, 3) . '-' . substr($value, 7, 2) . '-' . substr($value, 9)
                : $value,
            set: fn (string $value) => preg_replace('/\D/', '', $value)
        );
    }

}

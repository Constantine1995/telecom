<?php

namespace App\Models;

use App\Enums\DeviceStatusType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

class Device extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_status',
        'name',
        'ip_address',
        'mac_address',
        'date_connection',
    ];

    protected $casts = [
        'date_connection' => 'date',
        'device_status' => DeviceStatusType::class,
    ];

    public function contract() : hasOne
    {
        return $this->hasOne(Contract::class);
    }

    protected function formattedDateConnection(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->date_connection ? Carbon::parse($this->date_connection)->format('d-m-Y') : null
        );
    }
}

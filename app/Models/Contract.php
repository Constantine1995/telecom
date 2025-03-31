<?php

namespace App\Models;

use App\Enums\ContractStatus;
use App\Enums\MonthlyPaymentStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class Contract extends Model
{
    protected $fillable = [
        'user_id',
        'tariff_id',
        'contract_status',
        'payment_status',
        'address_id',
        'device_id',
        'connect_request_id',
        'contract_number',
        'date_connection',
        'date_disconnection',
    ];

    protected $casts = [
        'date_connection' => 'date',
        'date_disconnection' => 'date',
        'contract_status' => ContractStatus::class,
        'payment_status' => MonthlyPaymentStatus::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function tariff(): BelongsTo
    {
        return $this->belongsTo(Tariff::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    public function connectRequest(): BelongsTo
    {
        return $this->belongsTo(ConnectRequest::class);
    }

    public static function generateContractNumber()
    {
        $randomNumber = rand(10000, 99999);

        // Проверка на уникальность номера
        while (self::where('contract_number', $randomNumber)->exists()) {
            $randomNumber = rand(10000, 99999);
        }

        return $randomNumber;
    }

    public function getAvailableDateDisconnection(): bool
    {
        return in_array($this->contract_status->value, [ContractStatus::ACTIVE->value, ContractStatus::PENDING_ACTIVATION->value]);
    }

    public function getContractStatus(): bool
    {
        return in_array($this->contract_status->value, [ContractStatus::ACTIVE->value]);
    }

    public function getContractStatusColor(): string
    {
        return match ($this->contract_status) {
            ContractStatus::TERMINATED,
            ContractStatus::SUSPENDED,
            ContractStatus::EXPIRED,
            ContractStatus::CANCELLED => '#7f1d1d',
            ContractStatus::PENDING_ACTIVATION => '#d4af37',
            ContractStatus::ACTIVE => '#0b9c73',
        };
    }

    protected function formattedDateConnection(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->date_connection ? Carbon::parse($this->date_connection)->format('d-m-Y') : null
        );
    }

    protected function formattedDateDisconnection(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->date_disconnection ? Carbon::parse($this->date_disconnection)->format('d-m-Y') : null
        );
    }
}

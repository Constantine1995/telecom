<?php

use App\Enums\ContractStatus;
use App\Enums\MonthlyPaymentStatus;
use App\Models\Address;
use App\Models\ConnectRequest;
use App\Models\Device;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Tariff;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Tariff::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Address::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Device::class)->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(ConnectRequest::class)->constrained()->cascadeOnDelete();
            $table->enum('contract_status', [ContractStatus::values()])->default(ContractStatus::PENDING_ACTIVATION->value);
            $table->enum('payment_status', [MonthlyPaymentStatus::values()])->default(MonthlyPaymentStatus::UNPAID->value);
            $table->string('contract_number')->unique();
            $table->datetime('date_connection');
            $table->datetime('date_disconnection')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};

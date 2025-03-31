<?php

use App\Enums\PaymentStatus;
use App\Enums\TransactionType;
use App\Models\Contract;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Contract::class)->nullable()->constrained()->cascadeOnDelete();
            $table->string('payment_id')->nullable()->unique(); // ID платежа из YooKassa
            $table->decimal('amount', 10, 2);
            $table->enum('payment_status', [PaymentStatus::values()])->default(PaymentStatus::PENDING->value);
            $table->enum('transaction_type', [TransactionType::values()])->default(TransactionType::DEPOSIT->value);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};

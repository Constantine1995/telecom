<?php

namespace App\Jobs;

use App\Enums\MonthlyPaymentStatus;
use App\Models\Contract;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class UpdateContractPaymentStatus implements ShouldQueue
{
    use Queueable;

    public $tries = 3;

    protected $contractId;

    /**
     * Create a new job instance.
     */
    public function __construct($contractId)
    {
        $this->contractId = $contractId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $contract = Contract::findOrFail($this->contractId);
        $contract->update(['payment_status' => MonthlyPaymentStatus::UNPAID->value]);

        Log::info("Updated payment status for contract ID {$this->contractId} to UNPAID");
    }
}

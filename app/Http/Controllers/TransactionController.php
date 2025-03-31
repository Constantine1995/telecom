<?php

namespace App\Http\Controllers;


use App\Services\PaymentService;
use Illuminate\View\View;

class TransactionController extends Controller
{

    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function __invoke(): View
    {
        $contracts = auth()->user()->contracts;

        return view('transaction.index', compact('contracts'));
    }
}

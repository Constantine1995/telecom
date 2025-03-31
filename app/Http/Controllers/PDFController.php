<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class PDFController extends Controller
{
    public function __invoke($id): Response
    {
        $user = auth()->user();
        $contract = Contract::findOrFail($id);
        $passport = $user->passport()->first();

        // Загружаем PDF View
        $pdf = PDF::loadView('pdf.template', compact('contract', 'passport'));

        return $pdf->download("Договор_$contract->contract_number.pdf");
    }
}

<?php

use App\Http\Controllers\BaseInfoController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\PassportController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TariffController;
use Illuminate\Support\Facades\Route;

Route::get('/', MainController::class)->name('main');
Route::get('tariffs', TariffController::class)->name('tariff.index');
Route::get('contact', ContactController::class)->name('contact.index');

Route::middleware('auth')->group(function () {

    // Profile
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');

    // Base info
    Route::get('base-info', BaseInfoController::class)->name('base-info.index');

    // Passport
    Route::resource('passport', PassportController::class);

    // PDF
    Route::get('pdf/{id}', PDFController::class)->name('pdf.index');

    // Payments
    Route::resource('payments', PaymentController::class)->only(['index', 'store']);
    Route::match(['GET', 'POST'], 'payments/callback', [PaymentController::class, 'callback'])->name('payments.callback');
    Route::post('withdraw/{contract}', [PaymentController::class, 'withdraw'])->name('withdraw');

    // Transactions
    Route::get('transaction', TransactionController::class)->name('transaction.index');
});

require __DIR__ . '/auth.php';

<?php

use App\Http\Controllers\Api\BalanceController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\CreditController;
use App\Http\Controllers\Api\DebitController;
use App\Http\Controllers\Api\TransactionStatusController;
use Illuminate\Support\Facades\Route;

Route::middleware(['partner.api', 'partner.ip', 'throttle:60,1'])->group(function (): void {
    Route::post('/checkout', [CheckoutController::class, 'store'])
        ->name('api.checkout.store');
    
    Route::post('/wallet/debit', [DebitController::class, 'store'])
        ->name('api.wallet.debit');
    
    Route::post('/wallet/credit', [CreditController::class, 'store'])
        ->name('api.wallet.credit');
    
    Route::get('/transaction/{reference}', [TransactionStatusController::class, 'show'])
        ->name('api.transaction.show');
    
    Route::get('/wallet/balance/{userId}', [BalanceController::class, 'show'])
        ->name('api.wallet.balance.show');
});



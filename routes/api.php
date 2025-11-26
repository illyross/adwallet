<?php

use App\Http\Controllers\Api\CheckoutController;
use Illuminate\Support\Facades\Route;

Route::middleware(['partner.api', 'partner.ip', 'throttle:60,1'])->group(function (): void {
    Route::post('/checkout', [CheckoutController::class, 'store'])
        ->name('api.checkout.store');
});



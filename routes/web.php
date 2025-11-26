<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SsoController;
use App\Http\Controllers\StripeCompleteController;
use App\Http\Controllers\WalletDashboardController;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ChangePasswordController;
use App\Http\Controllers\Admin\WalletLogController;

Route::view('/', 'home')->name('home');

// Authentication routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::view('/imprint', 'legal.imprint')->name('legal.imprint');
Route::view('/terms', 'legal.terms')->name('legal.terms');
Route::view('/privacy', 'legal.privacy')->name('legal.privacy');
Route::view('/acceptable-use', 'legal.acceptable-use')->name('legal.acceptable-use');

Route::middleware('auth')
    ->group(function (): void {
        Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

        Route::middleware('admin')
            ->prefix('admin')
            ->group(function (): void {
                Route::prefix('wallet')
                    ->group(function (): void {
                        Route::get('/sso', [WalletLogController::class, 'sso'])
                            ->name('admin.wallet.sso');

                        Route::get('/webhooks', [WalletLogController::class, 'webhooks'])
                            ->name('admin.wallet.webhooks');
                    });

                Route::get('/password/change', [ChangePasswordController::class, 'showChangePasswordForm'])
                    ->name('admin.password.change');

                Route::post('/password/change', [ChangePasswordController::class, 'updatePassword'])
                    ->name('admin.password.update');
            });
    });

Route::post('/sso/{partner}', SsoController::class)
    ->middleware(['throttle:30,1', 'partner.ip'])
    ->name('sso.handle');

Route::get('/wallet', WalletDashboardController::class)
    ->middleware(['auth', 'not.admin'])
    ->name('wallet.dashboard');

Route::get('/checkout/{reference}', function (string $reference) {
    $transaction = WalletTransaction::query()
        ->where('reference', $reference)
        ->firstOrFail();

    return view('checkout', [
        'transaction' => $transaction,
    ]);
})->name('checkout.show');

Route::get('/stripe/complete/{reference}', StripeCompleteController::class)
    ->name('stripe.complete');

// Stripe webhook endpoint (must be outside CSRF protection)
Route::post('/stripe/webhook', \App\Http\Controllers\StripeWebhookController::class)
    ->middleware('throttle:120,1')
    ->name('stripe.webhook');

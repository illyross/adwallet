<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WalletAccount;
use App\Models\WalletTransaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'reference' => ['required', 'string', 'max:255'],
            'user.id' => ['required', 'integer'],
            'user.email' => ['nullable', 'email'],
            'package.credits' => ['required', 'integer', 'min:1'],
            'package.amount' => ['required', 'numeric', 'min:0.5'],
            'package.currency' => ['required', 'string', 'max:3'],
            'purchase.id' => ['required', 'integer'],
            'callback_url' => ['required', 'url', function ($attribute, $value, $fail) {
                if ($this->isUnreachableUrl($value)) {
                    $fail('The callback URL must be publicly accessible. Localhost and test domains are not allowed in production.');
                }
            }],
            'success_url' => ['required', 'url'],
            'cancel_url' => ['required', 'url'],
        ]);

        $partner = $request->attributes->get('partner');
        
        abort_unless($partner, 500, 'Partner not identified.');
        
        $partnerUserId = (int) data_get($validated, 'user.id');

        $account = WalletAccount::query()->updateOrCreate(
            [
                'partner' => $partner,
                'partner_user_id' => $partnerUserId,
            ],
            [
                'email' => data_get($validated, 'user.email'),
                'last_activity_at' => now(),
            ],
        );

        $transaction = $account->transactions()->create([
            'reference' => $validated['reference'],
            'partner_purchase_id' => data_get($validated, 'purchase.id'),
            'credits' => (int) data_get($validated, 'package.credits'),
            'amount' => (float) data_get($validated, 'package.amount'),
            'currency' => strtoupper((string) data_get($validated, 'package.currency', 'CHF')),
            'type' => WalletTransaction::TYPE_CREDIT,
            'status' => WalletTransaction::STATUS_PENDING,
            'payload' => $request->all(),
            'callback_url' => $validated['callback_url'],
            'success_url' => $validated['success_url'],
            'cancel_url' => $validated['cancel_url'],
        ]);

        $checkoutUrl = route('checkout.show', ['reference' => $transaction->reference]);

        $stripeSecret = config('services.stripe.secret');

        if ($stripeSecret) {
            // Lazily create a Stripe Checkout Session that will redirect back to
            // adwallet after payment. The redirect to the partner happens after
            // we confirm the transaction via webhook / completion handler.
            $session = $this->createStripeCheckoutSession(
                secret: $stripeSecret,
                reference: $transaction->reference,
                amount: $transaction->amount,
                currency: $transaction->currency,
            );

            $transaction->forceFill([
                'stripe_session_id' => $session->id,
            ])->save();

            $checkoutUrl = $session->url;
        }

        return response()->json([
            'checkout_url' => $checkoutUrl,
            'reference' => $transaction->reference,
            'transaction_id' => $transaction->id,
        ]);
    }

    protected function createStripeCheckoutSession(
        string $secret,
        string $reference,
        float $amount,
        string $currency,
    ): \Stripe\Checkout\Session {
        $client = new \Stripe\StripeClient($secret);

        $unitAmount = (int) round($amount * 100);

        return $client->checkout->sessions->create([
            'mode' => 'payment',
            'success_url' => route('stripe.complete', ['reference' => $reference]) . '?status=success',
            'cancel_url' => route('stripe.complete', ['reference' => $reference]) . '?status=cancel',
            'metadata' => [
                'reference' => $reference,
                'source' => 'adwallet',
            ],
            'line_items' => [
                [
                    'quantity' => 1,
                    'price_data' => [
                        'currency' => strtolower($currency),
                        'unit_amount' => $unitAmount,
                        'product_data' => [
                            'name' => 'Advertising credits',
                            'description' => 'Wallet top-up (' . $reference . ')',
                        ],
                    ],
                ],
            ],
        ]);
    }

    /**
     * Check if a URL is unreachable from production (localhost, test domains, etc.)
     */
    protected function isUnreachableUrl(string $url): bool
    {
        // Allow localhost/test domains only in local/testing environments
        if (app()->environment(['local', 'testing'])) {
            return false;
        }

        $host = parse_url($url, PHP_URL_HOST);
        
        if (! $host) {
            return true;
        }

        // Check for localhost variants
        $localHosts = ['localhost', '127.0.0.1', '::1', '0.0.0.0'];
        if (in_array(strtolower($host), $localHosts)) {
            return true;
        }

        // Check for .test, .local, .localhost TLDs
        if (preg_match('/\.(test|local|localhost)(:\d+)?$/i', $host)) {
            return true;
        }

        // Check for common local development patterns
        if (preg_match('/\.(test|local|localhost)$/i', $host)) {
            return true;
        }

        return false;
    }
}



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
            'user.email' => ['required', 'string', 'max:255', function ($attribute, $value, $fail) {
                if (! $this->isValidEmail($value)) {
                    $fail('The email must be a valid email address or a placeholder email (phone-{digits}@{domain}.local).');
                }
            }],
            'user.phone' => ['nullable', 'string', 'max:20', 'regex:/^\+[1-9]\d{1,14}$/'],
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

        $userEmail = data_get($validated, 'user.email');
        $userPhone = data_get($validated, 'user.phone');
        
        $account = WalletAccount::query()->updateOrCreate(
            [
                'partner' => $partner,
                'partner_user_id' => $partnerUserId,
            ],
            [
                'email' => $userEmail,
                'phone' => $userPhone,
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
                customerEmail: $this->formatStripeEmail($account->id),
                customerPhone: $userPhone ? $this->formatStripePhone($userPhone) : null,
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
        string $customerEmail,
        ?string $customerPhone = null,
    ): \Stripe\Checkout\Session {
        $client = new \Stripe\StripeClient($secret);

        $unitAmount = (int) round($amount * 100);

        $sessionParams = [
            'mode' => 'payment',
            'customer_email' => $customerEmail,
            'phone_number_collection' => [
                'enabled' => true, // Allow phone collection during checkout
            ],
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
        ];

        // Add customer details if phone is provided
        if ($customerPhone) {
            $sessionParams['customer_details'] = [
                'phone' => $customerPhone,
            ];
        }

        return $client->checkout->sessions->create($sessionParams);
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

    /**
     * Check if an email is valid (either real email or placeholder email)
     */
    protected function isValidEmail(string $email): bool
    {
        // Check if it's a placeholder email (phone-{digits}@{domain}.local or user-{id}@{domain}.local)
        if (preg_match('/^(phone-|user-)\d+@.+\.local$/', $email)) {
            return true;
        }
        
        // Check if it's a valid email
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Check if an email is a placeholder email
     */
    protected function isPlaceholderEmail(string $email): bool
    {
        return preg_match('/^(phone-|user-)\d+@.+\.local$/', $email) === 1;
    }

    /**
     * Format email for Stripe as wallet{account_id}@escortxxx.ch
     */
    protected function formatStripeEmail(int $accountId): string
    {
        return "wallet{$accountId}@escortxxx.ch";
    }

    /**
     * Format phone number for Stripe from +41799450139 to 079 945 01 39
     */
    protected function formatStripePhone(string $phone): string
    {
        // Remove the + sign and country code
        // For Swiss numbers: +41XXXXXXXXX -> 0XXXXXXXXX
        if (preg_match('/^\+41(\d{9})$/', $phone, $matches)) {
            $digits = $matches[1];
            // Format as: 0XX XXX XX XX
            return sprintf(
                '0%s %s %s %s',
                substr($digits, 0, 2),
                substr($digits, 2, 3),
                substr($digits, 5, 2),
                substr($digits, 7, 2)
            );
        }

        // If it doesn't match Swiss format, try to format as-is
        // Remove + and format remaining digits
        $digits = preg_replace('/[^0-9]/', '', $phone);
        
        // If it starts with 41, remove it and add 0
        if (str_starts_with($digits, '41') && strlen($digits) === 11) {
            $digits = '0' . substr($digits, 2);
        }

        // Format as: 0XX XXX XX XX if 9 digits after 0
        if (preg_match('/^0(\d{9})$/', $digits, $matches)) {
            $digits = $matches[1];
            return sprintf(
                '0%s %s %s %s',
                substr($digits, 0, 2),
                substr($digits, 2, 3),
                substr($digits, 5, 2),
                substr($digits, 7, 2)
            );
        }

        // Fallback: return as-is if we can't format it
        return $phone;
    }
}



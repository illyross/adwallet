<?php

namespace App\Http\Controllers;

use App\Models\WalletTransaction;
use App\Models\WalletWebhookEvent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class StripeCompleteController extends Controller
{
    public function __invoke(Request $request, string $reference): RedirectResponse
    {
        $transaction = WalletTransaction::query()
            ->where('reference', $reference)
            ->firstOrFail();
        $currentStatus = $transaction->status;

        $status = $request->query('status');

        // If already completed, do not mutate balance again; just redirect based on existing status.
        if ($currentStatus === WalletTransaction::STATUS_COMPLETED && $transaction->completed_at) {
            $redirectUrl = $transaction->success_url;

            return redirect()->away($redirectUrl);
        }

        $stripeSecret = config('services.stripe.secret');
        $paymentIntentId = null;

        if ($stripeSecret && $transaction->stripe_session_id) {
            try {
                $client = new \Stripe\StripeClient($stripeSecret);
                $session = $client->checkout->sessions->retrieve($transaction->stripe_session_id, []);

                $paymentIntentId = $session->payment_intent ?? null;
                $paid = $session->payment_status === 'paid';

                $status = $paid ? 'success' : 'failed';
            } catch (\Throwable $exception) {
                Log::warning('Unable to verify Stripe session.', [
                    'reference' => $reference,
                    'error' => $exception->getMessage(),
                ]);
            }
        }

        DB::transaction(function () use ($transaction, $status, $paymentIntentId): void {
            $transaction->refresh();

            // Only credit wallet balance on first successful completion.
            $wasCompleted = $transaction->status === WalletTransaction::STATUS_COMPLETED;

            $newStatus = $status === 'success'
                ? WalletTransaction::STATUS_COMPLETED
                : WalletTransaction::STATUS_FAILED;

            $account = $transaction->account()->lockForUpdate()->first();

            if ($newStatus === WalletTransaction::STATUS_COMPLETED && ! $wasCompleted) {
                // Handle credit (top-up) and debit (spending) transactions
                if ($transaction->isCredit()) {
                    $newBalance = $account->balance + $transaction->credits;
                } elseif ($transaction->isDebit()) {
                    $newBalance = $account->balance - $transaction->credits;
                } else {
                    // Default to credit for backward compatibility
                    $newBalance = $account->balance + $transaction->credits;
                }
                
                $account->forceFill(['balance' => $newBalance, 'last_activity_at' => now()])->save();
                $transaction->balance_after = $newBalance;
            }

            $transaction->fill([
                'status' => $newStatus,
                'stripe_payment_intent' => $paymentIntentId,
                'processed_at' => $transaction->processed_at ?: now(),
                'completed_at' => $newStatus === WalletTransaction::STATUS_COMPLETED
                    ? ($transaction->completed_at ?: now())
                    : $transaction->completed_at,
            ])->save();
        });

        $this->sendPartnerWebhook($transaction->fresh(), $status === 'success');

        $redirectUrl = $status === 'success'
            ? $transaction->success_url
            : $transaction->cancel_url;

        return redirect()->away($redirectUrl);
    }

    protected function sendPartnerWebhook(WalletTransaction $transaction, bool $success): void
    {
        $account = $transaction->account;
        $partnerKey = $account?->partner;

        if (! $partnerKey) {
            return;
        }

        $config = config("services.partners.{$partnerKey}", []);
        $secret = (string) ($config['webhook_secret'] ?? '');

        if ($secret === '') {
            return;
        }

        $event = $success ? 'wallet.topup.completed' : 'wallet.topup.failed';

        $payload = [
            'event' => $event,
            'transaction_id' => $transaction->id,
            'user_id' => $account->partner_user_id,
            'credits_purchased' => $transaction->credits,
            'currency' => $transaction->currency,
            'amount' => (string) $transaction->amount,
            'stripe_payment_intent' => $transaction->stripe_payment_intent,
            'processed_at' => optional($transaction->processed_at)->toIso8601String(),
            'metadata' => [
                'source' => 'adwallet',
            ],
        ];

        $signature = hash_hmac('sha256', json_encode($payload, JSON_UNESCAPED_SLASHES), $secret);

        $payload['signature'] = $signature;

        // Check if URL is unreachable before attempting
        if ($this->isUnreachableUrl($transaction->callback_url)) {
            Log::warning('Partner webhook skipped - unreachable URL (localhost/test domain).', [
                'partner' => $partnerKey,
                'transaction_id' => $transaction->id,
                'url' => $transaction->callback_url,
            ]);

            WalletWebhookEvent::create([
                'partner' => $partnerKey,
                'wallet_transaction_id' => $transaction->id,
                'event' => $event,
                'url' => $transaction->callback_url,
                'success' => false,
                'error' => 'Unreachable URL (localhost/test domain) - webhook skipped',
                'payload' => $payload,
            ]);

            return;
        }

        try {
            $response = Http::asJson()
                ->timeout(5)
                ->post($transaction->callback_url, $payload);

            WalletWebhookEvent::create([
                'partner' => $partnerKey,
                'wallet_transaction_id' => $transaction->id,
                'event' => $event,
                'url' => $transaction->callback_url,
                'success' => $response->successful(),
                'status_code' => $response->status(),
                'payload' => $payload,
            ]);
        } catch (\Throwable $exception) {
            Log::warning('Partner webhook failed.', [
                'partner' => $partnerKey,
                'transaction_id' => $transaction->id,
                'error' => $exception->getMessage(),
                'url' => $transaction->callback_url,
            ]);

            WalletWebhookEvent::create([
                'partner' => $partnerKey,
                'wallet_transaction_id' => $transaction->id,
                'event' => $event,
                'url' => $transaction->callback_url,
                'success' => false,
                'error' => $exception->getMessage(),
                'payload' => $payload,
            ]);
        }
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



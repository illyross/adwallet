<?php

namespace App\Http\Controllers;

use App\Models\WalletTransaction;
use App\Models\WalletWebhookEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class StripeWebhookController extends Controller
{
    /**
     * Handle Stripe webhook events.
     */
    public function __invoke(Request $request): Response
    {
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');
        $webhookSecret = config('services.stripe.webhook_secret');

        if (! $webhookSecret) {
            Log::warning('Stripe webhook secret not configured.');
            return response('Webhook secret not configured', 500);
        }

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $signature,
                $webhookSecret
            );
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::warning('Stripe webhook signature verification failed.', [
                'error' => $e->getMessage(),
            ]);
            return response('Invalid signature', 400);
        } catch (\Exception $e) {
            Log::error('Stripe webhook processing error.', [
                'error' => $e->getMessage(),
            ]);
            return response('Webhook error', 500);
        }

        // Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                $this->handleCheckoutSessionCompleted($event->data->object);
                break;

            case 'checkout.session.async_payment_succeeded':
                // For async payment methods (e.g., SEPA, bank transfers)
                $this->handleCheckoutSessionCompleted($event->data->object);
                break;

            case 'checkout.session.async_payment_failed':
                // For async payment methods that failed
                $this->handleCheckoutSessionFailed($event->data->object);
                break;

            case 'payment_intent.succeeded':
                $this->handlePaymentIntentSucceeded($event->data->object);
                break;

            case 'payment_intent.payment_failed':
                $this->handlePaymentIntentFailed($event->data->object);
                break;

            default:
                Log::info('Unhandled Stripe webhook event.', [
                    'type' => $event->type,
                ]);
        }

        return response('OK', 200);
    }

    protected function handleCheckoutSessionCompleted($session): void
    {
        if ($session->payment_status !== 'paid') {
            return;
        }

        $sessionId = $session->id;
        $paymentIntentId = $session->payment_intent;

        $transaction = WalletTransaction::query()
            ->where('stripe_session_id', $sessionId)
            ->first();

        if (! $transaction) {
            Log::warning('Stripe checkout session completed but transaction not found.', [
                'session_id' => $sessionId,
            ]);
            return;
        }

        $this->processTransaction($transaction, $paymentIntentId, true);
    }

    protected function handlePaymentIntentSucceeded($paymentIntent): void
    {
        $paymentIntentId = $paymentIntent->id;

        $transaction = WalletTransaction::query()
            ->where('stripe_payment_intent', $paymentIntentId)
            ->orWhere(function ($query) use ($paymentIntentId) {
                $query->whereNull('stripe_payment_intent')
                    ->whereNotNull('stripe_session_id');
            })
            ->first();

        if (! $transaction) {
            Log::warning('Stripe payment intent succeeded but transaction not found.', [
                'payment_intent_id' => $paymentIntentId,
            ]);
            return;
        }

        $this->processTransaction($transaction, $paymentIntentId, true);
    }

    protected function handlePaymentIntentFailed($paymentIntent): void
    {
        $paymentIntentId = $paymentIntent->id;

        $transaction = WalletTransaction::query()
            ->where('stripe_payment_intent', $paymentIntentId)
            ->first();

        if (! $transaction) {
            return;
        }

        $this->processTransaction($transaction, $paymentIntentId, false);
    }

    protected function handleCheckoutSessionFailed($session): void
    {
        $sessionId = $session->id;

        $transaction = WalletTransaction::query()
            ->where('stripe_session_id', $sessionId)
            ->first();

        if (! $transaction) {
            return;
        }

        $this->processTransaction($transaction, null, false);
    }

    protected function processTransaction(WalletTransaction $transaction, ?string $paymentIntentId, bool $success): void
    {
        DB::transaction(function () use ($transaction, $paymentIntentId, $success): void {
            $transaction->refresh();

            // Prevent double processing
            if ($transaction->status === WalletTransaction::STATUS_COMPLETED && $success) {
                return;
            }

            $wasCompleted = $transaction->status === WalletTransaction::STATUS_COMPLETED;

            $newStatus = $success
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
                
                $account->forceFill([
                    'balance' => $newBalance,
                    'last_activity_at' => now(),
                ])->save();
                $transaction->balance_after = $newBalance;
            }

            $transaction->fill([
                'status' => $newStatus,
                'stripe_payment_intent' => $paymentIntentId ?? $transaction->stripe_payment_intent,
                'processed_at' => $transaction->processed_at ?: now(),
                'completed_at' => $newStatus === WalletTransaction::STATUS_COMPLETED
                    ? ($transaction->completed_at ?: now())
                    : $transaction->completed_at,
            ])->save();
        });

        // Send webhook to partner
        $this->sendPartnerWebhook($transaction->fresh(), $success);
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
            $response = \Illuminate\Support\Facades\Http::asJson()
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


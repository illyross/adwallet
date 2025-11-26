<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WalletAccount;
use App\Models\WalletTransaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DebitController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'user_id' => ['required', 'integer'],
                'credits' => ['required', 'integer', 'min:1'],
                'currency' => ['required', 'string', 'max:3'],
                'amount' => ['required', 'string'],
                'reason' => ['required', 'string', 'max:500'],
                'transaction_id' => ['required', 'string', 'max:255'],
                'metadata' => ['nullable', 'array'],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => 'invalid_request',
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 400);
        }

        $partner = $request->attributes->get('partner');
        
        abort_unless($partner, 500, 'Partner not identified.');

        $partnerUserId = (int) $validated['user_id'];
        $credits = (int) $validated['credits'];
        $amount = (float) $validated['amount'];
        $currency = strtoupper((string) $validated['currency']);
        $transactionId = $validated['transaction_id'];
        
        // Validate currency
        if ($currency !== 'CHF') {
            return response()->json([
                'success' => false,
                'error' => 'invalid_request',
                'message' => 'Currency must be CHF',
                'errors' => [
                    'currency' => ['The currency field must be CHF.'],
                ],
            ], 400);
        }

        // Find or create wallet account
        $account = WalletAccount::query()
            ->where('partner', $partner)
            ->where('partner_user_id', $partnerUserId)
            ->first();

        if (! $account) {
            return response()->json([
                'success' => false,
                'error' => 'user_not_found',
                'message' => "User with ID {$partnerUserId} not found in adwallet system",
            ], 404);
        }

        // Check for idempotency - if transaction_id already exists globally
        $existingTransaction = WalletTransaction::query()
            ->where('transaction_id', $transactionId)
            ->first();

        if ($existingTransaction) {
            // If it's for a different account, return 409 conflict
            if ($existingTransaction->wallet_account_id !== $account->id) {
                return response()->json([
                    'success' => false,
                    'error' => 'duplicate_transaction',
                    'message' => "Transaction with ID {$transactionId} already processed",
                    'transaction_id' => $transactionId,
                    'processed_at' => $existingTransaction->processed_at?->toIso8601String() 
                        ?? $existingTransaction->created_at->toIso8601String(),
                ], 409);
            }
            
            // Return existing transaction result (idempotency - same account)
            return response()->json([
                'success' => true,
                'transaction_id' => $existingTransaction->transaction_id,
                'balance_after' => $existingTransaction->balance_after ?? $account->balance,
                'deducted_at' => $existingTransaction->completed_at?->toIso8601String() 
                    ?? $existingTransaction->processed_at?->toIso8601String(),
            ], 200);
        }

        // Check sufficient balance
        if ($account->balance < $credits) {
            return response()->json([
                'success' => false,
                'error' => 'insufficient_balance',
                'message' => "User does not have enough credits. Available: {$account->balance}, Required: {$credits}",
                'available_balance' => $account->balance,
                'requested_credits' => $credits,
            ], 400);
        }

        // Process debit transaction atomically
        try {
            $result = DB::transaction(function () use (
                $account,
                $partner,
                $partnerUserId,
                $credits,
                $amount,
                $currency,
                $transactionId,
                $validated
            ) {
                // Lock account for update to prevent race conditions
                $account = WalletAccount::query()
                    ->where('id', $account->id)
                    ->lockForUpdate()
                    ->first();

                // Double-check balance after lock
                if ($account->balance < $credits) {
                    throw new \Exception('Insufficient balance');
                }

                // Calculate new balance
                $newBalance = $account->balance - $credits;

                // Update account balance
                $account->forceFill([
                    'balance' => $newBalance,
                    'last_activity_at' => now(),
                ])->save();

                // Create debit transaction record
                $transaction = $account->transactions()->create([
                    'reference' => $transactionId, // Use transaction_id as reference for debits
                    'transaction_id' => $transactionId,
                    'credits' => $credits,
                    'amount' => $amount,
                    'currency' => $currency,
                    'reason' => $validated['reason'] ?? null,
                    'type' => WalletTransaction::TYPE_DEBIT,
                    'status' => WalletTransaction::STATUS_COMPLETED,
                    'balance_after' => $newBalance,
                    'payload' => $validated,
                    'processed_at' => now(),
                    'completed_at' => now(),
                ]);

                return [
                    'transaction' => $transaction,
                    'balance_after' => $newBalance,
                ];
            });

            return response()->json([
                'success' => true,
                'transaction_id' => $result['transaction']->transaction_id,
                'balance_after' => $result['balance_after'],
                'deducted_at' => $result['transaction']->completed_at->toIso8601String(),
            ], 200);

        } catch (\Exception $e) {
            if ($e->getMessage() === 'Insufficient balance') {
                $freshAccount = $account->fresh();
                return response()->json([
                    'success' => false,
                    'error' => 'insufficient_balance',
                    'message' => "User does not have enough credits. Available: {$freshAccount->balance}, Required: {$credits}",
                    'available_balance' => $freshAccount->balance,
                    'requested_credits' => $credits,
                ], 400);
            }

            Log::error('Debit transaction failed', [
                'partner' => $partner,
                'user_id' => $partnerUserId,
                'transaction_id' => $transactionId,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'transaction_failed',
                'message' => 'An error occurred while processing the transaction',
            ], 500);
        }
    }
}


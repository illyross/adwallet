<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WalletTransaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionStatusController extends Controller
{
    public function show(Request $request, string $reference): JsonResponse
    {
        $partner = $request->attributes->get('partner');
        
        abort_unless($partner, 500, 'Partner not identified.');

        // Find transaction by reference, scoped to the requesting partner
        // Reference can be either the checkout reference or transaction_id
        // We scope by partner through the account relationship for security
        $transaction = WalletTransaction::query()
            ->whereHas('account', function ($query) use ($partner) {
                $query->where('partner', $partner);
            })
            ->where(function ($query) use ($reference) {
                $query->where('reference', $reference)
                    ->orWhere('transaction_id', $reference);
            })
            ->with('account')
            ->first();

        if (! $transaction) {
            return response()->json([
                'error' => 'transaction_not_found',
                'message' => "Transaction with reference '{$reference}' not found",
            ], 404);
        }

        // Map status values
        $status = match ($transaction->status) {
            WalletTransaction::STATUS_COMPLETED => 'completed',
            WalletTransaction::STATUS_FAILED => 'failed',
            WalletTransaction::STATUS_PENDING => 'pending',
            default => 'pending',
        };

        // Build response
        $response = [
            'status' => $status,
            'transaction_id' => $transaction->transaction_id ?? $transaction->reference,
            'reference' => $transaction->reference,
            'user_id' => $transaction->account->partner_user_id,
            'credits' => $transaction->credits,
            'amount' => (string) number_format($transaction->amount, 2, '.', ''),
            'currency' => $transaction->currency,
            'metadata' => [
                'source' => 'adwallet',
                'type' => $transaction->type,
            ],
        ];

        // Add completed_at if transaction is completed
        if ($transaction->completed_at) {
            $response['completed_at'] = $transaction->completed_at->toIso8601String();
        }

        // Add processed_at if available
        if ($transaction->processed_at) {
            $response['processed_at'] = $transaction->processed_at->toIso8601String();
        }

        // Add reason if it's a debit transaction
        if ($transaction->isDebit() && $transaction->reason) {
            $response['reason'] = $transaction->reason;
        }

        // Add balance_after if available
        if ($transaction->balance_after !== null) {
            $response['balance_after'] = $transaction->balance_after;
        }

        return response()->json($response, 200);
    }
}


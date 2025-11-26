<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WalletAccount;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BalanceController extends Controller
{
    public function show(Request $request, int $userId): JsonResponse
    {
        $partner = $request->attributes->get('partner');
        
        abort_unless($partner, 500, 'Partner not identified.');

        $account = WalletAccount::query()
            ->where('partner', $partner)
            ->where('partner_user_id', $userId)
            ->first();

        if (! $account) {
            return response()->json([
                'success' => false,
                'error' => 'user_not_found',
                'message' => "User with ID {$userId} not found in adwallet system",
            ], 404);
        }

        return response()->json([
            'success' => true,
            'user_id' => $userId,
            'balance' => $account->balance,
            'currency' => 'CHF',
            'last_activity_at' => $account->last_activity_at?->toIso8601String(),
        ], 200);
    }
}


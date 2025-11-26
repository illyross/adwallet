<?php

namespace App\Http\Controllers;

use App\Models\WalletAccount;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class WalletDashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        // Regular users need partner session
        $partner = $request->session()->get('wallet.partner');
        $partnerUserId = $request->session()->get('wallet.partner_user_id');

        abort_unless($partner && $partnerUserId, 403);

        $account = WalletAccount::query()
            ->with(['transactions' => fn ($query) => $query->latest()->limit(50)])
            ->where('partner', $partner)
            ->where('partner_user_id', $partnerUserId)
            ->firstOrFail();

        return view('wallet.dashboard', [
            'account' => $account,
            'transactions' => $account->transactions,
        ]);
    }
}



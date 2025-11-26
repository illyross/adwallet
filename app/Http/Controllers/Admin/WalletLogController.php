<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WalletSsoEvent;
use App\Models\WalletWebhookEvent;
use Illuminate\Contracts\View\View;

class WalletLogController extends Controller
{
    public function sso(): View
    {
        $events = WalletSsoEvent::query()
            ->orderByDesc('created_at')
            ->limit(200)
            ->get();

        return view('admin.wallet.sso', [
            'events' => $events,
        ]);
    }

    public function webhooks(): View
    {
        $events = WalletWebhookEvent::query()
            ->orderByDesc('created_at')
            ->limit(200)
            ->get();

        return view('admin.wallet.webhooks', [
            'events' => $events,
        ]);
    }
}



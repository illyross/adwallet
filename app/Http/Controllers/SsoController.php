<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WalletAccount;
use App\Models\WalletSsoEvent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SsoController extends Controller
{
    public function __invoke(Request $request, string $partner): RedirectResponse
    {
        $partnerConfig = config("services.partners.{$partner}");

        abort_unless($partnerConfig, 404);

        $secret = (string) ($partnerConfig['sso_secret'] ?? '');

        abort_unless($secret !== '', 500, 'SSO is not configured for this partner.');

        $data = $request->validate([
            'user_id' => ['required', 'integer'],
            'email' => ['required', 'email'],
            'display_name' => ['nullable', 'string', 'max:255'],
            'role' => ['nullable', 'string', 'max:100'],
            'nonce' => ['required', 'string', 'max:255'],
            'expires_at' => ['required', 'date'],
            'redirect_back_url' => ['required', 'url'],
            'signature' => ['required', 'string'],
        ]);

        if (now()->greaterThan($data['expires_at'])) {
            abort(403, 'SSO token has expired.');
        }

        $signedPayload = implode('|', [
            $partner,
            $data['user_id'],
            $data['email'],
            $data['display_name'] ?? '',
            $data['role'] ?? '',
            $data['nonce'],
            $data['expires_at'],
            $data['redirect_back_url'],
        ]);

        $expectedSignature = hash_hmac('sha256', $signedPayload, $secret);

        if (! hash_equals($expectedSignature, (string) $data['signature'])) {
            abort(403, 'Invalid SSO signature.');
        }

        $account = WalletAccount::query()->updateOrCreate(
            [
                'partner' => $partner,
                'partner_user_id' => $data['user_id'],
            ],
            [
                'email' => $data['email'],
                'display_name' => $data['display_name'] ?? null,
                'role' => $data['role'] ?? null,
                'last_activity_at' => now(),
            ],
        );

        $user = User::query()->firstOrCreate(
            ['email' => $data['email']],
            [
                'name' => $data['display_name'] ?: 'Wallet User',
                'password' => bcrypt(Str::random(40)),
            ],
        );

        WalletSsoEvent::create([
            'partner' => $partner,
            'partner_user_id' => $data['user_id'],
            'email' => $data['email'],
            'display_name' => $data['display_name'] ?? null,
            'role' => $data['role'] ?? null,
            'nonce' => $data['nonce'],
            'expires_at' => $data['expires_at'],
            'redirect_back_url' => $data['redirect_back_url'],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'payload' => $request->all(),
        ]);

        Auth::login($user);

        $request->session()->put('wallet.partner', $partner);
        $request->session()->put('wallet.partner_user_id', $account->partner_user_id);
        $request->session()->put('wallet.redirect_back_url', $data['redirect_back_url']);

        return redirect()->route('wallet.dashboard');
    }
}



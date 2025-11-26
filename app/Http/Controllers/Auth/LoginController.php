<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function showLoginForm(): View|RedirectResponse
    {
        // If user is already authenticated, redirect to appropriate dashboard
        if (Auth::check()) {
            if (Auth::user()->is_admin) {
                return redirect()->route('admin.wallet.sso');
            }
            
            // Check if they have wallet session data
            if (session()->has('wallet.partner') && session()->has('wallet.partner_user_id')) {
                return redirect()->route('wallet.dashboard');
            }
            
            // If authenticated but no wallet session, redirect to home
            return redirect()->route('home');
        }
        
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Redirect admins to admin dashboard, regular users to wallet dashboard
            if (Auth::user()->is_admin) {
                return redirect()->intended(route('admin.wallet.sso'));
            }

            return redirect()->intended(route('wallet.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Die angegebenen Anmeldedaten sind nicht korrekt.',
        ])->onlyInput('email');
    }

    public function logout(Request $request): RedirectResponse
    {
        // Get redirect URL before invalidating session
        $redirectBackUrl = $request->session()->get('wallet.redirect_back_url');
        
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // If user came from partner platform, redirect back there
        if ($redirectBackUrl) {
            return redirect()->away($redirectBackUrl);
        }

        return redirect()->route('home');
    }
}


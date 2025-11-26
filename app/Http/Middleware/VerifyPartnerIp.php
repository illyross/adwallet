<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyPartnerIp
{
    public function handle(Request $request, Closure $next): Response
    {
        $partner = (string) ($request->route('partner') ?? $request->attributes->get('partner'));

        if ($partner === '') {
            return $next($request);
        }

        $config = config("services.partners.{$partner}", []);
        $allowed = $config['allowed_ips'] ?? null;

        if (! $allowed || ! is_array($allowed) || $allowed === []) {
            return $next($request);
        }

        $ip = $request->ip();

        foreach ($allowed as $candidate) {
            if ($candidate === $ip) {
                return $next($request);
            }
        }

        abort(403, 'Partner IP not allowed.');
    }
}



<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyPartnerToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = (string) $request->bearerToken();

        $partners = config('services.partners', []);

        foreach ($partners as $key => $config) {
            $expected = (string) ($config['api_token'] ?? '');

            if ($expected !== '' && hash_equals($expected, $token)) {
                // Optionally expose the resolved partner key for downstream use.
                $request->attributes->set('partner', $key);

                return $next($request);
            }
        }

        abort(401, 'Unauthenticated partner request.');

        return $next($request);
    }
}



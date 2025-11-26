<?php

use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Middleware\EnsureUserIsNotAdmin;
use App\Http\Middleware\SecurityHeaders;
use App\Http\Middleware\VerifyPartnerToken;
use App\Http\Middleware\VerifyPartnerIp;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Global security headers
        $middleware->append(SecurityHeaders::class);

        $middleware->alias([
            'partner.api' => VerifyPartnerToken::class,
            'partner.ip' => VerifyPartnerIp::class,
            'admin' => EnsureUserIsAdmin::class,
            'not.admin' => EnsureUserIsNotAdmin::class,
        ]);

        // Exclude Stripe webhook from CSRF protection
        $middleware->validateCsrfTokens(except: [
            'stripe/webhook',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

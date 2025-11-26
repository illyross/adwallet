<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## adwallet Integration (Partners)

**📖 Vollständige Integrationsdokumentation:** Siehe [INTEGRATION.md](INTEGRATION.md) für detaillierte Anleitung, Code-Beispiele und Best Practices.

This application exposes a small, opinionated surface area for partner marketplaces that want to sell advertising credits via adwallet.

### 1. SSO redirect into adwallet

Partners POST a signed payload to:

`POST https://adwallet.ch/sso/{partner}`

Example JSON body:

```json
{
  "user_id": 12345,
  "email": "user@example.com",
  "display_name": "Bella Noir",
  "role": "escort",
  "nonce": "9c4f56f0-...",
  "expires_at": "2025-11-26T09:15:00Z",
  "redirect_back_url": "https://escort.ch/credits/complete",
  "signature": "base64-hmac"
}
```

The signature is an HMAC of the pipe-joined fields

`{partner}|user_id|email|display_name|role|nonce|expires_at|redirect_back_url`

using the `services.partners.{partner}.sso_secret` key. adwallet validates the token, creates/updates the wallet account and user, logs the SSO event, and starts a session before redirecting the browser to the wallet dashboard.

### 2. Start a checkout from the partner

Partners initiate a credit purchase via:

`POST https://adwallet.ch/api/checkout`

Authenticated with a bearer token matching `services.partners.{partner}.api_token`.

Example JSON body:

```json
{
  "reference": "uuid-from-partner",
  "user": {
    "id": 12345,
    "email": "user@example.com"
  },
  "package": {
    "id": 1,
    "key": "credits_300",
    "name": "300 credits",
    "credits": 300,
    "amount": "300.00",
    "currency": "CHF"
  },
  "purchase": {
    "id": 987
  },
  "callback_url": "https://partner.test/credits/callback/uuid-from-partner",
  "success_url": "https://partner.test/credits/success",
  "cancel_url": "https://partner.test/credits/cancel"
}
```

adwallet validates the payload, links/creates the wallet account, creates a `wallet_transactions` row with `status=pending`, and responds with:

```json
{
  "checkout_url": "https://adwallet.ch/checkout/{reference}",
  "reference": "{reference}",
  "meta": {}
}
```

The partner stores the `reference`/transaction identifier and redirects the user to `checkout_url`.

### 3. Stripe checkout + completion

All Stripe interaction (checkout sessions, payment intents, webhook processing) happens inside adwallet. Success and cancel URLs always point back to adwallet; users never hit partner Stripe endpoints directly.

Once payment is confirmed, adwallet:

1. Credits the user’s wallet balance (1 CHF = 1 credit) idempotently.
2. Emits a webhook to the partner’s `callback_url`.
3. Redirects the browser back to the partner’s `success_url` / `cancel_url` for UX messaging.

### 4. Webhook payload and signature

On completion, adwallet POSTs a JSON webhook to the `callback_url` originally supplied by the partner:

```json
{
  "event": "wallet.topup.completed",
  "transaction_id": "txn_abc123",
  "user_id": 12345,
  "credits_purchased": 250,
  "currency": "CHF",
  "amount": "250.00",
  "stripe_payment_intent": "pi_1P9...",
  "processed_at": "2025-11-26T09:17:42Z",
  "metadata": {
    "source": "adwallet"
  },
  "signature": "base64-hmac"
}
```

The `signature` is an HMAC of the JSON body (without the `signature` field) using the `services.partners.{partner}.webhook_secret` key:

```php
$payload = [/* as above, without signature */];
$secret = config('services.partners.escort.webhook_secret');
$signature = hash_hmac('sha256', json_encode($payload, JSON_UNESCAPED_SLASHES), $secret);
```

Partners must:

- verify the signature before trusting the payload,
- enforce idempotency keyed on `transaction_id`, and
- treat the webhook as the source of truth for balances; the browser redirect is purely for UI.

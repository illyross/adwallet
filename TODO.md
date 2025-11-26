## adwallet.ch Kickoff Brief

### Mission
Build a standalone, Stripe-compliant wallet application that partner marketplaces can redirect users to for purchasing advertising credits. All balances map 1 CHF = 1 credit, there are no subscriptions, and every credit purchase stays isolated from restricted-industry branding.

### Core Features
- Neutral public landing/home page describing the wallet value prop and target industries.
- Signed, short-lived SSO redirect from partners; adwallet auto-auths the incoming user (no second login prompt).
- Wallet dashboard showing balance, history, and credit packages priced 1:1 with CHF, plus Stripe-powered checkout.
- Stripe Payment Intent + webhook handling lives entirely inside adwallet; partners never touch Stripe keys.
- Webhook + redirect callbacks so Escort (and others) can update on-platform balances and UI state after purchase.

### Partner → adwallet Redirect Payload
```json
{
  "user_id": 12345,
  "email": "user@example.com",
  "display_name": "Bella Noir",
  "role": "escort",
  "nonce": "9c4f56f0-...",
  "expires_at": "2025-11-26T09:15:00Z",
  "redirect_back_url": "https://escort.ch/credits/complete",
  "signature": "base64-hmac-or-jwt"
}
```
- `user_id` is the canonical identifier for any partner-side role (visitor, venue, club, escort, etc.).
- `role` is optional but useful for role-aware messaging; omit if not needed.
- Tokens must expire quickly and be single-use to prevent replay.

### Partner Purchase Flow Requirements
1. User clicks “Buy 300 credits” inside the partner product (e.g., `CreditService::initiatePurchase`). The partner calls `POST https://adwallet.ch/api/checkout` with the package data (credits=300, amount=CHF 300), `user_id`, and the signed token above.
2. adwallet endpoint validates the token, creates/uses the wallet session, and returns `{ "checkout_url": "...", "transaction_id": "txn_abc123" }`. The partner stores `transaction_id`, flashes “Redirecting…”, and redirects the browser to `checkout_url`.
3. The user completes payment on Stripe inside adwallet (success/cancel URLs always point back to adwallet).
4. Stripe redirects to `https://adwallet.ch/stripe/complete/{transaction_id}`. adwallet confirms the payment, updates wallet balance, emits the webhook below, then redirects the browser to the stored `redirect_back_url` (e.g. `https://partner-app.example/credits/complete?status=success&txn=txn_abc123&sig=...`).
5. The partner verifies the return signature for UX purposes while the webhook adds the credits (1 CHF = 1 credit). No user ever sees Stripe or adwallet URLs referencing the partner directly.

### adwallet → Partner Webhook Payload
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
- The partner verifies `signature`, enforces idempotency on `transaction_id`, and increments balance (1 credit = 1 CHF).

### Redirect Back After Checkout
`https://partner-app.example/credits/complete?status=success&txn=txn_abc123&sig=...`

Partners use this for UX messaging while the webhook remains the single source of truth.

### Suggested Tech Stack (mirror escort.ch)
- Laravel 11 + Livewire/Volt + Blade + Tailwind + Alpine + Vite (mirrors the escort.test stack to minimize context switching).
- MySQL as primary database (reuse schema conventions where possible) and Redis for queues/sessions/cache.
- Sanctum for API/session auth (matching escort implementation) with room to add Passport if partner tokens expand.
- Same tooling for DX/ops: PHPUnit/Pest tests, Laravel Pint, Sail/Docker (or Forge) deployments with separate sandbox & production environments plus Stripe CLI for local webhook testing.

### Landing & Legal Pages
- Marketing home page that positions adwallet as a universal advertising credit and campaign wallet (no explicit adult wording).
- Public Imprint/legal entity page with contact details, VAT/company numbers.
- Terms of Use, Privacy Policy, and Acceptable Use Policy (covering ad listing rules) accessible without authentication.
- Optional FAQ/pricing page showing example packages and industries served so Stripe reviewers can understand the platform.

### Milestones
1. Scaffold repo, configure environments, add CI.
2. Implement SSO token validation + auto-login session handling.
3. Build landing, login, and wallet dashboard pages.
4. Integrate Stripe checkout + webhooks with strict 1 CHF = 1 credit mapping.
5. Deliver Escort integration package (redirect payload helpers, webhook docs, sample client code).
6. Add admin tools, audit logging, partner API documentation, and monitoring.

### Security & Compliance Notes
- Enforce HTTPS, rotate shared secrets, and log every SSO + webhook event.
- Keep copy and metadata neutral to satisfy Stripe’s acceptable-use policies.
- Store `redirect_back_url` per request to control where users return post-payment.

This document reflects the required adwallet-first checkout loop: partner apps always initiate checkout via adwallet, Stripe always returns to adwallet, and adwallet alone redirects back to the originating partner after confirming the transaction.

Use this brief inside the new repository (README/issue) so the team aligns on scope before coding.


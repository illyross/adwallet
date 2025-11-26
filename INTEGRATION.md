# adwallet.ch Integration Guide

**Version:** 1.0  
**Base URL:** `https://adwallet.ch`  
**Status:** Production Ready

Diese Dokumentation erklärt, wie Sie adwallet.ch als Zahlungsmethode für Werbe-Guthaben in Ihrer Partner-Plattform integrieren.

---

## Inhaltsverzeichnis

1. [Übersicht](#übersicht)
2. [Authentifizierung](#authentifizierung)
3. [SSO-Integration](#sso-integration)
4. [Checkout-API](#checkout-api)
5. [Webhook-Handling](#webhook-handling)
6. [Vollständiger Flow](#vollständiger-flow)
7. [Code-Beispiele](#code-beispiele)
8. [Fehlerbehandlung](#fehlerbehandlung)
9. [Best Practices](#best-practices)

---

## Übersicht

adwallet.ch ist eine neutrale, Stripe-basierte Wallet-Plattform für Werbe-Guthaben. Partner-Plattformen können:

- Benutzer per SSO zu adwallet weiterleiten
- Checkout-Sessions für Guthaben-Käufe initiieren
- Webhooks für Zahlungsbestätigungen empfangen

**Wichtig:** adwallet hostet keine Produktangebote oder Werbeinhalte. Es verwaltet ausschließlich Guthaben und Zahlungen.

### Kernkonzepte

- **1 CHF = 1 Credit:** Alle Guthaben werden 1:1 in CHF abgerechnet
- **Prepaid-System:** Guthaben müssen vor der Verwendung aufgeladen werden
- **Idempotenz:** Alle Transaktionen sind idempotent (mehrfache Verarbeitung führt nicht zu doppelten Gutschriften)
- **Webhook als Quelle der Wahrheit:** Browser-Redirects sind nur für UX; Webhooks sind verbindlich

---

## Authentifizierung

adwallet verwendet drei verschiedene Secrets für verschiedene Zwecke:

### 1. API Token (für Checkout-API)

**Verwendung:** Authentifizierung bei `POST /api/checkout`  
**Format:** Bearer Token im `Authorization` Header  
**Konfiguration:** Wird von adwallet bereitgestellt

```http
Authorization: Bearer YOUR_API_TOKEN
```

### 2. SSO Secret (für SSO-Redirects)

**Verwendung:** Signierung von SSO-Payloads  
**Format:** HMAC-SHA256  
**Konfiguration:** Wird von adwallet bereitgestellt

### 3. Webhook Secret (für Webhook-Verifikation)

**Verwendung:** Verifikation von Webhook-Signaturen  
**Format:** HMAC-SHA256  
**Konfiguration:** Wird von adwallet bereitgestellt

---

## SSO-Integration

### Endpoint

```
POST https://adwallet.ch/sso/{partner}
```

**{partner}** ist Ihr Partner-Key (z.B. `escort`, `yourplatform`)

### Payload-Struktur

```json
{
  "user_id": 12345,
  "email": "user@example.com",
  "display_name": "Max Mustermann",
  "role": "premium_user",
  "nonce": "unique-nonce-uuid",
  "expires_at": "2025-11-26T10:15:00Z",
  "redirect_back_url": "https://yourplatform.com/wallet/complete",
  "signature": "hmac-signature"
}
```

### Felder

| Feld | Typ | Erforderlich | Beschreibung |
|------|-----|--------------|--------------|
| `user_id` | integer | ✅ | Eindeutige Benutzer-ID in Ihrer Plattform |
| `email` | string | ✅ | E-Mail-Adresse des Benutzers |
| `display_name` | string | ❌ | Anzeigename (optional) |
| `role` | string | ❌ | Benutzerrolle (optional, z.B. "premium_user") |
| `nonce` | string | ✅ | Eindeutiger, einmaliger Wert (UUID empfohlen) |
| `expires_at` | datetime | ✅ | Ablaufzeitpunkt (ISO 8601, max. 5 Minuten in Zukunft) |
| `redirect_back_url` | url | ✅ | URL, zu der nach Wallet-Zugriff zurückgeleitet wird |
| `signature` | string | ✅ | HMAC-SHA256 Signatur |

### Signatur-Berechnung

Die Signatur wird wie folgt berechnet:

```php
$payload = [
    $partner,              // z.B. "escort"
    $user_id,              // z.B. 12345
    $email,                // z.B. "user@example.com"
    $display_name ?? '',   // Leerstring falls null
    $role ?? '',           // Leerstring falls null
    $nonce,                // z.B. "9c4f56f0-..."
    $expires_at,           // z.B. "2025-11-26T10:15:00Z"
    $redirect_back_url,    // z.B. "https://yourplatform.com/wallet/complete"
];

$stringToSign = implode('|', $payload);
$signature = hash_hmac('sha256', $stringToSign, $ssoSecret);
```

### Beispiel-Request

```php
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

$partner = 'escort';
$ssoSecret = config('services.adwallet.sso_secret');
$user = Auth::user();

$nonce = Str::uuid()->toString();
$expiresAt = now()->addMinutes(5)->toIso8601String();
$redirectBackUrl = route('wallet.complete');

$payload = [
    $partner,
    $user->id,
    $user->email,
    $user->name ?? '',
    $user->role ?? '',
    $nonce,
    $expiresAt,
    $redirectBackUrl,
];

$signature = hash_hmac('sha256', implode('|', $payload), $ssoSecret);

$response = Http::asJson()->post("https://adwallet.ch/sso/{$partner}", [
    'user_id' => $user->id,
    'email' => $user->email,
    'display_name' => $user->name,
    'role' => $user->role,
    'nonce' => $nonce,
    'expires_at' => $expiresAt,
    'redirect_back_url' => $redirectBackUrl,
    'signature' => $signature,
]);

// Browser wird automatisch zu adwallet weitergeleitet
// Nach Wallet-Zugriff wird zu redirect_back_url weitergeleitet
```

### Antwort

- **Status 200:** Erfolgreich → Browser wird zu adwallet weitergeleitet
- **Status 403:** Token abgelaufen oder ungültige Signatur
- **Status 404:** Partner nicht gefunden
- **Status 500:** SSO nicht konfiguriert

---

## Checkout-API

### Endpoint

```
POST https://adwallet.ch/api/checkout
```

**Authentifizierung:** Bearer Token (API Token)

### Request-Header

```http
Authorization: Bearer YOUR_API_TOKEN
Content-Type: application/json
Accept: application/json
```

### Payload-Struktur

```json
{
  "reference": "unique-reference-uuid",
  "user": {
    "id": 12345,
    "email": "user@example.com"
  },
  "package": {
    "id": 1,
    "key": "credits_300",
    "name": "300 Credits",
    "credits": 300,
    "amount": "300.00",
    "currency": "CHF"
  },
  "purchase": {
    "id": 987
  },
  "callback_url": "https://yourplatform.com/api/credits/callback/unique-reference-uuid",
  "success_url": "https://yourplatform.com/credits/success",
  "cancel_url": "https://yourplatform.com/credits/cancel"
}
```

### Felder

| Feld | Typ | Erforderlich | Beschreibung |
|------|-----|--------------|--------------|
| `reference` | string | ✅ | Eindeutige Referenz (UUID empfohlen), max. 255 Zeichen |
| `user.id` | integer | ✅ | Benutzer-ID in Ihrer Plattform |
| `user.email` | string | ❌ | E-Mail-Adresse (optional, wird für Wallet-Konto verwendet) |
| `package.credits` | integer | ✅ | Anzahl Credits (min. 1) |
| `package.amount` | numeric | ✅ | Betrag in CHF (min. 0.50) |
| `package.currency` | string | ✅ | Währung (z.B. "CHF"), max. 3 Zeichen |
| `purchase.id` | integer | ✅ | Purchase-ID in Ihrer Plattform |
| `callback_url` | url | ✅ | URL für Webhook-Benachrichtigung |
| `success_url` | url | ✅ | URL für erfolgreiche Zahlung (Browser-Redirect) |
| `cancel_url` | url | ✅ | URL für abgebrochene Zahlung (Browser-Redirect) |

**Hinweis:** `package.id`, `package.key` und `package.name` sind optional, werden aber empfohlen für besseres Logging.

### Erfolgreiche Antwort

```json
{
  "checkout_url": "https://adwallet.ch/checkout/unique-reference-uuid",
  "reference": "unique-reference-uuid",
  "transaction_id": 123
}
```

### Beispiel-Request

```php
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

$apiToken = config('services.adwallet.api_token');
$reference = Str::uuid()->toString();

$response = Http::withToken($apiToken)
    ->asJson()
    ->post('https://adwallet.ch/api/checkout', [
        'reference' => $reference,
        'user' => [
            'id' => $user->id,
            'email' => $user->email,
        ],
        'package' => [
            'id' => $package->id,
            'key' => $package->key,
            'name' => $package->name,
            'credits' => $package->credits,
            'amount' => number_format($package->price, 2, '.', ''),
            'currency' => 'CHF',
        ],
        'purchase' => [
            'id' => $purchase->id,
        ],
        'callback_url' => route('credits.callback', ['reference' => $reference]),
        'success_url' => route('credits.success'),
        'cancel_url' => route('credits.cancel'),
    ]);

if ($response->successful()) {
    $data = $response->json();
    
    // Speichern Sie reference und transaction_id in Ihrer Datenbank
    $purchase->update([
        'adwallet_reference' => $data['reference'],
        'adwallet_transaction_id' => $data['transaction_id'],
        'checkout_url' => $data['checkout_url'],
    ]);
    
    // Browser zu Checkout weiterleiten
    return redirect()->away($data['checkout_url']);
}
```

### Fehlerantworten

**400 Bad Request:**
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "package.credits": ["The package.credits field is required."]
  }
}
```

**401 Unauthorized:**
```json
{
  "message": "Unauthenticated partner request."
}
```

**429 Too Many Requests:**
```json
{
  "message": "Too Many Attempts."
}
```

---

## Webhook-Handling

### Webhook-Endpoint (Ihre Plattform)

adwallet sendet Webhooks an die `callback_url`, die Sie beim Checkout angegeben haben.

### Webhook-Payload (Erfolgreich)

```json
{
  "event": "wallet.topup.completed",
  "transaction_id": 123,
  "user_id": 12345,
  "credits_purchased": 300,
  "currency": "CHF",
  "amount": "300.00",
  "stripe_payment_intent": "pi_1P9...",
  "processed_at": "2025-11-26T10:17:42Z",
  "metadata": {
    "source": "adwallet"
  },
  "signature": "hmac-signature"
}
```

### Webhook-Payload (Fehlgeschlagen)

```json
{
  "event": "wallet.topup.failed",
  "transaction_id": 123,
  "user_id": 12345,
  "credits_purchased": 300,
  "currency": "CHF",
  "amount": "300.00",
  "stripe_payment_intent": null,
  "processed_at": "2025-11-26T10:17:42Z",
  "metadata": {
    "source": "adwallet"
  },
  "signature": "hmac-signature"
}
```

### Signatur-Verifikation

**WICHTIG:** Verifizieren Sie immer die Signatur, bevor Sie die Daten verarbeiten!

```php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

public function handleWebhook(Request $request, string $reference)
{
    $webhookSecret = config('services.adwallet.webhook_secret');
    $payload = $request->all();
    $receivedSignature = $payload['signature'] ?? '';
    
    // Entfernen Sie signature aus Payload für Verifikation
    unset($payload['signature']);
    
    // Berechnen Sie erwartete Signatur
    $payloadJson = json_encode($payload, JSON_UNESCAPED_SLASHES);
    $expectedSignature = hash_hmac('sha256', $payloadJson, $webhookSecret);
    
    // Verifizieren Sie Signatur
    if (!hash_equals($expectedSignature, $receivedSignature)) {
        Log::warning('Invalid webhook signature', [
            'reference' => $reference,
            'received' => substr($receivedSignature, 0, 10) . '...',
        ]);
        
        abort(403, 'Invalid signature');
    }
    
    // Signatur ist gültig, verarbeiten Sie die Daten
    $transactionId = $payload['transaction_id'];
    $userId = $payload['user_id'];
    $credits = $payload['credits_purchased'];
    $event = $payload['event'];
    
    // IDEMPOTENZ: Prüfen Sie, ob diese Transaktion bereits verarbeitet wurde
    $purchase = CreditPurchase::where('adwallet_transaction_id', $transactionId)
        ->first();
    
    if (!$purchase) {
        Log::warning('Purchase not found for transaction', [
            'transaction_id' => $transactionId,
        ]);
        return response()->json(['status' => 'not_found'], 404);
    }
    
    // Verhindern Sie doppelte Verarbeitung
    if ($purchase->status === 'completed') {
        return response()->json(['status' => 'already_processed']);
    }
    
    if ($event === 'wallet.topup.completed') {
        // Guthaben gutschreiben
        DB::transaction(function () use ($purchase, $credits, $userId) {
            $user = User::findOrFail($userId);
            $user->increment('credits', $credits);
            
            $purchase->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);
        });
        
        return response()->json(['status' => 'completed']);
    } elseif ($event === 'wallet.topup.failed') {
        $purchase->update([
            'status' => 'failed',
            'failed_at' => now(),
        ]);
        
        return response()->json(['status' => 'failed']);
    }
    
    return response()->json(['status' => 'unknown_event']);
}
```

### Webhook-Antwort

adwallet erwartet eine **200 OK** Antwort. Alle anderen Status-Codes werden als Fehler behandelt und der Webhook wird erneut versucht.

**Empfohlene Antwort:**
```json
{
  "status": "completed"
}
```

---

## Vollständiger Flow

### 1. Benutzer möchte Guthaben kaufen

```
[Ihre Plattform]
User klickt "300 Credits kaufen"
↓
POST /api/checkout an adwallet.ch
↓
Speichern: reference, transaction_id, checkout_url
↓
Browser-Redirect zu checkout_url
```

### 2. Zahlung bei adwallet

```
[adwallet.ch]
User sieht Checkout-Seite
↓
Stripe Checkout Session
↓
Zahlung erfolgreich/fehlgeschlagen
↓
adwallet verarbeitet Zahlung:
  - Guthaben wird gutgeschrieben (idempotent)
  - Webhook wird an callback_url gesendet
  - Browser wird zu success_url/cancel_url weitergeleitet
```

### 3. Webhook-Verarbeitung (Ihre Plattform)

```
[Ihre Plattform]
Webhook empfangen an callback_url
↓
Signatur verifizieren
↓
Idempotenz prüfen (transaction_id bereits verarbeitet?)
↓
Guthaben gutschreiben
↓
Status aktualisieren
↓
200 OK antworten
```

### 4. Browser-Redirect (UX)

```
[Browser]
Weiterleitung zu success_url/cancel_url
↓
[Ihre Plattform]
Zeigen Sie Erfolgs-/Fehlermeldung
(Webhook ist bereits verarbeitet)
```

---

## Code-Beispiele

### Laravel (PHP)

#### SSO-Redirect

```php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class AdwalletService
{
    protected string $baseUrl;
    protected string $ssoSecret;
    protected string $apiToken;
    protected string $webhookSecret;
    protected string $partner;

    public function __construct()
    {
        $this->baseUrl = config('services.adwallet.base_url', 'https://adwallet.ch');
        $this->ssoSecret = config('services.adwallet.sso_secret');
        $this->apiToken = config('services.adwallet.api_token');
        $this->webhookSecret = config('services.adwallet.webhook_secret');
        $this->partner = config('services.adwallet.partner', 'escort');
    }

    public function generateSsoRedirect(User $user, string $redirectBackUrl): string
    {
        $nonce = Str::uuid()->toString();
        $expiresAt = now()->addMinutes(5)->toIso8601String();

        $payload = [
            $this->partner,
            $user->id,
            $user->email,
            $user->name ?? '',
            $user->role ?? '',
            $nonce,
            $expiresAt,
            $redirectBackUrl,
        ];

        $signature = hash_hmac('sha256', implode('|', $payload), $this->ssoSecret);

        $url = "{$this->baseUrl}/sso/{$this->partner}";
        
        // POST Request mit Form-Daten (für Browser-Redirect)
        $formData = [
            'user_id' => $user->id,
            'email' => $user->email,
            'display_name' => $user->name,
            'role' => $user->role,
            'nonce' => $nonce,
            'expires_at' => $expiresAt,
            'redirect_back_url' => $redirectBackUrl,
            'signature' => $signature,
        ];

        // Für Browser-Redirect: Formular erstellen oder URL mit Query-Params
        // Oder: JavaScript POST Request
        return $url . '?' . http_build_query($formData);
    }

    public function createCheckoutSession(User $user, CreditPackage $package, CreditPurchase $purchase): array
    {
        $reference = Str::uuid()->toString();

        $payload = [
            'reference' => $reference,
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
            ],
            'package' => [
                'id' => $package->id,
                'key' => $package->key,
                'name' => $package->name,
                'credits' => $package->credits,
                'amount' => number_format($package->price, 2, '.', ''),
                'currency' => 'CHF',
            ],
            'purchase' => [
                'id' => $purchase->id,
            ],
            'callback_url' => route('credits.callback', ['reference' => $reference]),
            'success_url' => route('credits.success'),
            'cancel_url' => route('credits.cancel'),
        ];

        $response = Http::withToken($this->apiToken)
            ->asJson()
            ->timeout(10)
            ->post("{$this->baseUrl}/api/checkout", $payload)
            ->throw()
            ->json();

        return $response;
    }

    public function verifyWebhookSignature(array $payload, string $signature): bool
    {
        $payloadWithoutSignature = $payload;
        unset($payloadWithoutSignature['signature']);

        $payloadJson = json_encode($payloadWithoutSignature, JSON_UNESCAPED_SLASHES);
        $expectedSignature = hash_hmac('sha256', $payloadJson, $this->webhookSecret);

        return hash_equals($expectedSignature, $signature);
    }
}
```

#### Webhook-Controller

```php
namespace App\Http\Controllers;

use App\Models\CreditPurchase;
use App\Models\User;
use App\Services\AdwalletService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreditCallbackController extends Controller
{
    public function __construct(
        protected AdwalletService $adwallet
    ) {}

    public function __invoke(Request $request, string $reference): JsonResponse
    {
        $payload = $request->all();
        $signature = $payload['signature'] ?? '';

        // Signatur verifizieren
        if (!$this->adwallet->verifyWebhookSignature($payload, $signature)) {
            Log::warning('Invalid webhook signature', [
                'reference' => $reference,
            ]);
            
            return response()->json(['error' => 'Invalid signature'], 403);
        }

        $transactionId = $payload['transaction_id'] ?? null;
        $userId = $payload['user_id'] ?? null;
        $event = $payload['event'] ?? '';

        if (!$transactionId || !$userId) {
            return response()->json(['error' => 'Missing required fields'], 400);
        }

        // Purchase finden
        $purchase = CreditPurchase::where('adwallet_transaction_id', $transactionId)
            ->first();

        if (!$purchase) {
            Log::warning('Purchase not found', [
                'transaction_id' => $transactionId,
                'reference' => $reference,
            ]);
            
            return response()->json(['error' => 'Purchase not found'], 404);
        }

        // Idempotenz: Prüfen ob bereits verarbeitet
        if ($purchase->status === 'completed') {
            return response()->json(['status' => 'already_processed']);
        }

        // Event verarbeiten
        if ($event === 'wallet.topup.completed') {
            DB::transaction(function () use ($purchase, $payload, $userId) {
                $user = User::findOrFail($userId);
                $credits = $payload['credits_purchased'] ?? 0;

                $user->increment('credits', $credits);

                $purchase->update([
                    'status' => 'completed',
                    'completed_at' => now(),
                    'metadata' => array_merge($purchase->metadata ?? [], [
                        'webhook_payload' => $payload,
                    ]),
                ]);
            });

            return response()->json(['status' => 'completed']);
        }

        if ($event === 'wallet.topup.failed') {
            $purchase->update([
                'status' => 'failed',
                'failed_at' => now(),
                'metadata' => array_merge($purchase->metadata ?? [], [
                    'webhook_payload' => $payload,
                ]),
            ]);

            return response()->json(['status' => 'failed']);
        }

        return response()->json(['status' => 'unknown_event'], 400);
    }
}
```

### Node.js / Express

```javascript
const crypto = require('crypto');
const axios = require('axios');

class AdwalletClient {
    constructor(config) {
        this.baseUrl = config.baseUrl || 'https://adwallet.ch';
        this.partner = config.partner;
        this.apiToken = config.apiToken;
        this.ssoSecret = config.ssoSecret;
        this.webhookSecret = config.webhookSecret;
    }

    generateSsoSignature(user, nonce, expiresAt, redirectBackUrl) {
        const payload = [
            this.partner,
            user.id.toString(),
            user.email,
            user.displayName || '',
            user.role || '',
            nonce,
            expiresAt,
            redirectBackUrl,
        ].join('|');

        return crypto
            .createHmac('sha256', this.ssoSecret)
            .update(payload)
            .digest('hex');
    }

    async createCheckoutSession(user, package, purchase) {
        const reference = require('uuid').v4();

        const payload = {
            reference,
            user: {
                id: user.id,
                email: user.email,
            },
            package: {
                id: package.id,
                key: package.key,
                name: package.name,
                credits: package.credits,
                amount: package.price.toFixed(2),
                currency: 'CHF',
            },
            purchase: {
                id: purchase.id,
            },
            callback_url: `https://yourplatform.com/api/credits/callback/${reference}`,
            success_url: 'https://yourplatform.com/credits/success',
            cancel_url: 'https://yourplatform.com/credits/cancel',
        };

        const response = await axios.post(
            `${this.baseUrl}/api/checkout`,
            payload,
            {
                headers: {
                    'Authorization': `Bearer ${this.apiToken}`,
                    'Content-Type': 'application/json',
                },
            }
        );

        return response.data;
    }

    verifyWebhookSignature(payload, signature) {
        const payloadWithoutSignature = { ...payload };
        delete payloadWithoutSignature.signature;

        const payloadJson = JSON.stringify(payloadWithoutSignature);
        const expectedSignature = crypto
            .createHmac('sha256', this.webhookSecret)
            .update(payloadJson)
            .digest('hex');

        return crypto.timingSafeEqual(
            Buffer.from(signature),
            Buffer.from(expectedSignature)
        );
    }
}

// Verwendung
const adwallet = new AdwalletClient({
    partner: 'escort',
    apiToken: process.env.ADWALLET_API_TOKEN,
    ssoSecret: process.env.ADWALLET_SSO_SECRET,
    webhookSecret: process.env.ADWALLET_WEBHOOK_SECRET,
});

// Webhook Handler
app.post('/api/credits/callback/:reference', async (req, res) => {
    const { reference } = req.params;
    const payload = req.body;
    const signature = payload.signature;

    if (!adwallet.verifyWebhookSignature(payload, signature)) {
        return res.status(403).json({ error: 'Invalid signature' });
    }

    const transactionId = payload.transaction_id;
    const userId = payload.user_id;
    const event = payload.event;

    // Idempotenz prüfen und verarbeiten...
    
    res.json({ status: 'completed' });
});
```

### Python / Django

```python
import hmac
import hashlib
import json
import uuid
from datetime import datetime, timedelta
from django.http import JsonResponse
from django.views.decorators.csrf import csrf_exempt
import requests

class AdwalletClient:
    def __init__(self, base_url, partner, api_token, sso_secret, webhook_secret):
        self.base_url = base_url or 'https://adwallet.ch'
        self.partner = partner
        self.api_token = api_token
        self.sso_secret = sso_secret
        self.webhook_secret = webhook_secret

    def generate_sso_signature(self, user, nonce, expires_at, redirect_back_url):
        payload = '|'.join([
            self.partner,
            str(user.id),
            user.email,
            user.display_name or '',
            user.role or '',
            nonce,
            expires_at,
            redirect_back_url,
        ])
        
        return hmac.new(
            self.sso_secret.encode(),
            payload.encode(),
            hashlib.sha256
        ).hexdigest()

    def create_checkout_session(self, user, package, purchase):
        reference = str(uuid.uuid4())
        
        payload = {
            'reference': reference,
            'user': {
                'id': user.id,
                'email': user.email,
            },
            'package': {
                'id': package.id,
                'key': package.key,
                'name': package.name,
                'credits': package.credits,
                'amount': f'{package.price:.2f}',
                'currency': 'CHF',
            },
            'purchase': {
                'id': purchase.id,
            },
            'callback_url': f'https://yourplatform.com/api/credits/callback/{reference}',
            'success_url': 'https://yourplatform.com/credits/success',
            'cancel_url': 'https://yourplatform.com/credits/cancel',
        }

        response = requests.post(
            f'{self.base_url}/api/checkout',
            json=payload,
            headers={
                'Authorization': f'Bearer {self.api_token}',
                'Content-Type': 'application/json',
            },
            timeout=10
        )
        
        response.raise_for_status()
        return response.json()

    def verify_webhook_signature(self, payload, signature):
        payload_without_signature = payload.copy()
        payload_without_signature.pop('signature', None)
        
        payload_json = json.dumps(payload_without_signature, separators=(',', ':'))
        expected_signature = hmac.new(
            self.webhook_secret.encode(),
            payload_json.encode(),
            hashlib.sha256
        ).hexdigest()
        
        return hmac.compare_digest(expected_signature, signature)

# Webhook Handler
@csrf_exempt
def credit_callback(request, reference):
    payload = json.loads(request.body)
    signature = payload.get('signature')
    
    client = AdwalletClient(
        base_url='https://adwallet.ch',
        partner='escort',
        api_token=settings.ADWALLET_API_TOKEN,
        sso_secret=settings.ADWALLET_SSO_SECRET,
        webhook_secret=settings.ADWALLET_WEBHOOK_SECRET,
    )
    
    if not client.verify_webhook_signature(payload, signature):
        return JsonResponse({'error': 'Invalid signature'}, status=403)
    
    # Verarbeiten...
    
    return JsonResponse({'status': 'completed'})
```

---

## Fehlerbehandlung

### HTTP-Status-Codes

| Code | Bedeutung | Aktion |
|------|-----------|--------|
| 200 | Erfolgreich | Daten verarbeiten |
| 400 | Ungültige Anfrage | Payload prüfen |
| 401 | Nicht authentifiziert | API Token prüfen |
| 403 | Zugriff verweigert | Signatur/Token prüfen |
| 404 | Nicht gefunden | Partner/Route prüfen |
| 429 | Zu viele Anfragen | Rate Limiting beachten |
| 500 | Server-Fehler | Retry mit Backoff |

### Rate Limiting

- **API Checkout:** 60 Requests pro Minute
- **SSO:** 30 Requests pro Minute
- **Webhook:** 120 Requests pro Minute

### Retry-Strategie

Bei temporären Fehlern (500, 502, 503, 504):

1. **Exponential Backoff:** Warten Sie 1s, 2s, 4s, 8s...
2. **Max. Retries:** 3-5 Versuche
3. **Idempotenz:** Verwenden Sie dieselbe `reference` für Retries

---

## Best Practices

### 1. Sicherheit

- ✅ **Immer Signatur verifizieren** (SSO und Webhooks)
- ✅ **Nonces einmalig verwenden** (verhindert Replay-Angriffe)
- ✅ **Kurze Ablaufzeiten** (max. 5 Minuten für SSO-Tokens)
- ✅ **HTTPS verwenden** (für alle URLs)
- ✅ **IP-Allowlisting** (optional, aber empfohlen)

### 2. Idempotenz

- ✅ **transaction_id als Idempotenz-Key verwenden**
- ✅ **Prüfen Sie Status vor Verarbeitung**
- ✅ **Transaktionen verwenden** (für Datenbank-Updates)

### 3. Fehlerbehandlung

- ✅ **Logging implementieren** (für Debugging)
- ✅ **Retry-Logik** (für temporäre Fehler)
- ✅ **User-Feedback** (klare Fehlermeldungen)

### 4. Testing

- ✅ **Test-Keys verwenden** (Stripe Test Mode)
- ✅ **Webhook-Testing** (Stripe CLI: `stripe listen`)
- ✅ **Idempotenz testen** (mehrfache Webhooks senden)

### 5. Monitoring

- ✅ **Webhook-Logs überwachen**
- ✅ **Failed Webhooks tracken**
- ✅ **Transaction-Status überwachen**

---

## Support & Kontakt

- **Website:** https://adwallet.ch
- **Support:** support@adwallet.ch
- **Dokumentation:** Diese Datei

---

## Changelog

### Version 1.0 (2025-11-26)
- Initiale Produktions-Version
- SSO-Integration
- Checkout-API
- Webhook-Support
- 5 Stripe Webhook Events

---

**Letzte Aktualisierung:** 2025-11-26




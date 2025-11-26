# Partner Platform Update: Prevent Duplicate Credits in Polling Fallback

## Context

We have a polling mechanism that calls `POST /api/wallet/credit` every minute to check for pending payments when adwallet's webhook fails. However, this was creating duplicate credit transactions when the Stripe payment had already been processed successfully.

adwallet has been updated to prevent duplicate credits by checking if a Stripe transaction for the same purchase already exists. For this to work, we need to ensure our polling calls include the original `purchase_id` in the metadata.

## Required Change

When calling the `POST /api/wallet/credit` endpoint for polling/fallback scenarios, we must include `purchase_id` in the `metadata` field. This `purchase_id` must match the `purchase.id` that was sent in the original `POST /api/checkout` call.

## What to Update

Find the code that calls `POST /api/wallet/credit` (or `POST https://adwallet.ch/api/wallet/credit`) for polling/fallback scenarios and ensure it includes:

```json
{
  "metadata": {
    "purchase_id": <original_purchase_id_from_checkout>
  }
}
```

## Code Example

### Before (may cause duplicates):
```php
// Polling fallback call
$response = Http::withToken($apiToken)
    ->post("https://adwallet.ch/api/wallet/credit", [
        'user_id' => $userId,
        'credits' => $credits,
        'amount' => (string) $amount,
        'currency' => 'CHF',
        'reason' => "Credit package \"{$packageName}\" purchase completed.",
        'transaction_id' => $pollingTransactionId,
        'metadata' => [
            'source' => 'escort',
            'description' => "...",
            // ❌ Missing purchase_id
        ],
    ]);
```

### After (prevents duplicates):
```php
// Polling fallback call
$response = Http::withToken($apiToken)
    ->post("https://adwallet.ch/api/wallet/credit", [
        'user_id' => $userId,
        'credits' => $credits,
        'amount' => (string) $amount,
        'currency' => 'CHF',
        'reason' => "Credit package \"{$packageName}\" purchase completed.",
        'transaction_id' => $pollingTransactionId,
        'metadata' => [
            'purchase_id' => $purchase->id, // ✅ Must match purchase.id from checkout
            'source' => 'escort',
            'description' => "...",
        ],
    ]);
```

## Requirements

1. **Store purchase_id**: When initiating checkout with `POST /api/checkout`, store the `purchase.id` value (e.g., in your `CreditPurchase` model or similar).

2. **Include in polling**: When calling `POST /api/wallet/credit` for polling, retrieve the stored `purchase.id` and include it in `metadata.purchase_id`.

3. **Match exactly**: The `metadata.purchase_id` must exactly match the `purchase.id` that was sent in the original checkout request.

## How It Works

- When adwallet receives a `POST /api/wallet/credit` call with `metadata.purchase_id`, it checks if there's already a completed Stripe transaction with that `partner_purchase_id`.
- If found, it returns the existing transaction instead of creating a duplicate credit.
- This prevents double-crediting when the webhook already processed the payment successfully.

## Testing

After implementing this change:
1. Complete a Stripe payment successfully
2. Wait for the webhook to process (or simulate webhook failure)
3. Trigger the polling fallback mechanism
4. Verify that no duplicate credit transaction is created
5. Check that the balance is only credited once

## Notes

- The `transaction_id` in the polling call should still be unique for each polling attempt (for idempotency of the polling call itself).
- The `purchase_id` in metadata is what links the polling call to the original checkout transaction.
- This change is backward compatible - if `purchase_id` is missing, the system will still work, but duplicate prevention won't be as effective.


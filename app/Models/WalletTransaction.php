<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalletTransaction extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_FAILED = 'failed';

    public const TYPE_CREDIT = 'credit';
    public const TYPE_DEBIT = 'debit';

    protected $fillable = [
        'wallet_account_id',
        'reference',
        'transaction_id',
        'partner_purchase_id',
        'credits',
        'amount',
        'balance_after',
        'currency',
        'status',
        'type',
        'reason',
        'stripe_payment_intent',
        'stripe_session_id',
        'payload',
        'callback_url',
        'success_url',
        'cancel_url',
        'processed_at',
        'completed_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'processed_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    protected $attributes = [
        'type' => self::TYPE_CREDIT,
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(WalletAccount::class, 'wallet_account_id');
    }

    public function isCredit(): bool
    {
        return $this->type === self::TYPE_CREDIT;
    }

    public function isDebit(): bool
    {
        return $this->type === self::TYPE_DEBIT;
    }
}



<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalletTransaction extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_FAILED = 'failed';

    protected $fillable = [
        'wallet_account_id',
        'reference',
        'partner_purchase_id',
        'credits',
        'amount',
        'balance_after',
        'currency',
        'status',
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

    public function account(): BelongsTo
    {
        return $this->belongsTo(WalletAccount::class, 'wallet_account_id');
    }
}



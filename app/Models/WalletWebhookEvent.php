<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalletWebhookEvent extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'partner',
        'wallet_transaction_id',
        'event',
        'url',
        'success',
        'status_code',
        'error',
        'payload',
    ];

    protected $casts = [
        'success' => 'boolean',
        'payload' => 'array',
        'created_at' => 'datetime',
    ];

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(WalletTransaction::class, 'wallet_transaction_id');
    }
}



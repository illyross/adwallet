<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletSsoEvent extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'partner',
        'partner_user_id',
        'email',
        'display_name',
        'role',
        'nonce',
        'expires_at',
        'redirect_back_url',
        'ip_address',
        'user_agent',
        'payload',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'payload' => 'array',
    ];
}



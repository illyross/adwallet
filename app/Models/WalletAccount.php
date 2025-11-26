<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WalletAccount extends Model
{
    protected $fillable = [
        'partner',
        'partner_user_id',
        'email',
        'phone',
        'display_name',
        'role',
        'balance',
        'last_activity_at',
    ];

    protected $casts = [
        'last_activity_at' => 'datetime',
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class);
    }

    /**
     * Check if this account uses a placeholder email (phone-only user)
     */
    public function isPhoneOnly(): bool
    {
        if (! $this->email) {
            return false;
        }
        
        return preg_match('/^(phone-|user-)\d+@.+\.local$/', $this->email) === 1;
    }
}



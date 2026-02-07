<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    const PLAN_FREE = 'free';
    const PLAN_BASIC = 'basic';
    const PLAN_PROFESSIONAL = 'professional';
    const PLAN_ENTERPRISE = 'enterprise';

    protected $fillable = [
        'user_id',
        'plan',
        'status',
        'start_date',
        'renewal_date',
        'monthly_cost',
        'storage_used',
        'storage_total',
        'auto_renew',
    ];

    protected $casts = [
        'start_date' => 'date',
        'renewal_date' => 'date',
        'monthly_cost' => 'decimal:2',
        'storage_used' => 'decimal:2',
        'storage_total' => 'decimal:2',
        'auto_renew' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Calculate storage usage percentage
     */
    public function storagePercent(): int
    {
        if ($this->storage_total == 0) {
            return 0;
        }
        return (int) (($this->storage_used / $this->storage_total) * 100);
    }

    /**
     * Check if subscription is expired
     */
    public function isExpired(): bool
    {
        return now()->toDateString() > $this->renewal_date;
    }
}

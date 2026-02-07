<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProfile extends Model
{
    protected $fillable = [
        'user_id',
        'avatar',
        'phone',
        'bio',
        'two_factor_enabled',
        'two_factor_secret',
        'system_language',
        'report_language',
        'timezone',
        'use_24hour_format',
        'date_format',
        'calendar_type',
    ];

    protected $casts = [
        'two_factor_enabled' => 'boolean',
        'use_24hour_format' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

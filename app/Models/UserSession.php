<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class UserSession extends Model
{
    protected $fillable = [
        'user_id',
        'session_key',
        'ip_address',
        'user_agent',
        'device_type',
        'browser',
        'os',
        'location',
        'is_current',
        'last_activity',
    ];

    protected $casts = [
        'is_current' => 'boolean',
        'last_activity' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get formatted device information
     */
    public function getDeviceInfo(): string
    {
        if ($this->device_type && $this->os) {
            return "{$this->device_type} • {$this->os}";
        }
        return $this->user_agent ? substr($this->user_agent, 0, 50) : "Unknown Device";
    }

    /**
     * Get formatted browser information
     */
    public function getBrowserInfo(): string
    {
        return $this->browser ?: "Unknown Browser";
    }

    /**
     * Get human readable time since last activity
     */
    public function timeSinceActivity(): string
    {
        if ($this->is_current) {
            return "الآن";
        }
        return "منذ " . $this->last_activity->diffForHumans(null, true);
    }
}

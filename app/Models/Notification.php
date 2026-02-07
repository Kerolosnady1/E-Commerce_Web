<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    const LEVEL_INFO = 'info';
    const LEVEL_WARNING = 'warning';
    const LEVEL_CRITICAL = 'critical';

    protected $fillable = [
        'title',
        'message',
        'level',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];
}

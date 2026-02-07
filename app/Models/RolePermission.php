<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RolePermission extends Model
{
    const ACTION_VIEW = 'view';
    const ACTION_ADD = 'add';
    const ACTION_CHANGE = 'change';
    const ACTION_DELETE = 'delete';
    const ACTION_EXPORT = 'export';

    protected $fillable = [
        'role_id',
        'module_id',
        'action',
        'is_allowed',
    ];

    protected $casts = [
        'is_allowed' => 'boolean',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }
}

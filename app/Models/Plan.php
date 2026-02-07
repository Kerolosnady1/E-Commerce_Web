<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'slug',
        'name_ar',
        'name_en',
        'price_monthly',
        'currency',
        'max_users',
        'storage_limit_gb',
        'features',
        'is_active',
        'is_popular',
    ];

    protected $casts = [
        'price_monthly' => 'decimal:2',
        'features' => 'array',
        'is_active' => 'boolean',
        'is_popular' => 'boolean',
    ];
}

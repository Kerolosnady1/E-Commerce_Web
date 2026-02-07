<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanySettings extends Model
{
    protected $fillable = [
        'company_name_ar',
        'company_name_en',
        'currency',
        'timezone',
        'logo',
        'seal',
        'tax_enabled',
        'vat_number',
        'default_tax_rate',
        'prices_include_tax',
        'show_vat_on_invoice',
        'default_print_template',
        'notification_preferences',
        'password_policy',
        'storage_used_mb',
        'storage_quota_mb',
    ];

    protected $casts = [
        'tax_enabled' => 'boolean',
        'default_tax_rate' => 'decimal:2',
        'prices_include_tax' => 'boolean',
        'show_vat_on_invoice' => 'boolean',
        'notification_preferences' => 'array',
        'password_policy' => 'array',
        'storage_used_mb' => 'float',
        'storage_quota_mb' => 'float',
    ];
}

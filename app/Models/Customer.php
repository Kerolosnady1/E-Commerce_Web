<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    const TYPE_INDIVIDUAL = 'individual';
    const TYPE_COMPANY = 'company';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'customer_type',
        'balance',
        'is_active',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function invoices(): HasMany
    {
        return $this->hasMany(SaleInvoice::class);
    }
}

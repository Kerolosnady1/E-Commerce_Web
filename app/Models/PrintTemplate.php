<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PrintTemplate extends Model
{
    const TYPE_SALES_INVOICE = 'sales_invoice';
    const TYPE_PURCHASE_ORDER = 'purchase_order';
    const TYPE_INVENTORY_REPORT = 'inventory_report';
    const TYPE_CUSTOMER_STATEMENT = 'customer_statement';

    const STYLE_STANDARD = 'standard';
    const STYLE_THERMAL = 'thermal';
    const STYLE_MINIMAL = 'minimal';

    protected $fillable = [
        'name',
        'template_type',
        'style',
        'is_active',
        'is_default',
        'show_qr_code',
        'show_signature',
        'show_vat',
        'header_title',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'show_qr_code' => 'boolean',
        'show_signature' => 'boolean',
        'show_vat' => 'boolean',
    ];

    public function saleInvoices(): HasMany
    {
        return $this->hasMany(SaleInvoice::class);
    }

    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class);
    }
}

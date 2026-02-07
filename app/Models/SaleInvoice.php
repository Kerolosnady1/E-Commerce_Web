<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SaleInvoice extends Model
{
    const STATUS_PAID = 'paid';
    const STATUS_PENDING = 'pending';
    const STATUS_OVERDUE = 'overdue';

    protected $fillable = [
        'number',
        'customer_id',
        'issued_date',
        'status',
        'total',
        'notes',
        'print_template_id',
        'includes_vat',
    ];

    protected $casts = [
        'issued_date' => 'date',
        'total' => 'decimal:2',
        'includes_vat' => 'boolean',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function printTemplate(): BelongsTo
    {
        return $this->belongsTo(PrintTemplate::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class, 'invoice_id');
    }

    /**
     * Calculate subtotal from all items
     */
    public function getSubtotal(): float
    {
        return $this->items->sum('subtotal') ?? 0;
    }

    /**
     * Calculate VAT based on settings and includes_vat flag
     */
    public function getVatAmount(): float
    {
        return $this->items->sum('tax_amount') ?? 0;
    }

    /**
     * Calculate net amount (Total - VAT)
     */
    public function getNetAmount(): float
    {
        return $this->getTotal() - $this->getVatAmount();
    }

    /**
     * Calculate total including or excluding VAT based on settings
     */
    public function getTotal(): float
    {
        $subtotal = $this->getSubtotal();
        $vat = $this->getVatAmount();

        if ($this->includes_vat) {
            return $subtotal;
        } else {
            return $subtotal + $vat;
        }
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseOrder extends Model
{
    const STATUS_DRAFT = 'draft';
    const STATUS_SENT = 'sent';
    const STATUS_RECEIVED = 'received';

    protected $fillable = [
        'number',
        'supplier_id',
        'issued_date',
        'status',
        'total',
        'notes',
        'print_template_id',
    ];

    protected $casts = [
        'issued_date' => 'date',
        'total' => 'decimal:2',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function printTemplate(): BelongsTo
    {
        return $this->belongsTo(PrintTemplate::class);
    }
}

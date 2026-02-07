<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable = [
        'product_id',
        'warehouse_id',
        'quantity',
        'reorder_level',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'reorder_level' => 'integer',
    ];

    /**
     * Get the product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the warehouse
     */
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}

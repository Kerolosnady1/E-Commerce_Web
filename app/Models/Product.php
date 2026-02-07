<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    protected $fillable = [
        'name',
        'sku',
        'category_id',
        'price',
        'sale_price',
        'cost_price',
        'tax_rate',
        'image',
        'note',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function inventory(): HasOne
    {
        return $this->hasOne(InventoryItem::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    /**
     * Return sale price if available, otherwise regular price
     */
    public function getDisplayPrice(): float
    {
        return (float) ($this->sale_price ?? $this->price);
    }
}

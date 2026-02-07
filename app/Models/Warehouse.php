<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_MAINTENANCE = 'maintenance';

    protected $fillable = [
        'name',
        'location',
        'capacity',
        'status',
        'description',
        'is_active',
    ];

    protected $casts = [
        'capacity' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get inventory items in this warehouse
     */
    public function inventoryItems()
    {
        return $this->hasMany(\App\Models\Inventory::class);
    }

    /**
     * Return capacity status based on percentage
     */
    public function getCapacityStatus(): string
    {
        if ($this->capacity >= 80) {
            return 'high';
        } elseif ($this->capacity >= 50) {
            return 'medium';
        } else {
            return 'low';
        }
    }
}

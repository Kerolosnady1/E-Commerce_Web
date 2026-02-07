<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JournalEntry extends Model
{
    protected $fillable = ['entry_date', 'description', 'reference'];

    protected $casts = [
        'entry_date' => 'date',
    ];

    public function items()
    {
        return $this->hasMany(JournalItem::class, 'entry_id');
    }
}

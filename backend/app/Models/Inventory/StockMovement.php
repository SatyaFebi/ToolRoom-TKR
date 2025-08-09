<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'movement_type',
        'qty',
        'location',
        'photo_path',
        'notes',
        'movement_date',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}

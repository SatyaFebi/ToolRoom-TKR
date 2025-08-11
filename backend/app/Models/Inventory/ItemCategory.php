<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ItemCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_type_id',
        'name',
        'description',
    ];

    public function type()
    {
        return $this->belongsTo(ItemType::class, 'item_type_id');
    }

    public function items()
    {
        return $this->hasMany(Item::class, 'item_category_id');
    }
}

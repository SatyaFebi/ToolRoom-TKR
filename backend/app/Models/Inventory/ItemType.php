<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ItemType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public function categories()
    {
        return $this->hasMany(ItemCategory::class, 'item_type_id');
    }
}

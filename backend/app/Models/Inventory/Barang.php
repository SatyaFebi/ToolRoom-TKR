<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';
    
    protected $fillable = ['nama', 'jenis', 'kode', 'kategori_id', 'jumlah'];

    // Relasi: Barang punya 1 kategori
    public function kategori()
    {
        return $this->belongsTo(KategoriBarang::class, 'kategori_id');
    }
}
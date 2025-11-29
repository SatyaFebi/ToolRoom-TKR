<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class KategoriBarang extends Model
{
    use HasFactory;

    protected $table = 'kategori_barang';

    protected $fillable = ['nama_kategori_barang', 'kode_kategori'];

    public function databarang()
    {
        return $this->hasMany(Barang::class, 'kategori_barang_id');
    }

}

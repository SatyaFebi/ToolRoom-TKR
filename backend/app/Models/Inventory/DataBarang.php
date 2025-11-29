<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DataBarang extends Model
{
    use HasFactory;
    
    protected $table = 'data_barang';

    protected $fillable = ['nama_barang', 'jenis_barang', 'kode_barang', 'kategori_barang_id','lokasi_penyimpanan', 'status_barang'];


    // enum values
    public const LOKASI_PENYIMPANAN_OPTIONS =[
        'ruang toolman',
        'ruang ttep',
        'ruang bengkel tefa'
    ];

    public const STATUS_BARANG_OPTIONS = [
        'tersedia',
        'dipinjam'
    ];


    public function KategoriBarang()
    {
        return $this->belongsTo(KategoriBarang::class, 'kategori_barang_id');
    }
}

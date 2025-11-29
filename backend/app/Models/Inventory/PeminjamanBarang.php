<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeminjamanBarang extends Model
{
    use HasFactory;

    protected $table = 'peminjaman_barang';

    protected $fillable = [
        'user_id',
        'barang_id',
        'tanggal_peminjaman',
        'tanggal_pengembalian',
        'status',
        'keterangan'
    ];

    public function user() {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function barang() {
        return $this->belongsTo(DataBarang::class);
    }
}
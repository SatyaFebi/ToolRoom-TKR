<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Model;
use App\Models\Service\Customers;

class Vehicles extends Model
{
    protected $connection = 'tkr_service_management';

    protected $table = 'vehicles';

    protected $fillable = [
        'customer_id',
        'jenis_kendaraan',
        'merek',
        'model',
        'tahun_produksi',
        'no_polisi'
    ];

    public function customer()
    {
        return $this->belongsTo(Customers::class, 'customer_id');
    }
}

<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Model;

class ServiceOrder extends Model
{
    protected $connection = 'tkr_service_management';

    protected $table = 'service_orders';

    protected $fillable = [
        'vehicle_id',
        'keluhan_pelanggan',
        'taksiran_biaya',
        'tanggal_masuk',
        'tanggal_selesai',
        'status',
        'total_biaya_akhir'
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicles::class);
    }
}

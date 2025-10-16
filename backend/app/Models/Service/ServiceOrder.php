<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Model;
use App\Models\Service\Vehicles;

class ServiceOrder extends Model
{
    protected $connection = 'tkr_service_management';

    protected $table = 'service_orders';

    protected $appends = ['no_polisi'];

    protected $fillable = [
        'vehicle_id',
        'keluhan_pelanggan',
        'taksiran_biaya',
        'estimasi',
        'tanggal_masuk',
        'tanggal_selesai',
        'status',
        'total_biaya_akhir'
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicles::class, 'vehicle_id', 'id');
    }

    public function getNoPolisiAttribute()
    {
      return $this->vehicle ? $this->vehicle->no_polisi : null;
    }
}

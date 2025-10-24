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
        'pembayaran',
        'penggantian_part_material',
        'catatan_service',
        'total_biaya_akhir'
    ];

    public function vehicles()
    {
        return $this->belongsTo(Vehicles::class, 'vehicle_id', 'id');
    }

    public function getNoPolisiAttribute()
    {
      return $this->vehicles ? $this->vehicles->no_polisi : null;
    }
}

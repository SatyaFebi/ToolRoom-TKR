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
        'merek',
        'model',
        'tahun',
        'no_polisi'
    ];

    public function customer()
    {
        return $this->belongsTo(Customers::class, 'customer_id');
    }
}

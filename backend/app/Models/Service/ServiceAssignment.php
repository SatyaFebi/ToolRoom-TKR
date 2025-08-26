<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Service\ServiceOrder;

class ServiceAssignment extends Model
{
    protected $connection = 'tkr_service_management';
    protected $table = 'service_assignments';
    protected $fillable = [
        'order_id',
        'murid_id',
        'peran_murid',
        'catatan_guru'
    ];

    public function order()
    {
        return $this->belongsTo(ServiceOrder::class, 'order_id');
    }

    public function murid()
    {
        return $this->setConnection('tkr_inventory_management')
                    ->belongsTo(User::class, 'murid_id');
    }
}

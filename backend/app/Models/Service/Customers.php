<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    protected $connection = 'tkr_service_management';
    protected $table = 'customers';
    protected $fillable = [
        'name',
        'no_telp',
        'alamat'
    ];
}

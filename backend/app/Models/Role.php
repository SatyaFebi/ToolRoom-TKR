<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Model;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    protected $connection = 'tkr_inventory_management';
    protected $table = 'roles';
    protected $fillable = [
        'name',
        'guard_name'
    ];
}

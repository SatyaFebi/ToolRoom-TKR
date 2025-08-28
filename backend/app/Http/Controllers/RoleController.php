<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;


class RoleController extends Controller
{
    public function index()
    {
        $role = Role::select('id', 'name')->get();

        return response()->json([
            'success' => true,
            'data' => $role
        ]);
    }
}

<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service\Vehicles;
use Illuminate\Support\Facades\Log;

class VehiclesController extends Controller
{
    public function add(Request $request)
    {
        try {
            $validated = $request->validate([
                'merek' => 'required|string',
                'model' => 'string|nullable',
                'tahun' => 'integer|nullable',
                'no_polisi' => 'string|unique:vehicles,no_polisi|nullable'
            ]);

            Vehicles::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Berhasil menambah data kendaraan'
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error ketika menambah data kendaraan : ' . $e);
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambah data kendaraan : ' . $e
            ], 400);
        }
    }
}

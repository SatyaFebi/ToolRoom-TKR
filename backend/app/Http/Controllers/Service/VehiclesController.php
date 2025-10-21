<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service\Vehicles;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class VehiclesController extends Controller
{
    public function get()
    {
        $data = Vehicles::all();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function add(Request $request)
    {
        try {
            $validated = $request->validate([
                'customer_id' => [
                    'required',
                    Rule::exists('tkr_service_management.customers', 'id'), // connection khusus
                ],
                'jenis_kendaraan' => 'required|in:Mobil,Motor',
                'merek' => 'required|string',
                'model' => 'string|nullable',
                'tahun_produksi' => 'integer|nullable',
                'no_polisi' => [
                    'string',
                    'nullable',
                    Rule::unique('tkr_service_management.vehicles', 'no_polisi')
                ]
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

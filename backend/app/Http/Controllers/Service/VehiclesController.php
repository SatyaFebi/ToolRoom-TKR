<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service\Vehicles;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class VehiclesController extends Controller
{
    public function get(Request $request)
    {
        $query = $request->input('search');

        $vehicles = Vehicles::query()
            ->when($query, function ($q) use ($query) {
                $q->where('merek', 'like', "%{$query}%")
                    ->orWhere('model', 'like', "%{$query}%")
                    ->orWhere('no_polisi', 'like', "%{$query}%");
            })
            ->limit($request->input('limit', 10))
            ->get();

        return response()->json([
            'success' => true,
            'data' => $vehicles
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

            $vehicle = Vehicles::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Berhasil menambah data kendaraan',
                'data' => $vehicle
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

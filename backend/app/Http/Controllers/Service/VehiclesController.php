<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service\Vehicles;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class VehiclesController extends Controller
{
public function checkVehicle(Request $request, $no_polisi)
{
    try {
        $no_polisi = strtoupper(trim($no_polisi));
        $showAll = $request->boolean('show_all', false); 

        $vehicle = Vehicles::where('no_polisi', $no_polisi)
            ->with([
                'customer',
                'serviceOrders' => function ($q) {
                    $q->orderBy('created_at', 'desc');
                }
            ])
            ->first();

        if (!$vehicle) {
            return response()->json([
                'success' => false,
                'message' => 'Kendaraan dengan plat ' . $no_polisi . ' tidak ditemukan'
            ], 404);
        }

        $serviceOrders = $vehicle->serviceOrders;
        $latestOrder = $serviceOrders->first();

        return response()->json([
            'success' => true,
            'data' => [
                'nama_pelanggan' => $vehicle->customer->name ?? 'Tidak diketahui',
                'no_polisi' => $vehicle->no_polisi,
                'jenis_kendaraan' => $vehicle->jenis_kendaraan,
                'merek' => $vehicle->merek,
                'model' => $vehicle->model,
                'tahun_produksi' => $vehicle->tahun_produksi,
                'status_service' => $latestOrder->status ?? 'Belum ada service',
                'tanggal_masuk' => $latestOrder->tanggal_masuk ?? null,
                'tanggal_selesai' => $latestOrder->tanggal_selesai ?? null,
                'jumlah_service' => $serviceOrders->count(),

                // ambil 3 terakhir dulu, kecuali kalau show_all=true
                'riwayat_service' => ($showAll ? $serviceOrders : $serviceOrders->take(3))
                    ->map(function ($order) {
                        return [
                            'id' => $order->id,
                            'status_service' => $order->status,
                            'tanggal_masuk' => $order->tanggal_masuk,
                            'tanggal_selesai' => $order->tanggal_selesai,
                        ];
                    }),
            ]
        ], 200);

    } catch (\Exception $e) {
        Log::error('Error saat mencari kendaraan: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan pada server'
        ], 500);
    }
}
    
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

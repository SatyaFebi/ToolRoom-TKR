<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Service\ServiceOrder;

class ServiceOrderController extends Controller
{
    public function get()
    {
        $service = ServiceOrder::with('vehicle')->get();

        return response()->json([
            'success' => true,
            'data' => $service
        ], 200);
    }

    public function add(Request $request)
    {
        try {
            $validated = $request->validate([
                'vehicle_id' => 'required|integer',
                'keluhan_pelanggan' => 'required|string',
                'taksiran_biaya' => 'nullable|numeric',
                'estimasi' => 'nullable|string',
                'tanggal_masuk' => 'required|date',
                'tanggal_selesai' => 'nullable|date',
                'status' => 'nullable|in:menunggu,dikerjakan,selesai,dibatalkan',
                'pembayaran' => 'nullable|string|in:cash,credit_card,debit_card,e-wallet,qris,transfer',
                'penggantian_part_material' => 'nullable|string|in:langsung,izin',
                'catatan_service' => 'nullable|string',
                'total_biaya_akhir' => 'nullable|numeric'
            ]);

            ServiceOrder::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Data Service Order berhasil dibuat!'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan data service order : ' . $e
            ], 400);
        }
    }

    public function update($id, Request $request)
    {
        $service = ServiceOrder::findOrFail($id);
        try {
            $validated = $request->validate([
                'vehicle_id' => 'sometimes|integer',
                'keluhan_pelanggan' => 'sometimes|string',
                'taksiran_biaya' => 'nullable|numeric',
                'estimasi' => 'nullable|string',
                'tanggal_masuk' => 'sometimes|date',
                'tanggal_selesai' => 'nullable|date',
                'status' => 'nullable|in:menunggu,dikerjakan,selesai,dibatalkan',
                'pembayaran' => 'nullable|string|in:cash,credit_card,debit_card,e-wallet,qris,transfer',
                'penggantian_part_material' => 'nullable|string|in:langsung,izin',
                'catatan_service' => 'nullable|string',
                'total_biaya_akhir' => 'nullable|numeric'
            ]);

            $service->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Data Service berhasil diupdate!'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate data service order : ' . $e
            ], 400);
        }
    }

    public function delete($id)
    {
        $data = ServiceOrder::findOrFail($id);
        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data service berhasil dihapus!'
        ]);
    }
}

<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service\Customers;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    public function getData()
    {
        $data = Customers::all();

        return response()->json([
            'success' => true,
            'data' => $data
        ], 200);
    }

    public function add(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string',
                'no_telp' => 'required'
            ]);

            if (Customers::where('no_telp', $request->no_telp)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nomor telepon sudah ada di database!'
                ], 422);
            }

            Customers::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Berhasil menambahkan data customer!'
            ], 201);
        } catch (\Exception $e) {
            Log::error('Gagal menambahkan customer!' . $e);
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan data customer! ' . $e,
                'connection' => (new Customers())->getConnectionName()
            ], 400);
        }
    }
}

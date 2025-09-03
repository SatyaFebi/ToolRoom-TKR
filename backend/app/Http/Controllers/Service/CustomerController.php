<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service\Customers;
use Illuminate\Support\Facades\Log;

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
                'no_telp' => 'required|unique:customers,no_telp'
            ]);

            Customers::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Berhasil menambahkan data customer!'
            ], 201);
        } catch (\Exception $e) {
            Log::error('Gagal menambahkan customer!' . $e);
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan data customer!' . $e
            ], 400);
        }
    }
}

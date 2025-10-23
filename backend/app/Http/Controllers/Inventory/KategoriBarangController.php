<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory\KategoriBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KategoriBarangController extends Controller
{
   public function index()
    {
        $kategori = KategoriBarang::all();
        return response()->json($kategori);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:100',
            'kode' => 'required|string|size:3|unique:kategori_barang,kode'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $kategori = KategoriBarang::create([
            'nama' => $request->nama,
            'kode' => strtoupper($request->kode)
        ]);

        return response()->json([
            'message' => 'Kategori berhasil ditambahkan',
            'data' => $kategori
        ], 201);
    }

    public function destroy($id)
    {
        $kategori = KategoriBarang::find($id);
        
        if (!$kategori) {
            return response()->json(['message' => 'Kategori tidak ditemukan'], 404);
        }

        $kategori->delete();
        
        return response()->json([
            'message' => 'Kategori berhasil dihapus'
        ]);
    }
}

<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory\DataBarang;
use App\Models\Inventory\KategoriBarang;

class KategoriBarangController extends Controller
{
     // GET semua kategori
    public function index()
    {
            $kategori = KategoriBarang::all();
            return response()->json(['data' => $kategori]);

    }

    // POST tambah kategori
    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori_barang' => 'required|string|max:100',
            'kode_kategori' => 'required|string|size:3|unique:kategori_barang,kode_kategori',
        ]);

        $kategori = KategoriBarang::create([
            'nama_kategori_barang' => $request->nama_kategori_barang,
            'kode_kategori' => strtoupper($request->kode_kategori),
        ]);

        return response()->json([
            'message' => 'Kategori berhasil ditambahkan',
            'data' => $kategori
        ], 201);
    }

    // DELETE kategori
    public function destroy($id)
    {
        $kategori = KategoriBarang::find($id);
        
        if (!$kategori) {
            return response()->json(['message' => 'Kategori tidak ditemukan'], 404);
        }

        $kategori->delete();
        return response()->json(['message' => 'Kategori berhasil dihapus']);
    }
}

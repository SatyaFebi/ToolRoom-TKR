<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Barang;
use App\Models\Inventory\KategoriBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BarangController extends Controller
{
    // GET semua barang
    public function index()
    {
        $barang = Barang::with('kategori')->get();
        return response()->json([
            'success' => true,
            'data' => $barang
        ]);
    }

    // POST tambah barang (auto-generate kode)
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:100',
            'jenis' => 'required|string|max:100',
            'jumlah' => 'required|integer|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Cari kategori berdasarkan nama jenis
        $kategori = KategoriBarang::where('nama', $request->jenis)->first();
        
        if (!$kategori) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori tidak ditemukan'
            ], 404);
        }

        // Generate kode otomatis
        $randomNum = rand(1000, 9999);
        $kode = $kategori->kode . '-' . $randomNum;

        // Cek kalau kode sudah ada
        while (Barang::where('kode', $kode)->exists()) {
            $randomNum = rand(1000, 9999);
            $kode = $kategori->kode . '-' . $randomNum;
        }

        $barang = Barang::create([
            'nama' => $request->nama,
            'jenis' => $request->jenis,
            'kode' => $kode,
            'kategori_id' => $kategori->id,
            'jumlah' => $request->jumlah //
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil ditambahkan',
            'data' => $barang->load('kategori')
        ], 201);
    }

    // PUT update barang
    public function update(Request $request, $id)
    {
        $barang = Barang::find($id);
        
        if (!$barang) {
            return response()->json([
                'success' => false,
                'message' => 'Barang tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:100',
            'jenis' => 'required|string|max:100',
            'jumlah' => 'required|integer|min:0' // 
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Kalau jenis berubah, generate kode baru
        if ($request->jenis !== $barang->jenis) {
            $kategori = KategoriBarang::where('nama', $request->jenis)->first();
            
            if (!$kategori) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kategori tidak ditemukan'
                ], 404);
            }

            $randomNum = rand(1000, 9999);
            $kode = $kategori->kode . '-' . $randomNum;
            
            while (Barang::where('kode', $kode)->where('id', '!=', $id)->exists()) {
                $randomNum = rand(1000, 9999);
                $kode = $kategori->kode . '-' . $randomNum;
            }

            $barang->update([
                'nama' => $request->nama,
                'jenis' => $request->jenis,
                'kode' => $kode,
                'kategori_id' => $kategori->id,
                'jumlah' => $request->jumlah
            ]);
        } else {
            $barang->update([
                'nama' => $request->nama,
                'jenis' => $request->jenis,
                'jumlah' => $request->jumlah
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil diupdate',
            'data' => $barang->load('kategori')
        ]);
    }

    // DELETE barang
    public function destroy($id)
    {
        $barang = Barang::find($id);
        
        if (!$barang) {
            return response()->json([
                'success' => false,
                'message' => 'Barang tidak ditemukan'
            ], 404);
        }

        $barang->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil dihapus'
        ]);
    }
}
<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory\DataBarang;
use App\Models\Inventory\KategoriBarang;

class DataBarangController extends Controller
{
    // GET semua barang
    public function index()
    {
        $barang = DataBarang::with('kategoriBarang')->get();

        return response()->json([
            'success' => true,
            'data' => $barang
        ]);
    }

    // POST tambah barang (auto-generate kode_barang)
    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:150',
            'jenis_barang' => 'required|string|max:100',
            'status_barang' => 'required|in:' . implode(',', DataBarang::STATUS_BARANG_OPTIONS),
            'lokasi_penyimpanan' => 'required|in:' . implode(',', DataBarang::LOKASI_PENYIMPANAN_OPTIONS)
            
        ]);

        // Cari kategori berdasarkan nama jenis
        $kategori = KategoriBarang::where('nama_kategori_barang', $request->jenis_barang)->first();

        if (!$kategori) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori tidak ditemukan'
            ], 404);
        }

        // Ambil prefix dari kategori
        $prefix = $kategori->kode_kategori;

        // Cari barang terakhir dengan prefix ini
        $lastBarang = DataBarang::where('kategori_barang_id', $kategori->id)
                                ->where('kode_barang', 'like', $prefix . '-%')
                                ->orderBy('id', 'desc')
                                ->first();

        // Hitung nomor urut
        $newNumber = $lastBarang
            ? ((int) substr($lastBarang->kode_barang, -3)) + 1
            : 1;

        // Format kode barang
        $kode_barang = $prefix . '-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        // Simpan barang
        $barang = DataBarang::create([
            'nama_barang' => $request->nama_barang,
            'jenis_barang' => $request->jenis_barang,
            'kode_barang' => $kode_barang,
            'kategori_barang_id' => $kategori->id,
            'status_barang' => $request->status_barang,
            'lokasi_penyimpanan' => $request->lokasi_penyimpanan,
            
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil ditambahkan',
            'data' => $barang->load('kategoriBarang')
        ], 201);
    }

    // PUT update barang
    public function update(Request $request, $id)
    {
        $barang = DataBarang::find($id);

        if (!$barang) {
            return response()->json([
                'success' => false,
                'message' => 'Barang tidak ditemukan'
            ], 404);
        }

        $request->validate([
            'nama_barang' => 'required|string|max:150',
            'jenis_barang' => 'required|string|max:100',
            'status_barang' => 'required|in:' . implode(',', DataBarang::STATUS_BARANG_OPTIONS),
            'lokasi_penyimpanan' => 'required|in:' . implode(',', DataBarang::LOKASI_PENYIMPANAN_OPTIONS)
        ]);

        // Jika jenis berubah, update kategori dan kode
        if ($request->jenis_barang !== $barang->jenis_barang) {
            $kategori = KategoriBarang::where('nama_kategori_barang', $request->jenis_barang)->first();

            if (!$kategori) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kategori tidak ditemukan'
                ], 404);
            }

            $prefix = $kategori->kode_kategori;

            $lastBarang = DataBarang::where('kategori_barang_id', $kategori->id)
                                    ->where('kode_barang', 'like', $prefix . '-%')
                                    ->orderBy('id', 'desc')
                                    ->first();

            $newNumber = $lastBarang
                ? ((int) substr($lastBarang->kode_barang, -3)) + 1
                : 1;

            $kode_barang = $prefix . '-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

            $barang->update([
                'nama_barang' => $request->nama_barang,
                'jenis_barang' => $request->jenis_barang,
                'kode_barang' => $kode_barang,
                'kategori_barang_id' => $kategori->id,
                'status_barang' => $request->status_barang,
                'lokasi_penyimpanan' => $request->lokasi_penyimpanan
            ]);
        } else {
            // Jika jenis tidak berubah, update biasa
            $barang->update([
                'nama_barang' => $request->nama_barang,
                'jenis_barang' => $request->jenis_barang,
                'status_barang' => $request->status_barang,
                'lokasi_penyimpanan' => $request->lokasi_penyimpanan
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil diupdate',
            'data' => $barang->load('kategoriBarang')
        ]);
    }

    // DELETE barang
    public function destroy($id)
    {
        $barang = DataBarang::find($id);

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
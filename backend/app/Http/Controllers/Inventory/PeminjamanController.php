<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory\PeminjamanBarang;
use App\Models\Inventory\DataBarang;

class PeminjamanController extends Controller
{
    // List semua peminjaman
    public function index()
    {
        $peminjaman = PeminjamanBarang::with(['barang','user'])->get();

        return response()->json([
            'success' => true,
            'data'    => $peminjaman
        ]);
    }

    //proses peminjaman
    public function store(Request $request)
    {
        $request->validate([
            'kode_barang' => 'required|string',
        ]);

        $barang = DataBarang::where('kode_barang', $request->kode_barang)->first();

        if (!$barang) {
            return response()->json(['success' => false, 'message' => 'Barang tidak ditemukan'], 404);
        }

        if ($barang->status_barang !== 'tersedia') {
            return response()->json(['success' => false, 'message' => 'Barang sedang tidak tersedia'], 400);
        }

        // update status barang
        $barang->update(['status_barang' => 'dipinjam']);

        // Simpan peminjaman dengan user login
        $peminjaman = PeminjamanBarang::create([
            'user_id'              => auth()->id(),   // otomatis dari user login
            'barang_id'            => $barang->id,
            'tanggal_peminjaman'   => now(),
            'status'               => 'dipinjam'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil dipinjam',
            // 'data'    => $peminjaman->load('barang','user')
        ]);
    }

    // proses pengembalian
    public function pengembalian(Request $request, $id)
    {
        $peminjaman = PeminjamanBarang::find($id);

        if (!$peminjaman) {
            return response()->json(['success' => false, 'message' => 'Data peminjaman tidak ditemukan'], 404);
        }

        $peminjaman->update([
            'tanggal_pengembalian' => now(),
            'status' => 'dikembalikan',
            'keterangan' => $request->input('keterangan')
        ]);

        $peminjaman->barang->update(['status_barang' => 'tersedia']);

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil dikembalikan',
            // 'data'    => $peminjaman->load('barang','user')
        ]);
    }
}
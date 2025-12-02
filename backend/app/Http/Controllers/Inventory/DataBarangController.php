<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory\DataBarang;
use App\Models\Inventory\KategoriBarang;
use Illuminate\Support\Facades\Storage;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;

class DataBarangController extends Controller
{
    public function index()
    {
        $barang = DataBarang::with('kategoriBarang')->get();

        return response()->json([
            'success' => true,
            'data'    => $barang
        ]);
    }

    public function showByKode($kode)
{
    $barang = DataBarang::where('kode_barang', $kode)->with('kategoriBarang')->first();

    if (!$barang) {
        return response()->json([
            'success' => false,
            'message' => 'Barang tidak ditemukan'
        ], 404);
    }

    return response()->json([
        'success' => true,
        'data'    => $barang
    ]);
}
    private function generateQrPng($barang)
    {
        $dataQr = "Kode Barang: {$barang->kode_barang}\nNama: {$barang->nama_barang}\nJenis: {$barang->jenis_barang}";

        $result = Builder::create()
            ->writer(new PngWriter())
            ->data($dataQr)
            ->size(250)
            ->margin(10)
            ->build();

        return $result->getString(); // PNG binary
    }

    private function saveQrFiles($barang, $qrPng)
    {
        $fileName = "qrcode/{$barang->kode_barang}.png";

        Storage::disk('public')->put($fileName, $qrPng);

        $barang->qr_url = $fileName;
        $barang->save();
    }

    public function generateQr($id)
    {
        $barang = DataBarang::find($id);

        if (!$barang) {
            return response()->json([
                'success' => false,
                'message' => 'Barang Tidak Ditemukan'
            ], 404);
        }

        $qrPng = $this->generateQrPng($barang);
        $this->saveQrFiles($barang, $qrPng);

        return response()->json([
            'success' => true,
            'message' => 'QR code berhasil dibuat',
            'qr_url'  => asset("storage/{$barang->qr_url}")
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang'        => 'required|string|max:150',
            'jenis_barang'       => 'required|string|max:100',
            'status_barang'      => 'required|in:' . implode(',', DataBarang::STATUS_BARANG_OPTIONS),
            'lokasi_penyimpanan' => 'required|in:' . implode(',', DataBarang::LOKASI_PENYIMPANAN_OPTIONS),
        ]);

        $kategori = KategoriBarang::where('nama_kategori_barang', $request->jenis_barang)->first();

        if (!$kategori) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori tidak ditemukan'
            ], 404);
        }

        $prefix = $kategori->kode_kategori;

        $lastBarang = DataBarang::where('kategori_barang_id', $kategori->id)
            ->where('kode_barang', 'like', "$prefix-%")
            ->orderBy('id', 'desc')
            ->first();

        $newNumber = $lastBarang ? ((int) substr($lastBarang->kode_barang, -3)) + 1 : 1;
        $kode_barang = $prefix . '-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        $barang = DataBarang::create([
            'nama_barang'        => $request->nama_barang,
            'jenis_barang'       => $request->jenis_barang,
            'kode_barang'        => $kode_barang,
            'kategori_barang_id' => $kategori->id,
            'status_barang'      => $request->status_barang,
            'lokasi_penyimpanan' => $request->lokasi_penyimpanan,
        ]);

        $qrPng = $this->generateQrPng($barang);
        $this->saveQrFiles($barang, $qrPng);

        return response()->json([
            'success' => true,
            'message' => 'Barang dan QR berhasil dibuat',
            'data'    => $barang
        ]);
    }

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
            'nama_barang'        => 'required|string|max:150',
            'jenis_barang'       => 'required|string|max:100',
            'status_barang'      => 'required|in:' . implode(',', DataBarang::STATUS_BARANG_OPTIONS),
            'lokasi_penyimpanan' => 'required|in:' . implode(',', DataBarang::LOKASI_PENYIMPANAN_OPTIONS),
        ]);

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
                ->where('kode_barang', 'like', "$prefix-%")
                ->orderBy('id', 'desc')
                ->first();

            $newNumber = $lastBarang ? ((int) substr($lastBarang->kode_barang, -3)) + 1 : 1;
            $kode_barang = $prefix . '-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

            if ($barang->qr_url) {
                Storage::disk('public')->delete($barang->qr_url);
            }

            $barang->update([
                'nama_barang'        => $request->nama_barang,
                'jenis_barang'       => $request->jenis_barang,
                'kode_barang'        => $kode_barang,
                'kategori_barang_id' => $kategori->id,
                'status_barang'      => $request->status_barang,
                'lokasi_penyimpanan' => $request->lokasi_penyimpanan
            ]);
        } else {
            $barang->update([
                'nama_barang'        => $request->nama_barang,
                'jenis_barang'       => $request->jenis_barang,
                'status_barang'      => $request->status_barang,
                'lokasi_penyimpanan' => $request->lokasi_penyimpanan
            ]);
        }

        $qrPng = $this->generateQrPng($barang);
        $this->saveQrFiles($barang, $qrPng);

        return response()->json([
            'success' => true,
            'message' => 'Barang dan QR berhasil diupdate',
            'data'    => $barang->load('kategoriBarang')
        ]);
    }

    public function destroy($id)
    {
        $barang = DataBarang::find($id);

        if (!$barang) {
            return response()->json([
                'success' => false,
                'message' => 'Barang tidak ditemukan'
            ], 404);
        }

        if ($barang->qr_url) {
            Storage::disk('public')->delete($barang->qr_url);
        }

        $barang->delete();

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil dihapus'
        ]);
    }
}
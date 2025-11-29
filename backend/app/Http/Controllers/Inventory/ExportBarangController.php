<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\BarangExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class ExportBarangController extends Controller
{
    public function export(Request $request)
    {
        try {
            // Ambil query parameter ?with_qr=true
            $withQR = $request->query('with_qr', 'false') === 'true';
            $filename = $withQR ? 'DataBarang_QR.xlsx' : 'DataBarang.xlsx';

            Log::info('Export started', [
                'withQR' => $withQR,
                'filename' => $filename
            ]);

            return Excel::download(new BarangExport($withQR), $filename);

        } catch (\Exception $e) {
            Log::error('Export Error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => $e->getMessage(),
                'line'  => $e->getLine(),
                'file'  => $e->getFile()
            ], 500);
        }
    }
}
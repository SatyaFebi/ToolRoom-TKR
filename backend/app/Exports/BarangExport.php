<?php

namespace App\Exports;

use App\Models\Inventory\DataBarang;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Support\Facades\Log;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;

class BarangExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    protected $withQR;
    protected $barangs;

    public function __construct($withQR = false)
    {
        $this->withQR = $withQR;
        $this->barangs = DataBarang::with('KategoriBarang')->get();

        foreach ($this->barangs as $barang) {
            $pngPath = public_path("storage/qrcode/{$barang->kode_barang}.png");

            if (!file_exists($pngPath)) {
                try {
                    $qr = QrCode::create($barang->kode_barang)
                        ->setSize(200)
                        ->setMargin(10)
                        ->setEncoding(new Encoding('UTF-8'))
                        ->setErrorCorrectionLevel(new ErrorCorrectionLevelHigh());

                    $writer = new PngWriter();
                    $writer->write($qr)->saveToFile($pngPath);

                    Log::info("Generate QR untuk {$barang->kode_barang}", ['path' => $pngPath]);
                } catch (\Throwable $e) {
                    Log::error("Gagal generate QR untuk {$barang->kode_barang}", [
                        'message' => $e->getMessage(),
                        'line' => $e->getLine(),
                        'file' => $e->getFile()
                    ]);
                }
            }
        }
    }

    public function collection()
    {
        return $this->barangs;
    }

    public function headings(): array
    {
        return $this->withQR
            ? ['No', 'Nama Barang', 'Kode Barang', 'QR Code']
            : ['No', 'Nama Barang', 'Kategori Barang', 'Kode Barang', 'Lokasi Penyimpanan'];
    }

    public function map($barang): array
    {
        static $i = 1;

        return $this->withQR
            ? [$i++, $barang->nama_barang, $barang->kode_barang, '']
            : [
                $i++,
                $barang->nama_barang,
                $barang->kategoriBarang->nama_kategori_barang ?? 'N/A',
                $barang->kode_barang,
                $barang->lokasi_penyimpanan ?? '-',
            ];
    }

    public function registerEvents(): array
    {
        return [
            \Maatwebsite\Excel\Events\AfterSheet::class => function (\Maatwebsite\Excel\Events\AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Set lebar kolom
                $sheet->getColumnDimension('A')->setWidth(5);
                $sheet->getColumnDimension('B')->setWidth(25);
                $sheet->getColumnDimension('C')->setWidth(20);
                $sheet->getColumnDimension('D')->setWidth(20);
                if (!$this->withQR) {
                    $sheet->getColumnDimension('E')->setWidth(25);
                }

                // Styling header
                $headerStyles = $this->withQR
                    ? [
                        'A1' => 'FF92D050', // No
                        'B1' => 'FF92D050', // Nama Barang
                        'C1' => 'FF92D050', // Kode Barang
                        'D1' => 'FF92D050', // QR Code
                    ]
                    : [
                        'A1' => 'FFD9EAD3', // No
                        'B1' => 'FFEAD1DC', // Nama Barang
                        'C1' => 'FFFCE4D6', // Kategori
                        'D1' => 'FFE2EFDA', // Kode Barang
                        'E1' => 'FFD9E1F2', // Lokasi
                    ];

                foreach ($headerStyles as $cell => $color) {
                    $sheet->getStyle($cell)->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['argb' => $color],
                        ],
                        'font' => ['bold' => true],
                        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                            'vertical' => Alignment::VERTICAL_CENTER,
                        ],
                    ]);
                }

                $sheet->getRowDimension(1)->setRowHeight(25);

                // Baris data
                $row = 2;
                foreach ($this->barangs as $barang) {
                    if ($this->withQR) {
                        $pngPath = public_path("storage/qrcode/{$barang->kode_barang}.png");
                        if (file_exists($pngPath)) {
                            $drawing = new Drawing();
                            $drawing->setPath($pngPath);
                            $drawing->setCoordinates('D' . $row); // ✅ kolom QR yang benar
                            $drawing->setHeight(80);
                            $drawing->setOffsetY(5);
                            $drawing->setOffsetX(30);
                            $drawing->setWorksheet($sheet);
                        }
                    }

                    $lastCol = $this->withQR ? 'D' : 'E';

                    $sheet->getStyle("A{$row}:{$lastCol}{$row}")->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['argb' => 'FF000000'],
                            ],
                        ],
                        'alignment' => [
                            'vertical' => Alignment::VERTICAL_CENTER,
                            'horizontal' => Alignment::HORIZONTAL_LEFT,
                            'wrapText' => true,
                        ],
                    ]);

                    $sheet->getRowDimension($row)->setRowHeight($this->withQR ? 70 : 25);
                    $row++;
                }
            },
        ];
    }
}
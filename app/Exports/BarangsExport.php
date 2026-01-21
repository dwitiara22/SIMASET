<?php

namespace App\Exports;

use App\Models\Barang;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class BarangsExport implements FromCollection, WithHeadings, WithDrawings, WithMapping, WithEvents
{
    protected $barangs;

    public function __construct()
    {
        $this->barangs = Barang::all();
    }

    public function collection()
    {
        return $this->barangs;
    }

    public function map($barang): array
    {
        return [
            $barang->kode_barang,
            $barang->nama_barang,
            $barang->nup,
            $barang->kondisi,
            $barang->merek,
            $barang->tgl_peroleh,
            $barang->nilai_peroleh,
            $barang->lokasi,
            $barang->ruangan,
            $barang->nomor_sk_psp,
            '', '', '', '' // Placeholder kolom K, L, M, N untuk 4 Foto
        ];
    }

    public function headings(): array
    {
        return [
            'Kode Barang', 'Nama Barang', 'NUP', 'Kondisi', 'Merek',
            'Tanggal Peroleh', 'Nilai Perolehan', 'Lokasi', 'Ruangan',
            'Nomor SK PSP', 'Foto 1', 'Foto 2', 'Foto 3', 'Foto 4'
        ];
    }

    public function drawings()
    {
        $drawings = [];
        // Kolom target di Excel untuk masing-masing foto
        $columns = ['K', 'L', 'M', 'N'];

        foreach ($this->barangs as $rowIndex => $barang) {
            // Pastikan nama variabel sesuai dengan kolom DB: fotoBarang
            $fotos = $barang->fotoBarang;

            // Jika datanya masih string (belum dicast di model), kita decode manual
            if (is_string($fotos)) {
                $fotos = json_decode(stripslashes($fotos), true);
            }

            if (is_array($fotos)) {
                foreach ($fotos as $i => $fotoPath) {
                    if ($i >= 4) break; // Maksimal 4 foto

                    // Path ke storage
                    $path = storage_path('app/public/' . $fotoPath);

                    if (file_exists($path)) {
                        $drawing = new Drawing();
                        $drawing->setName('Foto_' . ($i + 1));
                        $drawing->setPath($path);
                        $drawing->setHeight(60); // Tinggi gambar

                        // Set koordinat: Kolom (K/L/M/N) dan Baris (Index + 2)
                        $drawing->setCoordinates($columns[$i] . ($rowIndex + 2));
                        $drawing->setOffsetX(15); // geser ke tengah horizontal
                        $drawing->setOffsetY(10); // geser ke tengah vertikal

                        $drawings[] = $drawing;
                    }
                }
            }
        }

        return $drawings;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $this->barangs->count() + 1;

                // Atur tinggi baris (75 cukup untuk gambar setinggi 60px)
                for ($i = 2; $i <= $lastRow; $i++) {
                    $sheet->getRowDimension($i)->setRowHeight(75);
                }

                // Lebar kolom K, L, M, N (Foto)
                foreach (['K', 'L', 'M', 'N'] as $col) {
                    $sheet->getColumnDimension($col)->setWidth(15);
                    $sheet->getColumnDimension('A')->setWidth(15); // Kode Barang
                    $sheet->getColumnDimension('B')->setWidth(15); // Nama Barang (lebih lebar)
                    $sheet->getColumnDimension('C')->setWidth(15);
                    $sheet->getColumnDimension('D')->setWidth(15);
                    $sheet->getColumnDimension('E')->setWidth(15);
                    $sheet->getColumnDimension('F')->setWidth(15);
                    $sheet->getColumnDimension('G')->setWidth(15);
                    $sheet->getColumnDimension('H')->setWidth(40);
                    $sheet->getColumnDimension('I')->setWidth(15);
                    $sheet->getColumnDimension('J')->setWidth(15);
                    $sheet->getColumnDimension('K')->setWidth(20);
                    $sheet->getColumnDimension('L')->setWidth(20);
                    $sheet->getColumnDimension('M')->setWidth(20);
                    $sheet->getColumnDimension('N')->setWidth(20);
                }

                // Styling teks ke tengah secara vertikal
                $sheet->getStyle('A1:N' . $lastRow)->getAlignment()->setVertical('center');
                $sheet->getStyle('A1:N1')->getFont()->setBold(true);

                $sheet->getStyle('A1:N' . $lastRow)->getAlignment()->setHorizontal('center');
                $sheet->getStyle('A1:N1')->getFont()->setBold(true);
            },
        ];
    }
}

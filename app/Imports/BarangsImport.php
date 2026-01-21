<?php

namespace App\Imports;

use App\Models\Barang;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class BarangsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // 1. Normalisasi Tanggal
        $tglPeroleh = null;
        if (!empty($row['tanggal_peroleh'])) {
            if (is_numeric($row['tanggal_peroleh'])) {
                $tglPeroleh = Date::excelToDateTimeObject($row['tanggal_peroleh'])->format('Y-m-d');
            } else {
                $tglPeroleh = date('Y-m-d', strtotime($row['tanggal_peroleh']));
            }
        }

        // 2. Normalisasi Nilai
        $nilaiPeroleh = 0;
        if (!empty($row['nilai_perolehan'])) {
            $nilaiPeroleh = (int) preg_replace('/[^0-9]/', '', $row['nilai_perolehan']);
        }

        // 3. Penanganan Foto
        $pathFolder = 'uploads/barang/';
        $defaultFoto = $pathFolder . 'no-image.png';

        /**
         * Logika Penangkapan Foto:
         * Kita cek beberapa kemungkinan header: 'foto_1' (slug) atau 'foto_1' (manual)
         * Jika di Excel isinya sudah path lengkap (misal: "uploads/barang/abc.jpg"),
         * kita ambil itu saja. Jika hanya nama file (misal: "abc.jpg"), kita tambahkan path folder.
         */
        $keys = ['foto_1', 'foto_2', 'foto_3', 'foto_4'];
        $fotos = [];

        foreach ($keys as $key) {
            $value = isset($row[$key]) ? trim($row[$key]) : null;

            if (!empty($value) && $value !== '-') {
                // Jika isinya sudah mengandung 'uploads/barang', jangan ditambah lagi
                if (str_contains($value, 'uploads/barang/')) {
                    $fotos[] = $value;
                } else {
                    $fotos[] = $pathFolder . $value;
                }
            } else {
                $fotos[] = $defaultFoto;
            }
        }

        // 4. Simpan ke Database
        return new Barang([
            'user_id'       => Auth::id(),
            'kode_barang'   => $row['kode_barang'] ?? '-',
            'nama_barang'   => $row['nama_barang'] ?? 'Tidak Diketahui',
            'nup'           => $row['nup'] ?? '-',
            'kondisi'       => in_array($row['kondisi'] ?? 'Baik', ['Baik', 'Rusak Ringan', 'Rusak Berat'])
                               ? $row['kondisi'] : 'Baik',
            'merek'         => $row['merek'] ?? null,
            'tgl_peroleh'   => $tglPeroleh ?? now()->toDateString(),
            'nilai_peroleh' => $nilaiPeroleh,
            'nomor_sk_psp'  => $row['nomor_sk_psp'] ?? null,
            'lokasi'        => $row['lokasi'] ?? null,
            'ruangan'       => $row['ruangan'] ?? null,
            'latitude'      => $row['latitude'] ?? null,
            'longitude'     => $row['longitude'] ?? null,
            'fotoBarang'    => $fotos, // Disimpan sebagai array (karena model sudah di-cast)
        ]);
    }
}

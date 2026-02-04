<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan_Inventaris_{{ date('d_m_Y') }}</title>

    <style>
        /* =======================
           PENGATURAN HALAMAN
        ======================= */
        @page {
            size: A4 portrait;
            margin: 1cm;
        }

        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact;
        }

        body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        /* =======================
           HEADER
        ======================= */
        .header {
            text-align: center;
            margin-bottom: 18px;
            border-bottom: 2px solid #333;
            padding-bottom: 8px;
        }

        .header h2 {
            margin: 0;
            font-size: 16px;
            text-transform: uppercase;
        }

        .header p {
            margin: 4px 0 0;
            font-size: 11px;
            color: #555;
        }

        /* =======================
           TABEL
        ======================= */
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed; /* WAJIB */
        }

        th {
            background-color: #f0f0f0 !important;
            border: 1px solid #000;
            padding: 6px 3px;
            font-size: 9px;
            text-transform: uppercase;
            font-weight: bold;
            text-align: center;
        }

        td {
            border: 1px solid #000;
            padding: 5px 4px;
            vertical-align: middle;
            word-wrap: break-word;
        }

        /* =======================
           LEBAR KOLOM (FIX)
        ======================= */
        .col-no {
            width: 22px !important;
            padding: 4px 0 !important;
            font-size: 9px;
            text-align: center;
        }

        .col-kode    { width: 80px; }
        .col-nama    { width: auto; }
        .col-kondisi { width: 60px; }
        .col-ruangan { width: 80px; }
        .col-lokasi  { width: 90px; }
        .col-tgl     { width: 70px; }
        .col-nilai   { width: 95px; }

        .text-center { text-align: center; }
        .text-right  { text-align: right; }
        .font-bold   { font-weight: bold; }

        /* =======================
           FOOTER / TTD
        ======================= */
        .footer-section {
            margin-top: 30px;
            width: 100%;
        }

        .signature-box {
            float: right;
            width: 200px;
            text-align: center;
        }

        .space {
            height: 50px;
        }
    </style>
</head>
<body>

    <!-- =======================
         HEADER LAPORAN
    ======================== -->
    <div class="header">
        <h2>Laporan Inventaris Barang</h2>
        <p>Sistem Informasi Aset • Dicetak pada: {{ date('d/m/Y H:i') }}</p>
    </div>

    <!-- =======================
         TABEL DATA
    ======================== -->
    <table>
        <thead>
            <tr>
                <th class="col-no">No</th>
                <th class="col-kode">Kode Barang</th>
                <th class="col-nama">Nama Barang</th>
                <th class="col-kondisi">Kondisi</th>
                <th class="col-ruangan">Ruangan</th>
                <th class="col-lokasi">Lokasi / Alamat</th>
                <th class="col-tgl">Tgl Peroleh</th>
                <th class="col-nilai">Nilai Peroleh</th>
            </tr>
        </thead>

        <tbody>
            @foreach($barangs as $index => $item)
            <tr>
                <td class="col-no">{{ $index + 1 }}</td>
                <td class="col-kode text-center font-bold">{{ $item->kode_barang }}</td>
                <td class="col-nama">{{ $item->nama_barang }}</td>
                <td class="col-kondisi text-center">{{ strtoupper($item->kondisi) }}</td>
                <td class="col-ruangan">{{ $item->ruangan }}</td>
                <td class="col-lokasi">{{ $item->lokasi ?? '-' }}</td>
                <td class="col-tgl text-center">
                    {{ $item->tgl_peroleh
                        ? \Carbon\Carbon::parse($item->tgl_peroleh)->translatedFormat('d/m/Y')
                        : '-' }}
                </td>
                <td class="col-nilai text-right">
                    Rp {{ number_format($item->nilai_peroleh, 0, ',', '.') }}
                </td>
            </tr>
            @endforeach
        </tbody>

        <tfoot>
            <tr style="background-color: #f9f9f9;">
                <th colspan="7" class="text-right">Total Keseluruhan</th>
                <th class="text-right">
                    Rp {{ number_format($barangs->sum('nilai_peroleh'), 0, ',', '.') }}
                </th>
            </tr>
        </tfoot>
    </table>

    <!-- =======================
         TANDA TANGAN
    ======================== -->
    <div class="footer-section">
        <div class="signature-box">
            <p>Pariaman, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            <p class="font-bold">Mengetahui,</p>
            <div class="space"></div>
            <p><u><strong>{{ auth()->user()->nama ?? 'Admin' }}</strong></u></p>
            <p>NIP. {{ auth()->user()->nip ?? '-' }}</p>
        </div>
        <div style="clear: both;"></div>
    </div>

</body>
</html>

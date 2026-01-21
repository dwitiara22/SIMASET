<!DOCTYPE html>
<html>
<head>
    <title>Detail Inventaris - {{ $barang->kode_barang }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; line-height: 1.6; color: #333; margin: 25px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h2 { margin: 0; text-transform: uppercase; font-size: 16px; }
        .title { text-align: center; text-decoration: underline; font-weight: bold; font-size: 14px; margin-top: 10px; text-transform: uppercase; }
        .submission-info { margin: 20px 0; padding: 10px; border: 1px dashed #ccc; background: #fafafa; }
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .info-table td { padding: 5px 8px; vertical-align: top; }
        .label { width: 28%; font-weight: bold; color: #444; }
        .separator { width: 3%; }
        .section-title { background: #f4f4f4; padding: 5px 10px; font-weight: bold; margin-top: 15px; border-left: 4px solid #0d9488; margin-bottom: 10px; }
        .signature-wrapper { margin-top: 40px; width: 100%; }
        .signature-box { float: right; width: 250px; text-align: center; }
        .signature-space { height: 70px; }
        .name-underline { text-decoration: underline; font-weight: bold; text-transform: uppercase; }
        .meta-info { font-size: 10px; color: #666; }
    </style>
</head>

<body>
    <div class="header">
        <h2>KARTU INVENTARIS BARANG</h2>
        <p>Sistem Informasi Manajemen Aset Digital • {{ date('Y') }}</p>
    </div>

    <div class="title">LAPORAN DETAIL DATA ASET</div>

    <div class="submission-info">
        <p>Telah diajukan pencatatan aset inventaris oleh:</p>
        <table style="width: 100%; margin-top: 5px;">
            <tr>
                <td style="width: 15%; font-weight: bold;">Nama Petugas</td>
                <td style="width: 2%;">:</td>
                <td>{{ $barang->user->nama ?? 'Admin System' }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Waktu Input</td>
                <td>:</td>
                <td>{{ \Carbon\Carbon::parse($barang->created_at)->translatedFormat('d F Y, H:i') }} WIB</td>
            </tr>
        </table>
    </div>

    <div class="section-title">I. IDENTITAS BARANG</div>
    <table class="info-table">
        <tr>
            <td class="label">Nama Barang</td>
            <td class="separator">:</td>
            <td>{{ $barang->nama_barang }}</td>
        </tr>
        <tr>
            <td class="label">Kode Barang</td>
            <td class="separator">:</td>
            <td><code>{{ $barang->kode_barang }}</code></td>
        </tr>
        <tr>
            <td class="label">Nomor NUP</td>
            <td class="separator">:</td>
            <td>{{ $barang->nup }}</td>
        </tr>
        <tr>
            <td class="label">Merek / Type</td>
            <td class="separator">:</td>
            <td>{{ $barang->merek ?? '-' }}</td>
        </tr>
        {{-- TAMBAHKAN NOMOR SK PSP DI SINI --}}
        <tr>
            <td class="label">Nomor SK PSP</td>
            <td class="separator">:</td>
            <td style="font-family: monospace;">{{ $barang->nomor_sk_psp ?? 'Data SK Belum Diinput' }}</td>
        </tr>
    </table>

    <div class="section-title">II. SPESIFIKASI & NILAI</div>
    <table class="info-table">
        <tr>
            <td class="label">Kondisi Barang</td>
            <td class="separator">:</td>
            <td><strong>{{ strtoupper($barang->kondisi) }}</strong></td>
        </tr>
        <tr>
            <td class="label">Tanggal Perolehan</td>
            <td class="separator">:</td>
            <td>{{ \Carbon\Carbon::parse($barang->tgl_peroleh)->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td class="label">Nilai Perolehan</td>
            <td class="separator">:</td>
            <td>Rp {{ number_format($barang->nilai_peroleh, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label">Lokasi Penempatan</td>
            <td class="separator">:</td>
            <td>{{ $barang->ruangan }} - {{ $barang->lokasi }}</td>
        </tr>
    </table>

    <div class="signature-wrapper">
        <div class="signature-box">
            <p>Pariaman, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            <p><strong>Menyetujui,</strong></p>
            <div class="signature-space"></div>
            <p class="name-underline">{{ Auth::user()->nama ?? Auth::user()->name }}</p>
            <p class="meta-info">NIP/ID: {{ Auth::user()->nip ?? '-' }}</p>
            <p class="meta-info" style="font-style: italic;">Dicetak oleh: {{ Auth::user()->nama ?? Auth::user()->name }}</p>
        </div>
        <div style="clear: both;"></div>
    </div>
</body>
</html>

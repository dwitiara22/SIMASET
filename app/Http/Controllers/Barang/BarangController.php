<?php

namespace App\Http\Controllers\Barang;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class BarangController extends Controller
{
    public function index() {
        $barangs = Barang::all();
        return view('barang.index', compact('barangs'));
    }

    public function create() {
        return view('barang.create');
    }

    public function store(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'nama_barang'    => 'required|string|max:255',
            'kode_barang'    => 'required|string|unique:barangs,kode_barang', // Sesuai nama tabel barangs
            'nup'            => 'required|string',
            'kondisi'        => 'required|in:Baik,Rusak Ringan,Rusak Berat',
            'tgl_peroleh'    => 'required|date',
            'nilai_peroleh'  => 'required|numeric',
            'fotoBarang'     => 'required|array|size:4',
            'fotoBarang.*'   => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        try {
            DB::beginTransaction();

            // 2. Proses Upload Foto ke Array
            $paths = [];
            if ($request->hasFile('fotoBarang')) {
                foreach ($request->file('fotoBarang') as $index => $file) {
                    $fileName = 'barang_' . time() . '_' . ($index + 1) . '.' . $file->getClientOriginalExtension();
                    // Simpan ke storage/app/public/uploads/barang
                    $path = $file->storeAs('uploads/barang', $fileName, 'public');
                    $paths[] = $path; // Masukkan path ke dalam array
                }
            }

            // 3. Simpan Data ke Database
            Barang::create([
                'user_id'       => auth()->id(), // Mengambil ID user yang sedang login
                'nama_barang'   => $request->nama_barang,
                'kode_barang'   => $request->kode_barang,
                'nup'           => $request->nup,
                'merek'         => $request->merek,
                'kondisi'       => $request->kondisi,
                'tgl_peroleh'   => $request->tgl_peroleh,
                'nilai_peroleh' => $request->nilai_peroleh,
                'nomor_sk_psp'  => $request->nomor_sk_psp,
                'ruangan'       => $request->ruangan,
                'lokasi'        => $request->lokasi,
                'latitude'      => $request->latitude,
                'longitude'     => $request->longitude,
                'fotoBarang'    => $paths, // Simpan sebagai array (otomatis jadi JSON oleh Model)
            ]);

            DB::commit();
            return redirect()->route('Barang.index')->with('success', 'Inventaris berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollback();
            // Hapus foto yang terlanjur diupload jika gagal insert DB
            if (!empty($paths)) {
                foreach ($paths as $p) {
                    Storage::disk('public')->delete($p);
                }
            }
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        return view('barang.edit', compact('barang'));
    }
}

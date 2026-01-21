<?php

namespace App\Http\Controllers\Barang;

use App\Models\Barang;
use Illuminate\Http\Request;
use App\Exports\BarangsExport;
use App\Imports\BarangsImport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        // Ambil parameter search dan per_page (default 10)
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $barangs = Barang::query()
            ->when($search, function ($query, $search) {
                return $query->where('nama_barang', 'like', "%{$search}%")
                            ->orWhere('kode_barang', 'like', "%{$search}%")
                            ->orWhere('ruangan', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString(); // Sangat penting agar filter tidak hilang saat ganti halaman

        return view('barang.index', compact('barangs'));
    }

    public function create() {
        return view('barang.create');
    }

    public function store(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'nama_barang'   => 'required|string|max:255',
            'kode_barang'   => 'required|string|unique:barangs,kode_barang',
            'nup'           => 'required|string',
            'kondisi'       => 'required|in:Baik,Rusak Ringan,Rusak Berat',
            'tgl_peroleh'   => 'required|date',
            'nilai_peroleh' => 'required|numeric',
            // TAMBAHKAN VALIDASI NOMOR SK PSP DI SINI
            'nomor_sk_psp'  => 'nullable|string|max:255',
            'ruangan'       => 'nullable|string|max:255',
            'lokasi'        => 'nullable|string',
            'latitude'      => 'nullable|string',
            'longitude'     => 'nullable|string',
            'fotoBarang'    => 'required|array|size:4',
            'fotoBarang.*'  => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        try {
            DB::beginTransaction();

            $paths = [];
            if ($request->hasFile('fotoBarang')) {
                foreach ($request->file('fotoBarang') as $index => $file) {
                    $fileName = 'barang_' . time() . '_' . ($index + 1) . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('uploads/barang', $fileName, 'public');
                    $paths[] = $path;
                }
            }

            // 3. Simpan Data
            Barang::create([
                'user_id'       => auth()->id(),
                'nama_barang'   => $request->nama_barang,
                'kode_barang'   => $request->kode_barang,
                'nup'           => $request->nup,
                'merek'         => $request->merek,
                'kondisi'       => $request->kondisi,
                'tgl_peroleh'   => $request->tgl_peroleh,
                'nilai_peroleh' => $request->nilai_peroleh,
                'nomor_sk_psp'  => $request->nomor_sk_psp, // Sekarang sudah melewati validasi
                'ruangan'       => $request->ruangan,
                'lokasi'        => $request->lokasi,
                'latitude'      => $request->latitude,
                'longitude'     => $request->longitude,
                'fotoBarang'    => $paths,
            ]);

            DB::commit();
            return redirect()->route('Barang.index')->with('success', 'Inventaris berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollback();
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

    public function update(Request $request, $id)
    {
        // 1. Validasi
        $request->validate([
            'nama_barang'   => 'required|string|max:255',
            'kode_barang'   => 'required|string|unique:barangs,kode_barang,' . $id, // Kecualikan ID sekarang agar tidak bentrok
            'nup'           => 'required|string',
            'kondisi'       => 'required|in:Baik,Rusak Ringan,Rusak Berat',
            'tgl_peroleh'   => 'required|date',
            'nilai_peroleh' => 'required|numeric',
            'nomor_sk_psp'  => 'nullable|string|max:255',
            'ruangan'       => 'nullable|string|max:255',
            'lokasi'        => 'nullable|string',
            'latitude'      => 'nullable|string',
            'longitude'     => 'nullable|string',
            'fotoBarang.*'  => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        try {
            DB::beginTransaction();
            $barang = Barang::findOrFail($id);

            // --- LOGIKA FOTO ---
            // Ambil list foto lama yang dipertahankan (dikirim dari hidden input JS)
            $existingPhotos = $request->input('existing_photos', []);
            $newPhotos = [];

            // Upload foto baru jika ada
            if ($request->hasFile('fotoBarang')) {
                foreach ($request->file('fotoBarang') as $file) {
                    $fileName = 'barang_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('uploads/barang', $fileName, 'public');
                    $newPhotos[] = $path;
                }
            }

            // Gabungkan foto lama dan baru
            $finalPhotos = array_merge($existingPhotos, $newPhotos);

            // Validasi Akhir: Total harus tetap 4
            if (count($finalPhotos) !== 4) {
                // Hapus foto baru yang baru saja diupload karena gagal validasi
                foreach ($newPhotos as $p) { Storage::disk('public')->delete($p); }
                return back()->with('error', 'Total foto harus berjumlah 4 aset.');
            }

            // --- PEMBERSIHAN FILE ---
            // Cari foto lama yang dihapus untuk dihapus dari Storage fisik
            $oldPhotosInDb = is_array($barang->fotoBarang) ? $barang->fotoBarang : json_decode($barang->fotoBarang, true);
            $deletedPhotos = array_diff($oldPhotosInDb, $existingPhotos);
            foreach ($deletedPhotos as $oldPath) {
                Storage::disk('public')->delete($oldPath);
            }

            // --- UPDATE DATABASE ---
            $barang->update([
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
                'fotoBarang'    => $finalPhotos, // Disimpan sebagai array/JSON
            ]);

            DB::commit();
            return redirect()->route('Barang.index')->with('success', 'Data inventaris berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollback();
            // Bersihkan foto baru jika proses database gagal
            if (!empty($newPhotos)) {
                foreach ($newPhotos as $p) { Storage::disk('public')->delete($p); }
            }
            return back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        // 1. Cari data barang
        $barang = Barang::findOrFail($id);

        try {
            DB::beginTransaction();

            // 2. Ambil daftar path foto dari database
            // Pastikan di Model sudah ada casting: protected $casts = ['fotoBarang' => 'array'];
            $photos = is_array($barang->fotoBarang) ? $barang->fotoBarang : json_decode($barang->fotoBarang, true);

            // 3. Hapus semua file fisik foto dari storage
            if (!empty($photos)) {
                foreach ($photos as $photoPath) {
                    if (Storage::disk('public')->exists($photoPath)) {
                        Storage::disk('public')->delete($photoPath);
                    }
                }
            }

            // 4. Hapus data dari database
            $barang->delete();

            DB::commit();
            return redirect()->route('Barang.index')->with('success', 'Data inventaris dan seluruh foto berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('Barang.index')->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $barang = Barang::findOrFail($id);
        return view('barang.detail', compact('barang'));
    }

    public function export()
    {
        return Excel::download(new BarangsExport, 'data-barang.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new BarangsImport, $request->file('file'));

        return redirect()->back()->with('success', 'Data barang berhasil diimport');
    }

    public function cetakPdf($id)
    {
        $barang = Barang::findOrFail($id);
        $user = Auth::user(); // User yang menyetujui/mencetak

        $pdf = Pdf::loadView('barang.pdf', compact('barang', 'user'))
                ->setPaper('a4', 'portrait');

        return $pdf->stream('Detail-Barang-' . $barang->kode_barang . '.pdf');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        // Ambil input pencarian dan jumlah per halaman (default 10)
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $users = User::query()
            // Filter hanya Role 1 dan Role 2
            ->whereIn('role', [1, 2])
            ->when($search, function ($query, $search) {
                // Gunakan where nested agar kondisi Role tidak terganggu oleh OR
                return $query->where(function($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('jabatan', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.index', compact('users'));
    }
    public function create(){
        return view('Admin.create');
    }

    public function store(Request $request)
    {
        // 1. Validasi Input (disesuaikan dengan panjang karakter di migration)
        $request->validate([
            'nip'         => 'required|unique:users,nip|max:30',
            'nama'        => 'required|string|max:100',
            'email'       => 'required|email|unique:users,email',
            'no_hp'       => 'nullable|max:20', // Tambahkan validasi HP
            'role'        => 'required|in:1,2', // Sesuai migration: 1=Super Admin, 2=Admin
            'jabatan'     => 'required|max:100',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'nip.unique'   => 'NIP sudah terdaftar.',
            'email.unique' => 'Email sudah digunakan.',
        ]);

        // 2. Ambil data kecuali foto_profil (kita handle manual)
        $data = $request->except('foto_profil');

        // Password Otomatis: 123456
        $data['password'] = Hash::make('123456');

        // 3. Handle Upload Foto Profil
        if ($request->hasFile('foto_profil')) {
            $file = $request->file('foto_profil');
            // Gunakan NIP agar nama file unik dan mudah dicari
            $nama_file = time() . '_' . $request->nip . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('foto_profil', $nama_file, 'public');
            $data['foto_profil'] = $path;
        }

        // 4. Simpan ke Database
        User::create($data);

        return redirect()->route('Admin.index')->with('success', 'Data Admin berhasil ditambahkan dengan password default: 123456');
    }

    public function edit($admin)
    {
        $user = User::findOrFail($admin);

        return view('admin.edit', [
            'activePage' => 'admin',
            'user' => $user
        ]);
    }


    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // 1. Validasi Input
        $request->validate([
            'nip'         => 'required|max:30|unique:users,nip,' . $id,
            'nama'        => 'required|string|max:100',
            'email'       => 'required|email|unique:users,email,' . $id,
            'no_hp'       => 'nullable|max:20',
            'role'        => 'required|in:1,2',
            'jabatan'     => 'required|max:100',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->except('foto_profil');

        // 2. Handle Update Foto Profil
        if ($request->hasFile('foto_profil')) {
            // Hapus foto lama jika ada
            if ($user->foto_profil && Storage::disk('public')->exists($user->foto_profil)) {
                Storage::disk('public')->delete($user->foto_profil);
            }

            // Upload foto baru
            $file = $request->file('foto_profil');
            $nama_file = time() . '_' . $request->nip . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('foto_profil', $nama_file, 'public');
            $data['foto_profil'] = $path;
        }

        // 3. Update Database
        $user->update($data);

        return redirect()->route('Admin.index')->with('success', 'Data Admin ' . $user->nama . ' berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // 1. Cegah penghapusan diri sendiri (opsional tapi disarankan)
        if (auth()->id() == $user->id) {
            return redirect()->route('Admin.index')->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
        }

        // 2. Hapus file foto dari storage jika ada
        if ($user->foto_profil && Storage::disk('public')->exists($user->foto_profil)) {
            Storage::disk('public')->delete($user->foto_profil);
        }

        // 3. Hapus data dari database
        $user->delete();

        return redirect()->route('Admin.index')->with('success', 'Data Admin berhasil dihapus secara permanen.');
    }
}

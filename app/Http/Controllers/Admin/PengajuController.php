<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PengajuController extends Controller
{
    public function index()
    {
        // Mengambil user dengan role 1 (Super Pengaju) dan 2 (Pengaju)
        $users = User::whereIn('role', [3])->latest()->get();

        return view('Pengaju.index', [
            'activePage' => 'Pengaju',
            'users' => $users
        ]);
    }
    public function create(){
        return view('Pengaju.create');
    }

    public function store(Request $request)
    {
        // 1. Validasi Input (Hapus 'role' dari validasi karena di-set otomatis)
        $request->validate([
            'nip'         => 'required|unique:users,nip|max:30',
            'nama'        => 'required|string|max:100',
            'email'       => 'required|email|unique:users,email',
            'no_hp'       => 'nullable|max:20',
            'jabatan'     => 'required|max:100',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'nip.unique'   => 'NIP sudah terdaftar.',
            'email.unique' => 'Email sudah digunakan.',
        ]);

        $data = $request->except('foto_profil');

        // Password Otomatis
        $data['password'] = Hash::make('123456');

        // SET ROLE OTOMATIS MENJADI 3
        $data['role'] = 3;

        // 3. Handle Upload Foto Profil
        if ($request->hasFile('foto_profil')) {
            $file = $request->file('foto_profil');
            $nama_file = time() . '_' . $request->nip . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('foto_profil', $nama_file, 'public');
            $data['foto_profil'] = $path;
        }

        // 4. Simpan ke Database
        User::create($data);

        return redirect()->route('Pengaju.index')->with('success', 'Data Pengaju berhasil ditambahkan dengan role Pengaju.');
    }

    public function edit($Pengaju)
    {
        $user = User::findOrFail($Pengaju);

        return view('Pengaju.edit', [
            'activePage' => 'Pengaju',
            'user' => $user
        ]);
    }


    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nip'         => 'required|max:30|unique:users,nip,' . $id,
            'nama'        => 'required|string|max:100',
            'email'       => 'required|email|unique:users,email,' . $id,
            'no_hp'       => 'nullable|max:20',
            'jabatan'     => 'required|max:100',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->except('foto_profil');

        // Opsional: Tetap pastikan role adalah 3 saat update
        $data['role'] = 3;

        if ($request->hasFile('foto_profil')) {
            if ($user->foto_profil && Storage::disk('public')->exists($user->foto_profil)) {
                Storage::disk('public')->delete($user->foto_profil);
            }

            $file = $request->file('foto_profil');
            $nama_file = time() . '_' . $request->nip . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('foto_profil', $nama_file, 'public');
            $data['foto_profil'] = $path;
        }

        $user->update($data);

        return redirect()->route('Pengaju.index')->with('success', 'Data Pengaju berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // 1. Cegah penghapusan diri sendiri (opsional tapi disarankan)
        if (auth()->id() == $user->id) {
            return redirect()->route('Pengaju.index')->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
        }

        // 2. Hapus file foto dari storage jika ada
        if ($user->foto_profil && Storage::disk('public')->exists($user->foto_profil)) {
            Storage::disk('public')->delete($user->foto_profil);
        }

        // 3. Hapus data dari database
        $user->delete();

        return redirect()->route('Pengaju.index')->with('success', 'Data Pengaju berhasil dihapus secara permanen.');
    }

}

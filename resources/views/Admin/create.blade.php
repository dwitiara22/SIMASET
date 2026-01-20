@extends('layouts.app', [
    'activePage' => 'admin',
])

@section('content')
<div class="p-6 min-h-screen bg-slate-50">
    <div class="max-w-4xl mx-auto">
        {{-- Breadcrumb --}}
        <nav class="flex mb-4 text-sm text-slate-500">
            <a href="{{ route('Admin.index') }}" class="hover:text-teal-600 transition-colors">Data Admin</a>
            <span class="mx-2">/</span>
            <span class="text-slate-900 font-medium">Tambah Admin Baru</span>
        </nav>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                <h2 class="text-xl font-bold text-slate-900">Form Tambah Admin</h2>
                <p class="text-sm text-slate-500">Pastikan NIP dan Email unik.</p>
            </div>

            <form action="{{ route('Admin.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- NIP --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">NIP</label>
                        <input type="text" name="nip" value="{{ old('nip') }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all outline-none text-sm" placeholder="Contoh: 199001..." required>
                        @error('nip') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Nama --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Lengkap</label>
                        <input type="text" name="nama" value="{{ old('nama') }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all outline-none text-sm" placeholder="Nama Lengkap" required>
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all outline-none text-sm" placeholder="email@gmail.com" required>
                    </div>

                    {{-- No HP --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">No. HP</label>
                        <input type="number" name="no_hp" value="{{ old('no_hp') }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all outline-none text-sm" placeholder="0812...">
                    </div>

                    {{-- Jabatan --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Jabatan</label>
                        <input type="text" name="jabatan" value="{{ old('jabatan') }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all outline-none text-sm" placeholder="Staff IT / Manager" required>
                    </div>

                    {{-- Role --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Role Akses</label>
                        <select name="role" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all outline-none text-sm bg-white">
                            <option value="2">Admin</option>
                            <option value="1">Super Admin</option>
                        </select>
                    </div>

                    {{-- Foto Profil --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Foto Profil</label>
                        <input type="file" name="foto_profil" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 transition-all">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                    <a href="{{ route('Admin.index') }}" class="px-6 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-all">Batal</a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-teal-600 text-white font-semibold text-sm hover:bg-teal-700 shadow-lg shadow-teal-600/20 transition-all">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

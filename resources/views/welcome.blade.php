@extends('layouts.app', [
    'activePage' => 'admin',
])

@section('content')

<div x-data="{ openDelete: false, deleteRoute: '', showImage: false, imgFull: '', adminNama: '' }" class="relative">

    {{-- AREA NOTIFIKASI --}}
    <div class="fixed inset-0 z-[9999] flex flex-col items-center justify-center pointer-events-none p-4 gap-4">
        @if(session('success'))
            <div x-data="{ show: true }"
                 x-init="setTimeout(() => show = false, 4000)"
                 x-show="show"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="scale-90 opacity-0"
                 x-transition:enter-end="scale-100 opacity-100"
                 x-transition:leave="transition ease-in duration-300"
                 class="bg-white border-b-4 border-teal-500 p-6 rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.15)] flex flex-col items-center min-w-[300px] max-w-sm pointer-events-auto text-center">
                <div class="bg-teal-100 p-4 rounded-full mb-4 text-teal-600">
                    <i class="fas fa-check text-2xl"></i>
                </div>
                <h3 class="font-bold text-slate-900 text-lg">Berhasil!</h3>
                <p class="text-slate-500 text-sm mt-1">{{ session('success') }}</p>
                <button @click="show = false" class="mt-5 text-sm font-semibold text-slate-400 hover:text-slate-600 transition-colors">Tutup</button>
            </div>
        @endif
        {{-- Sesi Error Sama Dengan Diatas --}}
    </div>

    <div class="p-4 md:p-6 min-h-screen bg-slate-50">

        {{-- Stats Cards - Dioptimasi untuk Mobile --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-teal-100 text-teal-600 rounded-lg">
                        <i class="fas fa-user-shield text-xl"></i>
                    </div>
                    <div>
                        <p class="text-xs md:text-sm text-slate-500 font-medium">Total Admin</p>
                        <p class="text-xl md:text-2xl font-bold text-slate-900">{{ $users->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Header Section - Responsive Stack --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-xl md:text-2xl font-bold text-slate-900">Manajemen Data Admin</h1>
                <p class="text-xs md:text-sm text-slate-500">Daftar pengguna akses administrator sistem.</p>
            </div>
            <a href="{{ route('Admin.create') }}" class="w-full md:w-auto flex items-center justify-center bg-teal-600 hover:bg-teal-700 text-white px-5 py-3 rounded-xl text-sm font-semibold transition-all shadow-lg shadow-teal-600/20">
                <i class="fas fa-plus mr-2"></i> Tambah Admin
            </a>
        </div>

        {{-- Table & Card Section --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

            {{-- VIEW DESKTOP: Tabel Biasa (hidden di mobile) --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600 text-xs uppercase tracking-wider">
                            <th class="px-6 py-4 font-semibold text-center w-16">No</th>
                            <th class="px-6 py-4 font-semibold">Profil</th>
                            <th class="px-6 py-4 font-semibold">NIP & Jabatan</th>
                            <th class="px-6 py-4 font-semibold">Kontak</th>
                            <th class="px-6 py-4 font-semibold text-center">Role</th>
                            <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($users as $user)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 text-center text-sm font-medium text-slate-400">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($user->foto_profil)
                                        <button @click="showImage = true; imgFull = '{{ asset('storage/' . $user->foto_profil) }}'" class="group relative">
                                            <img src="{{ asset('storage/' . $user->foto_profil) }}" class="w-10 h-10 rounded-full object-cover border border-slate-200 group-hover:brightness-75 transition-all">
                                            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100"><i class="fas fa-search-plus text-white text-[10px]"></i></div>
                                        </button>
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-teal-100 flex items-center justify-center text-teal-700 font-bold border border-teal-200 text-xs">{{ substr($user->nama, 0, 1) }}</div>
                                    @endif
                                    <p class="text-sm font-bold text-slate-900">{{ $user->nama }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-slate-700 font-medium">{{ $user->nip }}</p>
                                <p class="text-xs text-slate-500 italic">{{ $user->jabatan ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-xs text-slate-600"><i class="fas fa-envelope w-4"></i> {{ $user->email }}</div>
                                <div class="text-xs text-slate-600"><i class="fas fa-phone w-4"></i> {{ $user->no_hp ?? '-' }}</div>
                            </td>
                           <td class="px-6 py-4 text-center">
                                @if($user->role == 3)
                                    <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-[10px] font-bold uppercase rounded-full border border-emerald-200">
                                        Pengaju
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-slate-100 text-slate-700 text-[10px] font-bold uppercase rounded-full border border-slate-200">
                                        Lainnya
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('Admin.edit', $user->id) }}" class="p-2 text-slate-400 hover:text-blue-600"><i class="fas fa-edit"></i></a>
                                    @if($user->role != 1)
                                        <button @click="openDelete = true; deleteRoute = '{{ route('Admin.destroy', $user->id) }}'; adminNama = '{{ $user->nama }}'" class="p-2 text-slate-400 hover:text-red-600"><i class="fas fa-trash"></i></button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="px-6 py-10 text-center text-slate-400 italic text-sm">Data tidak ditemukan</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- VIEW MOBILE: List Card (Hanya muncul di mobile) --}}
            <div class="md:hidden divide-y divide-slate-100">
                @forelse($users as $user)
                <div class="p-4 flex flex-col gap-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            {{-- Foto Profil --}}
                            @if($user->foto_profil)
                                <img src="{{ asset('storage/' . $user->foto_profil) }}" @click="showImage = true; imgFull = '{{ asset('storage/' . $user->foto_profil) }}'" class="w-12 h-12 rounded-full object-cover border-2 border-slate-100">
                            @else
                                <div class="w-12 h-12 rounded-full bg-teal-100 flex items-center justify-center text-teal-700 font-bold border border-teal-200">{{ substr($user->nama, 0, 1) }}</div>
                            @endif
                            <div>
                                <h4 class="font-bold text-slate-900">{{ $user->nama }}</h4>
                                <span class="px-2 py-0.5 text-[9px] font-bold uppercase rounded-full border {{ $user->role == 1 ? 'bg-purple-100 text-purple-700 border-purple-200' : 'bg-blue-100 text-blue-700 border-blue-200' }}">
                                    {{ $user->role == 1 ? 'Super Admin' : 'Admin' }}
                                </span>
                            </div>
                        </div>
                        <div class="flex gap-1">
                            <a href="{{ route('Admin.edit', $user->id) }}" class="p-2 text-blue-600 bg-blue-50 rounded-lg"><i class="fas fa-edit"></i></a>
                            @if($user->role != 1)
                                <button @click="openDelete = true; deleteRoute = '{{ route('Admin.destroy', $user->id) }}'; adminNama = '{{ $user->nama }}'" class="p-2 text-red-600 bg-red-50 rounded-lg"><i class="fas fa-trash"></i></button>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2 text-xs bg-slate-50 p-3 rounded-xl border border-slate-100">
                        <div>
                            <p class="text-slate-400 font-medium">NIP</p>
                            <p class="text-slate-800 font-semibold">{{ $user->nip }}</p>
                        </div>
                        <div>
                            <p class="text-slate-400 font-medium">Jabatan</p>
                            <p class="text-slate-800">{{ $user->jabatan ?? '-' }}</p>
                        </div>
                        <div class="col-span-2 mt-1">
                            <p class="text-slate-400 font-medium">Email</p>
                            <p class="text-slate-800 italic">{{ $user->email }}</p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-10 text-center text-slate-400 italic text-sm">Data tidak ditemukan</div>
                @endforelse
            </div>
        </div>
    </div>

     {{-- MODAL PREVIEW FOTO --}}
    <div x-show="showImage"
         class="fixed inset-0 z-[10001] flex items-center justify-center p-4"
         style="display: none;"
         x-cloak>
        <div class="fixed inset-0 bg-slate-900/90 backdrop-blur-sm" @click="showImage = false"></div>
        <div x-show="showImage"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-90"
             x-transition:enter-end="opacity-100 scale-100"
             class="relative z-10 max-w-4xl w-full flex flex-col items-center">
            <button @click="showImage = false" class="absolute -top-12 right-0 md:-right-10 text-white hover:text-slate-300 text-2xl">
                <i class="fas fa-times"></i>
            </button>
            <img :src="imgFull" class="rounded-xl shadow-2xl border-4 border-white max-h-[80vh] object-contain bg-white">
            <p class="text-white mt-4 font-medium text-sm">Klik di luar gambar untuk menutup</p>
        </div>
    </div>

 {{-- MODAL POPUP KONFIRMASI HAPUS --}}
<div x-show="openDelete"
     class="fixed inset-0 z-[10000] overflow-y-auto"
     style="display: none;"
     x-cloak>
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="openDelete = false"></div>
    <div class="flex min-h-full items-center justify-center p-4 text-center">
        <div x-show="openDelete"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="relative w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 shadow-2xl transition-all">

            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-red-100 text-red-600 mb-4">
                <i class="fas fa-exclamation-triangle text-2xl"></i>
            </div>

            <h3 class="text-lg font-bold text-slate-900">Hapus Data Admin?</h3>

            {{-- Penambahan Nama Admin di sini --}}
            <div class="mt-2 p-3 bg-slate-50 rounded-xl border border-dashed border-slate-200">
                <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold">Admin yang dipilih:</p>
                <p class="text-sm font-bold text-red-600" x-text="adminNama"></p>
            </div>

            <p class="mt-3 text-sm text-slate-500">Tindakan ini tidak dapat dibatalkan secara permanen.</p>

            <div class="mt-6 flex justify-center gap-3">
                <button @click="openDelete = false" type="button" class="px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-100 rounded-xl">Batal</button>
                <form :action="deleteRoute" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-xl shadow-lg">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

</div>

<style>
    [x-cloak] { display: none !important; }
</style>

@endsection

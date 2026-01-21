@extends('layouts.app', [
    'activePage' => 'barang',
])

@section('content')
<div class="p-6 min-h-screen bg-slate-50">
    <div class="max-w-7xl mx-auto">
        {{-- Header Section --}}
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-8">
            {{-- Title & Subtitle --}}
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Data Inventaris</h1>
                <p class="text-sm text-slate-500 mt-1">Kelola dan pantau semua aset barang dalam satu panel terintegrasi.</p>
            </div>

            {{-- Action Buttons Group: Hanya Tampil Jika Login --}}
            @auth
            <div class="flex flex-wrap items-center gap-3">
                <div class="flex items-center bg-white p-1 rounded-2xl border border-slate-200 shadow-sm">
                    {{-- EXPORT --}}
                    <a href="{{ route('barangs.export') }}"
                       class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-xl shadow">
                        <i class="fas fa-file-excel mr-2"></i> Export
                    </a>

                    <div class="w-px h-6 bg-slate-200 mx-1"></div>

                    {{-- IMPORT --}}
                    <form action="{{ route('barangs.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
                        @csrf
                        <label class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow cursor-pointer">
                            <i class="fas fa-file-import mr-2"></i>Import
                            <input type="file" name="file" class="hidden" onchange="this.form.submit()" required>
                        </label>
                    </form>
                </div>

                {{-- Primary Action (Add) --}}
                <a href="{{ route('Barang.create') }}"
                   class="inline-flex items-center justify-center px-6 py-2.5 bg-teal-600 hover:bg-teal-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-teal-600/30 transition-all transform hover:-translate-y-0.5 active:translate-y-0">
                    <i class="fas fa-plus mr-2"></i> Tambah Barang
                </a>
            </div>
            @else
            {{-- Pesan untuk User yang Belum Login (Opsional) --}}

            @endauth
        </div>

        {{-- Statistik Ringkas --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
                <div class="text-slate-500 text-xs font-bold uppercase tracking-wider">Total Aset</div>
                <div class="text-2xl font-bold text-slate-900">
                    {{ $barangs->count() }}
                    <span class="text-sm font-normal text-slate-400 ml-1">Barang</span>
                </div>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm border-l-4 border-l-green-500">
                <div class="text-slate-500 text-xs font-bold uppercase tracking-wider text-green-600">Kondisi Baik</div>
                <div class="text-2xl font-bold text-slate-900">{{ $barangs->where('kondisi', 'Baik')->count() }}</div>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm border-l-4 border-l-red-500">
                <div class="text-slate-500 text-xs font-bold uppercase tracking-wider text-red-600">Rusak Berat</div>
                <div class="text-2xl font-bold text-slate-900">{{ $barangs->where('kondisi', 'Rusak Berat')->count() }}</div>
            </div>
        </div>
{{-- Filter & Search Section --}}
<div class="mb-6 flex flex-col md:flex-row gap-4 justify-between items-end">
    <form action="{{ route('Barang.index') }}" method="GET" class="w-full flex flex-col md:flex-row gap-4 items-end">

        {{-- Entries Per Page --}}
        <div class="w-full md:w-auto">
            <label class="text-[10px] font-bold text-slate-400 uppercase mb-1 block ml-1">Tampilkan</label>
            <select name="per_page" onchange="this.form.submit()"
                    class="w-full md:w-24 bg-white border border-slate-200 text-slate-700 text-sm rounded-xl focus:ring-teal-500 focus:border-teal-500 block p-2.5 shadow-sm">
                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
            </select>
        </div>

        {{-- Search Input --}}
        <div class="relative w-full md:w-80">
            <label class="text-[10px] font-bold text-slate-400 uppercase mb-1 block ml-1">Cari Barang</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fas fa-search text-slate-400 text-xs"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="bg-white border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-teal-500 focus:border-teal-500 block w-full pl-10 p-2.5 shadow-sm"
                       placeholder="Nama, kode, atau ruangan...">
                @if(request('search'))
                    <a href="{{ route('Barang.index') }}" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-red-500">
                        <i class="fas fa-times-circle"></i>
                    </a>
                @endif
            </div>
        </div>

        <button type="submit" class="hidden md:block bg-slate-800 text-white px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-slate-700 transition-all">
            Filter
        </button>
    </form>
</div>

{{-- Statistik Ringkas dan Bagian Tabel tetap di bawah sini... --}}
        {{-- Main Content Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">

                {{-- TABLE VIEW (Desktop) --}}
                <table class="w-full text-left border-collapse hidden md:table">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100 uppercase text-[11px] font-bold text-slate-500 tracking-wider">
                            <th class="px-4 py-4 text-center w-10">No</th>
                            <th class="px-6 py-4">Informasi Barang</th>
                            <th class="px-6 py-4">Foto</th>
                            <th class="px-6 py-4 text-center">Kondisi</th>
                            <th class="px-6 py-4">Lokasi/Ruangan</th>
                            <th class="px-6 py-4">Input Oleh</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($barangs as $index => $item)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-4 py-4 text-center text-xs font-mono text-slate-400">
                                {{ ($barangs instanceof \Illuminate\Pagination\LengthAwarePaginator) ? ($barangs->firstItem() + $index) : ($index + 1) }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="font-bold text-slate-900 text-sm mb-0.5">{{ $item->nama_barang }}</span>
                                    <div class="flex items-center gap-2">
                                        <span class="text-[9px] bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded font-mono font-bold uppercase">{{ $item->kode_barang }}</span>
                                        <span class="text-[11px] text-teal-600 font-bold">Rp {{ number_format($item->nilai_peroleh, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex -space-x-3">
                                    @php $fotos = is_array($item->fotoBarang) ? $item->fotoBarang : json_decode($item->fotoBarang); @endphp
                                    @if($fotos)
                                        @foreach(array_slice($fotos, 0, 3) as $foto)
                                            <img src="{{ asset('storage/' . $foto) }}" class="w-9 h-9 rounded-lg border-2 border-white object-cover shadow-sm bg-slate-200">
                                        @endforeach
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $color = [
                                        'Baik' => 'bg-green-100 text-green-700',
                                        'Rusak Ringan' => 'bg-orange-100 text-orange-700',
                                        'Rusak Berat' => 'bg-red-100 text-red-700'
                                    ][$item->kondisi] ?? 'bg-slate-100';
                                @endphp
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase {{ $color }}">
                                    {{ $item->kondisi }}
                                </span>
                            </td>
                            {{-- Kolom Lokasi --}}
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-1">
                                    <div class="text-xs text-slate-700 font-bold flex items-center">
                                        <i class="fas fa-door-open mr-1.5 text-teal-500"></i> {{ $item->ruangan ?? 'Tanpa Ruangan' }}
                                    </div>
                                    @if($item->latitude && $item->longitude)
                                        <a href="https://www.google.com/maps?q={{ $item->latitude }},{{ $item->longitude }}"
                                        target="_blank"
                                        class="group/loc flex flex-col max-w-[180px] hover:text-teal-600 transition-colors">
                                            <div class="text-[10px] text-slate-500 flex items-start leading-tight">
                                                <i class="fas fa-map-marker-alt mr-1.5 text-red-500 mt-0.5"></i>
                                                <span class="underline decoration-slate-300 underline-offset-2 group-hover/loc:decoration-teal-500">
                                                    {{ $item->lokasi ?? 'Lihat di Peta' }}
                                                </span>
                                            </div>
                                        </a>
                                    @else
                                        <div class="text-[10px] text-slate-400 flex items-center italic">
                                            <i class="fas fa-map-marker-alt mr-1.5 opacity-50"></i> GPS tidak tersedia
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-slate-100 border flex items-center justify-center text-[10px] font-bold text-slate-500">
                                        {{ strtoupper(substr($item->user->nama ?? 'S', 0, 1)) }}
                                    </div>
                                    <span class="text-xs text-slate-700">{{ $item->user->nama ?? 'System' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    {{-- Tombol Lihat: Bisa dilihat oleh publik maupun yang sudah login --}}
                                    <a href="{{ route('Barang.show', $item->id) }}"
                                    class="p-2 text-slate-400 hover:text-teal-600 transition-colors"
                                    title="Lihat Detail">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>

                                    {{-- Bagian Khusus User Login --}}
                                    @auth
                                        {{-- Tombol Edit --}}
                                        <a href="{{ route('Barang.edit', $item->id) }}"
                                        class="p-2 text-slate-400 hover:text-blue-600 transition-colors"
                                        title="Edit Data">
                                            <i class="fas fa-edit text-sm"></i>
                                        </a>

                                        {{-- Tombol Hapus --}}
                                        <form action="{{ route('Barang.destroy', $item->id) }}" method="POST" class="inline form-delete">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    class="p-2 text-slate-400 hover:text-red-600 btn-delete transition-colors"
                                                    data-name="{{ $item->nama_barang }}"
                                                    title="Hapus Data">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        </form>
                                    @endauth
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-500">Belum ada data inventaris.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- MOBILE VIEW --}}
                <div class="md:hidden divide-y divide-slate-100">
                    @forelse ($barangs as $index => $item)
                    <div class="p-4">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex flex-col">
                                <span class="text-[10px] font-mono text-slate-400">#{{ ($barangs instanceof \Illuminate\Pagination\LengthAwarePaginator) ? ($barangs->firstItem() + $index) : ($index + 1) }}</span>
                                <span class="font-bold text-slate-900">{{ $item->nama_barang }}</span>
                            </div>
                            <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase {{ $item->kondisi == 'Baik' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $item->kondisi }}
                            </span>
                        </div>
                       <div class="flex justify-between items-center">
                            {{-- Harga Barang tetap tampil untuk publik --}}
                            <span class="text-xs font-bold text-teal-600">
                                Rp {{ number_format($item->nilai_peroleh, 0, ',', '.') }}
                            </span>

                            <div class="flex gap-2">
                                {{-- Tombol Lihat (Show) - Tampil untuk siapa saja --}}
                                <a href="{{ route('Barang.show', $item->id) }}"
                                class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-100 text-slate-500 hover:bg-slate-200 transition-colors">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>

                                {{-- Hanya tampil jika user sudah LOGIN --}}
                                @auth
                                    {{-- Tombol Edit --}}
                                    <a href="{{ route('Barang.edit', $item->id) }}"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors">
                                        <i class="fas fa-edit text-xs"></i>
                                    </a>

                                    {{-- Tombol Delete --}}
                                    <form action="{{ route('Barang.destroy', $item->id) }}" method="POST" class="inline form-delete">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                                class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-600 btn-delete hover:bg-red-100 transition-colors"
                                                data-name="{{ $item->nama_barang }}">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                @endauth
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="p-10 text-center text-slate-400 text-sm">Data kosong.</div>
                    @endforelse
                </div>
            </div>

            @if($barangs instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="p-4 border-t border-slate-100 bg-slate-50/30">
                {{ $barangs->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

{{-- SCRIPT SWEETALERT --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Cari semua tombol yang punya class btn-delete
        const deleteButtons = document.querySelectorAll('.btn-delete');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                const form = this.closest('form');
                const barangName = this.getAttribute('data-name');

                Swal.fire({
                    title: 'Hapus Barang?',
                    text: `Apakah Anda yakin ingin menghapus "${barangName}"? Data yang dihapus tidak bisa dikembalikan.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#0d9488', // Warna Teal-600
                    cancelButtonColor: '#f43f5e',  // Warna Rose-500
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    borderRadius: '1rem',
                    customClass: {
                        popup: 'rounded-3xl',
                        confirmButton: 'rounded-xl px-6 py-2.5 text-sm font-bold',
                        cancelButton: 'rounded-xl px-6 py-2.5 text-sm font-bold'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        // Tampilkan Notifikasi Sukses Jika Ada (Session Flash)
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 2000,
                borderRadius: '1rem'
            });
        @endif
    });
</script>
@endsection

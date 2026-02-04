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
        @auth
            <div class="flex flex-wrap items-center justify-end gap-3">

                {{-- GROUP 1: UTILITY (KHUSUS ROLE 2) --}}
                @if(auth()->user()->role == 2)
                    <div class="flex flex-wrap items-center bg-white p-1.5 rounded-2xl border border-slate-200 shadow-sm gap-1">

                        {{-- EXPORT --}}
                        <button onclick="openExportModal()"
                            class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-xs font-bold rounded-xl transition-all shadow-sm">
                            <i class="fas fa-file-excel mr-2"></i> Export
                        </button>

                        {{-- IMPORT --}}
                        <form action="{{ route('barangs.import') }}" method="POST" enctype="multipart/form-data" id="importForm" class="m-0">
                            @csrf
                            <label class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-sm cursor-pointer transition-all">
                                <i class="fas fa-file-import mr-2"></i> Import
                                <input type="file" name="file" class="hidden" onchange="this.form.submit()" required>
                            </label>
                        </form>

                        <div class="w-px h-6 bg-slate-200 mx-1 hidden sm:block"></div>

                        {{-- CETAK PDF & TAHUN --}}
                        <div class="flex items-center gap-1.5 pl-1">
                            <select id="filterTahun"
                                class="text-[11px] py-1.5 border-slate-200 rounded-xl focus:ring-slate-500 focus:border-slate-500 bg-slate-50 font-semibold">
                                <option value="">Semua Tahun</option>
                                @foreach(range(date('Y'), 2020) as $year)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endforeach
                            </select>

                            <button onclick="bulkPrint()"
                                class="inline-flex items-center px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-xl transition-all shadow-sm">
                                <i class="fas fa-print mr-2"></i> Cetak PDF
                            </button>
                        </div>
                        <button onclick="clearAllSelection()" class="text-[10px] text-red-500 font-bold hover:underline ml-2">
                            Reset Pilihan
                        </button>
                    </div>
                @endif

                {{-- GROUP 2: PRIMARY ACTION (SEMUA ROLE) --}}
                <a href="{{ route('Barang.create') }}"
                    class="inline-flex items-center justify-center px-6 py-2.5 bg-teal-600 hover:bg-teal-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-teal-600/30 transition-all transform hover:-translate-y-0.5 active:translate-y-0">
                    <i class="fas fa-plus mr-2"></i> Tambah Barang
                </a>

            </div>
        @endauth
        </div>

        {{-- Statistik Ringkas --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
                <div class="text-slate-500 text-[10px] font-bold uppercase tracking-wider">Total Aset</div>
                <div class="text-xl md:text-2xl font-bold text-slate-900">
                    {{ $barangs->total() }}
                    <span class="text-xs font-normal text-slate-400"> unit</span>
                </div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm border-l-4 border-l-green-500">
                <div class="text-green-600 text-[10px] font-bold uppercase tracking-wider">Baik</div>
                <div class="text-xl md:text-2xl font-bold text-slate-900">
                    {{ $allStats->where('kondisi', 'Baik')->count() }}
                </div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm border-l-4 border-l-orange-400">
                <div class="text-orange-500 text-[10px] font-bold uppercase tracking-wider">Rusak Ringan</div>
                <div class="text-xl md:text-2xl font-bold text-slate-900">
                    {{ $allStats->where('kondisi', 'Rusak Ringan')->count() }}
                </div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm border-l-4 border-l-red-500">
                <div class="text-red-600 text-[10px] font-bold uppercase tracking-wider">Rusak Berat</div>
                <div class="text-xl md:text-2xl font-bold text-slate-900">
                    {{ $allStats->where('kondisi', 'Rusak Berat')->count() }}
                </div>
            </div>
        </div>

        {{-- Filter & Search Section --}}
        <div class="mb-6 flex flex-col md:flex-row gap-4 justify-between items-end">
            <form action="{{ route('Barang.index') }}" method="GET" class="w-full flex flex-col md:flex-row gap-4 items-end">

                {{-- Filter Per Page --}}
                <div class="w-full md:w-auto">
                    <label class="text-[10px] font-bold text-slate-400 uppercase mb-1 block ml-1">Tampilkan</label>
                    <select name="per_page" onchange="this.form.submit()"
                            class="w-full md:w-24 bg-white border border-slate-200 text-slate-700 text-sm rounded-xl focus:ring-teal-500 focus:border-teal-500 block p-2.5 shadow-sm">
                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    </select>
                </div>

                {{-- BARU: Filter Kondisi --}}
                <div class="w-full md:w-auto">
                    <label class="text-[10px] font-bold text-slate-400 uppercase mb-1 block ml-1">Kondisi</label>
                    <select name="kondisi" onchange="this.form.submit()"
                            class="w-full md:w-40 bg-white border border-slate-200 text-slate-700 text-sm rounded-xl focus:ring-teal-500 focus:border-teal-500 block p-2.5 shadow-sm">
                        <option value="">Semua Kondisi</option>
                        <option value="Baik" {{ request('kondisi') == 'Baik' ? 'selected' : '' }}>Baik</option>
                        <option value="Rusak Ringan" {{ request('kondisi') == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                        <option value="Rusak Berat" {{ request('kondisi') == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
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
                    </div>
                </div>

                <button type="submit" class="bg-slate-800 text-white px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-slate-700 transition-all">
                    Filter
                </button>

                {{-- Tombol Reset --}}
                @if(request('search') || request('kondisi'))
                    <a href="{{ route('Barang.index') }}" class="text-xs text-red-500 font-bold mb-3 hover:underline">Reset</a>
                @endif
            </form>
        </div>

        {{-- Main Content Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">

                {{-- TABLE VIEW (Desktop) --}}
                <table class="w-full text-left border-collapse hidden md:table">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100 uppercase text-[11px] font-bold text-slate-500 tracking-wider">
                            <th class="px-4 py-4 text-center w-10">No</th>
                            @if(auth()->check() && auth()->user()->role == 2)
                            {{-- Di THEAD: Tambahkan kolom checkbox --}}
                            <th class="px-4 py-4 text-center w-10">
                                <input type="checkbox" id="selectAll" class="rounded border-gray-300">
                            </th>
                            @endif
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
                            @if(auth()->check() && auth()->user()->role == 2)
                            {{-- Di TBODY: Tambahkan checkbox di setiap baris --}}
                            <td class="px-4 py-4 text-center">
                                <input type="checkbox" name="ids[]" value="{{ $item->id }}" class="barang-checkbox rounded border-gray-300">
                            </td>
                            @endif
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
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-1">
                                    <div class="text-xs text-slate-700 font-bold flex items-center">
                                        <i class="fas fa-door-open mr-1.5 text-teal-500"></i> {{ $item->ruangan ?? 'Tanpa Ruangan' }}
                                    </div>
                                    @if($item->latitude && $item->longitude)
                                        <a href="https://www.google.com/maps?q={{ $item->latitude }},{{ $item->longitude }}"
                                        target="_blank" class="group/loc flex flex-col max-w-[180px] hover:text-teal-600 transition-colors">
                                            <div class="text-[10px] text-slate-500 flex items-start leading-tight">
                                                <i class="fas fa-map-marker-alt mr-1.5 text-red-500 mt-0.5"></i>
                                                <span class="underline decoration-slate-300 underline-offset-2">
                                                    {{ $item->lokasi ?? 'Lihat di Peta' }}
                                                </span>
                                            </div>
                                        </a>
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
                                    <a href="{{ route('Barang.show', $item->id) }}" class="p-2 text-slate-400 hover:text-teal-600 transition-colors" title="Lihat Detail">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>

                                    @auth
                                        @if(auth()->user()->role == 2)
                                            <a href="{{ route('Barang.edit', $item->id) }}" class="p-2 text-slate-400 hover:text-blue-600 transition-colors" title="Edit Data">
                                                <i class="fas fa-edit text-sm"></i>
                                            </a>
                                            <form action="{{ route('Barang.destroy', $item->id) }}" method="POST" class="inline form-delete">
                                                @csrf @method('DELETE')
                                                <button type="button" class="p-2 text-slate-400 hover:text-red-600 btn-delete transition-colors" data-name="{{ $item->nama_barang }}" title="Hapus Data">
                                                    <i class="fas fa-trash text-sm"></i>
                                                </button>
                                            </form>
                                        @endif
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
                            <span class="text-xs font-bold text-teal-600">
                                Rp {{ number_format($item->nilai_peroleh, 0, ',', '.') }}
                            </span>

                            <div class="flex gap-2">
                                <a href="{{ route('Barang.show', $item->id) }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-100 text-slate-500">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>

                                @auth
                                    @if(auth()->user()->role == 2)
                                        <a href="{{ route('Barang.edit', $item->id) }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                                            <i class="fas fa-edit text-xs"></i>
                                        </a>
                                        <form action="{{ route('Barang.destroy', $item->id) }}" method="POST" class="inline form-delete">
                                            @csrf @method('DELETE')
                                            <button type="button" class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-600 btn-delete" data-name="{{ $item->nama_barang }}">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    @endif
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
    <div id="exportModal" class="fixed inset-0 z-[99] hidden overflow-y-auto">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeExportModal()"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative w-full max-w-md transform overflow-hidden rounded-3xl bg-white p-6 shadow-2xl transition-all border border-slate-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-slate-900">Opsi Export Excel</h3>
                <button onclick="closeExportModal()" class="text-slate-400 hover:text-slate-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="space-y-4">
                <a href="{{ route('barangs.export.download') }}"
                   class="flex items-center p-4 rounded-2xl bg-green-50 border border-green-100 hover:bg-green-100 transition-colors group">
                    <div class="w-10 h-10 rounded-xl bg-green-600 flex items-center justify-center text-white mr-4 shadow-lg shadow-green-200">
                        <i class="fas fa-file-download"></i>
                    </div>
                    <div>
                        <div class="font-bold text-green-900 text-sm">Download Langsung</div>
                        <div class="text-xs text-green-700/70">Unduh file .xlsx ke perangkat Anda</div>
                    </div>
                </a>

                <a href="{{ route('barangs.export.server') }}"
                   class="flex items-center p-4 rounded-2xl bg-blue-50 border border-blue-100 hover:bg-blue-100 transition-colors group">
                    <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center text-white mr-4 shadow-lg shadow-blue-200">
                        <i class="fas fa-server"></i>
                    </div>
                    <div>
                        <div class="font-bold text-blue-900 text-sm">Simpan di Server</div>
                        <div class="text-xs text-blue-700/70">Arsipkan file di folder storage server</div>
                    </div>
                </a>
            </div>

            <p class="mt-6 text-[10px] text-center text-slate-400 uppercase tracking-widest font-bold">Laporan Inventaris v2.0</p>
        </div>
    </div>
</div>
</div>

{{-- MODAL SCRIPTS --}}
<script>
    function openExportModal() {
        document.getElementById('exportModal').classList.remove('hidden');
    }
    function closeExportModal() {
        document.getElementById('exportModal').classList.add('hidden');
    }
</script>

{{-- SCRIPT SWEETALERT --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('.btn-delete');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                const form = this.closest('form');
                const barangName = this.getAttribute('data-name');
                Swal.fire({
                    title: 'Hapus Barang?',
                    text: `Hapus "${barangName}"? Data tidak bisa dikembalikan.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#0d9488',
                    cancelButtonColor: '#f43f5e',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    customClass: { popup: 'rounded-3xl' }
                }).then((result) => {
                    if (result.isConfirmed) form.submit();
                });
            });
        });

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 2000,
                customClass: { popup: 'rounded-3xl' }
            });
        @endif
    });
</script>
<script>
    // 1. Inisialisasi Storage saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
        renderSelectionFromStorage();
    });

    // 2. Fungsi untuk menyimpan/menghapus ID saat checkbox diklik
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('barang-checkbox')) {
            let selectedIds = JSON.parse(sessionStorage.getItem('selected_barang_ids')) || [];
            const id = e.target.value;

            if (e.target.checked) {
                if (!selectedIds.includes(id)) selectedIds.push(id);
            } else {
                selectedIds = selectedIds.filter(item => item !== id);
                document.getElementById('selectAll').checked = false;
            }

            sessionStorage.setItem('selected_barang_ids', JSON.stringify(selectedIds));
            updateVisualCount();
        }
    });

    // 3. Handle Select All (Hanya untuk halaman yang sedang dibuka)
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.barang-checkbox');
        let selectedIds = JSON.parse(sessionStorage.getItem('selected_barang_ids')) || [];

        checkboxes.forEach(cb => {
            cb.checked = this.checked;
            const id = cb.value;
            if (this.checked) {
                if (!selectedIds.includes(id)) selectedIds.push(id);
            } else {
                selectedIds = selectedIds.filter(item => item !== id);
            }
        });

        sessionStorage.setItem('selected_barang_ids', JSON.stringify(selectedIds));
        updateVisualCount();
    });

    // 4. Fungsi Sinkronisasi Checkbox saat pindah halaman
    function renderSelectionFromStorage() {
        const selectedIds = JSON.parse(sessionStorage.getItem('selected_barang_ids')) || [];
        const checkboxes = document.querySelectorAll('.barang-checkbox');
        let allCheckedOnPage = checkboxes.length > 0;

        checkboxes.forEach(cb => {
            if (selectedIds.includes(cb.value)) {
                cb.checked = true;
            } else {
                allCheckedOnPage = false;
            }
        });

        if (checkboxes.length > 0) {
            document.getElementById('selectAll').checked = allCheckedOnPage;
        }
        updateVisualCount();
    }

    // 5. Update Tampilan jumlah yang dipilih (Opsional tapi berguna)
    function updateVisualCount() {
        const selectedIds = JSON.parse(sessionStorage.getItem('selected_barang_ids')) || [];
        const count = selectedIds.length;
        // Anda bisa menambahkan elemen teks di UI untuk menunjukkan "X data dipilih"
        console.log("Data dipilih lintas halaman: " + count);
    }

    async function bulkPrint() {
    const selectedIds = JSON.parse(sessionStorage.getItem('selected_barang_ids')) || [];
    const tahun = document.getElementById('filterTahun').value;

    // Buat parameter URL
    let params = new URLSearchParams();
    if (selectedIds.length > 0) params.append('selected_ids', selectedIds.join(','));
    if (tahun) params.append('tahun', tahun);

    const checkUrl = "{{ route('Barang.cetakData') }}?" + params.toString();

    // Tampilkan Loading
    Swal.fire({
        title: 'Memproses...',
        text: 'Sedang mengecek ketersediaan data',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });

    try {
        // Cek data via AJAX (Header X-Requested-With buat deteksi $request->ajax() di Laravel)
        const response = await fetch(checkUrl, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });

        if (response.ok) {
            const result = await response.json();

            if (result.status === 'empty') {
                Swal.fire({
                    icon: 'error',
                    title: 'Data Kosong',
                    text: `Tidak ada data barang di tahun ${tahun || 'tersebut'}.`,
                    confirmButtonColor: '#0d9488'
                });
            }
        } else {
            // Jika response bukan JSON (berarti isinya HTML PDF), maka data ADA
            Swal.close();
            window.open(checkUrl, '_blank');
        }
    } catch (error) {
        // Fallback jika terjadi error fetch
        Swal.close();
        window.open(checkUrl, '_blank');
    }
}

</script>
<script>
function clearAllSelection() {
    sessionStorage.removeItem('selected_barang_ids');
    location.reload(); // Refresh untuk mengosongkan centang secara visual
}
</script>
@endsection

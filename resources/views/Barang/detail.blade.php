@extends('layouts.app', ['activePage' => 'barang'])

@section('content')
<div class="p-6 min-h-screen bg-slate-50">
    <div class="max-w-5xl mx-auto">
        {{-- Breadcrumb --}}
        <nav class="flex mb-4 text-sm text-slate-500">
            <a href="{{ route('Barang.index') }}" class="hover:text-teal-600">Data Barang</a>
            <span class="mx-2">/</span>
            <span class="text-slate-900 font-medium">Detail Inventaris</span>
        </nav>

        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">

            {{-- 1. HEADER UTAMA (Identitas Paling Penting) --}}
            <div class="p-8 border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white flex justify-between items-start">
                <div>
                    <span class="px-3 py-1 bg-teal-100 text-teal-700 text-[10px] font-bold uppercase rounded-md mb-2 inline-block tracking-widest">Nama Aset</span>
                    <h2 class="text-3xl font-extrabold text-slate-900 leading-none">{{ $barang->nama_barang }}</h2>

                    <div class="mt-4 flex flex-wrap gap-4">
                        {{-- KODE BARANG --}}
                        <div class="flex items-center bg-white border border-slate-200 rounded-lg px-3 py-2 shadow-sm">
                            <i class="fas fa-barcode text-slate-400 mr-2"></i>
                            <div>
                                <p class="text-[10px] text-slate-400 uppercase leading-none font-bold">Kode Barang</p>
                                <p class="text-sm font-mono font-bold text-slate-700">{{ $barang->kode_barang }}</p>
                            </div>
                        </div>
                        {{-- NOMOR NUP --}}
                        <div class="flex items-center bg-white border border-slate-200 rounded-lg px-3 py-2 shadow-sm">
                            <i class="fas fa-tag text-slate-400 mr-2"></i>
                            <div>
                                <p class="text-[10px] text-slate-400 uppercase leading-none font-bold">No. NUP</p>
                                <p class="text-sm font-mono font-bold text-slate-700">{{ $barang->nup }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Ganti bagian ini di file Blade asli Anda --}}
                <div class="flex gap-2">
                    {{-- Tombol Cetak PDF --}}
                    <a href="{{ route('Barang.cetakPdf', $barang->id) }}" target="_blank" class="px-5 py-2.5 bg-slate-800 text-white rounded-xl text-sm font-bold hover:bg-slate-900 transition-all shadow-lg shadow-slate-200 flex items-center">
                        <i class="fas fa-file-pdf mr-2"></i> Download PDF
                    </a>

                    <a href="{{ route('Barang.edit', $barang->id) }}" class="px-5 py-2.5 bg-amber-500 text-white rounded-xl text-sm font-bold hover:bg-amber-600 transition-all shadow-lg shadow-amber-200">
                        <i class="fas fa-edit mr-2"></i> Edit Data
                    </a>
                </div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-0">
                {{-- KOLOM KIRI: INFO DETAIL --}}
                <div class="lg:col-span-2 p-8 space-y-10 border-r border-slate-100">

                    {{-- Group: Kondisi & Spesifikasi --}}
                    <section>
                        <h3 class="text-sm font-bold text-slate-900 mb-5 flex items-center">
                            <i class="fas fa-info-circle mr-2 text-teal-500"></i> Spesifikasi & Kondisi
                        </h3>
                        <div class="grid grid-cols-2 gap-8">
                            <div class="space-y-1">
                                <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Merek / Type</p>
                                <p class="text-slate-700 font-semibold text-lg border-b border-slate-50 pb-1">{{ $barang->merek ?? 'Tidak Ada Merek' }}</p>
                            </div>
                            <div class="space-y-2">
                                <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Status Kondisi</p>
                                <div>
                                    <span class="px-4 py-1.5 rounded-full text-xs font-black uppercase
                                        {{ $barang->kondisi == 'Baik' ? 'bg-green-100 text-green-700 ring-4 ring-green-50' : ($barang->kondisi == 'Rusak Ringan' ? 'bg-orange-100 text-orange-700 ring-4 ring-orange-50' : 'bg-red-100 text-red-700 ring-4 ring-red-50') }}">
                                        ● {{ $barang->kondisi }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- Group: Nilai & Legalitas --}}
                    <section class="bg-slate-50 rounded-2xl p-6 border border-slate-100">
                        <h3 class="text-sm font-bold text-slate-900 mb-5 flex items-center">
                            <i class="fas fa-file-invoice-dollar mr-2 text-teal-500"></i> Nilai & Legalitas
                        </h3>
                        <div class="grid grid-cols-2 gap-y-6 gap-x-8">
                            <div>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">Tanggal Perolehan</p>
                                <p class="text-slate-700 font-bold italic">{{ \Carbon\Carbon::parse($barang->tgl_peroleh)->translatedFormat('d F Y') }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">Nilai Perolehan (Aset)</p>
                                <p class="text-teal-600 font-black text-xl font-mono">Rp {{ number_format($barang->nilai_peroleh, 0, ',', '.') }}</p>
                            </div>
                            <div class="col-span-2 pt-2 border-t border-slate-200">
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">Nomor SK PSP</p>
                                <p class="text-slate-700 font-medium font-mono">{{ $barang->nomor_sk_psp ?? 'Data SK Belum Diinput' }}</p>
                            </div>
                        </div>
                    </section>
                    {{-- Group: Pengaju / Penanggung Jawab --}}
                    <section class="mt-8 pt-6 border-t border-slate-100">
                        <h3 class="text-sm font-bold text-slate-900 mb-4 flex items-center">
                            <i class="fas fa-user-check mr-2 text-teal-500"></i> Admin Pengelola
                        </h3>
                        <div class="flex items-center gap-4 bg-white p-4 rounded-2xl border border-slate-100 shadow-sm w-fit">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-teal-500 to-teal-600 border-4 border-teal-50 flex items-center justify-center text-sm font-black text-white shadow-sm">
                                {{ strtoupper(substr($barang->user->nama ?? 'S', 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider leading-none mb-1">Diinput Oleh:</p>
                                <p class="text-slate-800 font-bold text-base leading-none">{{ $barang->user->nama ?? 'System / Admin' }}</p>
                                <p class="text-[10px] text-slate-500 mt-1 italic">Pada: {{ \Carbon\Carbon::parse($barang->created_at)->translatedFormat('d M Y, H:i') }} WIB</p>
                            </div>
                        </div>
                    </section>
                    {{-- Group: Lokasi --}}
                    <section>
                        <h3 class="text-sm font-bold text-slate-900 mb-4 flex items-center">
                            <i class="fas fa-map-marked-alt mr-2 text-teal-500"></i> Penempatan Aset
                        </h3>
                        <div class="space-y-4">
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-lg bg-teal-50 flex items-center justify-center text-teal-600 shrink-0">
                                    <i class="fas fa-door-open"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Lokasi Ruangan</p>
                                    <p class="text-slate-800 font-bold text-lg">{{ $barang->ruangan ?? 'Belum Ditentukan' }}</p>
                                </div>
                            </div>
                            <div class="p-4 bg-slate-100 rounded-xl border-l-4 border-slate-300">
                                <p class="text-[10px] text-slate-500 font-bold uppercase mb-1">Alamat GPS Terkunci:</p>
                                <p class="text-slate-600 text-sm italic">"{{ $barang->lokasi }}"</p>
                            </div>
                        </div>
                    </section>
                </div>

                {{-- KOLOM KANAN: VISUAL --}}
                <div class="bg-slate-50/50 p-8 space-y-8">
                    {{-- Galeri --}}
                    <div>
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest italic">Dokumentasi Foto</h3>
                            <span class="px-2 py-0.5 bg-slate-200 text-slate-600 text-[10px] font-bold rounded">4 Foto</span>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            @php $fotos = is_array($barang->fotoBarang) ? $barang->fotoBarang : json_decode($barang->fotoBarang, true); @endphp
                            @foreach($fotos as $foto)
                            <div class="group relative aspect-square rounded-2xl overflow-hidden shadow-md cursor-zoom-in" onclick="openModal('/storage/{{ $foto }}')">
                                <img src="/storage/{{ $foto }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <i class="fas fa-search-plus text-white text-xl"></i>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Map --}}
                    <div class="pt-6 border-t border-slate-200">
                        <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4 italic">Titik Koordinat</h3>
                        <div class="rounded-2xl overflow-hidden h-56 border-4 border-white shadow-xl relative">
                             <iframe width="100%" height="100%" frameborder="0" src="https://maps.google.com/maps?q={{ $barang->latitude }},{{ $barang->longitude }}&hl=id&z=17&output=embed"></iframe>
                        </div>
                        <p class="mt-3 text-[10px] font-mono text-center text-slate-400 italic">Coord: {{ $barang->latitude }}, {{ $barang->longitude }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- Modal Zoom Foto --}}
<div id="photoModal" class="fixed inset-0 bg-black/90 hidden z-[99] flex items-center justify-center p-4 transition-all" onclick="closeModal()">
    <button class="absolute top-6 right-6 text-white text-3xl hover:rotate-90 transition-transform">&times;</button>
    <img id="modalImg" src="" class="max-w-full max-h-full rounded-lg shadow-2xl">
</div>

<script>
    function openModal(src) {
        document.getElementById('modalImg').src = src;
        document.getElementById('photoModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeModal() {
        document.getElementById('photoModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
</script>

@endsection

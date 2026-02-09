@extends('layouts.app', ['activePage' => 'barang'])

@section('content')
<div class="p-6 min-h-screen bg-slate-50">
    <div class="max-w-5xl mx-auto">

        {{-- BREADCRUMB & TOMBOL KEMBALI --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <nav class="flex text-sm text-slate-500">
                <a href="{{ route('barang.index') }}" class="hover:text-teal-600 transition-colors">Data Barang</a>
                <span class="mx-2">/</span>
                <span class="text-slate-900 font-semibold">Detail Inventaris</span>
            </nav>

            <a href="{{ route('barang.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-bold hover:bg-slate-50 hover:text-slate-900 transition-all shadow-sm w-fit">
                <i class="fas fa-arrow-left mr-2 text-teal-500"></i> Kembali
            </a>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">

            {{-- 1. HEADER UTAMA --}}
            <div class="p-8 border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white flex flex-col lg:flex-row justify-between lg:items-center gap-6">
                <div class="space-y-1">
                    <span class="px-3 py-1 bg-teal-100 text-teal-700 text-[10px] font-bold uppercase rounded-md tracking-widest inline-block">Nama Aset</span>
                    <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $barang->nama_barang }}</h2>

                    <div class="mt-4 flex flex-wrap gap-3">
                        {{-- Kode Barang --}}
                        <div class="flex items-center bg-white border border-slate-200 rounded-xl px-4 py-2 shadow-sm">
                            <i class="fas fa-barcode text-slate-400 mr-3"></i>
                            <div>
                                <p class="text-[9px] text-slate-400 uppercase font-black leading-none mb-1">Kode Barang</p>
                                <p class="text-sm font-mono font-bold text-slate-700">{{ $barang->kode_barang }}</p>
                            </div>
                        </div>
                        {{-- Nomor NUP --}}
                        <div class="flex items-center bg-white border border-slate-200 rounded-xl px-4 py-2 shadow-sm">
                            <i class="fas fa-tag text-slate-400 mr-3"></i>
                            <div>
                                <p class="text-[9px] text-slate-400 uppercase font-black leading-none mb-1">No. NUP</p>
                                <p class="text-sm font-mono font-bold text-slate-700">{{ $barang->nup }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TOMBOL AKSI (Role 2 Only) --}}
                <div class="flex flex-wrap gap-3">
                    @auth
                    @if(auth()->user()->role == 2)
                        <a href="{{ route('Barang.cetakPdf', $barang->id) }}" target="_blank" class="flex-1 lg:flex-none px-6 py-3 bg-slate-800 text-white rounded-2xl text-sm font-bold hover:bg-slate-900 transition-all shadow-lg shadow-slate-200 flex items-center justify-center">
                            <i class="fas fa-file-pdf mr-2 text-teal-400"></i> Download PDF
                        </a>

                        <a href="{{ route('Barang.edit', $barang->id) }}" class="flex-1 lg:flex-none px-6 py-3 bg-amber-500 text-white rounded-2xl text-sm font-bold hover:bg-amber-600 transition-all shadow-lg shadow-amber-200 flex items-center justify-center">
                            <i class="fas fa-edit mr-2"></i> Edit Data
                        </a>
                    @else
                        <div class="px-4 py-2 bg-slate-100 border border-slate-200 rounded-xl flex items-center text-slate-400">
                            <i class="fas fa-lock mr-2 text-xs"></i>
                            <span class="text-[10px] font-bold uppercase tracking-wider">Mode Pratinjau</span>
                        </div>
                    @endif
                    @endauth
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3">
                {{-- KOLOM KIRI: INFO DETAIL --}}
                <div class="lg:col-span-2 p-8 space-y-10 border-r border-slate-100">

                    {{-- Spesifikasi --}}
                    <section>
                        <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-6 flex items-center italic">
                            <span class="w-8 h-[2px] bg-teal-500 mr-3"></span> Spesifikasi & Kondisi
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                            <div>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">Merek / Type</p>
                                <p class="text-slate-800 font-bold text-xl">{{ $barang->merek ?? 'Tidak Ada Merek' }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-2">Status Kondisi</p>
                                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-[11px] font-black uppercase tracking-tight
                                    {{ $barang->kondisi == 'Baik' ? 'bg-green-100 text-green-700 ring-4 ring-green-50' :
                                       ($barang->kondisi == 'Rusak Ringan' ? 'bg-orange-100 text-orange-700 ring-4 ring-orange-50' : 'bg-red-100 text-red-700 ring-4 ring-red-50') }}">
                                    <span class="w-2 h-2 rounded-full bg-current mr-2 animate-pulse"></span>
                                    {{ $barang->kondisi }}
                                </span>
                            </div>
                        </div>
                    </section>

                    {{-- Nilai & Legalitas --}}
                    <section class="bg-slate-50 rounded-3xl p-8 border border-slate-100 relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-4 opacity-5">
                            <i class="fas fa-file-invoice-dollar text-6xl text-slate-900"></i>
                        </div>
                        <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-6 flex items-center italic text-slate-500">
                             Informasi Perolehan
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-8 gap-x-12">
                            <div>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">Tanggal Perolehan</p>
                                <p class="text-slate-700 font-bold text-lg leading-tight">{{ \Carbon\Carbon::parse($barang->tgl_peroleh)->translatedFormat('d F Y') }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">Nilai Perolehan (Aset)</p>
                                <p class="text-teal-600 font-black text-2xl font-mono tracking-tighter">Rp {{ number_format($barang->nilai_peroleh, 0, ',', '.') }}</p>
                            </div>
                            <div class="sm:col-span-2 pt-6 border-t border-slate-200/60">
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">Nomor SK PSP</p>
                                <p class="text-slate-700 font-semibold font-mono bg-white inline-block px-3 py-1 rounded-md border border-slate-100 shadow-sm">
                                    {{ $barang->nomor_sk_psp ?? 'Data SK Belum Diinput' }}
                                </p>
                            </div>
                        </div>
                    </section>

                    {{-- Lokasi & Admin --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-10 pt-4">
                         <section>
                            <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-5 italic">Lokasi Penempatan</h3>
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-white border border-slate-200 flex items-center justify-center text-teal-600 shadow-sm shrink-0">
                                    <i class="fas fa-door-open text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase">Ruangan</p>
                                    <p class="text-slate-800 font-extrabold text-lg leading-tight">{{ $barang->ruangan ?? 'Belum Ditentukan' }}</p>
                                    <p class="text-[11px] text-slate-500 italic mt-1 leading-relaxed">{{ $barang->lokasi }}</p>
                                </div>
                            </div>
                        </section>

                        <section>
                            <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-5 italic text-right sm:text-left">Admin Pengelola</h3>
                            <div class="flex items-center gap-4 bg-white p-3 rounded-2xl border border-slate-100 shadow-sm">
                                <div class="w-10 h-10 rounded-full bg-teal-600 flex items-center justify-center text-white font-black text-sm ring-4 ring-teal-50">
                                    {{ strtoupper(substr($barang->user->nama ?? 'S', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-slate-800 font-bold text-sm leading-none mb-1">{{ $barang->user->nama ?? 'System Admin' }}</p>
                                    <p class="text-[9px] text-slate-400 font-medium uppercase tracking-tighter italic">
                                        {{ \Carbon\Carbon::parse($barang->created_at)->translatedFormat('d M Y') }}
                                    </p>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>

                {{-- KOLOM KANAN: VISUAL --}}
                <div class="bg-slate-50/50 p-8 space-y-10">
                    {{-- Galeri --}}
                    <div>
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest italic">Dokumentasi Foto</h3>
                            <span class="bg-teal-500 text-white text-[9px] px-2 py-0.5 rounded-full font-bold">LIVE PREVIEW</span>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            @php
                                $fotos = is_array($barang->fotoBarang) ? $barang->fotoBarang : json_decode($barang->fotoBarang, true);
                            @endphp

                            @if($fotos && count($fotos) > 0)
                                @foreach($fotos as $foto)
                                <div class="group relative aspect-square rounded-2xl overflow-hidden shadow-lg border-2 border-white cursor-zoom-in" onclick="openModal('/storage/{{ $foto }}')">
                                    <img src="/storage/{{ $foto }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-125">
                                    <div class="absolute inset-0 bg-teal-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <i class="fas fa-expand text-white text-xl"></i>
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <div class="col-span-2 aspect-video bg-slate-200 rounded-2xl flex flex-col items-center justify-center border-2 border-dashed border-slate-300">
                                    <i class="fas fa-image text-slate-400 text-3xl mb-2"></i>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase">Foto tidak tersedia</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Map --}}
                    <div class="pt-8 border-t border-slate-200">
                        <div class="flex items-center mb-4">
                            <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>
                            <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest italic">Titik Koordinat</h3>
                        </div>
                        <div class="rounded-3xl overflow-hidden h-60 border-4 border-white shadow-2xl relative group">
                             <iframe width="100%" height="100%" frameborder="0" src="https://maps.google.com/maps?q={{ $barang->latitude }},{{ $barang->longitude }}&hl=id&z=17&output=embed"></iframe>
                             <div class="absolute bottom-3 left-3 right-3 bg-white/90 backdrop-blur px-3 py-2 rounded-xl border border-slate-200 shadow-sm opacity-0 group-hover:opacity-100 transition-opacity">
                                 <p class="text-[9px] font-mono text-slate-600 text-center leading-none">LAT: {{ $barang->latitude }}<br>LONG: {{ $barang->longitude }}</p>
                             </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL ZOOM --}}
<div id="photoModal" class="fixed inset-0 bg-slate-950/95 hidden z-[999] flex items-center justify-center p-6 transition-all duration-300 backdrop-blur-sm" onclick="closeModal()">
    <button class="absolute top-8 right-8 text-white/50 hover:text-white text-4xl transition-colors">
        <i class="fas fa-times-circle"></i>
    </button>
    <img id="modalImg" src="" class="max-w-full max-h-full rounded-2xl shadow-2xl border-4 border-white/10 scale-95 transition-transform duration-300">
</div>

<script>
    function openModal(src) {
        const modal = document.getElementById('photoModal');
        const img = document.getElementById('modalImg');
        img.src = src;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => img.classList.remove('scale-95'), 10);
        document.body.style.overflow = 'hidden';
    }
    function closeModal() {
        const modal = document.getElementById('photoModal');
        const img = document.getElementById('modalImg');
        img.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 200);
        document.body.style.overflow = 'auto';
    }
</script>
@endsection

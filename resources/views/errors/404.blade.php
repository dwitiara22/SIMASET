@extends('layouts.app', ['activePage' => 'error'])

@section('content')
<div class="min-h-[80vh] flex items-center justify-center p-6">
    <div class="max-w-md w-full text-center">
        {{-- Ilustrasi 404 --}}
        <div class="relative mb-8">
            <h1 class="text-[120px] font-extrabold text-slate-200 leading-none">404</h1>
            <div class="absolute inset-0 flex items-center justify-center">
                <i class="fas fa-search text-slate-400 text-4xl mt-8 opacity-50"></i>
            </div>
        </div>

        <h2 class="text-2xl font-bold text-slate-900 mb-3">Halaman Tidak Ditemukan</h2>
        <p class="text-slate-500 mb-8 font-medium">
            Maaf, halaman atau data barang yang Anda cari tidak tersedia atau telah dipindahkan.
        </p>

        <div class="grid grid-cols-2 gap-4">
            <a href="{{ url()->previous() }}" class="flex items-center justify-center px-6 py-3 border border-slate-200 rounded-2xl text-slate-600 font-bold hover:bg-white transition-all shadow-sm">
                <i class="fas fa-arrow-left mr-2 text-sm"></i> Kembali
            </a>
            <a href="{{ route('dashboard') }}" class="flex items-center justify-center px-6 py-3 bg-slate-900 rounded-2xl text-white font-bold hover:bg-slate-800 transition-all shadow-lg shadow-slate-200">
                <i class="fas fa-home mr-2 text-sm"></i> Dashboard
            </a>
        </div>

        {{-- Help Center Link --}}
        <div class="mt-12 pt-8 border-t border-slate-200 text-sm text-slate-400 font-medium">
            Butuh bantuan? Hubungi <a href="#" class="text-blue-500 hover:underline">Administrator Sistem</a>
        </div>
    </div>
</div>
@endsection

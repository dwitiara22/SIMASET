@extends('layouts.app', ['activePage' => 'dashboard'])

@section('content')
<div class="p-6 min-h-screen bg-slate-50">
    <div class="max-w-7xl mx-auto">
        {{-- Header --}}
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Dashboard Overview</h1>
                <p class="text-sm text-slate-500 mt-1">Pantau status inventaris real-time Anda.</p>
            </div>
            <div class="text-sm font-semibold text-slate-400 bg-white px-4 py-2 rounded-xl shadow-sm border border-slate-200">
                <i class="far fa-calendar-alt mr-2"></i> {{ date('d M Y') }}
            </div>
        </div>

        {{-- Row 1: Statistik Utama (6 Kolom agar muat Tahun Perolehan) --}}
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">

            {{-- Total Inventaris --}}
            <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm relative overflow-hidden">
                <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mb-3">
                    <i class="fas fa-boxes"></i>
                </div>
                <p class="text-slate-500 text-[11px] font-bold uppercase tracking-wider">Total Aset</p>
                <h3 class="text-2xl font-bold text-slate-900">{{ $barangs->count() }}</h3>
            </div>

            {{-- Pengadaan Tahun Ini (NEW) --}}
            <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm">
                <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center mb-3">
                    <i class="fas fa-calendar-plus"></i>
                </div>
                <p class="text-slate-500 text-[11px] font-bold uppercase tracking-wider">Tahun {{ date('Y') }}</p>
                <h3 class="text-2xl font-bold text-slate-900">
                    {{ $barangs->filter(fn($item) => \Carbon\Carbon::parse($item->tgl_peroleh)->year == date('Y'))->count() }}
                </h3>
            </div>

            {{-- Nilai Perolehan --}}
            <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm">
                <div class="w-10 h-10 bg-teal-50 text-teal-600 rounded-xl flex items-center justify-center mb-3">
                    <i class="fas fa-money-check-alt"></i>
                </div>
                <p class="text-slate-500 text-[11px] font-bold uppercase tracking-wider">Nilai Aset</p>
                <h3 class="text-sm font-bold text-slate-900">Rp{{ number_format($barangs->sum('nilai_peroleh'), 0, ',', '.') }}</h3>
            </div>

            {{-- Kondisi Baik --}}
            <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm border-l-4 border-l-green-500">
                <p class="text-slate-500 text-[11px] font-bold uppercase tracking-wider mb-2">Kondisi Baik</p>
                <h3 class="text-2xl font-bold text-slate-900">{{ $barangs->where('kondisi', 'Baik')->count() }}</h3>
                <div class="mt-3 w-full bg-slate-100 h-1.5 rounded-full">
                    <div class="bg-green-500 h-1.5 rounded-full" style="width: {{ $barangs->count() > 0 ? ($barangs->where('kondisi', 'Baik')->count() / $barangs->count()) * 100 : 0 }}%"></div>
                </div>
            </div>

            {{-- Rusak Ringan --}}
            <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm border-l-4 border-l-amber-400">
                <p class="text-slate-500 text-[11px] font-bold uppercase tracking-wider mb-2">Rusak Ringan</p>
                <h3 class="text-2xl font-bold text-slate-900">{{ $barangs->where('kondisi', 'Rusak Ringan')->count() }}</h3>
                <div class="mt-3 w-full bg-slate-100 h-1.5 rounded-full">
                    <div class="bg-amber-400 h-1.5 rounded-full" style="width: {{ $barangs->count() > 0 ? ($barangs->where('kondisi', 'Rusak Ringan')->count() / $barangs->count()) * 100 : 0 }}%"></div>
                </div>
            </div>

            {{-- Rusak Berat --}}
            <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm border-l-4 border-l-red-500">
                <p class="text-slate-500 text-[11px] font-bold uppercase tracking-wider mb-2">Rusak Berat</p>
                <h3 class="text-2xl font-bold text-slate-900">{{ $barangs->where('kondisi', 'Rusak Berat')->count() }}</h3>
                <div class="mt-3 w-full bg-slate-100 h-1.5 rounded-full">
                    <div class="bg-red-500 h-1.5 rounded-full" style="width: {{ $barangs->count() > 0 ? ($barangs->where('kondisi', 'Rusak Berat')->count() / $barangs->count()) * 100 : 0 }}%"></div>
                </div>
            </div>
        </div>

        {{-- Row 2: GRAFIK (Tambah Grafik Tren Tahunan) --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            {{-- Tren Perolehan Per Tahun (NEW) --}}
            <div class="lg:col-span-2 bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
                <h3 class="font-bold text-slate-800 uppercase text-xs tracking-widest mb-6">Tren Pengadaan Barang (Per Tahun)</h3>
                <canvas id="yearlyTrendChart" height="120"></canvas>
            </div>

            {{-- Komposisi Kondisi Aset --}}
            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
                <h3 class="font-bold text-slate-800 uppercase text-xs tracking-widest mb-6 text-center">Kondisi Aset</h3>
                <div class="max-w-[220px] mx-auto">
                    <canvas id="conditionChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Row 3: Tabel & Ruangan --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="font-bold text-slate-800">Aktivitas Terakhir</h3>
                    <a href="{{ route('Barang.index') }}" class="text-blue-600 text-xs font-bold hover:underline">Lihat Semua</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 text-[10px] uppercase font-bold text-slate-400">
                            <tr>
                                <th class="px-6 py-3">Nama Barang</th>
                                <th class="px-6 py-3">Tahun Peroleh</th>
                                <th class="px-6 py-3">Kondisi</th>
                                <th class="px-6 py-3 text-right">Nilai</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($barangs->sortByDesc('created_at')->take(5) as $item)
                            <tr class="hover:bg-slate-50/50 transition-colors text-sm">
                                <td class="px-6 py-4 font-bold text-slate-700">{{ $item->nama_barang }}</td>
                                <td class="px-6 py-4 text-slate-500">{{ \Carbon\Carbon::parse($item->tgl_peroleh)->format('Y') }}</td>
                                <td class="px-6 py-4">
                                    <span class="text-[9px] font-bold px-2 py-1 rounded-full {{ $item->kondisi == 'Baik' ? 'bg-green-100 text-green-700' : ($item->kondisi == 'Rusak Ringan' ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700') }}">
                                        {{ strtoupper($item->kondisi) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-bold text-slate-700 text-right">Rp{{ number_format($item->nilai_peroleh, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Distribusi Ruangan (Tetap ada) --}}
            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
                <h3 class="font-bold text-slate-800 mb-6 uppercase text-xs tracking-widest">Distribusi Ruangan</h3>
                <canvas id="locationChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // 1. Chart Kondisi (Doughnut)
    const ctxCondition = document.getElementById('conditionChart').getContext('2d');
    new Chart(ctxCondition, {
        type: 'doughnut',
        data: {
            labels: ['Baik', 'Rusak Ringan', 'Rusak Berat'],
            datasets: [{
                data: [{{ $kondisiStats['Baik'] ?? 0 }}, {{ $kondisiStats['Rusak Ringan'] ?? 0 }}, {{ $kondisiStats['Rusak Berat'] ?? 0 }}],
                backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
                borderWidth: 0
            }]
        },
        options: { cutout: '75%', plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, font: { size: 10 } } } } }
    });

    // 2. Chart Lokasi (Bar)
    const ctxLocation = document.getElementById('locationChart').getContext('2d');
    new Chart(ctxLocation, {
        type: 'bar',
        data: {
            labels: {!! json_encode($lokasiStats->keys()) !!},
            datasets: [{
                label: 'Unit',
                data: {!! json_encode($lokasiStats->values()) !!},
                backgroundColor: '#3b82f6',
                borderRadius: 5
            }]
        },
        options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
    });

    // 3. Chart Tren Tahunan (Line Chart) - NEW
    @php
        $yearlyData = $barangs->groupBy(fn($item) => \Carbon\Carbon::parse($item->tgl_peroleh)->format('Y'))->map->count()->sortKeys();
    @endphp
    const ctxYearly = document.getElementById('yearlyTrendChart').getContext('2d');
    new Chart(ctxYearly, {
        type: 'line',
        data: {
            labels: {!! json_encode($yearlyData->keys()) !!},
            datasets: [{
                label: 'Jumlah Pengadaan',
                data: {!! json_encode($yearlyData->values()) !!},
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99, 102, 241, 0.1)',
                fill: true,
                tension: 0.4,
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } }
            }
        }
    });
</script>
@endsection

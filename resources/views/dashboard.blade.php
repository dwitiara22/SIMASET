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

        {{-- Row 1: Statistik Utama (Cards) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm relative overflow-hidden group">
                <div class="absolute right-[-10px] top-[-10px] opacity-5 group-hover:scale-110 transition-transform">
                    <i class="fas fa-boxes fa-6x text-blue-600"></i>
                </div>
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-4">
                    <i class="fas fa-boxes fa-lg"></i>
                </div>
                <p class="text-slate-500 text-sm font-medium">Total Inventaris</p>
                <h3 class="text-3xl font-bold text-slate-900">{{ $barangs->count() }} <span class="text-sm font-normal text-slate-400">Unit</span></h3>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm relative overflow-hidden group">
                <div class="w-12 h-12 bg-teal-50 text-teal-600 rounded-2xl flex items-center justify-center mb-4">
                    <i class="fas fa-money-check-alt fa-lg"></i>
                </div>
                <p class="text-slate-500 text-sm font-medium">Nilai Perolehan</p>
                <h3 class="text-2xl font-bold text-slate-900">Rp {{ number_format($barangs->sum('nilai_peroleh'), 0, ',', '.') }}</h3>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm border-l-4 border-l-green-500 group">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-slate-500 text-sm font-medium">Kondisi Layak</p>
                        <h3 class="text-3xl font-bold text-slate-900">{{ $barangs->where('kondisi', 'Baik')->count() }}</h3>
                    </div>
                    <div class="text-green-500 bg-green-50 p-2 rounded-lg"><i class="fas fa-check"></i></div>
                </div>
                <div class="mt-4 w-full bg-slate-100 h-1.5 rounded-full">
                    <div class="bg-green-500 h-1.5 rounded-full" style="width: {{ $barangs->count() > 0 ? ($barangs->where('kondisi', 'Baik')->count() / $barangs->count()) * 100 : 0 }}%"></div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm border-l-4 border-l-red-500 group">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-slate-500 text-sm font-medium">Rusak Berat</p>
                        <h3 class="text-3xl font-bold text-slate-900">{{ $barangs->where('kondisi', 'Rusak Berat')->count() }}</h3>
                    </div>
                    <div class="text-red-500 bg-red-50 p-2 rounded-lg"><i class="fas fa-times"></i></div>
                </div>
                <div class="mt-4 w-full bg-slate-100 h-1.5 rounded-full">
                    <div class="bg-red-500 h-1.5 rounded-full" style="width: {{ $barangs->count() > 0 ? ($barangs->where('kondisi', 'Rusak Berat')->count() / $barangs->count()) * 100 : 0 }}%"></div>
                </div>
            </div>
        </div>

        {{-- Row 2: GRAFIK --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-bold text-slate-800 italic uppercase text-sm tracking-widest">Distribusi Ruangan</h3>
                    <i class="fas fa-chart-bar text-slate-300"></i>
                </div>
                <canvas id="locationChart" height="250"></canvas>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-bold text-slate-800 italic uppercase text-sm tracking-widest">Status Kondisi Aset</h3>
                    <i class="fas fa-chart-pie text-slate-300"></i>
                </div>
                <div class="max-w-[280px] mx-auto">
                    <canvas id="conditionChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Row 3: Tabel Activity & Rooms --}}
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
                                <th class="px-6 py-3">Barang</th>
                                <th class="px-6 py-3">Ruangan</th>
                                <th class="px-6 py-3">Status</th>
                                <th class="px-6 py-3 text-right">Nilai</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($barangs->sortByDesc('created_at')->take(5) as $item)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center font-bold text-slate-400 text-xs">
                                            {{ substr($item->nama_barang, 0, 1) }}
                                        </div>
                                        <div class="text-sm font-bold text-slate-700">{{ $item->nama_barang }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-500">{{ $item->ruangan ?? 'N/A' }}</td>
                                <td class="px-6 py-4">
                                    <span class="text-[9px] font-bold px-2 py-1 rounded-full {{ $item->kondisi == 'Baik' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ strtoupper($item->kondisi) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm font-bold text-slate-700 text-right">
                                    Rp {{ number_format($item->nilai_peroleh, 0, ',', '.') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
                <h3 class="font-bold text-slate-800 mb-6">Padat Ruangan</h3>
                <div class="space-y-6">
                    @foreach($barangs->groupBy('ruangan')->take(5) as $ruangan => $items)
                    <div>
                        <div class="flex justify-between text-xs font-bold mb-2">
                            <span class="text-slate-600">{{ $ruangan ?? 'Umum' }}</span>
                            <span class="text-slate-400">{{ $items->count() }} Unit</span>
                        </div>
                        <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                            <div class="bg-blue-500 h-full" style="width: {{ ($items->count() / $barangs->count()) * 100 }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Scripts untuk Grafik --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // 1. Chart Kondisi (Doughnut)
    const ctxCondition = document.getElementById('conditionChart').getContext('2d');
    new Chart(ctxCondition, {
        type: 'doughnut',
        data: {
            labels: ['Baik', 'Rusak Ringan', 'Rusak Berat'],
            datasets: [{
                data: [
                    {{ $kondisiStats['Baik'] }},
                    {{ $kondisiStats['Rusak Ringan'] }},
                    {{ $kondisiStats['Rusak Berat'] }}
                ],
                backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            cutout: '70%',
            plugins: {
                legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } }
            }
        }
    });

    // 2. Chart Lokasi (Bar)
    const ctxLocation = document.getElementById('locationChart').getContext('2d');
    new Chart(ctxLocation, {
        type: 'bar',
        data: {
            labels: {!! json_encode($lokasiStats->keys()) !!},
            datasets: [{
                label: 'Jumlah Barang',
                data: {!! json_encode($lokasiStats->values()) !!},
                backgroundColor: '#3b82f6',
                borderRadius: 8,
                barThickness: 25
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true, grid: { display: false } },
                x: { grid: { display: false } }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });
</script>
@endsection

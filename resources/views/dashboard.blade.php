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

        {{-- Row 1: Statistik Utama (Cards) - Sekarang menjadi 5 Kolom atau tetap 4 dengan Grid Responsif --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">

            {{-- Total Inventaris --}}
            <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm relative overflow-hidden group">
                <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mb-3">
                    <i class="fas fa-boxes"></i>
                </div>
                <p class="text-slate-500 text-[11px] font-bold uppercase tracking-wider">Total Aset</p>
                <h3 class="text-2xl font-bold text-slate-900">{{ $barangs->count() }} <span class="text-xs font-normal text-slate-400">Unit</span></h3>
            </div>

            {{-- Nilai Perolehan --}}
            <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm">
                <div class="w-10 h-10 bg-teal-50 text-teal-600 rounded-xl flex items-center justify-center mb-3">
                    <i class="fas fa-money-check-alt"></i>
                </div>
                <p class="text-slate-500 text-[11px] font-bold uppercase tracking-wider">Nilai Aset</p>
                <h3 class="text-lg font-bold text-slate-900">Rp{{ number_format($barangs->sum('nilai_peroleh'), 0, ',', '.') }}</h3>
            </div>

            {{-- Kondisi Baik --}}
            <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm border-l-4 border-l-green-500">
                <div class="flex justify-between items-start mb-2">
                    <p class="text-slate-500 text-[11px] font-bold uppercase tracking-wider">Kondisi Baik</p>
                    <i class="fas fa-check-circle text-green-500"></i>
                </div>
                <h3 class="text-2xl font-bold text-slate-900">{{ $barangs->where('kondisi', 'Baik')->count() }}</h3>
                <div class="mt-3 w-full bg-slate-100 h-1.5 rounded-full">
                    <div class="bg-green-500 h-1.5 rounded-full" style="width: {{ $barangs->count() > 0 ? ($barangs->where('kondisi', 'Baik')->count() / $barangs->count()) * 100 : 0 }}%"></div>
                </div>
            </div>

            {{-- Kondisi Rusak Ringan --}}
            <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm border-l-4 border-l-amber-400">
                <div class="flex justify-between items-start mb-2">
                    <p class="text-slate-500 text-[11px] font-bold uppercase tracking-wider">Rusak Ringan</p>
                    <i class="fas fa-exclamation-triangle text-amber-400"></i>
                </div>
                <h3 class="text-2xl font-bold text-slate-900">{{ $barangs->where('kondisi', 'Rusak Ringan')->count() }}</h3>
                <div class="mt-3 w-full bg-slate-100 h-1.5 rounded-full">
                    <div class="bg-amber-400 h-1.5 rounded-full" style="width: {{ $barangs->count() > 0 ? ($barangs->where('kondisi', 'Rusak Ringan')->count() / $barangs->count()) * 100 : 0 }}%"></div>
                </div>
            </div>

            {{-- Kondisi Rusak Berat --}}
            <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm border-l-4 border-l-red-500">
                <div class="flex justify-between items-start mb-2">
                    <p class="text-slate-500 text-[11px] font-bold uppercase tracking-wider">Rusak Berat</p>
                    <i class="fas fa-times-circle text-red-500"></i>
                </div>
                <h3 class="text-2xl font-bold text-slate-900">{{ $barangs->where('kondisi', 'Rusak Berat')->count() }}</h3>
                <div class="mt-3 w-full bg-slate-100 h-1.5 rounded-full">
                    <div class="bg-red-500 h-1.5 rounded-full" style="width: {{ $barangs->count() > 0 ? ($barangs->where('kondisi', 'Rusak Berat')->count() / $barangs->count()) * 100 : 0 }}%"></div>
                </div>
            </div>
        </div>

        {{-- Row 2: GRAFIK --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm text-center">
                <h3 class="font-bold text-slate-800 uppercase text-xs tracking-widest mb-6">Distribusi Barang per Ruangan</h3>
                <canvas id="locationChart" height="250"></canvas>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
                <h3 class="font-bold text-slate-800 uppercase text-xs tracking-widest mb-6 text-center">Komposisi Kondisi Aset</h3>
                <div class="max-w-[280px] mx-auto">
                    <canvas id="conditionChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Row 3: Tabel Activity --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="font-bold text-slate-800">Aktivitas Input Terakhir</h3>
                    <a href="{{ route('Barang.index') }}" class="text-blue-600 text-xs font-bold hover:underline">Lihat Semua</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 text-[10px] uppercase font-bold text-slate-400">
                            <tr>
                                <th class="px-6 py-3">Barang</th>
                                <th class="px-6 py-3">Ruangan</th>
                                <th class="px-6 py-3">Status Kondisi</th>
                                <th class="px-6 py-3 text-right">Nilai Perolehan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($barangs->sortByDesc('created_at')->take(5) as $item)
                            <tr class="hover:bg-slate-50/50 transition-colors text-sm">
                                <td class="px-6 py-4">
                                    <span class="font-bold text-slate-700">{{ $item->nama_barang }}</span>
                                </td>
                                <td class="px-6 py-4 text-slate-500">{{ $item->ruangan ?? 'Umum' }}</td>
                                <td class="px-6 py-4">
                                    @if($item->kondisi == 'Baik')
                                        <span class="text-[9px] font-bold px-2 py-1 rounded-full bg-green-100 text-green-700">BAIK</span>
                                    @elseif($item->kondisi == 'Rusak Ringan')
                                        <span class="text-[9px] font-bold px-2 py-1 rounded-full bg-amber-100 text-amber-700">RUSAK RINGAN</span>
                                    @else
                                        <span class="text-[9px] font-bold px-2 py-1 rounded-full bg-red-100 text-red-700">RUSAK BERAT</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 font-bold text-slate-700 text-right">
                                    Rp{{ number_format($item->nilai_peroleh, 0, ',', '.') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
                <h3 class="font-bold text-slate-800 mb-6">Persentase per Ruangan</h3>
                <div class="space-y-6">
                    @foreach($barangs->groupBy('ruangan')->take(5) as $ruangan => $items)
                    <div>
                        <div class="flex justify-between text-xs font-bold mb-2">
                            <span class="text-slate-600">{{ $ruangan ?? 'Lainnya' }}</span>
                            <span class="text-slate-400">{{ $items->count() }} Unit</span>
                        </div>
                        <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                            <div class="bg-blue-500 h-full" style="width: {{ $barangs->count() > 0 ? ($items->count() / $barangs->count()) * 100 : 0 }}%"></div>
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
                    {{ $kondisiStats['Baik'] ?? 0 }},
                    {{ $kondisiStats['Rusak Ringan'] ?? 0 }},
                    {{ $kondisiStats['Rusak Berat'] ?? 0 }}
                ],
                backgroundColor: ['#10b981', '#f59e0b', '#ef4444'], // Hijau, Amber, Merah
                borderWidth: 0,
                hoverOffset: 15
            }]
        },
        options: {
            cutout: '75%',
            plugins: {
                legend: { position: 'bottom', labels: { usePointStyle: true, padding: 25, font: { size: 11, weight: 'bold' } } }
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
                label: 'Jumlah Unit',
                data: {!! json_encode($lokasiStats->values()) !!},
                backgroundColor: '#3b82f6',
                borderRadius: 10,
                barThickness: 20
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { font: { size: 10 } } },
                x: { grid: { display: false }, ticks: { font: { size: 10, weight: 'bold' } } }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });
</script>
@endsection

<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class DashboarController extends Controller
{
   public function index()
{
    $barangs = Barang::all();

    // Data untuk Grafik Kondisi (Pie/Doughnut Chart)
    $kondisiStats = [
        'Baik' => $barangs->where('kondisi', 'Baik')->count(),
        'Rusak Ringan' => $barangs->where('kondisi', 'Rusak Ringan')->count(),
        'Rusak Berat' => $barangs->where('kondisi', 'Rusak Berat')->count(),
    ];

    // Data untuk Grafik Lokasi (Bar Chart)
    $lokasiStats = $barangs->groupBy('ruangan')->map->count();

    return view('dashboard', compact('barangs', 'kondisiStats', 'lokasiStats'));
}
}

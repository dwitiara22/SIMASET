<?php

namespace App\Http\Controllers\Barang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\BarangsExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class BarangExportController extends Controller
{
    public function exportDownload()
    {
        return Excel::download(new BarangsExport, 'data-barang.xlsx');
    }

    public function exportToServer()
    {
        $filename = 'data-barang-' . now()->format('Ymd_His') . '.xlsx';
        $path = 'exports/' . $filename;

        Excel::store(new BarangsExport, $path, 'public');

        // Redirect ke Google Drive (manual upload)
        return redirect()->away('https://drive.google.com/drive/my-drive');
    }

}

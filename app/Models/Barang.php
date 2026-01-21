<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Barang extends Model
{
        protected $fillable = [
        'user_id', 'nama_barang', 'kode_barang', 'nup', 'kondisi',
        'merek', 'tgl_peroleh', 'nilai_peroleh', 'nomor_sk_psp',
        'lokasi', 'ruangan', 'latitude', 'longitude', 'fotoBarang'
    ];

    // Penting: Ubah kolom fotoBarang menjadi array/json secara otomatis
    protected $casts = [
        'fotoBarang' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

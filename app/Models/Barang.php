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

     public function getStatusKelengkapanAttribute()
    {
        $wajib = [
            $this->kode_barang,
            $this->nup,
            $this->nomor_sk_psp,
            $this->ruangan,
            $this->lokasi,
            $this->latitude,
            $this->longitude,
        ];

        foreach ($wajib as $item) {
            if (is_null($item) || $item === '') {
                return 'Tidak Lengkap';
            }
        }

        return 'Lengkap';
    }

    // ===============================
    // SCOPE FILTER
    // ===============================
    public function scopeLengkap($query)
    {
        return $query
            ->whereNotNull('kode_barang')
            ->whereNotNull('nup')
            ->whereNotNull('nomor_sk_psp')
            ->whereNotNull('ruangan')
            ->whereNotNull('lokasi')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude');
    }

    public function scopeTidakLengkap($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('kode_barang')
              ->orWhereNull('nup')
              ->orWhereNull('nomor_sk_psp')
              ->orWhereNull('ruangan')
              ->orWhereNull('lokasi')
              ->orWhereNull('latitude')
              ->orWhereNull('longitude');
        });
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

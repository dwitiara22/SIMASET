<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barangs', function (Blueprint $table) {
            // Perbaikan di sini: Hapus $table yang ganda
            $table->id();

            // Relasi User
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Identitas & Legalitas
            $table->string('nama_barang');
            $table->string('kode_barang', 50);
            $table->string('nup', 20);
            $table->enum('kondisi', ['Baik', 'Rusak Ringan', 'Rusak Berat']);
            $table->string('merek')->nullable();
            $table->date('tgl_peroleh');
            $table->decimal('nilai_peroleh', 15, 2);
            $table->string('nomor_sk_psp')->nullable();

            // Lokasi & Koordinat
            $table->string('lokasi')->nullable();
            $table->string('ruangan')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();

            // Foto Barang (Gunakan text untuk menyimpan path foto dalam format JSON)
            $table->text('fotoBarang');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};

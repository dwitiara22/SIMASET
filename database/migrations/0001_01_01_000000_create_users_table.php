<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // ROLE
            $table->tinyInteger('role')
                  ->comment('1=Super Admin, 2=Admin, 3=Pengajuan');

            // DATA USER
            $table->string('nip', 30)->unique();
            $table->string('nama', 100);
            $table->string('no_hp', 20)->nullable();
            $table->string('email')->unique();
            $table->string('jabatan', 100)->nullable();
            $table->string('foto_profil')->nullable();

            // AUTH
            $table->string('password');

            $table->timestamps();
        });

        // DEFAULT SUPER ADMIN
        DB::table('users')->insert([
            'role' => 1, // SUPER ADMIN
            'nip' => 'SA001',
            'nama' => 'Super Admin',
            'no_hp' => '081234567890',
            'email' => 'admin@gmail.com',
            'jabatan' => 'Super Administrator',
            'foto_profil' => null,
            'password' => Hash::make('123456'), // ganti setelah login
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

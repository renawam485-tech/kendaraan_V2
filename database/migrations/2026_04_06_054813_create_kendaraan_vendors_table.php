<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kendaraan_vendors', function (Blueprint $table) {
            $table->id();
            $table->string('nama_vendor'); // Contoh: TRAC, Gocar, GrabCar
            $table->string('nama_kendaraan'); // Contoh: Toyota Hiace, Innova
            $table->string('plat_nomor')->nullable()->unique(); // Bisa kosong jika pesan taksi online acak
            $table->integer('kapasitas_penumpang')->nullable();
            $table->enum('status_kendaraan', ['Tersedia', 'Tidak Tersedia'])->default('Tersedia');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kendaraan_vendors');
    }
};
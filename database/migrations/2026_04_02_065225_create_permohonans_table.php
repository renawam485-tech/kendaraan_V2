<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('permohonans', function (Blueprint $table) {
            $table->id();

            // --- INPUT PENGGUNA (LANGKAH 1) ---
            // Jika user dihapus, permohonannya ikut terhapus (cascade)
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('nama_pic');
            $table->string('kontak_pic');
            $table->string('kendaraan_dibutuhkan');
            $table->string('titik_jemput');
            $table->string('tujuan');
            $table->dateTime('waktu_berangkat');
            $table->dateTime('waktu_kembali');
            $table->integer('jumlah_penumpang');
            $table->string('file_surat_penugasan'); // Path file PDF/JPG
            $table->string('anggaran_diajukan');

            // --- PROSES KEPALA ADMIN (LANGKAH 2) ---
            $table->enum('kategori_kegiatan', ['Dinas SITH', 'Non SITH'])->nullable();
            $table->text('rekomendasi_admin')->nullable();

            // --- PROSES KASUBAG SPSI (LANGKAH 3) ---
            // Jika kendaraan dihapus, ubah jadi NULL (agar riwayat tidak hilang)
            $table->foreignId('kendaraan_id')->nullable()->constrained('kendaraans')->nullOnDelete();
            $table->foreignId('pengemudi_id')->nullable()->constrained('pengemudis')->nullOnDelete();
            $table->decimal('estimasi_biaya_operasional', 15, 2)->nullable();

            // --- PROSES KASUBAG KEUANGAN (LANGKAH 4) ---
            $table->decimal('rab_disetujui', 15, 2)->nullable();
            $table->string('mekanisme_pembayaran')->nullable();

            // --- STATUS TRACKING ---
            $table->enum('status_permohonan', [
    'Menunggu Validasi Admin', 
    'Menunggu Proses SPSI', 
    'Menunggu Proses Keuangan', 
    'Menunggu Finalisasi', 
    'Disetujui', 
    'Ditolak', 
    'Selesai',
    'Menunggu Pengembalian Dana',       // <--- Tambahan Baru
    'Menunggu Verifikasi Pengembalian'  // <--- Tambahan Baru
])->default('Menunggu Validasi Admin');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permohonans');
    }
};

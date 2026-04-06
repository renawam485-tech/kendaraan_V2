<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('permohonans', function (Blueprint $table) {
            // 1. Hapus kolom string yang lama
            $table->dropColumn('kendaraan_vendor');
            
            // 2. Tambahkan kolom relasi (Foreign Key) yang baru
            $table->foreignId('kendaraan_vendor_id')
                  ->nullable()
                  ->after('kendaraan_id')
                  ->constrained('kendaraan_vendors')
                  ->nullOnDelete(); // Mencegah error jika data vendor dihapus
        });
    }

    public function down(): void
    {
        Schema::table('permohonans', function (Blueprint $table) {
            // Rollback: Hapus relasi dan kembalikan kolom string
            $table->dropForeign(['kendaraan_vendor_id']);
            $table->dropColumn('kendaraan_vendor_id');
            $table->string('kendaraan_vendor')->nullable()->after('kendaraan_id');
        });
    }
};
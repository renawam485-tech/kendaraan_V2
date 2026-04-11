<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('permohonans', function (Blueprint $table) {
            // Ubah enum → string agar ekstensibel & kompatibel SQLite/MySQL
            $table->string('status_permohonan')
                ->default('Menunggu Validasi Admin')
                ->change();

            $table->timestamp('waktu_serah_terima')->nullable()->after('status_permohonan');
            $table->timestamp('waktu_mulai_perjalanan')->nullable()->after('waktu_serah_terima');
        });
    }

    public function down(): void
    {
        Schema::table('permohonans', function (Blueprint $table) {
            $table->dropColumn(['waktu_serah_terima', 'waktu_mulai_perjalanan']);
        });
    }
};
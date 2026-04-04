<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('permohonans', function (Blueprint $table) {
            $table->text('catatan_pemohon')->nullable()->after('anggaran_diajukan');
        });
    }
    public function down()
    {
        Schema::table('permohonans', function (Blueprint $table) {
            $table->dropColumn('catatan_pemohon');
        });
    }
};
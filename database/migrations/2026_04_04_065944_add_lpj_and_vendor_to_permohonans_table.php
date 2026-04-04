<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('permohonans', function (Blueprint $table) {
            $table->string('kendaraan_vendor')->nullable()->after('kendaraan_id');
            $table->integer('biaya_aktual')->nullable()->after('rab_disetujui');
            $table->string('bukti_lpj')->nullable()->after('biaya_aktual');
            $table->string('bukti_pengembalian')->nullable()->after('bukti_lpj');
        });
    }
    public function down()
    {
        Schema::table('permohonans', function (Blueprint $table) {
            $table->dropColumn(['kendaraan_vendor', 'biaya_aktual', 'bukti_lpj', 'bukti_pengembalian']);
        });
    }
};

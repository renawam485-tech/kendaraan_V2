<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KendaraanVendor extends Model
{
    use HasFactory;

    protected $table = 'kendaraan_vendors';

    protected $fillable = [
        'nama_vendor',
        'nama_kendaraan',
        'plat_nomor',
        'kapasitas_penumpang',
        'status_kendaraan',
    ];

    // Relasi: Satu vendor bisa dipakai di banyak permohonan
    public function permohonans()
    {
        return $this->hasMany(Permohonan::class, 'kendaraan_vendor_id');
    }
}
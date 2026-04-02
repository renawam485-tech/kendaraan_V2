<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kendaraan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kendaraan', 'plat_nomor', 'kapasitas_penumpang', 'status_kendaraan'
    ];

    public function permohonans()
    {
        return $this->hasMany(Permohonan::class);
    }
}
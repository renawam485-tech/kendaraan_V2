<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengemudi extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_pengemudi', 'kontak', 'status_pengemudi'
    ];

    public function permohonans()
    {
        return $this->hasMany(Permohonan::class);
    }
}
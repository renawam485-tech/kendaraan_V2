<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permohonan extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_permohonan','user_id', 'nama_pic', 'kontak_pic', 'kendaraan_dibutuhkan', 
        'titik_jemput', 'tujuan', 'waktu_berangkat', 'waktu_kembali', 
        'jumlah_penumpang', 'file_surat_penugasan', 'anggaran_diajukan','catatan_pemohon',
        'kategori_kegiatan', 'rekomendasi_admin',
        'kendaraan_id', 'pengemudi_id', 'estimasi_biaya_operasional',
        'rab_disetujui', 'mekanisme_pembayaran', 'status_permohonan'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class);
    }

    public function pengemudi()
    {
        return $this->belongsTo(Pengemudi::class);
    }

    public function kendaraanVendor()
    {
        return $this->belongsTo(KendaraanVendor::class, 'kendaraan_vendor_id');
    }
}
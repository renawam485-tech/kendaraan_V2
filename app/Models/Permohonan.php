<?php

namespace App\Models;

use App\Enums\StatusPermohonan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permohonan extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_permohonan',
        'user_id',
        'nama_pic',
        'kontak_pic',
        'kendaraan_dibutuhkan',
        'titik_jemput',
        'tujuan',
        'waktu_berangkat',
        'waktu_kembali',
        'jumlah_penumpang',
        'file_surat_penugasan',
        'anggaran_diajukan',
        'catatan_pemohon',
        'alasan_penolakan',
        'kategori_kegiatan',
        'rekomendasi_admin',
        'kendaraan_id',
        'kendaraan_vendor_id',   
        'pengemudi_id',
        'estimasi_biaya_operasional',
        'rab_disetujui',
        'mekanisme_pembayaran',
        'bukti_lpj',              
        'biaya_aktual',           
        'bukti_pengembalian',     
        'status_permohonan',
        'waktu_serah_terima',
        'waktu_mulai_perjalanan',
        'waktu_kembali_aktual',
    ];

    protected function casts(): array
    {
        return [
            'status_permohonan'      => StatusPermohonan::class,
            'waktu_berangkat'        => 'datetime',
            'waktu_kembali'          => 'datetime',
            'waktu_serah_terima'     => 'datetime',
            'waktu_mulai_perjalanan' => 'datetime',
            'waktu_kembali_aktual'   => 'datetime',
        ];
    }

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
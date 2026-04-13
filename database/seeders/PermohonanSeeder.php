<?php

namespace Database\Seeders;

use App\Enums\StatusPermohonan;
use App\Models\Permohonan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PermohonanSeeder extends Seeder
{
    public function run(): void
    {
        $pengguna = User::where('role', 'pengguna')->get();

        if ($pengguna->isEmpty()) {
            $this->command->warn('Tidak ada user dengan role pengguna. Jalankan DatabaseSeeder terlebih dahulu.');
            return;
        }

        $dummyData = [
            [
                'nama_pic'             => 'Budi Santoso',
                'kontak_pic'           => '+6281234567891',
                'kendaraan_dibutuhkan' => 'Toyota Hiace',
                'titik_jemput'         => 'Gedung Rektorat Lt. 1',
                'tujuan'               => 'Kementerian Pendidikan, Jakarta',
                'waktu_berangkat'      => Carbon::now()->addDays(3)->setTime(7, 0),
                'waktu_kembali'        => Carbon::now()->addDays(3)->setTime(18, 0),
                'jumlah_penumpang'     => 12,
                'anggaran_diajukan'    => 'BBM Rp 300.000, Tol Rp 150.000, Parkir Rp 50.000 → Total ±Rp 500.000',
                'catatan_pemohon'      => 'Mohon kendaraan besar karena rombongan banyak.',
            ],
            [
                'nama_pic'             => 'Siti Rahayu',
                'kontak_pic'           => '+6281234567892',
                'kendaraan_dibutuhkan' => 'Toyota Innova',
                'titik_jemput'         => 'Laboratorium Teknik Sipil',
                'tujuan'               => 'Universitas Gadjah Mada, Yogyakarta',
                'waktu_berangkat'      => Carbon::now()->addDays(5)->setTime(6, 30),
                'waktu_kembali'        => Carbon::now()->addDays(6)->setTime(20, 0),
                'jumlah_penumpang'     => 6,
                'anggaran_diajukan'    => 'BBM Rp 400.000, Tol Rp 200.000 → Total ±Rp 600.000',
                'catatan_pemohon'      => 'Perjalanan 2 hari untuk seminar nasional.',
            ],
            [
                'nama_pic'             => 'Ahmad Fauzi',
                'kontak_pic'           => '+6281234567893',
                'kendaraan_dibutuhkan' => 'Honda CR-V',
                'titik_jemput'         => 'Kantor Dekan Fakultas Teknik',
                'tujuan'               => 'Balai Penelitian Bogor',
                'waktu_berangkat'      => Carbon::now()->addDays(2)->setTime(8, 0),
                'waktu_kembali'        => Carbon::now()->addDays(2)->setTime(17, 0),
                'jumlah_penumpang'     => 3,
                'anggaran_diajukan'    => 'BBM Rp 150.000, Tol Rp 75.000 → Total ±Rp 225.000',
                'catatan_pemohon'      => null,
            ],
            [
                'nama_pic'             => 'Dewi Lestari',
                'kontak_pic'           => '+6281234567894',
                'kendaraan_dibutuhkan' => 'Toyota Hiace',
                'titik_jemput'         => 'Aula Serbaguna Gedung A',
                'tujuan'               => 'Kawasan Industri Pulogadung, Jakarta Timur',
                'waktu_berangkat'      => Carbon::now()->addDays(7)->setTime(7, 30),
                'waktu_kembali'        => Carbon::now()->addDays(7)->setTime(16, 30),
                'jumlah_penumpang'     => 14,
                'anggaran_diajukan'    => 'BBM Rp 250.000, Tol Rp 100.000, Parkir Rp 30.000 → Total ±Rp 380.000',
                'catatan_pemohon'      => 'Kunjungan industri mahasiswa semester 6.',
            ],
            [
                'nama_pic'             => 'Rizky Pratama',
                'kontak_pic'           => '+6281234567895',
                'kendaraan_dibutuhkan' => 'Mitsubishi Pajero',
                'titik_jemput'         => 'Parkiran Gedung Rektorat',
                'tujuan'               => 'Kantor Gubernur Jawa Barat, Bandung',
                'waktu_berangkat'      => Carbon::now()->addDays(4)->setTime(9, 0),
                'waktu_kembali'        => Carbon::now()->addDays(4)->setTime(18, 0),
                'jumlah_penumpang'     => 5,
                'anggaran_diajukan'    => null,
                'catatan_pemohon'      => 'Kegiatan non-dinas, biaya ditanggung pribadi.',
            ],
            [
                'nama_pic'             => 'Rina Marlina',
                'kontak_pic'           => '+6281234567896',
                'kendaraan_dibutuhkan' => 'Mitsubishi Xpander',
                'titik_jemput'         => 'Gedung Pascasarjana Lt. 2',
                'tujuan'               => 'Perpustakaan Nasional, Jakarta Pusat',
                'waktu_berangkat'      => Carbon::now()->addDays(1)->setTime(8, 30),
                'waktu_kembali'        => Carbon::now()->addDays(1)->setTime(15, 0),
                'jumlah_penumpang'     => 4,
                'anggaran_diajukan'    => 'BBM Rp 100.000, Tol Rp 50.000 → Total ±Rp 150.000',
                'catatan_pemohon'      => null,
            ],
            [
                'nama_pic'             => 'Hendra Gunawan',
                'kontak_pic'           => '+6281234567897',
                'kendaraan_dibutuhkan' => 'Honda Brio',
                'titik_jemput'         => 'Pintu Gerbang Utama Kampus',
                'tujuan'               => 'Bandara Soekarno-Hatta, Tangerang',
                'waktu_berangkat'      => Carbon::now()->addDays(2)->setTime(4, 0),
                'waktu_kembali'        => Carbon::now()->addDays(2)->setTime(7, 0),
                'jumlah_penumpang'     => 2,
                'anggaran_diajukan'    => 'BBM Rp 80.000, Tol Rp 40.000 → Total ±Rp 120.000',
                'catatan_pemohon'      => 'Antar pejabat yang akan dinas luar kota.',
            ],
            [
                'nama_pic'             => 'Yuliana Putri',
                'kontak_pic'           => '+6281234567898',
                'kendaraan_dibutuhkan' => 'Toyota Hiace',
                'titik_jemput'         => 'Gedung Olahraga Kampus',
                'tujuan'               => 'Gelora Bung Karno, Jakarta',
                'waktu_berangkat'      => Carbon::now()->addDays(10)->setTime(6, 0),
                'waktu_kembali'        => Carbon::now()->addDays(10)->setTime(22, 0),
                'jumlah_penumpang'     => 15,
                'anggaran_diajukan'    => null,
                'catatan_pemohon'      => 'Kegiatan olahraga mahasiswa (non-dinas).',
            ],
            [
                'nama_pic'             => 'Doni Setiawan',
                'kontak_pic'           => '+6281234567899',
                'kendaraan_dibutuhkan' => 'Mitsubishi Xpander',
                'titik_jemput'         => 'Ruang Rapat Dekan',
                'tujuan'               => 'LIPI Cibinong, Bogor',
                'waktu_berangkat'      => Carbon::now()->addDays(6)->setTime(7, 0),
                'waktu_kembali'        => Carbon::now()->addDays(6)->setTime(16, 0),
                'jumlah_penumpang'     => 5,
                'anggaran_diajukan'    => 'BBM Rp 200.000, Tol Rp 80.000 → Total ±Rp 280.000',
                'catatan_pemohon'      => 'Koordinasi penelitian bersama LIPI.',
            ],
            [
                'nama_pic'             => 'Mega Wulandari',
                'kontak_pic'           => '+6281234567800',
                'kendaraan_dibutuhkan' => 'Toyota Innova',
                'titik_jemput'         => 'Lobi Gedung Rektorat',
                'tujuan'               => 'PT. PLN (Persero) HQ, Jakarta Selatan',
                'waktu_berangkat'      => Carbon::now()->addDays(8)->setTime(10, 0),
                'waktu_kembali'        => Carbon::now()->addDays(8)->setTime(17, 30),
                'jumlah_penumpang'     => 3,
                'anggaran_diajukan'    => 'BBM Rp 120.000, Parkir Rp 30.000 → Total ±Rp 150.000',
                'catatan_pemohon'      => 'Pertemuan MoU dengan mitra industri.',
            ],
            [
                'nama_pic'             => 'Fajar Nugroho',
                'kontak_pic'           => '+6281234567811',
                'kendaraan_dibutuhkan' => 'Honda CR-V',
                'titik_jemput'         => 'Sekretariat BEM Kampus',
                'tujuan'               => 'Kota Tua, Jakarta Barat',
                'waktu_berangkat'      => Carbon::now()->addDays(12)->setTime(8, 0),
                'waktu_kembali'        => Carbon::now()->addDays(12)->setTime(19, 0),
                'jumlah_penumpang'     => 4,
                'anggaran_diajukan'    => null,
                'catatan_pemohon'      => 'Dokumentasi sejarah untuk tugas akhir (non-dinas).',
            ],
        ];

        $successCount = 0;
        
        foreach ($dummyData as $index => $data) {
            $user = $pengguna->get($index % $pengguna->count());
            
            try {
                Permohonan::create(array_merge($data, [
                    'user_id'           => $user->id,
                    'kode_permohonan'   => $this->generateUniqueKode(),
                    'file_surat_penugasan' => 'surat_penugasan/dummy_surat_' . ($index + 1) . '.pdf',
                    'status_permohonan' => StatusPermohonan::MENUNGGU_VALIDASI_ADMIN,
                ]));
                $successCount++;
            } catch (\Exception $e) {
                $this->command->error("Gagal insert data ke-" . ($index + 1) . ": " . $e->getMessage());
            }
        }

        $this->command->info("✅ {$successCount} dari " . count($dummyData) . " data permohonan dummy berhasil dibuat.");
    }

    /**
     * Generate kode permohonan yang unik (pasti tidak akan duplikat)
     */
    private function generateUniqueKode(): string
    {
        // Method 1: Menggunakan timestamp + random (paling aman)
        $kode = 'P' . Carbon::now()->format('YmdHis') . Str::upper(Str::random(4));
        
        // Method 2: Alternatif jika ingin lebih pendek (uncomment jika mau)
        // $kode = 'P' . Str::upper(Str::random(10));
        
        // Pastikan benar-benar unik (hanya untuk jaga-jaga)
        while (Permohonan::where('kode_permohonan', $kode)->exists()) {
            $kode = 'P' . Carbon::now()->format('YmdHis') . Str::upper(Str::random(6));
        }
        
        return $kode;
    }
}
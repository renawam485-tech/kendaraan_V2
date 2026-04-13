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
        // Ambil user dengan nama 'Budi' dan role 'pengguna'
        $userBudi = User::where('name', 'Budi')->where('role', 'pengguna')->first();

        if (!$userBudi) {
            $this->command->error('User dengan nama "Budi" dan role pengguna tidak ditemukan!');
            $this->command->warn('Pastikan DatabaseSeeder sudah dijalankan terlebih dahulu.');
            return;
        }

        $this->command->info("Membuat 11 data permohonan untuk user: {$userBudi->name} (ID: {$userBudi->id})");

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
                'nama_pic'             => 'Budi Raharjo',
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
                'nama_pic'             => 'Budi Wijaya',
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
                'nama_pic'             => 'Budi Pranoto',
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
                'nama_pic'             => 'Budi Hartono',
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
                'nama_pic'             => 'Budi Setiawan',
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
                'nama_pic'             => 'Budi Kusuma',
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
                'nama_pic'             => 'Budi Santoso',
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
                'nama_pic'             => 'Budi Prasetyo',
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
                'nama_pic'             => 'Budi Wibowo',
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
                'nama_pic'             => 'Budi Nugroho',
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
        $failedData = [];
        
        foreach ($dummyData as $index => $data) {
            // Generate kode unik menggunakan timestamp + index + random
            $uniqueCode = 'P' . Carbon::now()->format('YmdHis') . Str::padLeft($index + 1, 3, '0') . Str::upper(Str::random(2));
            
            try {
                Permohonan::create(array_merge($data, [
                    'user_id'           => $userBudi->id,
                    'kode_permohonan'   => $uniqueCode,
                    'file_surat_penugasan' => 'surat_penugasan/dummy_surat_budi_' . ($index + 1) . '.pdf',
                    'status_permohonan' => StatusPermohonan::MENUNGGU_VALIDASI_ADMIN,
                ]));
                $successCount++;
                $this->command->line("  ✓ Data {$index} berhasil: {$data['nama_pic']} - Kode: {$uniqueCode}");
            } catch (\Exception $e) {
                $failedData[] = [
                    'index' => $index + 1,
                    'nama' => $data['nama_pic'],
                    'error' => $e->getMessage()
                ];
                $this->command->error("  ✗ Gagal insert data {$index}: {$data['nama_pic']} - " . $e->getMessage());
            }
        }

        $this->command->newLine();
        $this->command->info("=================== HASIL SEEDER ===================");
        $this->command->info("📊 Target: 11 data permohonan untuk user '{$userBudi->name}'");
        $this->command->info("✅ Berhasil: {$successCount} dari 11 data");
        
        if (count($failedData) > 0) {
            $this->command->warn("❌ Gagal: " . count($failedData) . " data");
            foreach ($failedData as $failed) {
                $this->command->warn("   - Data {$failed['index']} ({$failed['nama']}): {$failed['error']}");
            }
        } else {
            $this->command->info("🎉 SEMUA 11 DATA BERHASIL DIMASUKKAN!");
            $this->command->info("💡 Total permohonan untuk user '{$userBudi->name}': " . Permohonan::where('user_id', $userBudi->id)->count());
        }
        $this->command->info("====================================================");
    }
}
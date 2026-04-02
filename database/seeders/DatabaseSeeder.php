<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kendaraan;
use App\Models\Pengemudi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat Akun Pengguna (Pemohon)
        User::create([
            'name' => 'Budi (Pengguna)',
            'email' => 'pengguna@sith.com',
            'password' => Hash::make('password123'),
            'role' => 'pengguna',
        ]);

        // 2. Buat Akun Kepala Administrasi
        User::create([
            'name' => 'Bapak Kepala Admin',
            'email' => 'admin@sith.com',
            'password' => Hash::make('password123'),
            'role' => 'kepala_admin',
        ]);

        // 3. Buat Akun Kasubag SPSI
        User::create([
            'name' => 'Ibu Kasubag SPSI',
            'email' => 'spsi@sith.com',
            'password' => Hash::make('password123'),
            'role' => 'spsi',
        ]);

        // 4. Buat Akun Kasubag Keuangan
        User::create([
            'name' => 'Bapak Kasubag Keuangan',
            'email' => 'keuangan@sith.com',
            'password' => Hash::make('password123'),
            'role' => 'keuangan',
        ]);

        // 5. Buat Data Kendaraan Dummy
        Kendaraan::create([
            'nama_kendaraan' => 'Toyota Hiace',
            'plat_nomor' => 'D 1234 SITH',
            'kapasitas_penumpang' => 15,
            'status_kendaraan' => 'Tersedia'
        ]);

        Kendaraan::create([
            'nama_kendaraan' => 'Toyota Innova',
            'plat_nomor' => 'D 5678 SITH',
            'kapasitas_penumpang' => 7,
            'status_kendaraan' => 'Tersedia'
        ]);

        // 6. Buat Data Pengemudi Dummy
        Pengemudi::create([
            'nama_pengemudi' => 'Asep Supir',
            'kontak' => '08123456789',
            'status_pengemudi' => 'Tersedia'
        ]);
    }
}

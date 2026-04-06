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

    public function run(): void
    {
        // 1. Super Admin
        User::create([
            'name'     => 'Super Admin',
            'email'    => 'superadmin@sith.com',
            'password' => Hash::make('password123'),
            'role'     => 'super_admin',
        ]);

        // 2. Pengguna
        User::create([
            'name'     => 'Budi (Pengguna)',
            'email'    => 'pengguna@sith.com',
            'password' => Hash::make('password123'),
            'role'     => 'pengguna',
        ]);

        // 3. Kepala Admin
        User::create([
            'name'     => 'Bapak Kepala Admin',
            'email'    => 'admin@sith.com',
            'password' => Hash::make('password123'),
            'role'     => 'kepala_admin',
        ]);

        // 4. SPSI
        User::create([
            'name'     => 'Ibu Kasubag SPSI',
            'email'    => 'spsi@sith.com',
            'password' => Hash::make('password123'),
            'role'     => 'spsi',
        ]);

        // 5. Keuangan
        User::create([
            'name'     => 'Bapak Kasubag Keuangan',
            'email'    => 'keuangan@sith.com',
            'password' => Hash::make('password123'),
            'role'     => 'keuangan',
        ]);

        // 6. Kendaraan
        Kendaraan::create([
            'nama_kendaraan'      => 'Toyota Hiace',
            'plat_nomor'          => 'D 1234 SITH',
            'kapasitas_penumpang' => 15,
            'status_kendaraan'    => 'Tersedia',
        ]);

        Kendaraan::create([
            'nama_kendaraan'      => 'Toyota Innova',
            'plat_nomor'          => 'D 5678 SITH',
            'kapasitas_penumpang' => 7,
            'status_kendaraan'    => 'Tersedia',
        ]);

        // 7. Pengemudi — FIX: kontak harus format +62
        Pengemudi::create([
            'nama_pengemudi'  => 'Asep Supir',
            'kontak'          => '+6281234567890',
            'status_pengemudi'=> 'Tersedia',
        ]);
    }
}
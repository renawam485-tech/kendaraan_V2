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
            'name'     => 'Budi',
            'email'    => 'pengguna@sith.com',
            'password' => Hash::make('password123'),
            'role'     => 'pengguna',
        ]);

        User::create([
            'name'     => 'Edo',
            'email'    => 'edo@sith.com',
            'password' => Hash::make('password123'),
            'role'     => 'pengguna',
        ]);

        // 3. Kepala Admin
        User::create([
            'name'     => 'admin',
            'email'    => 'admin@sith.com',
            'password' => Hash::make('password123'),
            'role'     => 'kepala_admin',
        ]);

        // 4. SPSI
        User::create([
            'name'     => 'spsi',
            'email'    => 'spsi@sith.com',
            'password' => Hash::make('password123'),
            'role'     => 'spsi',
        ]);

        // 5. Keuangan
        User::create([
            'name'     => 'keuangan',
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

        Kendaraan::create([
            'nama_kendaraan'      => 'Honda CR-V',
            'plat_nomor'          => 'D 3453 SITH',
            'kapasitas_penumpang' => 4,
            'status_kendaraan'    => 'Tersedia',
        ]);

        Kendaraan::create([
            'nama_kendaraan'      => 'Honda Brio',
            'plat_nomor'          => 'D 7654 SITH',
            'kapasitas_penumpang' => 4,
            'status_kendaraan'    => 'Tersedia',
        ]);

        Kendaraan::create([
            'nama_kendaraan'      => 'Mitsubishi Pajero',
            'plat_nomor'          => 'D 8564 SITH',
            'kapasitas_penumpang' => 7,
            'status_kendaraan'    => 'Tersedia',
        ]);

        Kendaraan::create([
            'nama_kendaraan'      => 'Mitsubishi Xpander',
            'plat_nomor'          => 'D 3452 SITH',
            'kapasitas_penumpang' => 7,
            'status_kendaraan'    => 'Tersedia',
        ]);

        // 7. Pengemudi — FIX: kontak harus format +62
        Pengemudi::create([
            'nama_pengemudi'  => 'Asep',
            'kontak'          => '+6281234567890',
            'status_pengemudi'=> 'Tersedia',
        ]);

        Pengemudi::create([
            'nama_pengemudi'  => 'Opik',
            'kontak'          => '+6281234567890',
            'status_pengemudi'=> 'Tersedia',
        ]);

        Pengemudi::create([
            'nama_pengemudi'  => 'Ndit',
            'kontak'          => '+6281234567890',
            'status_pengemudi'=> 'Tersedia',
        ]);
    }
}
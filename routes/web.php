<?php

use App\Http\Controllers\BantuanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PermohonanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\KendaraanVendorController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {

    // =========================================================
    // DASHBOARD (Semua Role)
    // =========================================================
    Route::get('/dashboard', [PermohonanController::class, 'index'])->name('dashboard');

    // =========================================================
    // 1. PENGGUNA (Pemohon)
    // =========================================================
    Route::get('/permohonan/buat', [PermohonanController::class, 'create'])->name('permohonan.create');
    Route::post('/permohonan', [PermohonanController::class, 'store'])->name('permohonan.store');
    Route::put('/permohonan/{id}/selesai', [PermohonanController::class, 'selesaikanSewa'])->name('permohonan.selesai');
    Route::put('/permohonan/{id}/mulai-perjalanan', [PermohonanController::class, 'mulaiPerjalanan'])->name('permohonan.mulai_perjalanan');
    Route::put('/permohonan/{id}/lapor-kembali', [PermohonanController::class, 'laporKembali'])->name('permohonan.lapor_kembali');
    Route::put('/permohonan/{id}/submit-pengembalian', [PermohonanController::class, 'submitPengembalian'])->name('permohonan.submit_pengembalian');

    // =========================================================
    // 2. KEPALA ADMINISTRASI
    // =========================================================
    Route::middleware(['role:kepala_admin'])->group(function () {
        Route::get('/admin/validasi', [PermohonanController::class, 'adminValidasi'])->name('admin.validasi');
        Route::get('/admin/finalisasi', [PermohonanController::class, 'adminFinalisasi'])->name('admin.finalisasi');
        Route::get('/admin/riwayat', [PermohonanController::class, 'adminRiwayat'])->name('admin.riwayat');

        Route::get('/permohonan/{id}/validasi-admin', [PermohonanController::class, 'validasiAdminForm'])->name('permohonan.validasi_admin');
        Route::put('/permohonan/{id}/validasi-admin', [PermohonanController::class, 'validasiAdminProses'])->name('permohonan.validasi_admin_proses');
        Route::get('/permohonan/{id}/finalisasi', [PermohonanController::class, 'finalisasiAdminForm'])->name('permohonan.finalisasi_admin');
        Route::put('/permohonan/{id}/finalisasi', [PermohonanController::class, 'finalisasiAdminSubmit'])->name('permohonan.finalisasi_admin_submit');
    });

    // =========================================================
    // 3. SPSI
    // =========================================================
    Route::middleware(['role:spsi'])->group(function () {
        Route::get('/spsi/alokasi', [PermohonanController::class, 'spsiAlokasi'])->name('spsi.alokasi');
        Route::get('/spsi/monitoring', [PermohonanController::class, 'spsiMonitoring'])->name('spsi.monitoring');
        Route::get('/spsi/serah-terima', [PermohonanController::class, 'spsiSerahTerima'])->name('spsi.serah_terima');
        Route::put('/permohonan/{id}/serah-terima-kunci', [PermohonanController::class, 'serahTerimaKunci'])->name('permohonan.serah_terima_kunci');
        Route::get('/permohonan/{id}/proses-spsi', [PermohonanController::class, 'prosesSpsiForm'])->name('permohonan.proses_spsi');
        Route::put('/permohonan/{id}/proses-spsi', [PermohonanController::class, 'prosesSpsiSubmit'])->name('permohonan.proses_spsi_submit');
        Route::put('/permohonan/{id}/konfirmasi-kembali', [PermohonanController::class, 'konfirmasiKembali'])->name('permohonan.konfirmasi_kembali');
    });

    // =========================================================
    // 4. KEUANGAN
    // =========================================================
    Route::middleware(['role:keuangan'])->group(function () {
        Route::get('/keuangan/rab', [PermohonanController::class, 'keuanganRab'])->name('keuangan.rab');
        Route::get('/keuangan/monitoring', [PermohonanController::class, 'keuanganMonitoring'])->name('keuangan.monitoring');

        Route::get('/permohonan/{id}/proses-keuangan', [PermohonanController::class, 'prosesKeuanganForm'])->name('permohonan.proses_keuangan');
        Route::put('/permohonan/{id}/proses-keuangan', [PermohonanController::class, 'prosesKeuanganSubmit'])->name('permohonan.proses_keuangan_submit');
        Route::put('/permohonan/{id}/verifikasi-pengembalian', [PermohonanController::class, 'verifikasiPengembalian'])->name('permohonan.verifikasi_pengembalian');
    });

    // =========================================================
    // 5. SUPER ADMIN — Master Data & Manajemen Sistem
    // =========================================================
    Route::middleware(['role:super_admin'])->prefix('superadmin')->name('superadmin.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('dashboard');

        // CRUD Kendaraan Internal
        Route::get('/kendaraan', [SuperAdminController::class, 'kendaraanIndex'])->name('kendaraan.index');
        Route::get('/kendaraan/tambah', [SuperAdminController::class, 'kendaraanCreate'])->name('kendaraan.create');
        Route::post('/kendaraan', [SuperAdminController::class, 'kendaraanStore'])->name('kendaraan.store');
        Route::get('/kendaraan/{id}/edit', [SuperAdminController::class, 'kendaraanEdit'])->name('kendaraan.edit');
        Route::put('/kendaraan/{id}', [SuperAdminController::class, 'kendaraanUpdate'])->name('kendaraan.update');
        Route::delete('/kendaraan/{id}', [SuperAdminController::class, 'kendaraanDestroy'])->name('kendaraan.destroy');

        // CRUD Kendaraan Vendor — SINGLE resource route, named correctly via prefix
        Route::resource('kendaraan_vendor', KendaraanVendorController::class);

        // CRUD Pengemudi
        Route::get('/pengemudi', [SuperAdminController::class, 'pengemudiIndex'])->name('pengemudi.index');
        Route::get('/pengemudi/tambah', [SuperAdminController::class, 'pengemudiCreate'])->name('pengemudi.create');
        Route::post('/pengemudi', [SuperAdminController::class, 'pengemudiStore'])->name('pengemudi.store');
        Route::get('/pengemudi/{id}/edit', [SuperAdminController::class, 'pengemudiEdit'])->name('pengemudi.edit');
        Route::put('/pengemudi/{id}', [SuperAdminController::class, 'pengemudiUpdate'])->name('pengemudi.update');
        Route::delete('/pengemudi/{id}', [SuperAdminController::class, 'pengemudiDestroy'])->name('pengemudi.destroy');

        // CRUD Pengguna & Role
        Route::get('/users', [SuperAdminController::class, 'usersIndex'])->name('users.index');
        Route::get('/users/tambah', [SuperAdminController::class, 'usersCreate'])->name('users.create');
        Route::post('/users', [SuperAdminController::class, 'usersStore'])->name('users.store');
        Route::get('/users/{id}/edit', [SuperAdminController::class, 'usersEdit'])->name('users.edit');
        Route::put('/users/{id}', [SuperAdminController::class, 'usersUpdate'])->name('users.update');
        Route::delete('/users/{id}', [SuperAdminController::class, 'usersDestroy'])->name('users.destroy');
    });

    // =========================================================
    // 6. LAPORAN (Semua Role — data difilter per role di controller)
    // =========================================================
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/export/excel', [LaporanController::class, 'exportExcel'])->name('laporan.export.excel');
    Route::get('/laporan/export/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.export.pdf');

    // =========================================================
    // 7. PUSAT BANTUAN (Semua Role)
    // =========================================================
    Route::get('/bantuan', [BantuanController::class, 'index'])->name('bantuan.index');

    // =========================================================
    // 8. AKSES BERSAMA — Detail & Cetak SPJ
    // =========================================================
    Route::get('/permohonan/{id}/detail', [PermohonanController::class, 'show'])->name('permohonan.show');
    Route::get('/permohonan/{id}/cetak', [PermohonanController::class, 'cetakSuratJalan'])->name('permohonan.cetak');

    // =========================================================
    // 9. NOTIFIKASI
    // =========================================================
    Route::post('/notifikasi/baca-semua', [PermohonanController::class, 'bacaSemuaNotif'])->name('notif.baca_semua');
    Route::delete('/notifikasi/hapus-terbaca', [PermohonanController::class, 'hapusNotifTerbaca'])->name('notif.hapus_terbaca');
    Route::post('/notifikasi/{id}/baca', [PermohonanController::class, 'bacaSatuNotif'])->name('notif.baca_satu');

    // =========================================================
    // 10. PROFILE (Laravel Breeze)
    // =========================================================
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PermohonanController;
use Illuminate\Support\Facades\Route;

// =========================================================
// HALAMAN AWAL (LANDING PAGE)
// =========================================================
Route::get('/', function () {
    return view('welcome');
});

// =========================================================
// GRUP RUTE WAJIB LOGIN
// =========================================================
Route::middleware(['auth', 'verified'])->group(function () {

    // --- DASHBOARD UTAMA ---
    Route::get('/dashboard', [PermohonanController::class, 'index'])->name('dashboard');

    // =========================================================
    // 1. RUTE PENGGUNA (PEMOHON)
    // =========================================================
    Route::get('/permohonan/buat', [PermohonanController::class, 'create'])->name('permohonan.create');
    Route::post('/permohonan', [PermohonanController::class, 'store'])->name('permohonan.store');
    Route::put('/permohonan/{id}/selesai', [PermohonanController::class, 'selesaikanSewa'])->name('permohonan.selesai');
    Route::put('/permohonan/{id}/submit-pengembalian', [PermohonanController::class, 'submitPengembalian'])->name('permohonan.submit_pengembalian');
    // FIX BUG 1: verifikasiPengembalian DIPINDAH ke grup keuangan di bawah (lihat seksi 4)

    // =========================================================
    // 2. GRUP KEAMANAN: KEPALA ADMINISTRASI
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
    // 3. GRUP KEAMANAN: KASUBAG SPSI
    // =========================================================
    Route::middleware(['role:spsi'])->group(function () {
        Route::get('/spsi/alokasi', [PermohonanController::class, 'spsiAlokasi'])->name('spsi.alokasi');
        Route::get('/spsi/monitoring', [PermohonanController::class, 'spsiMonitoring'])->name('spsi.monitoring');

        Route::get('/permohonan/{id}/proses-spsi', [PermohonanController::class, 'prosesSpsiForm'])->name('permohonan.proses_spsi');
        Route::put('/permohonan/{id}/proses-spsi', [PermohonanController::class, 'prosesSpsiSubmit'])->name('permohonan.proses_spsi_submit');
    });

    // =========================================================
    // 4. GRUP KEAMANAN: KASUBAG KEUANGAN
    // =========================================================
    Route::middleware(['role:keuangan'])->group(function () {
        Route::get('/keuangan/rab', [PermohonanController::class, 'keuanganRab'])->name('keuangan.rab');
        Route::get('/keuangan/monitoring', [PermohonanController::class, 'keuanganMonitoring'])->name('keuangan.monitoring');

        Route::get('/permohonan/{id}/proses-keuangan', [PermohonanController::class, 'prosesKeuanganForm'])->name('permohonan.proses_keuangan');
        Route::put('/permohonan/{id}/proses-keuangan', [PermohonanController::class, 'prosesKeuanganSubmit'])->name('permohonan.proses_keuangan_submit');

        // FIX BUG 1: dipindah ke sini — hanya keuangan yang boleh verifikasi pengembalian dana
        Route::put('/permohonan/{id}/verifikasi-pengembalian', [PermohonanController::class, 'verifikasiPengembalian'])->name('permohonan.verifikasi_pengembalian');
    });

    // =========================================================
    // 5. RUTE AKSES BERSAMA (DETAIL & CETAK SPJ)
    // =========================================================
    Route::get('/permohonan/{id}/detail', [PermohonanController::class, 'show'])->name('permohonan.show');
    Route::get('/permohonan/{id}/cetak', [PermohonanController::class, 'cetakSuratJalan'])->name('permohonan.cetak');

    // =========================================================
    // 6. RUTE FITUR NOTIFIKASI REAL-TIME
    // =========================================================
    Route::post('/notifikasi/baca-semua', [PermohonanController::class, 'bacaSemuaNotif'])->name('notif.baca_semua');
    Route::delete('/notifikasi/hapus-terbaca', [PermohonanController::class, 'hapusNotifTerbaca'])->name('notif.hapus_terbaca');
    Route::post('/notifikasi/{id}/baca', [PermohonanController::class, 'bacaSatuNotif'])->name('notif.baca_satu');

    // =========================================================
    // 7. RUTE PROFILE (Bawaan Laravel Breeze)
    // =========================================================
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
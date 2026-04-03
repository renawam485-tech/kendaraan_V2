<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PermohonanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () { return view('welcome'); });

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [PermohonanController::class, 'index'])->name('dashboard');
    Route::get('/admin/tugas', [PermohonanController::class, 'tugasAdmin'])->name('admin.tugas');
    Route::get('/spsi/tugas', [PermohonanController::class, 'tugasSpsi'])->name('spsi.tugas');
    Route::get('/keuangan/tugas', [PermohonanController::class, 'tugasKeuangan'])->name('keuangan.tugas');
    Route::get('/permohonan/buat', [PermohonanController::class, 'create'])->name('permohonan.create');
    Route::post('/permohonan', [PermohonanController::class, 'store'])->name('permohonan.store');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/permohonan/{id}/validasi-admin', [PermohonanController::class, 'validasiAdminForm'])->name('permohonan.validasi_admin');
    Route::put('/permohonan/{id}/validasi-admin', [PermohonanController::class, 'validasiAdminProses'])->name('permohonan.validasi_admin_proses');

    Route::get('/permohonan/{id}/proses-spsi', [PermohonanController::class, 'prosesSpsiForm'])->name('permohonan.proses_spsi');
    Route::put('/permohonan/{id}/proses-spsi', [PermohonanController::class, 'prosesSpsiSubmit'])->name('permohonan.proses_spsi_submit');

    Route::get('/permohonan/{id}/proses-keuangan', [PermohonanController::class, 'prosesKeuanganForm'])->name('permohonan.proses_keuangan');
    Route::put('/permohonan/{id}/proses-keuangan', [PermohonanController::class, 'prosesKeuanganSubmit'])->name('permohonan.proses_keuangan_submit');

    Route::get('/permohonan/{id}/finalisasi', [PermohonanController::class, 'finalisasiAdminForm'])->name('permohonan.finalisasi_admin');
    Route::put('/permohonan/{id}/finalisasi', [PermohonanController::class, 'finalisasiAdminSubmit'])->name('permohonan.finalisasi_admin_submit');

    Route::get('/permohonan/{id}/detail', [PermohonanController::class, 'show'])->name('permohonan.show');
    Route::put('/permohonan/{id}/selesai', [PermohonanController::class, 'selesaikanSewa'])->name('permohonan.selesai');

    Route::post('/notifikasi/baca-semua', [PermohonanController::class, 'bacaSemuaNotif'])->name('notif.baca_semua');
    Route::delete('/notifikasi/hapus-terbaca', [PermohonanController::class, 'hapusNotifTerbaca'])->name('notif.hapus_terbaca');

    Route::post('/notifikasi/{id}/baca', [PermohonanController::class, 'bacaSatuNotif'])->name('notif.baca_satu');
});

require __DIR__.'/auth.php';
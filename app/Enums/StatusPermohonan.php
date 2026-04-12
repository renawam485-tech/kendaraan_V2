<?php

namespace App\Enums;

enum StatusPermohonan: string
{
    // ── Alur Administrasi ───────────────────────────────────────
    case MENUNGGU_VALIDASI_ADMIN       = 'Menunggu Validasi Admin';
    case MENUNGGU_PROSES_SPSI          = 'Menunggu Proses SPSI';
    case MENUNGGU_PROSES_KEUANGAN      = 'Menunggu Proses Keuangan';
    case MENUNGGU_FINALISASI           = 'Menunggu Finalisasi';
    case DISETUJUI                     = 'Disetujui';
    case DITOLAK                       = 'Ditolak';

    // ── Alur Perjalanan ─────────────────────────────────────────
    case MENUNGGU_MULAI_PERJALANAN     = 'Menunggu Mulai Perjalanan';
    case PERJALANAN_BERLANGSUNG        = 'Perjalanan Berlangsung';
    case MENUNGGU_KONFIRMASI_KEMBALI   = 'Menunggu Konfirmasi Kembali';
    case MENUNGGU_PENYELESAIAN         = 'Menunggu Penyelesaian';

    // ── Alur Keuangan Pasca-Perjalanan ──────────────────────────
    case MENUNGGU_PENGEMBALIAN_DANA    = 'Menunggu Pengembalian Dana';
    case MENUNGGU_VERIFIKASI_KEMBALI   = 'Menunggu Verifikasi Pengembalian';
    case SELESAI                       = 'Selesai';

    // ────────────────────────────────────────────────────────────

    /**
     * Tailwind badge classes untuk setiap status.
     */
    public function badgeClass(): string
    {
        return match($this) {
            self::MENUNGGU_VALIDASI_ADMIN     => 'bg-yellow-50 text-yellow-700 border-yellow-200',
            self::MENUNGGU_PROSES_SPSI        => 'bg-amber-50 text-amber-700 border-amber-200',
            self::MENUNGGU_PROSES_KEUANGAN    => 'bg-orange-50 text-orange-700 border-orange-200',
            self::MENUNGGU_FINALISASI         => 'bg-purple-50 text-purple-700 border-purple-200',
            self::DISETUJUI                   => 'bg-blue-50 text-blue-700 border-blue-200',
            self::DITOLAK                     => 'bg-red-50 text-red-700 border-red-200',
            self::MENUNGGU_MULAI_PERJALANAN   => 'bg-yellow-50 text-yellow-700 border-yellow-200',
            self::PERJALANAN_BERLANGSUNG      => 'bg-teal-50 text-teal-700 border-teal-200',
            self::MENUNGGU_KONFIRMASI_KEMBALI => 'bg-indigo-50 text-indigo-700 border-indigo-200',
            self::MENUNGGU_PENYELESAIAN       => 'bg-purple-50 text-purple-700 border-purple-200',
            self::MENUNGGU_PENGEMBALIAN_DANA  => 'bg-orange-50 text-orange-700 border-orange-200',
            self::MENUNGGU_VERIFIKASI_KEMBALI => 'bg-pink-50 text-pink-700 border-pink-200',
            self::SELESAI                     => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        };
    }

    /**
     * Icon Bootstrap Icons untuk status bar di halaman detail.
     */
    public function icon(): string
    {
        return match($this) {
            self::SELESAI                     => 'bi-patch-check-fill text-emerald-500',
            self::DISETUJUI                   => 'bi-check-circle-fill text-blue-500',
            self::MENUNGGU_MULAI_PERJALANAN   => 'bi-key-fill text-yellow-500',
            self::PERJALANAN_BERLANGSUNG      => 'bi-geo-alt-fill text-teal-500',
            self::MENUNGGU_KONFIRMASI_KEMBALI => 'bi-arrow-return-left text-indigo-500',
            self::MENUNGGU_PENYELESAIAN       => 'bi-clipboard2-check text-purple-500',
            self::DITOLAK                     => 'bi-x-circle-fill text-red-500',
            self::MENUNGGU_PENGEMBALIAN_DANA  => 'bi-exclamation-triangle-fill text-orange-500',
            self::MENUNGGU_VERIFIKASI_KEMBALI => 'bi-hourglass-split text-amber-500',
            default                           => 'bi-hourglass-split text-slate-400',
        };
    }

    /**
     * Warna background container di status bar halaman detail.
     */
    public function containerClass(): string
    {
        return match($this) {
            self::SELESAI                     => 'bg-emerald-50 border-emerald-200',
            self::DISETUJUI                   => 'bg-blue-50 border-blue-200',
            self::MENUNGGU_MULAI_PERJALANAN   => 'bg-yellow-50 border-yellow-200',
            self::PERJALANAN_BERLANGSUNG      => 'bg-teal-50 border-teal-200',
            self::MENUNGGU_KONFIRMASI_KEMBALI => 'bg-indigo-50 border-indigo-200',
            self::MENUNGGU_PENYELESAIAN       => 'bg-purple-50 border-purple-200',
            self::DITOLAK                     => 'bg-red-50 border-red-200',
            self::MENUNGGU_PENGEMBALIAN_DANA  => 'bg-orange-50 border-orange-200',
            self::MENUNGGU_VERIFIKASI_KEMBALI => 'bg-amber-50 border-amber-200',
            default                           => 'bg-slate-50 border-slate-200',
        };
    }

    /**
     * Apakah status ini termasuk "aktif" / sedang dalam proses.
     */
    public function isActive(): bool
    {
        return in_array($this, [
            self::MENUNGGU_VALIDASI_ADMIN,
            self::MENUNGGU_PROSES_SPSI,
            self::MENUNGGU_PROSES_KEUANGAN,
            self::MENUNGGU_FINALISASI,
            self::DISETUJUI,
            self::MENUNGGU_MULAI_PERJALANAN,
            self::PERJALANAN_BERLANGSUNG,
            self::MENUNGGU_KONFIRMASI_KEMBALI,
            self::MENUNGGU_PENYELESAIAN,
            self::MENUNGGU_PENGEMBALIAN_DANA,
            self::MENUNGGU_VERIFIKASI_KEMBALI,
        ]);
    }

    /**
     * Apakah dokumen SPJ sudah boleh dicetak.
     */
    public function canPrint(): bool
    {
        return in_array($this, [
            self::DISETUJUI,
            self::MENUNGGU_MULAI_PERJALANAN,
            self::PERJALANAN_BERLANGSUNG,
            self::MENUNGGU_KONFIRMASI_KEMBALI,
            self::MENUNGGU_PENYELESAIAN,
            self::MENUNGGU_PENGEMBALIAN_DANA,
            self::MENUNGGU_VERIFIKASI_KEMBALI,
            self::SELESAI,
        ]);
    }

    /**
     * Semua nilai string (untuk query whereIn).
     */
    public static function values(self ...$cases): array
    {
        return array_map(fn(self $c) => $c->value, $cases);
    }
}

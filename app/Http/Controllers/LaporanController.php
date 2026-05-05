<?php

namespace App\Http\Controllers;

use App\Enums\StatusPermohonan;
use App\Exports\LaporanExport;
use App\Models\Kendaraan;
use App\Models\Pengemudi;
use App\Models\Permohonan;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $user  = Auth::user();
        $data  = $this->getData($request, $user->role);
        $stats = $this->getStats($user->role);

        return view('laporan.index', compact('data', 'stats', 'request'));
    }

    /**
     * Halaman riwayat berdasarkan role
     */
    public function riwayat(Request $request)
    {
        $user = Auth::user();
        $role = $user->role;

        // Hanya untuk admin/keuangan/super_admin
        if (in_array($role, ['pengguna', 'spsi'])) {
            abort(403, 'Akses ditolak');
        }

        $data = $this->getRiwayatData($request, $role);

        return view('laporan.riwayat.riwayat', compact('data', 'request', 'role'));
    }

    public function exportExcel(Request $request)
    {
        $user    = Auth::user();
        $data    = $this->getData($request, $user->role);
        $headers = $this->getHeaders($user->role);
        $rows    = $this->getRows($data, $user->role);
        $judul   = $this->getJudul($user->role);

        return Excel::download(
            new LaporanExport($rows, $headers, $judul),
            'laporan-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    public function exportPdf(Request $request)
    {
        $user  = Auth::user();
        $data  = $this->getData($request, $user->role);
        $stats = $this->getStats($user->role);
        $judul = $this->getJudul($user->role);

        $pdf = Pdf::loadView('laporan.pdf', compact('data', 'stats', 'judul', 'user'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('laporan-' . now()->format('Y-m-d') . '.pdf');
    }

    // ─────────────────────────────────────────────────────────────
    // PRIVATE HELPERS
    // ─────────────────────────────────────────────────────────────

    private function getData(Request $request, string $role)
    {
        $query = Permohonan::with(['user', 'kendaraan', 'pengemudi']);
        $perPage = 15;

        if ($request->filled('dari')) {
            $query->whereDate('created_at', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('created_at', '<=', $request->sampai);
        }

        switch ($role) {
            case 'super_admin':
                if ($request->filled('status')) {
                    $query->where('status_permohonan', $request->status);
                }
                if ($request->filled('kategori')) {
                    $query->where('kategori_kegiatan', $request->kategori);
                }
                return $query->orderBy('created_at', 'desc')->paginate($perPage);

            case 'kepala_admin':
                if ($request->filled('status')) {
                    $query->where('status_permohonan', $request->status);
                } else {
                    $query->where('status_permohonan', '!=', StatusPermohonan::MENUNGGU_VALIDASI_ADMIN->value);
                }
                return $query->orderBy('updated_at', 'desc')->paginate($perPage);

            case 'spsi':
                $query->where(function ($q) {
                    $q->whereNotNull('kendaraan_id')->orWhereNotNull('kendaraan_vendor_id');
                });
                if ($request->filled('kendaraan')) {
                    $query->where('kendaraan_id', $request->kendaraan);
                }
                return $query->orderBy('waktu_berangkat', 'desc')->paginate($perPage);

            case 'keuangan':
                $query->whereNotNull('rab_disetujui');
                if ($request->filled('status')) {
                    $query->where('status_permohonan', $request->status);
                }
                return $query->orderBy('updated_at', 'desc')->paginate($perPage);

            case 'pengguna':
                $search = $request->query('search');
                $perPageUser = 10;

                $query->where('user_id', Auth::id())
                    ->whereIn('status_permohonan', [
                        StatusPermohonan::SELESAI->value,
                        StatusPermohonan::DITOLAK->value,
                    ]);

                if ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('tujuan', 'like', "%{$search}%")
                            ->orWhere('kode_permohonan', 'like', "%{$search}%")
                            ->orWhere('titik_jemput', 'like', "%{$search}%")
                            ->orWhere('nama_pic', 'like', "%{$search}%");
                    });
                }

                return $query->orderBy('updated_at', 'desc')
                    ->paginate($perPageUser)
                    ->appends(['search' => $search]);

            default:
                return collect();
        }
    }

    /**
     * Get riwayat data berdasarkan role
     */
    private function getRiwayatData(Request $request, string $role)
    {
        $query = Permohonan::with(['user', 'kendaraan', 'pengemudi', 'kendaraanVendor']);
        $perPage = 15;

        // Filter tanggal
        if ($request->filled('dari')) {
            $query->whereDate('updated_at', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('updated_at', '<=', $request->sampai);
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status_permohonan', $request->status);
        }

        switch ($role) {
            case 'super_admin':
                // Tampilkan SEMUA data yang sudah selesai proses (riwayat)
                $query->whereIn('status_permohonan', [
                    StatusPermohonan::DISETUJUI->value,
                    StatusPermohonan::DITOLAK->value,
                    StatusPermohonan::SELESAI->value,
                ]);

                if ($request->filled('kategori')) {
                    $query->where('kategori_kegiatan', $request->kategori);
                }
                if ($request->filled('search')) {
                    $search = $request->search;
                    $query->where(function ($q) use ($search) {
                        $q->where('nama_pic', 'like', "%{$search}%")
                            ->orWhere('tujuan', 'like', "%{$search}%")
                            ->orWhere('kode_permohonan', 'like', "%{$search}%");
                    });
                }
                break;

            case 'kepala_admin':
                // Riwayat yang sudah divalidasi/ditolak/finalisasi
                $query->whereIn('status_permohonan', [
                    StatusPermohonan::DISETUJUI->value,
                    StatusPermohonan::DITOLAK->value,
                    StatusPermohonan::SELESAI->value,
                    StatusPermohonan::MENUNGGU_PROSES_SPSI->value,
                ]);

                if ($request->filled('search')) {
                    $search = $request->search;
                    $query->where(function ($q) use ($search) {
                        $q->where('nama_pic', 'like', "%{$search}%")
                            ->orWhere('tujuan', 'like', "%{$search}%");
                    });
                }
                break;

            case 'spsi':
                // Riwayat penggunaan kendaraan
                $query->where(function ($q) {
                    $q->whereNotNull('kendaraan_id')
                        ->orWhereNotNull('kendaraan_vendor_id');
                })->whereIn('status_permohonan', [
                    StatusPermohonan::PERJALANAN_BERLANGSUNG->value,
                    StatusPermohonan::MENUNGGU_KONFIRMASI_KEMBALI->value,
                    StatusPermohonan::MENUNGGU_PENYELESAIAN->value,
                    StatusPermohonan::MENUNGGU_PENGEMBALIAN_DANA->value,
                    StatusPermohonan::MENUNGGU_VERIFIKASI_KEMBALI->value,
                    StatusPermohonan::SELESAI->value,
                ]);

                if ($request->filled('kendaraan')) {
                    $query->where('kendaraan_id', $request->kendaraan);
                }
                if ($request->filled('search')) {
                    $search = $request->search;
                    $query->where(function ($q) use ($search) {
                        $q->where('nama_pic', 'like', "%{$search}%")
                            ->orWhere('tujuan', 'like', "%{$search}%");
                    });
                }
                break;

            case 'keuangan':
                // Riwayat transaksi keuangan
                $query->whereNotNull('rab_disetujui')
                    ->whereIn('status_permohonan', [
                        StatusPermohonan::MENUNGGU_PENGEMBALIAN_DANA->value,
                        StatusPermohonan::MENUNGGU_VERIFIKASI_KEMBALI->value,
                        StatusPermohonan::SELESAI->value,
                    ]);

                if ($request->filled('search')) {
                    $search = $request->search;
                    $query->where(function ($q) use ($search) {
                        $q->where('nama_pic', 'like', "%{$search}%")
                            ->orWhere('tujuan', 'like', "%{$search}%");
                    });
                }
                break;
        }

        return $query->orderBy('updated_at', 'desc')->paginate($perPage);
    }

    /**
     * Get statistik untuk halaman riwayat
     */
    private function getRiwayatStats(string $role): array
    {
        switch ($role) {
            case 'super_admin':
                return [
                    'total_riwayat' => Permohonan::whereIn('status_permohonan', [
                        StatusPermohonan::DISETUJUI->value,
                        StatusPermohonan::DITOLAK->value,
                        StatusPermohonan::SELESAI->value,
                    ])->count(),
                    'disetujui' => Permohonan::where('status_permohonan', StatusPermohonan::DISETUJUI)->count(),
                    'ditolak' => Permohonan::where('status_permohonan', StatusPermohonan::DITOLAK)->count(),
                    'selesai' => Permohonan::where('status_permohonan', StatusPermohonan::SELESAI)->count(),
                ];

            case 'kepala_admin':
                return [
                    'total_validasi' => Permohonan::whereIn('status_permohonan', [
                        StatusPermohonan::DISETUJUI->value,
                        StatusPermohonan::DITOLAK->value,
                        StatusPermohonan::SELESAI->value,
                    ])->count(),
                    'disetujui' => Permohonan::where('status_permohonan', StatusPermohonan::DISETUJUI)->count(),
                    'ditolak' => Permohonan::where('status_permohonan', StatusPermohonan::DITOLAK)->count(),
                    'selesai' => Permohonan::where('status_permohonan', StatusPermohonan::SELESAI)->count(),
                ];

            case 'spsi':
                return [
                    'total_perjalanan' => Permohonan::where(function ($q) {
                        $q->whereNotNull('kendaraan_id')
                            ->orWhereNotNull('kendaraan_vendor_id');
                    })->whereIn('status_permohonan', [
                        StatusPermohonan::PERJALANAN_BERLANGSUNG->value,
                        StatusPermohonan::MENUNGGU_KONFIRMASI_KEMBALI->value,
                        StatusPermohonan::MENUNGGU_PENYELESAIAN->value,
                        StatusPermohonan::SELESAI->value,
                    ])->count(),
                    'selesai' => Permohonan::where('status_permohonan', StatusPermohonan::SELESAI)
                        ->where(function ($q) {
                            $q->whereNotNull('kendaraan_id')
                                ->orWhereNotNull('kendaraan_vendor_id');
                        })->count(),
                    'kendaraan_digunakan' => Kendaraan::where('status_kendaraan', 'Dipinjam')->count(),
                ];

            case 'keuangan':
                return [
                    'total_transaksi' => Permohonan::whereNotNull('rab_disetujui')
                        ->whereIn('status_permohonan', [
                            StatusPermohonan::SELESAI->value,
                            StatusPermohonan::MENUNGGU_PENGEMBALIAN_DANA->value,
                            StatusPermohonan::MENUNGGU_VERIFIKASI_KEMBALI->value,
                        ])->count(),
                    'total_rab' => Permohonan::whereNotNull('rab_disetujui')->sum('rab_disetujui'),
                    'total_realisasi' => Permohonan::whereNotNull('biaya_aktual')->sum('biaya_aktual'),
                    'total_selisih' => Permohonan::whereNotNull('biaya_aktual')
                        ->selectRaw('SUM(rab_disetujui - biaya_aktual) as selisih')
                        ->value('selisih') ?? 0,
                ];
        }

        return [];
    }

    private function getStats(string $role): array
    {
        switch ($role) {
            case 'super_admin':
                return [
                    'total'     => Permohonan::count(),
                    'disetujui' => Permohonan::where('status_permohonan', StatusPermohonan::DISETUJUI)->count(),
                    'selesai'   => Permohonan::where('status_permohonan', StatusPermohonan::SELESAI)->count(),
                    'ditolak'   => Permohonan::where('status_permohonan', StatusPermohonan::DITOLAK)->count(),
                    'total_rab' => Permohonan::whereIn('status_permohonan', StatusPermohonan::values(
                        StatusPermohonan::DISETUJUI,
                        StatusPermohonan::SELESAI,
                    ))->sum('rab_disetujui'),
                ];

            case 'kepala_admin':
                return [
                    'total'     => Permohonan::count(),
                    'disetujui' => Permohonan::whereIn('status_permohonan', StatusPermohonan::values(
                        StatusPermohonan::DISETUJUI,
                        StatusPermohonan::SELESAI,
                    ))->count(),
                    'ditolak'   => Permohonan::where('status_permohonan', StatusPermohonan::DITOLAK)->count(),
                    'proses'    => Permohonan::whereNotIn('status_permohonan', StatusPermohonan::values(
                        StatusPermohonan::DISETUJUI,
                        StatusPermohonan::SELESAI,
                        StatusPermohonan::DITOLAK,
                    ))->count(),
                ];

            case 'spsi':
                return [
                    'total_kendaraan'    => Kendaraan::count(),
                    'kendaraan_tersedia' => Kendaraan::where('status_kendaraan', 'Tersedia')->count(),
                    'kendaraan_dipinjam' => Kendaraan::where('status_kendaraan', 'Dipinjam')->count(),
                    'total_pengemudi'    => Pengemudi::count(),
                    'pengemudi_bertugas' => Pengemudi::where('status_pengemudi', 'Bertugas')->count(),
                    'total_perjalanan'   => Permohonan::whereNotNull('kendaraan_id')->count(),
                ];

            case 'keuangan':
                return [
                    'total_rab'        => Permohonan::whereNotNull('rab_disetujui')->sum('rab_disetujui'),
                    'total_realisasi'  => Permohonan::whereNotNull('biaya_aktual')->sum('biaya_aktual'),
                    'total_sisa'       => Permohonan::whereNotNull('biaya_aktual')
                        ->selectRaw('SUM(rab_disetujui - biaya_aktual) as sisa')
                        ->value('sisa') ?? 0,
                    'jumlah_transaksi' => Permohonan::whereNotNull('rab_disetujui')->count(),
                ];

            case 'pengguna':
                $mine = Permohonan::where('user_id', Auth::id());
                return [
                    'total'     => (clone $mine)->count(),
                    'selesai'   => (clone $mine)->where('status_permohonan', StatusPermohonan::SELESAI->value)->count(),
                    'ditolak'   => (clone $mine)->where('status_permohonan', StatusPermohonan::DITOLAK->value)->count(),
                    'proses'    => (clone $mine)->whereNotIn('status_permohonan', [
                        StatusPermohonan::SELESAI->value,
                        StatusPermohonan::DITOLAK->value,
                    ])->count(),
                ];
        }

        return [];
    }

    private function getHeaders(string $role): array
    {
        $base = ['No', 'Nama PIC', 'Tujuan', 'Tgl Berangkat', 'Tgl Kembali', 'Kategori', 'Status'];

        return match ($role) {
            'super_admin', 'kepala_admin' => array_merge($base, ['Armada', 'RAB (Rp)', 'Dibuat Oleh']),
            'spsi'     => ['No', 'Nama PIC', 'Tujuan', 'Tgl Berangkat', 'Tgl Kembali', 'Kendaraan', 'Plat', 'Pengemudi', 'Est. Biaya (Rp)', 'Status'],
            'keuangan' => ['No', 'Nama PIC', 'Tujuan', 'Kategori', 'RAB Disetujui (Rp)', 'Biaya Aktual (Rp)', 'Selisih (Rp)', 'Mekanisme', 'Status'],
            'pengguna' => ['No', 'Kode', 'Tujuan', 'Tgl Berangkat', 'Tgl Kembali', 'Kendaraan', 'Status'],
            default    => $base,
        };
    }

    private function getRows($data, string $role): array
    {
        $rows = [];
        foreach ($data as $i => $p) {
            $status = $p->status_permohonan instanceof StatusPermohonan
                ? $p->status_permohonan->value
                : $p->status_permohonan;

            switch ($role) {
                case 'super_admin':
                case 'kepala_admin':
                    $rows[] = [
                        $i + 1,
                        $p->nama_pic,
                        $p->tujuan,
                        \Carbon\Carbon::parse($p->waktu_berangkat)->format('d/m/Y H:i'),
                        \Carbon\Carbon::parse($p->waktu_kembali)->format('d/m/Y H:i'),
                        $p->kategori_kegiatan ?? '-',
                        $status,
                        $p->kendaraan_id ? ($p->kendaraan->nama_kendaraan ?? '-') : ($p->kendaraanVendor?->nama_kendaraan ?? '-'),
                        number_format($p->rab_disetujui ?? 0, 0, ',', '.'),
                        $p->user->name ?? '-',
                    ];
                    break;

                case 'spsi':
                    $rows[] = [
                        $i + 1,
                        $p->nama_pic,
                        $p->tujuan,
                        \Carbon\Carbon::parse($p->waktu_berangkat)->format('d/m/Y H:i'),
                        \Carbon\Carbon::parse($p->waktu_kembali)->format('d/m/Y H:i'),
                        $p->kendaraan?->nama_kendaraan ?? ($p->kendaraanVendor?->nama_kendaraan ?? '-'),
                        $p->kendaraan?->plat_nomor ?? '-',
                        $p->pengemudi?->nama_pengemudi ?? 'Tanpa Supir',
                        number_format($p->estimasi_biaya_operasional ?? 0, 0, ',', '.'),
                        $status,
                    ];
                    break;

                case 'keuangan':
                    $selisih = ($p->rab_disetujui ?? 0) - ($p->biaya_aktual ?? 0);
                    $rows[]  = [
                        $i + 1,
                        $p->nama_pic,
                        $p->tujuan,
                        $p->kategori_kegiatan ?? '-',
                        number_format($p->rab_disetujui ?? 0, 0, ',', '.'),
                        number_format($p->biaya_aktual ?? 0, 0, ',', '.'),
                        number_format($selisih, 0, ',', '.'),
                        $p->mekanisme_pembayaran ?? '-',
                        $status,
                    ];
                    break;

                case 'pengguna':
                    $rows[] = [
                        $i + 1,
                        $p->kode_permohonan ?? '-',
                        $p->tujuan,
                        \Carbon\Carbon::parse($p->waktu_berangkat)->format('d/m/Y H:i'),
                        \Carbon\Carbon::parse($p->waktu_kembali)->format('d/m/Y H:i'),
                        $p->kendaraan?->nama_kendaraan ?? ($p->kendaraanVendor?->nama_kendaraan ?? '-'),
                        $status,
                    ];
                    break;
            }
        }

        return $rows;
    }

    private function getJudul(string $role): string
    {
        return match ($role) {
            'super_admin'  => 'Laporan Rekap Seluruh Permohonan',
            'kepala_admin' => 'Laporan Aktivitas Validasi & Persetujuan',
            'spsi'         => 'Laporan Penggunaan Armada Kendaraan',
            'keuangan'     => 'Laporan Rekapitulasi Anggaran & RAB',
            'pengguna'     => 'Riwayat Pengajuan Kendaraan Saya',
            default        => 'Laporan',
        };
    }
}

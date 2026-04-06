<?php

namespace App\Http\Controllers;

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
    /**
     * Tampilkan halaman laporan yang adaptif per role.
     */
    public function index(Request $request)
    {
        $user  = Auth::user();
        $data  = $this->getData($request, $user->role);
        $stats = $this->getStats($user->role);

        return view('laporan.index', compact('data', 'stats', 'request'));
    }

    /**
     * Export ke Excel.
     */
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

    /**
     * Export ke PDF.
     */
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

    // =========================================================
    // PRIVATE HELPERS
    // =========================================================

    private function getData(Request $request, string $role)
    {
        $query = Permohonan::with(['user', 'kendaraan', 'pengemudi']);

        // Filter tanggal (berlaku untuk semua role)
        if ($request->filled('dari')) {
            $query->whereDate('created_at', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('created_at', '<=', $request->sampai);
        }

        switch ($role) {
            case 'super_admin':
                // Super admin: semua data + filter status & kategori
                if ($request->filled('status')) {
                    $query->where('status_permohonan', $request->status);
                }
                if ($request->filled('kategori')) {
                    $query->where('kategori_kegiatan', $request->kategori);
                }
                return $query->orderBy('created_at', 'desc')->get();

            case 'kepala_admin':
                // Admin: semua permohonan yang sudah diproses
                if ($request->filled('status')) {
                    $query->where('status_permohonan', $request->status);
                } else {
                    $query->whereNotIn('status_permohonan', ['Menunggu Validasi Admin']);
                }
                return $query->orderBy('updated_at', 'desc')->get();

            case 'spsi':
                // SPSI: hanya yang sudah ada alokasi armada
                $query->whereNotNull('kendaraan_id')->orWhereNotNull('kendaraan_vendor');
                if ($request->filled('kendaraan')) {
                    $query->where('kendaraan_id', $request->kendaraan);
                }
                return $query->orderBy('waktu_berangkat', 'desc')->get();

            case 'keuangan':
                // Keuangan: yang sudah ada proses anggaran
                $query->whereNotNull('rab_disetujui');
                if ($request->filled('status')) {
                    $query->where('status_permohonan', $request->status);
                }
                return $query->orderBy('updated_at', 'desc')->get();

            case 'pengguna':
                // Pengguna: hanya milik sendiri
                return $query->where('user_id', Auth::id())
                    ->orderBy('created_at', 'desc')->get();
        }

        return collect();
    }

    private function getStats(string $role): array
    {
        switch ($role) {
            case 'super_admin':
                return [
                    'total'      => Permohonan::count(),
                    'disetujui'  => Permohonan::where('status_permohonan', 'Disetujui')->count(),
                    'selesai'    => Permohonan::where('status_permohonan', 'Selesai')->count(),
                    'ditolak'    => Permohonan::where('status_permohonan', 'Ditolak')->count(),
                    'total_rab'  => Permohonan::whereIn('status_permohonan', ['Disetujui','Selesai'])->sum('rab_disetujui'),
                ];
            case 'kepala_admin':
                return [
                    'total'     => Permohonan::count(),
                    'disetujui' => Permohonan::whereIn('status_permohonan', ['Disetujui','Selesai'])->count(),
                    'ditolak'   => Permohonan::where('status_permohonan', 'Ditolak')->count(),
                    'proses'    => Permohonan::whereNotIn('status_permohonan', ['Disetujui','Selesai','Ditolak'])->count(),
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
                    'total_rab'       => Permohonan::whereNotNull('rab_disetujui')->sum('rab_disetujui'),
                    'total_realisasi' => Permohonan::whereNotNull('biaya_aktual')->sum('biaya_aktual'),
                    'total_sisa'      => Permohonan::whereNotNull('biaya_aktual')
                        ->selectRaw('SUM(rab_disetujui - biaya_aktual) as sisa')
                        ->value('sisa') ?? 0,
                    'jumlah_transaksi'=> Permohonan::whereNotNull('rab_disetujui')->count(),
                ];
            case 'pengguna':
                $mine = Permohonan::where('user_id', Auth::id());
                return [
                    'total'     => (clone $mine)->count(),
                    'disetujui' => (clone $mine)->whereIn('status_permohonan', ['Disetujui','Selesai'])->count(),
                    'ditolak'   => (clone $mine)->where('status_permohonan', 'Ditolak')->count(),
                    'proses'    => (clone $mine)->whereNotIn('status_permohonan', ['Disetujui','Selesai','Ditolak'])->count(),
                ];
        }
        return [];
    }

    private function getHeaders(string $role): array
    {
        $base = ['No', 'Nama PIC', 'Tujuan', 'Tgl Berangkat', 'Tgl Kembali', 'Kategori', 'Status'];

        switch ($role) {
            case 'super_admin':
            case 'kepala_admin':
                return array_merge($base, ['Armada', 'RAB (Rp)', 'Dibuat Oleh']);
            case 'spsi':
                return ['No', 'Nama PIC', 'Tujuan', 'Tgl Berangkat', 'Tgl Kembali', 'Kendaraan', 'Plat', 'Pengemudi', 'Est. Biaya (Rp)', 'Status'];
            case 'keuangan':
                return ['No', 'Nama PIC', 'Tujuan', 'Kategori', 'RAB Disetujui (Rp)', 'Biaya Aktual (Rp)', 'Selisih (Rp)', 'Mekanisme', 'Status'];
            case 'pengguna':
                return ['No', 'Tujuan', 'Tgl Berangkat', 'Tgl Kembali', 'Kendaraan', 'Status'];
        }
        return $base;
    }

    private function getRows($data, string $role): array
    {
        $rows = [];
        foreach ($data as $i => $p) {
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
                        $p->status_permohonan,
                        $p->kendaraan_id ? ($p->kendaraan->nama_kendaraan ?? '-') : ($p->kendaraan_vendor ?? '-'),
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
                        $p->kendaraan_id ? ($p->kendaraan->nama_kendaraan ?? '-') : ($p->kendaraan_vendor ?? 'Vendor Luar'),
                        $p->kendaraan->plat_nomor ?? '-',
                        $p->pengemudi->nama_pengemudi ?? 'Tanpa Supir',
                        number_format($p->estimasi_biaya_operasional ?? 0, 0, ',', '.'),
                        $p->status_permohonan,
                    ];
                    break;
                case 'keuangan':
                    $selisih = ($p->rab_disetujui ?? 0) - ($p->biaya_aktual ?? 0);
                    $rows[] = [
                        $i + 1,
                        $p->nama_pic,
                        $p->tujuan,
                        $p->kategori_kegiatan ?? '-',
                        number_format($p->rab_disetujui ?? 0, 0, ',', '.'),
                        number_format($p->biaya_aktual ?? 0, 0, ',', '.'),
                        number_format($selisih, 0, ',', '.'),
                        $p->mekanisme_pembayaran ?? '-',
                        $p->status_permohonan,
                    ];
                    break;
                case 'pengguna':
                    $rows[] = [
                        $i + 1,
                        $p->tujuan,
                        \Carbon\Carbon::parse($p->waktu_berangkat)->format('d/m/Y H:i'),
                        \Carbon\Carbon::parse($p->waktu_kembali)->format('d/m/Y H:i'),
                        $p->kendaraan_id ? ($p->kendaraan->nama_kendaraan ?? '-') : ($p->kendaraan_vendor ?? '-'),
                        $p->status_permohonan,
                    ];
                    break;
            }
        }
        return $rows;
    }

    private function getJudul(string $role): string
    {
        return match($role) {
            'super_admin'  => 'Laporan Rekap Seluruh Permohonan',
            'kepala_admin' => 'Laporan Aktivitas Validasi & Persetujuan',
            'spsi'         => 'Laporan Penggunaan Armada Kendaraan',
            'keuangan'     => 'Laporan Rekapitulasi Anggaran & RAB',
            'pengguna'     => 'Riwayat Pengajuan Kendaraan Saya',
            default        => 'Laporan',
        };
    }
}
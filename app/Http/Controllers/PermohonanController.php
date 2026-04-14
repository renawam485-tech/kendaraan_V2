<?php

namespace App\Http\Controllers;

use App\Enums\StatusPermohonan;
use App\Models\Kendaraan;
use App\Models\KendaraanVendor;
use App\Models\Pengemudi;
use App\Models\Permohonan;
use App\Models\User;
use App\Notifications\StatusPermohonanNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermohonanController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->role === 'super_admin') {
            return redirect()->route('superadmin.dashboard');
        }

        if ($user->role === 'pengguna') {
            $search = $request->query('search');

            // Stats dihitung dari SEMUA data user (bukan hanya current page)
            $baseQuery = Permohonan::where('user_id', $user->id);

            $nonAktifStatuses = [
                StatusPermohonan::SELESAI->value,
                StatusPermohonan::DITOLAK->value,
            ];

            $stats = [
                'total'   => (clone $baseQuery)->count(),
                'proses'  => (clone $baseQuery)->whereNotIn('status_permohonan', $nonAktifStatuses)->count(),
                'selesai' => (clone $baseQuery)->where('status_permohonan', StatusPermohonan::SELESAI->value)->count(),
                'ditolak' => (clone $baseQuery)->where('status_permohonan', StatusPermohonan::DITOLAK->value)->count(),
            ];

            // Tabel hanya tampilkan status aktif (exclude selesai & ditolak) — server-side
            $aktifQuery = Permohonan::where('user_id', $user->id)
                ->whereNotIn('status_permohonan', $nonAktifStatuses);

            if ($search) {
                $aktifQuery->where(function ($q) use ($search) {
                    $q->where('tujuan', 'like', "%{$search}%")
                        ->orWhere('kode_permohonan', 'like', "%{$search}%")
                        ->orWhere('titik_jemput', 'like', "%{$search}%");
                });
            }

            $permohonans = $aktifQuery
                ->orderBy('created_at', 'desc')
                ->paginate(5)
                ->appends(['search' => $search]);

            return view('dashboard.pengguna', compact('permohonans', 'stats'));
        }

        $stats        = [];
        $tugasTerbaru = collect();
        $ruteTugas    = '';

        if ($user->role === 'kepala_admin') {
            $cVal = Permohonan::where('status_permohonan', StatusPermohonan::MENUNGGU_VALIDASI_ADMIN)->count();
            $cFin = Permohonan::where('status_permohonan', StatusPermohonan::MENUNGGU_FINALISASI)->count();

            $stats['total_semua']         = Permohonan::count();
            $stats['menunggu_validasi']   = $cVal;
            $stats['menunggu_finalisasi'] = $cFin;

            $tugasTerbaru = Permohonan::whereIn('status_permohonan', StatusPermohonan::values(
                StatusPermohonan::MENUNGGU_VALIDASI_ADMIN,
                StatusPermohonan::MENUNGGU_FINALISASI,
            ))->orderBy('updated_at', 'desc')->take(10)->get();

            $ruteTugas = ($cFin > $cVal) ? route('admin.finalisasi') : route('admin.validasi');
        } elseif ($user->role === 'spsi') {
            $stats['menunggu_alokasi'] = Permohonan::where('status_permohonan', StatusPermohonan::MENUNGGU_PROSES_SPSI)->count();
            $stats['mobil_tersedia']   = Kendaraan::where('status_kendaraan', 'Tersedia')->count();
            $stats['supir_tersedia']   = Pengemudi::where('status_pengemudi', 'Tersedia')->count();

            $tugasTerbaru = Permohonan::where('status_permohonan', StatusPermohonan::MENUNGGU_PROSES_SPSI)
                ->orderBy('updated_at', 'desc')->take(10)->get();

            $ruteTugas = route('spsi.alokasi');
        } elseif ($user->role === 'keuangan') {
            $cRab = Permohonan::where('status_permohonan', StatusPermohonan::MENUNGGU_PROSES_KEUANGAN)->count();
            $cVer = Permohonan::where('status_permohonan', StatusPermohonan::MENUNGGU_VERIFIKASI_KEMBALI)->count();

            $stats['menunggu_rab']        = $cRab;
            $stats['menunggu_verifikasi'] = $cVer;
            $stats['rab_disetujui']       = Permohonan::whereIn('status_permohonan', StatusPermohonan::values(
                StatusPermohonan::DISETUJUI,
                StatusPermohonan::SELESAI,
            ))->sum('rab_disetujui');

            $tugasTerbaru = Permohonan::whereIn('status_permohonan', StatusPermohonan::values(
                StatusPermohonan::MENUNGGU_PROSES_KEUANGAN,
                StatusPermohonan::MENUNGGU_VERIFIKASI_KEMBALI,
            ))->orderBy('updated_at', 'desc')->take(10)->get();

            $ruteTugas = ($cVer > $cRab) ? route('keuangan.monitoring') : route('keuangan.rab');
        }

        return view('dashboard', compact('stats', 'tugasTerbaru', 'ruteTugas'));
    }

    // ─────────────────────────────────────────────────────────────
    // PENGGUNA
    // ─────────────────────────────────────────────────────────────

    public function create()
    {
        $jadwalBooking = Permohonan::with('kendaraan')
            ->whereIn('status_permohonan', StatusPermohonan::values(
                StatusPermohonan::DISETUJUI,
                StatusPermohonan::MENUNGGU_FINALISASI,
            ))
            ->where('waktu_kembali', '>=', now())
            ->whereNotNull('kendaraan_id')
            ->orderBy('waktu_berangkat', 'asc')
            ->get();

        $mobilKampus  = Kendaraan::select('nama_kendaraan', 'kapasitas_penumpang')
            ->where('status_kendaraan', '!=', 'Maintenance')->get();
        $mobilVendor  = KendaraanVendor::select('nama_kendaraan', 'kapasitas_penumpang')
            ->where('status_kendaraan', 'Tersedia')->get();
        $kombinasiMobil = $mobilKampus->concat($mobilVendor)
            ->unique(fn($i) => strtolower(trim($i['nama_kendaraan'])))->values();

        return view('permohonan.create', compact('jadwalBooking', 'kombinasiMobil'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pic'             => 'required|string|max:100',
            'kontak_pic'           => ['required', 'string', 'regex:/^\+62[0-9]{8,15}$/'],
            'kendaraan_dibutuhkan' => 'required|string|max:255',
            'titik_jemput'         => 'required|string|max:150',
            'tujuan'               => 'required|string|max:150',
            'waktu_berangkat'      => 'required|date',
            'waktu_kembali'        => 'required|date|after:waktu_berangkat',
            'jumlah_penumpang'     => 'required|integer|min:1|max:60',
            'anggaran_diajukan'    => 'nullable|string|max:500',
            'catatan_pemohon'      => 'nullable|string|max:500',
            'file_surat'           => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ], [
            'waktu_kembali.after' => 'Waktu kembali harus lebih lambat dari waktu keberangkatan.',
            'kontak_pic.regex'    => 'Format nomor kontak harus diawali +62 dan berisi 8-15 angka.',
        ]);

        $filePath = $request->file('file_surat')->store('surat_penugasan', 'public');

        $permohonan = Permohonan::create([
            'user_id'              => Auth::id(),
            'kode_permohonan'      => $this->generateKodePermohonan(),
            'nama_pic'             => $request->nama_pic,
            'kontak_pic'           => $request->kontak_pic,
            'kendaraan_dibutuhkan' => $request->kendaraan_dibutuhkan,
            'titik_jemput'         => $request->titik_jemput,
            'tujuan'               => $request->tujuan,
            'waktu_berangkat'      => $request->waktu_berangkat,
            'waktu_kembali'        => $request->waktu_kembali,
            'jumlah_penumpang'     => $request->jumlah_penumpang,
            'anggaran_diajukan'    => $request->anggaran_diajukan,
            'catatan_pemohon'      => $request->catatan_pemohon,
            'file_surat_penugasan' => $filePath,
            'status_permohonan'    => StatusPermohonan::MENUNGGU_VALIDASI_ADMIN,
        ]);

        foreach (User::where('role', 'kepala_admin')->get() as $admin) {
            $admin->notify(new StatusPermohonanNotification($permohonan, 'Permohonan baru dari ' . $request->nama_pic));
        }
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->notify(new StatusPermohonanNotification($permohonan, 'Pengajuan berhasil dikirim.'));

        return redirect()->route('dashboard')->with('success', 'Berhasil diajukan.');
    }

    // ─────────────────────────────────────────────────────────────
    // KEPALA ADMIN
    // ─────────────────────────────────────────────────────────────

    public function validasiAdminForm($id)
    {
        return view('permohonan.validasi_admin', ['permohonan' => Permohonan::findOrFail($id)]);
    }

    public function validasiAdminProses(Request $request, $id)
    {
        $permohonan = Permohonan::findOrFail($id);

        $request->validate([
            'status_permohonan' => 'required|in:' . implode(',', StatusPermohonan::values(
                StatusPermohonan::MENUNGGU_PROSES_SPSI,
                StatusPermohonan::DITOLAK,
            )),
            'kategori_kegiatan' => 'required_if:status_permohonan,' . StatusPermohonan::MENUNGGU_PROSES_SPSI->value,
            'rekomendasi_admin' => 'nullable|string',
        ]);

        $statusBaru = StatusPermohonan::from($request->status_permohonan);
        $anggaranAktual = $permohonan->anggaran_diajukan;

        if (
            $statusBaru === StatusPermohonan::MENUNGGU_PROSES_SPSI
            && $request->kategori_kegiatan === 'Non SITH'
        ) {
            $anggaranAktual = 0;
        }

        $permohonan->update([
            'status_permohonan' => $statusBaru,
            'kategori_kegiatan' => $request->kategori_kegiatan,
            'rekomendasi_admin' => $request->rekomendasi_admin,
            'anggaran_diajukan' => $anggaranAktual,
        ]);

        if ($statusBaru === StatusPermohonan::MENUNGGU_PROSES_SPSI) {
            foreach (User::where('role', 'spsi')->get() as $spsi) {
                $spsi->notify(new StatusPermohonanNotification($permohonan, 'Butuh alokasi armada.'));
            }
            $permohonan->user?->notify(new StatusPermohonanNotification($permohonan, 'Disetujui Admin, sedang diproses SPSI.'));
        } elseif ($statusBaru === StatusPermohonan::DITOLAK) {
            $permohonan->user?->notify(new StatusPermohonanNotification($permohonan, 'Mohon maaf, permohonan Anda ditolak oleh Admin.'));
        }

        return redirect()->route('admin.validasi')->with('success', 'Permohonan divalidasi.');
    }

    public function finalisasiAdminForm($id)
    {
        return view('permohonan.finalisasi_admin', [
            'permohonan' => Permohonan::with(['kendaraan', 'pengemudi', 'user'])->findOrFail($id),
        ]);
    }

    public function finalisasiAdminSubmit(Request $request, $id)
    {
        $permohonan = Permohonan::findOrFail($id);
        $permohonan->update(['status_permohonan' => StatusPermohonan::DISETUJUI]);

        $permohonan->user->notify(new StatusPermohonanNotification(
            $permohonan,
            'Permohonan Anda DISETUJUI! Tim SPSI akan segera menghubungi Anda untuk serah terima kunci kendaraan.'
        ));
        foreach (User::where('role', 'spsi')->get() as $spsi) {
            $spsi->notify(new StatusPermohonanNotification(
                $permohonan,
                'Permohonan ' . $permohonan->kode_permohonan . ' disetujui. Siapkan serah terima kunci untuk ' . $permohonan->nama_pic . '.'
            ));
        }

        return redirect()->route('admin.finalisasi')->with('success', 'Permohonan difinalisasi.');
    }

    // ─────────────────────────────────────────────────────────────
    // SPSI — ALOKASI
    // ─────────────────────────────────────────────────────────────

    public function prosesSpsiForm($id)
    {
        return view('permohonan.proses_spsi', [
            'permohonan'      => Permohonan::findOrFail($id),
            'kendaraans'      => Kendaraan::where('status_kendaraan', 'Tersedia')->get(),
            'pengemudis'      => Pengemudi::where('status_pengemudi', 'Tersedia')->get(),
            'kendaraanVendors' => KendaraanVendor::where('status_kendaraan', 'Tersedia')->get(),
        ]);
    }

    public function prosesSpsiSubmit(Request $request, $id)
    {
        $permohonan = Permohonan::findOrFail($id);

        // Fix #1: validasi sumber_armada dulu, baru gunakan nilainya
        $validated = $request->validate([
            'sumber_armada'              => 'required|in:Kampus,Vendor',
            'kendaraan_id'               => 'required_if:sumber_armada,Kampus|nullable|exists:kendaraans,id',
            'kendaraan_vendor_id'        => 'required_if:sumber_armada,Vendor|nullable|exists:kendaraan_vendors,id',
            'pengemudi_id'               => 'nullable|exists:pengemudis,id',
            'estimasi_biaya_operasional' => 'required|numeric|min:0',
        ]);

        $isVendor = $validated['sumber_armada'] === 'Vendor';

        $this->bebaskanArmada($permohonan);

        $statusLanjut = $permohonan->kategori_kegiatan !== 'Non SITH'
            ? StatusPermohonan::MENUNGGU_PROSES_KEUANGAN
            : StatusPermohonan::MENUNGGU_FINALISASI;

        $permohonan->update([
            'kendaraan_id'               => $isVendor ? null : $validated['kendaraan_id'],
            'kendaraan_vendor_id'        => $isVendor ? $validated['kendaraan_vendor_id'] : null,
            'pengemudi_id'               => $validated['pengemudi_id'] ?? null,
            'estimasi_biaya_operasional' => $validated['estimasi_biaya_operasional'],
            'status_permohonan'          => $statusLanjut,
            'sumber_armada'              => $validated['sumber_armada'],
        ]);

        if (!$isVendor && $validated['kendaraan_id']) {
            Kendaraan::findOrFail($validated['kendaraan_id'])
                ->update(['status_kendaraan' => 'Dipinjam']);
        }
        if ($validated['pengemudi_id']) {
            Pengemudi::findOrFail($validated['pengemudi_id'])
                ->update(['status_pengemudi' => 'Bertugas']);
        }

        if ($statusLanjut === StatusPermohonan::MENUNGGU_PROSES_KEUANGAN) {
            foreach (User::where('role', 'keuangan')->get() as $keu) {
                $keu->notify(new StatusPermohonanNotification($permohonan, 'Permohonan butuh persetujuan RAB.'));
            }
            $permohonan->user?->notify(new StatusPermohonanNotification($permohonan, 'Armada dialokasikan! Menunggu proses keuangan.'));
        } else {
            foreach (User::where('role', 'kepala_admin')->get() as $admin) {
                $admin->notify(new StatusPermohonanNotification($permohonan, 'Armada dialokasikan. Siap difinalisasi.'));
            }
            $permohonan->user?->notify(new StatusPermohonanNotification($permohonan, 'Armada dialokasikan! Menunggu finalisasi akhir Admin.'));
        }

        return redirect()->route('spsi.alokasi')->with('success', 'Armada dialokasikan.');
    }

    // ─────────────────────────────────────────────────────────────
    // SPSI — SERAH TERIMA
    // ─────────────────────────────────────────────────────────────

    public function spsiSerahTerima(Request $request)
    {
        $with = ['kendaraan', 'kendaraanVendor', 'pengemudi', 'user'];
        $tab = $request->query('tab', 'pending');
        $search = $request->query('search');

        $pendingQuery = Permohonan::with($with)->where('status_permohonan', StatusPermohonan::DISETUJUI);
        $menungguMulaiQuery = Permohonan::with($with)->where('status_permohonan', StatusPermohonan::MENUNGGU_MULAI_PERJALANAN);
        $berlangsungQuery = Permohonan::with($with)->where('status_permohonan', StatusPermohonan::PERJALANAN_BERLANGSUNG);
        $menungguKonfirmasiQuery = Permohonan::with($with)->where('status_permohonan', StatusPermohonan::MENUNGGU_KONFIRMASI_KEMBALI);
        $riwayatQuery = Permohonan::with($with)
            ->whereIn('status_permohonan', StatusPermohonan::values(
                StatusPermohonan::MENUNGGU_PENYELESAIAN,
                StatusPermohonan::SELESAI,
                StatusPermohonan::MENUNGGU_PENGEMBALIAN_DANA,
                StatusPermohonan::MENUNGGU_VERIFIKASI_KEMBALI,
            ))
            ->whereNotNull('waktu_serah_terima');

        if ($search) {
            $searchTerm = '%' . $search . '%';
            $searchCallback = function ($q) use ($searchTerm) {
                $q->where('kode_permohonan', 'like', $searchTerm)
                    ->orWhere('nama_pic', 'like', $searchTerm)
                    ->orWhere('tujuan', 'like', $searchTerm);
            };

            match ($tab) {
                'pending' => $pendingQuery->where($searchCallback),
                'menunggu' => $menungguMulaiQuery->where($searchCallback),
                'berlangsung' => $berlangsungQuery->where($searchCallback),
                'konfirmasi' => $menungguKonfirmasiQuery->where($searchCallback),
                'riwayat' => $riwayatQuery->where($searchCallback),
                default => null,
            };
        }

        $pending = $pendingQuery->orderBy('waktu_berangkat', 'asc')->paginate(10, ['*'], 'pending_page');
        $menungguMulai = $menungguMulaiQuery->orderBy('waktu_serah_terima', 'desc')->paginate(10, ['*'], 'menunggu_page');
        $berlangsung = $berlangsungQuery->orderBy('waktu_mulai_perjalanan', 'desc')->paginate(10, ['*'], 'berlangsung_page');
        $menungguKonfirmasi = $menungguKonfirmasiQuery->orderBy('waktu_kembali_aktual', 'desc')->paginate(10, ['*'], 'konfirmasi_page');
        $riwayat = $riwayatQuery->orderBy('updated_at', 'desc')->paginate(10, ['*'], 'riwayat_page');

        return view('permohonan.serah_terima', compact(
            'pending',
            'menungguMulai',
            'berlangsung',
            'menungguKonfirmasi',
            'riwayat',
            'tab'
        ));
    }

    public function serahTerimaKunci($id)
    {
        $permohonan = Permohonan::findOrFail($id);

        if ($permohonan->status_permohonan !== StatusPermohonan::DISETUJUI) {
            return response()->json([
                'success' => false,
                'message' => 'Status tidak valid untuk serah terima kunci.'
            ]);
        }

        $permohonan->update([
            'status_permohonan'  => StatusPermohonan::MENUNGGU_MULAI_PERJALANAN,
            'waktu_serah_terima' => now(),
        ]);

        $permohonan->user?->notify(new StatusPermohonanNotification(
            $permohonan,
            'Kunci kendaraan sudah diserahkan! Silakan klik "Mulai Perjalanan" di halaman detail untuk memulai perjalanan secara resmi.'
        ));

        return response()->json([
            'success' => true,
            'message' => 'Serah terima kunci berhasil dicatat untuk ' . $permohonan->nama_pic . '.'
        ]);
    }

    public function konfirmasiKembali($id)
    {
        $permohonan = Permohonan::findOrFail($id);

        if ($permohonan->status_permohonan !== StatusPermohonan::MENUNGGU_KONFIRMASI_KEMBALI) {
            return response()->json([
                'success' => false,
                'message' => 'Status tidak valid untuk konfirmasi kembali.'
            ]);
        }

        $permohonan->update(['status_permohonan' => StatusPermohonan::MENUNGGU_PENYELESAIAN]);

        // Bebaskan armada di sini — kendaraan fisik sudah kembali dan diperiksa
        $this->bebaskanArmada($permohonan);

        $permohonan->user?->notify(new StatusPermohonanNotification(
            $permohonan,
            'SPSI telah mengkonfirmasi kendaraan sudah diterima kembali. Silakan lengkapi laporan perjalanan untuk menutup tiket.'
        ));

        return response()->json([
            'success' => true,
            'message' => 'Kendaraan dikonfirmasi sudah kembali.'
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    // KEUANGAN
    // ─────────────────────────────────────────────────────────────

    public function prosesKeuanganForm($id)
    {
        return view('permohonan.proses_keuangan', [
            'permohonan' => Permohonan::with(['kendaraan', 'pengemudi'])->findOrFail($id),
        ]);
    }

    public function prosesKeuanganSubmit(Request $request, $id)
    {
        $permohonan = Permohonan::findOrFail($id);

        $request->validate([
            'rab_disetujui'        => 'required|numeric|min:0',
            'mekanisme_pembayaran' => 'required|string|max:255',
        ]);

        $permohonan->update([
            'rab_disetujui'        => $request->rab_disetujui,
            'mekanisme_pembayaran' => $request->mekanisme_pembayaran,
            'status_permohonan'    => StatusPermohonan::MENUNGGU_FINALISASI,
        ]);

        foreach (User::where('role', 'kepala_admin')->get() as $admin) {
            $admin->notify(new StatusPermohonanNotification($permohonan, 'RAB disetujui. Siap difinalisasi.'));
        }
        $permohonan->user?->notify(new StatusPermohonanNotification($permohonan, 'Anggaran disetujui Keuangan! Menunggu finalisasi akhir.'));

        return redirect()->route('keuangan.rab')->with('success', 'Anggaran disetujui.');
    }

    // ─────────────────────────────────────────────────────────────
    // PENGGUNA — PERJALANAN
    // ─────────────────────────────────────────────────────────────

    public function mulaiPerjalanan($id)
    {
        $permohonan = Permohonan::findOrFail($id);

        if ($permohonan->user_id !== Auth::id()) {
            abort(403);
        }
        if ($permohonan->status_permohonan !== StatusPermohonan::MENUNGGU_MULAI_PERJALANAN) {
            return redirect()->back()->with('error', 'Kunci belum diserahkan oleh SPSI.');
        }

        $permohonan->update([
            'status_permohonan'      => StatusPermohonan::PERJALANAN_BERLANGSUNG,
            'waktu_mulai_perjalanan' => now(),
        ]);

        foreach (User::where('role', 'spsi')->get() as $spsi) {
            $spsi->notify(new StatusPermohonanNotification(
                $permohonan,
                'Pemohon ' . $permohonan->nama_pic . ' (' . $permohonan->kode_permohonan . ') telah memulai perjalanan ke ' . $permohonan->tujuan . '.'
            ));
        }

        return redirect()->route('permohonan.show', $id)
            ->with('success', 'Perjalanan dimulai! Selamat bepergian dan hati-hati di jalan.');
    }

    public function laporKembali($id)
    {
        $permohonan = Permohonan::findOrFail($id);

        if ($permohonan->user_id !== Auth::id()) {
            abort(403);
        }
        if ($permohonan->status_permohonan !== StatusPermohonan::PERJALANAN_BERLANGSUNG) {
            return redirect()->back()->with('error', 'Perjalanan belum dimulai atau status tidak valid.');
        }

        $permohonan->update([
            'status_permohonan'    => StatusPermohonan::MENUNGGU_KONFIRMASI_KEMBALI,
            'waktu_kembali_aktual' => now(),
        ]);

        foreach (User::where('role', 'spsi')->get() as $spsi) {
            $spsi->notify(new StatusPermohonanNotification(
                $permohonan,
                'Pemohon ' . $permohonan->nama_pic . ' (' . $permohonan->kode_permohonan . ') melaporkan sudah kembali. Konfirmasi penerimaan kendaraan di menu Serah Terima.'
            ));
        }

        return redirect()->route('permohonan.show', $id)
            ->with('success', 'Laporan kembali terkirim. Menunggu SPSI mengkonfirmasi penerimaan kendaraan.');
    }

    public function selesaikanSewa(Request $request, $id)
    {
        $permohonan = Permohonan::findOrFail($id);

        if ($permohonan->user_id !== Auth::id()) {
            abort(403);
        }
        if ($permohonan->status_permohonan !== StatusPermohonan::MENUNGGU_PENYELESAIAN) {
            return redirect()->back()->with('error', 'Perjalanan belum dikonfirmasi kembali oleh SPSI.');
        }

        if ($permohonan->kategori_kegiatan === 'Dinas SITH') {
            $request->validate([
                'biaya_aktual' => 'required|numeric|min:0',
                'bukti_lpj'    => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            ]);

            $permohonan->biaya_aktual = $request->biaya_aktual;

            if ($request->hasFile('bukti_lpj')) {
                $permohonan->bukti_lpj = $request->file('bukti_lpj')->store('bukti_lpj', 'public');
            }

            if ($permohonan->mekanisme_pembayaran === 'Reimburse') {
                $permohonan->status_permohonan = StatusPermohonan::SELESAI;
            } else {
                $permohonan->status_permohonan = $permohonan->biaya_aktual < $permohonan->rab_disetujui
                    ? StatusPermohonan::MENUNGGU_PENGEMBALIAN_DANA
                    : StatusPermohonan::SELESAI;
            }
        } else {
            $permohonan->status_permohonan = StatusPermohonan::SELESAI;
        }

        $permohonan->save();

        return redirect()->back()->with('success', 'Perjalanan telah diselesaikan. Laporan dicatat.');
    }

    public function submitPengembalian(Request $request, $id)
    {
        $permohonan = Permohonan::findOrFail($id);

        $request->validate([
            'bukti_pengembalian' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $permohonan->bukti_pengembalian = $request->file('bukti_pengembalian')
            ->store('bukti_pengembalian', 'public');
        $permohonan->status_permohonan  = StatusPermohonan::MENUNGGU_VERIFIKASI_KEMBALI;
        $permohonan->save();

        return redirect()->back()->with('success', 'Bukti pengembalian berhasil diunggah.');
    }

    public function verifikasiPengembalian($id)
    {
        $permohonan = Permohonan::findOrFail($id);
        $permohonan->update(['status_permohonan' => StatusPermohonan::SELESAI]);

        $permohonan->user?->notify(new StatusPermohonanNotification(
            $permohonan,
            'Pengembalian dana Anda telah diverifikasi. Tiket perjalanan resmi ditutup.'
        ));

        return redirect()->back()->with('success', 'Pengembalian dana diverifikasi. Tiket Selesai.');
    }

    // ─────────────────────────────────────────────────────────────
    // SHARED
    // ─────────────────────────────────────────────────────────────

    public function show($id)
    {
        $permohonan = Permohonan::with(['kendaraan', 'pengemudi', 'user', 'kendaraanVendor'])->findOrFail($id);
        return view('permohonan.show', compact('permohonan'));
    }

    public function cetakSuratJalan($id)
    {
        $permohonan = Permohonan::with(['kendaraan', 'pengemudi', 'user'])->findOrFail($id);

        if (!$permohonan->status_permohonan->canPrint()) {
            return redirect()->back()->with('error', 'Dokumen belum tersedia untuk dicetak.');
        }

        return view('permohonan.cetak', compact('permohonan'));
    }

    // ─────────────────────────────────────────────────────────────
    // DAFTAR TUGAS PER ROLE
    // ─────────────────────────────────────────────────────────────

    public function adminValidasi(Request $request)  // ← tambahkan Request $request
    {
        $query = Permohonan::where('status_permohonan', StatusPermohonan::MENUNGGU_VALIDASI_ADMIN);

        // Tambahkan pencarian
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_pic', 'like', "%{$search}%")
                    ->orWhere('tujuan', 'like', "%{$search}%")
                    ->orWhere('kode_permohonan', 'like', "%{$search}%")
                    ->orWhere('kontak_pic', 'like', "%{$search}%");
            });
        }

        $permohonans = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('dashboard.admin', compact('permohonans'))->with('judul', 'Validasi Permohonan Masuk');
    }

    public function adminFinalisasi(Request $request)  // ← tambahkan Request $request
    {
        $query = Permohonan::where('status_permohonan', StatusPermohonan::MENUNGGU_FINALISASI);

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_pic', 'like', "%{$search}%")
                    ->orWhere('tujuan', 'like', "%{$search}%")
                    ->orWhere('kode_permohonan', 'like', "%{$search}%");
            });
        }

        $permohonans = $query->orderBy('updated_at', 'desc')->paginate(15)->withQueryString();

        return view('dashboard.admin', compact('permohonans'))->with('judul', 'Finalisasi Penerbitan');
    }

    public function adminRiwayat(Request $request)
    {
        $statuses = StatusPermohonan::values(
            StatusPermohonan::DISETUJUI,
            StatusPermohonan::MENUNGGU_MULAI_PERJALANAN,
            StatusPermohonan::PERJALANAN_BERLANGSUNG,
            StatusPermohonan::MENUNGGU_KONFIRMASI_KEMBALI,
            StatusPermohonan::MENUNGGU_PENYELESAIAN,
            StatusPermohonan::MENUNGGU_PENGEMBALIAN_DANA,
            StatusPermohonan::MENUNGGU_VERIFIKASI_KEMBALI,
            StatusPermohonan::SELESAI,
            StatusPermohonan::DITOLAK,
        );

        $query = Permohonan::whereIn('status_permohonan', $statuses);

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_pic', 'like', "%{$search}%")
                    ->orWhere('tujuan', 'like', "%{$search}%")
                    ->orWhere('kode_permohonan', 'like', "%{$search}%");
            });
        }

        $permohonans = $query->orderBy('updated_at', 'desc')->paginate(15)->withQueryString();

        return view('dashboard.admin', compact('permohonans'))->with('judul', 'Arsip & Riwayat');
    }

    public function spsiAlokasi(Request $request)
    {
        $query = Permohonan::where('status_permohonan', StatusPermohonan::MENUNGGU_PROSES_SPSI);

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_pic', 'like', "%{$search}%")
                    ->orWhere('tujuan', 'like', "%{$search}%")
                    ->orWhere('kode_permohonan', 'like', "%{$search}%")
                    ->orWhere('kategori_kegiatan', 'like', "%{$search}%");
            });
        }

        $permohonans = $query->orderBy('updated_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('dashboard.spsi', compact('permohonans'))->with('judul', 'Penugasan Armada');
    }

    public function spsiMonitoring(Request $request)
    {
        $statuses = StatusPermohonan::values(
            StatusPermohonan::MENUNGGU_PROSES_KEUANGAN,
            StatusPermohonan::MENUNGGU_FINALISASI,
            StatusPermohonan::DISETUJUI,
            StatusPermohonan::MENUNGGU_MULAI_PERJALANAN,
            StatusPermohonan::PERJALANAN_BERLANGSUNG,
            StatusPermohonan::MENUNGGU_KONFIRMASI_KEMBALI,
            StatusPermohonan::MENUNGGU_PENYELESAIAN,
            StatusPermohonan::MENUNGGU_PENGEMBALIAN_DANA,
            StatusPermohonan::MENUNGGU_VERIFIKASI_KEMBALI,
            StatusPermohonan::SELESAI,
        );

        $query = Permohonan::whereIn('status_permohonan', $statuses);

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_pic', 'like', "%{$search}%")
                    ->orWhere('tujuan', 'like', "%{$search}%")
                    ->orWhere('kode_permohonan', 'like', "%{$search}%");
            });
        }

        $permohonans = $query->orderBy('updated_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('dashboard.spsi', compact('permohonans'))->with('judul', 'Pantauan & Riwayat Armada');
    }

    public function keuanganRab(Request $request)
    {
        $query = Permohonan::where('status_permohonan', StatusPermohonan::MENUNGGU_PROSES_KEUANGAN);

        // Pencarian server-side
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_pic', 'like', "%{$search}%")
                    ->orWhere('tujuan', 'like', "%{$search}%")
                    ->orWhere('kode_permohonan', 'like', "%{$search}%");
            });
        }

        $permohonans = $query->orderBy('updated_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('dashboard.keuangan', compact('permohonans'))->with('judul', 'Persetujuan Anggaran (RAB)');
    }

    public function keuanganMonitoring(Request $request)
    {
        $statuses = StatusPermohonan::values(
            StatusPermohonan::MENUNGGU_FINALISASI,
            StatusPermohonan::DISETUJUI,
            StatusPermohonan::MENUNGGU_MULAI_PERJALANAN,
            StatusPermohonan::PERJALANAN_BERLANGSUNG,
            StatusPermohonan::MENUNGGU_KONFIRMASI_KEMBALI,
            StatusPermohonan::MENUNGGU_PENYELESAIAN,
            StatusPermohonan::MENUNGGU_PENGEMBALIAN_DANA,
            StatusPermohonan::MENUNGGU_VERIFIKASI_KEMBALI,
            StatusPermohonan::SELESAI,
        );

        $query = Permohonan::whereIn('status_permohonan', $statuses);

        // Pencarian server-side
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_pic', 'like', "%{$search}%")
                    ->orWhere('tujuan', 'like', "%{$search}%")
                    ->orWhere('kode_permohonan', 'like', "%{$search}%");
            });
        }

        $permohonans = $query->orderBy('updated_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('dashboard.keuangan', compact('permohonans'))->with('judul', 'Pantauan Anggaran');
    }

    // ─────────────────────────────────────────────────────────────
    // NOTIFIKASI
    // ─────────────────────────────────────────────────────────────

    public function bacaSemuaNotif()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    }

    public function hapusNotifTerbaca()
    {
        Auth::user()->readNotifications()->delete();
        return response()->json(['success' => true]);
    }

    public function bacaSatuNotif($id)
    {
        $notif = Auth::user()->notifications()->find($id);
        $notif?->markAsRead();
        return response()->json(['success' => true]);
    }

    // ─────────────────────────────────────────────────────────────
    // PRIVATE HELPERS
    // ─────────────────────────────────────────────────────────────

    private function bebaskanArmada(Permohonan $permohonan): void
    {
        if ($permohonan->kendaraan_id) {
            Kendaraan::find($permohonan->kendaraan_id)
                ?->update(['status_kendaraan' => 'Tersedia']);
        }
        if ($permohonan->pengemudi_id) {
            Pengemudi::find($permohonan->pengemudi_id)
                ?->update(['status_pengemudi' => 'Tersedia']);
        }
    }

    private function generateKodePermohonan(): string
    {
        $tanggal = now()->format('Ymd');

        do {
            $urutan  = Permohonan::whereDate('created_at', now()->toDateString())->count() + 1;
            $random  = \Illuminate\Support\Str::upper(\Illuminate\Support\Str::random(5));
            $kode    = 'P' . $tanggal . str_pad($urutan, 3, '0', STR_PAD_LEFT) . $random;
        } while (Permohonan::where('kode_permohonan', $kode)->exists());

        return $kode;
    }
}

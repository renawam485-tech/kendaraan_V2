<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permohonan;
use App\Models\User;
use App\Models\Kendaraan;
use App\Models\Pengemudi;
use App\Models\KendaraanVendor;
use App\Notifications\StatusPermohonanNotification;
use Illuminate\Support\Facades\Auth;

class PermohonanController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->role === 'super_admin') {
            return redirect()->route('superadmin.dashboard');
        }

        if ($user->role === 'pengguna') {
            $permohonans = Permohonan::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')->get();
            return view('dashboard.pengguna', compact('permohonans'));
        }

        $stats      = [];
        $tugasTerbaru = collect();
        $ruteTugas  = '';

        if ($user->role === 'kepala_admin') {
            $cVal = Permohonan::where('status_permohonan', 'Menunggu Validasi Admin')->count();
            $cFin = Permohonan::where('status_permohonan', 'Menunggu Finalisasi')->count();

            $stats['total_semua']         = Permohonan::count();
            $stats['menunggu_validasi']   = $cVal;
            $stats['menunggu_finalisasi'] = $cFin;

            $tugasTerbaru = Permohonan::whereIn('status_permohonan', ['Menunggu Validasi Admin', 'Menunggu Finalisasi'])
                ->orderBy('updated_at', 'desc')->take(10)->get();

            // SMART ROUTING: Arahkan ke tugas yang paling banyak
            $ruteTugas = ($cFin > $cVal) ? route('admin.finalisasi') : route('admin.validasi');
        } elseif ($user->role === 'spsi') {
            $stats['menunggu_alokasi'] = Permohonan::where('status_permohonan', 'Menunggu Proses SPSI')->count();
            $stats['mobil_tersedia']   = Kendaraan::where('status_kendaraan', 'Tersedia')->count();
            $stats['supir_tersedia']   = Pengemudi::where('status_pengemudi', 'Tersedia')->count();

            $tugasTerbaru = Permohonan::where('status_permohonan', 'Menunggu Proses SPSI')
                ->orderBy('updated_at', 'desc')->take(10)->get();

            $ruteTugas = route('spsi.alokasi');
        } elseif ($user->role === 'keuangan') {
            $cRab = Permohonan::where('status_permohonan', 'Menunggu Proses Keuangan')->count();
            $cVer = Permohonan::where('status_permohonan', 'Menunggu Verifikasi Pengembalian')->count();

            $stats['menunggu_rab']        = $cRab;
            $stats['menunggu_verifikasi'] = $cVer;
            $stats['rab_disetujui']       = Permohonan::whereIn('status_permohonan', ['Disetujui', 'Selesai'])->sum('rab_disetujui');

            $tugasTerbaru = Permohonan::whereIn('status_permohonan', ['Menunggu Proses Keuangan', 'Menunggu Verifikasi Pengembalian'])
                ->orderBy('updated_at', 'desc')->take(10)->get();

            // SMART ROUTING: Arahkan ke tugas yang paling banyak
            $ruteTugas = ($cVer > $cRab) ? route('keuangan.monitoring') : route('keuangan.rab');
        }

        return view('dashboard', compact('stats', 'tugasTerbaru', 'ruteTugas'));
    }

    public function create()
    {
        // =========================================================
        // 1. LOGIKA LAMA (DIPERTAHANKAN) - Untuk Jadwal Booking
        // =========================================================
        $jadwalBooking = Permohonan::with('kendaraan')
            ->whereIn('status_permohonan', ['Disetujui', 'Menunggu Finalisasi'])
            ->where('waktu_kembali', '>=', now())
            ->whereNotNull('kendaraan_id')
            ->orderBy('waktu_berangkat', 'asc')
            ->get();

        // =========================================================
        // 2. LOGIKA BARU (TAMBAHAN) - Untuk Dropdown Pilihan Mobil
        // =========================================================
        // Ambil data mobil kampus (nama dan kapasitas)
        $mobilKampus = Kendaraan::select('nama_kendaraan', 'kapasitas_penumpang')->where('status_kendaraan', '!=', 'Maintenance')->get();

        // Ambil data mobil vendor (nama dan kapasitas)
        $mobilVendor = KendaraanVendor::select('nama_kendaraan', 'kapasitas_penumpang')->where('status_kendaraan', 'Tersedia')->get();

        // Gabungkan kedua data tersebut
        $semuaMobil = $mobilKampus->concat($mobilVendor);

        // Hilangkan duplikat berdasarkan 'nama_kendaraan' (huruf besar/kecil diabaikan)
        $kombinasiMobil = $semuaMobil->unique(function ($item) {
            return strtolower(trim($item['nama_kendaraan']));
        })->values();

        // =========================================================
        // 3. KIRIM KEDUA DATA KE VIEW
        // =========================================================
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
            'anggaran_diajukan'    => 'nullable|numeric|min:0|max:500000000',
            'catatan_pemohon'      => 'nullable|string|max:500',
            'file_surat'           => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ], [
            'waktu_kembali.after' => 'Waktu kembali harus lebih lambat dari waktu keberangkatan.',
            'kontak_pic.regex'    => 'Format nomor kontak harus diawali +62 dan berisi 8-15 angka.',
        ]);

        $filePath = $request->file('file_surat')->store('surat_penugasan', 'public');
        $anggaran = $request->anggaran_diajukan ?: 0;

        $permohonan = Permohonan::create([
            'user_id'               => Auth::id(),
            'kode_permohonan'       => $this->generateKodePermohonan(),
            'nama_pic'              => $request->nama_pic,
            'kontak_pic'            => $request->kontak_pic,
            'kendaraan_dibutuhkan'  => $request->kendaraan_dibutuhkan,
            'titik_jemput'          => $request->titik_jemput,
            'tujuan'                => $request->tujuan,
            'waktu_berangkat'       => $request->waktu_berangkat,
            'waktu_kembali'         => $request->waktu_kembali,
            'jumlah_penumpang'      => $request->jumlah_penumpang,
            'anggaran_diajukan'     => $anggaran,
            'catatan_pemohon'       => $request->catatan_pemohon,
            'file_surat_penugasan'  => $filePath,
            'status_permohonan'     => 'Menunggu Validasi Admin',
        ]);

        foreach (User::where('role', 'kepala_admin')->get() as $admin) {
            $admin->notify(new StatusPermohonanNotification($permohonan, 'Permohonan baru dari ' . $request->nama_pic));
        }
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->notify(new StatusPermohonanNotification($permohonan, 'Pengajuan berhasil dikirim.'));

        return redirect()->route('dashboard')->with('success', 'Berhasil diajukan.');
    }

    public function validasiAdminForm($id)
    {
        return view('permohonan.validasi_admin', ['permohonan' => Permohonan::findOrFail($id)]);
    }

    public function validasiAdminProses(Request $request, $id)
    {
        $permohonan = Permohonan::findOrFail($id);

        $request->validate([
            'status_permohonan'  => 'required|in:Menunggu Proses SPSI,Ditolak',
            'kategori_kegiatan'  => 'required_if:status_permohonan,Menunggu Proses SPSI',
            'rekomendasi_admin'  => 'nullable|string',
        ]);

        $anggaranAktual = $permohonan->anggaran_diajukan;
        if ($request->status_permohonan === 'Menunggu Proses SPSI' && $request->kategori_kegiatan === 'Non SITH') {
            $anggaranAktual = 0;
        }

        $permohonan->update([
            'status_permohonan' => $request->status_permohonan,
            'kategori_kegiatan' => $request->kategori_kegiatan,
            'rekomendasi_admin' => $request->rekomendasi_admin,
            'anggaran_diajukan' => $anggaranAktual,
        ]);

        if ($request->status_permohonan === 'Menunggu Proses SPSI') {
            foreach (User::where('role', 'spsi')->get() as $spsi) {
                $spsi->notify(new StatusPermohonanNotification($permohonan, 'Butuh alokasi armada.'));
            }
            if ($permohonan->user) {
                $permohonan->user->notify(new StatusPermohonanNotification($permohonan, 'Disetujui Admin, sedang diproses SPSI.'));
            }
        } elseif ($request->status_permohonan === 'Ditolak') {
            if ($permohonan->user) {
                $permohonan->user->notify(new StatusPermohonanNotification($permohonan, 'Mohon maaf, permohonan Anda ditolak oleh Admin.'));
            }
        }

        return redirect()->route('admin.validasi')->with('success', 'Permohonan divalidasi.');
    }

    public function prosesSpsiForm($id)
    {
        return view('permohonan.proses_spsi', [
            'permohonan' => Permohonan::findOrFail($id),
            'kendaraans' => Kendaraan::where('status_kendaraan', 'Tersedia')->get(),
            'pengemudis' => Pengemudi::where('status_pengemudi', 'Tersedia')->get(),
            'kendaraanVendors' => KendaraanVendor::where('status_kendaraan', 'Tersedia')->get(),
        ]);
    }

    public function prosesSpsiSubmit(Request $request, $id)
    {
        $permohonan = Permohonan::findOrFail($id);
        $isVendor   = $request->sumber_armada === 'Vendor';

        $request->validate([
            'kendaraan_id'               => $isVendor ? 'nullable' : 'required|exists:kendaraans,id',
            'kendaraan_vendor_id'        => $isVendor ? 'required|exists:kendaraan_vendors,id' : 'nullable',
            'pengemudi_id'               => 'nullable|exists:pengemudis,id',
            'estimasi_biaya_operasional' => 'required|numeric|min:0',
        ]);

        $statusLanjut = ($permohonan->kategori_kegiatan !== 'Non SITH')
            ? 'Menunggu Proses Keuangan'
            : 'Menunggu Finalisasi';

        $permohonan->update([
            'kendaraan_id'               => $isVendor ? null : $request->kendaraan_id,
            'kendaraan_vendor_id'        => $isVendor ? $request->kendaraan_vendor_id : null,
            'pengemudi_id'               => $request->pengemudi_id ?: null,
            'estimasi_biaya_operasional' => $request->estimasi_biaya_operasional,
            'status_permohonan'          => $statusLanjut,
        ]);

        // Update status kendaraan & pengemudi (logika ini sudah ada di versi asli di bagian truncated)
        if (!$isVendor && $request->kendaraan_id) {
            Kendaraan::find($request->kendaraan_id)->update(['status_kendaraan' => 'Dipinjam']);
        }
        if ($request->pengemudi_id) {
            Pengemudi::find($request->pengemudi_id)->update(['status_pengemudi' => 'Bertugas']);
        }

        // FIX BUG 6: notifikasi kondisional sesuai jalur, bukan selalu pesan finalisasi
        if ($statusLanjut === 'Menunggu Proses Keuangan') {
            foreach (User::where('role', 'keuangan')->get() as $keu) {
                $keu->notify(new StatusPermohonanNotification($permohonan, 'Permohonan butuh persetujuan RAB.'));
            }
            if ($permohonan->user) {
                $permohonan->user->notify(new StatusPermohonanNotification($permohonan, 'Armada dialokasikan! Menunggu proses keuangan.'));
            }
        } else {
            // Non-SITH: langsung ke Menunggu Finalisasi, notify admin
            foreach (User::where('role', 'kepala_admin')->get() as $admin) {
                $admin->notify(new StatusPermohonanNotification($permohonan, 'Armada dialokasikan. Siap difinalisasi.'));
            }
            if ($permohonan->user) {
                $permohonan->user->notify(new StatusPermohonanNotification($permohonan, 'Armada dialokasikan! Menunggu finalisasi akhir Admin.'));
            }
        }

        return redirect()->route('spsi.alokasi')->with('success', 'Armada dialokasikan.');
    }

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
            'rab_disetujui'       => 'required|numeric|min:0',
            'mekanisme_pembayaran' => 'required|string|max:255',
        ]);

        $permohonan->update([
            'rab_disetujui'       => $request->rab_disetujui,
            'mekanisme_pembayaran' => $request->mekanisme_pembayaran,
            'status_permohonan'   => 'Menunggu Finalisasi',
        ]);

        foreach (User::where('role', 'kepala_admin')->get() as $admin) {
            $admin->notify(new StatusPermohonanNotification($permohonan, 'RAB disetujui. Siap difinalisasi.'));
        }
        if ($permohonan->user) {
            $permohonan->user->notify(new StatusPermohonanNotification($permohonan, 'Anggaran disetujui Keuangan! Menunggu finalisasi akhir.'));
        }

        return redirect()->route('keuangan.rab')->with('success', 'Anggaran disetujui.');
    }

    public function finalisasiAdminForm($id)
    {
        $permohonan = Permohonan::with(['kendaraan', 'pengemudi', 'user'])->findOrFail($id);
        return view('permohonan.finalisasi_admin', compact('permohonan'));
    }

    public function finalisasiAdminSubmit(Request $request, $id)
{
    $permohonan = Permohonan::findOrFail($id);
    $permohonan->update(['status_permohonan' => 'Disetujui']);
 
    // Notif pengguna bahwa permohonan disetujui, menunggu serah terima
    $permohonan->user->notify(new StatusPermohonanNotification(
        $permohonan,
        'Permohonan Anda DISETUJUI! Tim SPSI akan segera menghubungi Anda untuk serah terima kunci kendaraan.'
    ));
 
    // Notif SPSI agar menyiapkan serah terima kunci
    foreach (User::where('role', 'spsi')->get() as $spsi) {
        $spsi->notify(new StatusPermohonanNotification(
            $permohonan,
            'Permohonan ' . $permohonan->kode_permohonan . ' disetujui. Siapkan serah terima kunci untuk ' . $permohonan->nama_pic . ' (berangkat: ' . \Carbon\Carbon::parse($permohonan->waktu_berangkat)->format('d M Y H:i') . ').'
        ));
    }
 
    return redirect()->route('admin.finalisasi')->with('success', 'Permohonan difinalisasi dan SPSI telah dinotifikasi.');
}

    public function show($id)
    {
        $permohonan = Permohonan::with(['kendaraan', 'pengemudi', 'user'])->findOrFail($id);
        return view('permohonan.show', compact('permohonan'));
    }

    public function selesaikanSewa(Request $request, $id)
{
    $permohonan = Permohonan::findOrFail($id);
 
    if ($permohonan->user_id !== Auth::id()) {
        abort(403);
    }
 
    if ($permohonan->status_permohonan !== 'Perjalanan Berlangsung') {
        return redirect()->back()->with('error', 'Perjalanan belum dimulai secara resmi.');
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
            $permohonan->status_permohonan = 'Selesai';
        } else {
            $permohonan->status_permohonan = $permohonan->biaya_aktual < $permohonan->rab_disetujui
                ? 'Menunggu Pengembalian Dana'
                : 'Selesai';
        }
    } else {
        $permohonan->status_permohonan = 'Selesai';
    }
 
    $permohonan->save();
    $this->bebaskanArmada($permohonan);
 
    return redirect()->back()->with('success', 'Perjalanan telah diselesaikan. Laporan dicatat.');
}

    /**
     * FIX BUG 2: Helper — bebaskan kendaraan & pengemudi saat perjalanan selesai.
     */
    private function bebaskanArmada(Permohonan $permohonan): void
    {
        if ($permohonan->kendaraan_id) {
            Kendaraan::find($permohonan->kendaraan_id)?->update(['status_kendaraan' => 'Tersedia']);
        }
        if ($permohonan->pengemudi_id) {
            Pengemudi::find($permohonan->pengemudi_id)?->update(['status_pengemudi' => 'Tersedia']);
        }
    }

    public function submitPengembalian(Request $request, $id)
    {
        $permohonan = Permohonan::findOrFail($id);

        $request->validate([
            'bukti_pengembalian' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        if ($request->hasFile('bukti_pengembalian')) {
            // Pastikan menggunakan parameter 'public' di sini
            $path = $request->file('bukti_pengembalian')->store('bukti_pengembalian', 'public');
            $permohonan->bukti_pengembalian = $path;
        }

        $permohonan->status_permohonan = 'Menunggu Verifikasi Pengembalian';
        $permohonan->save();

        return redirect()->back()->with('success', 'Bukti pengembalian berhasil diunggah.');
    }

    public function verifikasiPengembalian($id)
    {
        $permohonan = Permohonan::findOrFail($id);
        $permohonan->update(['status_permohonan' => 'Selesai']);

        // FIX BUG 5: Notify pengguna bahwa tiket sudah ditutup
        if ($permohonan->user) {
            $permohonan->user->notify(new StatusPermohonanNotification($permohonan, 'Pengembalian dana Anda telah diverifikasi. Tiket perjalanan resmi ditutup.'));
        }

        return redirect()->back()->with('success', 'Pengembalian dana diverifikasi. Tiket Selesai.');
    }

    public function bacaSemuaNotif()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    }

    public function hapusNotifTerbaca()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->readNotifications()->delete();
        return response()->json(['success' => true]);
    }

    public function bacaSatuNotif($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $notif = $user->notifications()->find($id);
        if ($notif && !$notif->read_at) {
            $notif->markAsRead();
        }
        return response()->json(['success' => true]);
    }

    public function adminValidasi()
    {
        return view('dashboard.admin', [
            'permohonans' => Permohonan::where('status_permohonan', 'Menunggu Validasi Admin')
                ->orderBy('created_at', 'desc')->get(),
        ])->with('judul', 'Validasi Permohonan Masuk');
    }

    public function adminFinalisasi()
    {
        return view('dashboard.admin', [
            'permohonans' => Permohonan::where('status_permohonan', 'Menunggu Finalisasi')
                ->orderBy('updated_at', 'desc')->get(),
        ])->with('judul', 'Finalisasi Penerbitan');
    }

    public function adminRiwayat()
    {
        // FIX BUG 7: tambahkan status pengembalian dana agar admin bisa pantau penuh
        return view('dashboard.admin', [
            'permohonans' => Permohonan::whereIn('status_permohonan', [
                'Disetujui',
                'Menunggu Pengembalian Dana',
                'Menunggu Verifikasi Pengembalian',
                'Selesai',
                'Ditolak',
            ])->orderBy('updated_at', 'desc')->get(),
        ])->with('judul', 'Arsip & Riwayat');
    }

    public function spsiAlokasi()
    {
        return view('dashboard.spsi', [
            'permohonans' => Permohonan::where('status_permohonan', 'Menunggu Proses SPSI')
                ->orderBy('updated_at', 'desc')->get(),
        ])->with('judul', 'Penugasan Armada');
    }

    public function spsiMonitoring()
{
    return view('dashboard.spsi', [
        'permohonans' => Permohonan::whereIn('status_permohonan', [
            'Menunggu Proses Keuangan',
            'Menunggu Finalisasi',
            'Disetujui',
            'Menunggu Mulai Perjalanan',
            'Perjalanan Berlangsung',
            'Menunggu Pengembalian Dana',
            'Menunggu Verifikasi Pengembalian',
            'Selesai',
        ])->orderBy('updated_at', 'desc')->get(),
    ])->with('judul', 'Pantauan & Riwayat Armada');
}

    public function keuanganRab()
    {
        return view('dashboard.keuangan', [
            'permohonans' => Permohonan::where('status_permohonan', 'Menunggu Proses Keuangan')
                ->orderBy('updated_at', 'desc')->get(),
        ])->with('judul', 'Persetujuan Anggaran (RAB)');
    }

    public function keuanganMonitoring()
    {
        // FIX BUG 7: tambahkan status pengembalian dana agar keuangan bisa pantau & verifikasi
        return view('dashboard.keuangan', [
            'permohonans' => Permohonan::whereIn('status_permohonan', [
                'Menunggu Finalisasi',
                'Disetujui',
                'Menunggu Pengembalian Dana',
                'Menunggu Verifikasi Pengembalian',
                'Selesai',
            ])->orderBy('updated_at', 'desc')->get(),
        ])->with('judul', 'Pantauan Anggaran');
    }

    public function spsiSerahTerima()
    {
        $pending = Permohonan::with(['kendaraan', 'kendaraanVendor', 'pengemudi', 'user'])
            ->where('status_permohonan', 'Disetujui')
            ->orderBy('waktu_berangkat', 'asc')
            ->get();

        $menungguMulai = Permohonan::with(['kendaraan', 'kendaraanVendor', 'pengemudi', 'user'])
            ->where('status_permohonan', 'Menunggu Mulai Perjalanan')
            ->orderBy('waktu_serah_terima', 'desc')
            ->get();

        $berlangsung = Permohonan::with(['kendaraan', 'kendaraanVendor', 'pengemudi', 'user'])
            ->where('status_permohonan', 'Perjalanan Berlangsung')
            ->orderBy('waktu_mulai_perjalanan', 'desc')
            ->get();

        $riwayat = Permohonan::with(['kendaraan', 'kendaraanVendor', 'pengemudi', 'user'])
            ->whereIn('status_permohonan', [
                'Selesai',
                'Menunggu Pengembalian Dana',
                'Menunggu Verifikasi Pengembalian',
            ])
            ->whereNotNull('waktu_serah_terima')
            ->orderBy('updated_at', 'desc')
            ->take(20)
            ->get();

        return view('permohonan.serah_terima', compact('pending', 'menungguMulai', 'berlangsung', 'riwayat'));
    }

    /**
     * SPSI konfirmasi serah terima kunci kepada pengguna.
     */
    public function serahTerimaKunci(Request $request, $id)
    {
        $permohonan = Permohonan::findOrFail($id);

        if ($permohonan->status_permohonan !== 'Disetujui') {
            return redirect()->back()->with('error', 'Status tidak valid untuk serah terima kunci.');
        }

        $permohonan->update([
            'status_permohonan'  => 'Menunggu Mulai Perjalanan',
            'waktu_serah_terima' => now(),
        ]);

        if ($permohonan->user) {
            $permohonan->user->notify(new StatusPermohonanNotification(
                $permohonan,
                'Kunci kendaraan sudah diserahkan! Silakan klik "Mulai Perjalanan" di halaman detail untuk memulai perjalanan secara resmi.'
            ));
        }

        return redirect()->route('spsi.serah_terima')
            ->with('success', 'Serah terima kunci berhasil dicatat untuk ' . $permohonan->nama_pic . '.');
    }

    /**
     * Pengguna mengkonfirmasi telah menerima kunci dan memulai perjalanan.
     */
    public function mulaiPerjalanan($id)
    {
        $permohonan = Permohonan::findOrFail($id);

        if ($permohonan->user_id !== Auth::id()) {
            abort(403, 'Anda tidak berhak memulai perjalanan ini.');
        }

        if ($permohonan->status_permohonan !== 'Menunggu Mulai Perjalanan') {
            return redirect()->back()->with('error', 'Kunci belum diserahkan oleh SPSI.');
        }

        $permohonan->update([
            'status_permohonan'      => 'Perjalanan Berlangsung',
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

    public function cetakSuratJalan($id)
{
    $permohonan = Permohonan::with(['kendaraan', 'pengemudi', 'user'])->findOrFail($id);
 
    if (!in_array($permohonan->status_permohonan, [
        'Disetujui',
        'Menunggu Mulai Perjalanan',
        'Perjalanan Berlangsung',
        'Menunggu Pengembalian Dana',
        'Menunggu Verifikasi Pengembalian',
        'Selesai',
    ])) {
        return redirect()->back()->with('error', 'Dokumen belum tersedia untuk dicetak.');
    }
 
    return view('permohonan.cetak', compact('permohonan'));
}

    /**
     * Generate kode permohonan unik.
     * Format: P + YYYYMMDD + 3-digit no urut pengajuan user di hari yang sama.
     * Contoh: P20260408001, P20260408002, P20260408003, ...
     */
    private function generateKodePermohonan(): string
    {
        $tanggal = now()->format('Ymd');
        $userId  = Auth::id();

        // Hitung berapa permohonan yang sudah dibuat user ini HARI INI
        $urutan = Permohonan::where('user_id', $userId)
            ->whereDate('created_at', now()->toDateString())
            ->count();

        // +1 karena record baru belum tersimpan saat dipanggil
        $noUrut = $urutan + 1;

        // Safeguard race condition: loop sampai kode benar-benar unik
        do {
            $kode   = 'P' . $tanggal . str_pad($noUrut, 3, '0', STR_PAD_LEFT);
            $exists = Permohonan::where('kode_permohonan', $kode)->exists();
            if ($exists) $noUrut++;
        } while ($exists);

        return $kode;
    }
}

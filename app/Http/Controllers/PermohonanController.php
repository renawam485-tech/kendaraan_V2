<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permohonan;
use App\Models\User;
use App\Models\Kendaraan;
use App\Models\Pengemudi;
use App\Notifications\StatusPermohonanNotification;
use Illuminate\Support\Facades\Auth;

class PermohonanController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->role === 'pengguna') {
            $permohonans = Permohonan::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')->get();
            return view('dashboard.pengguna', compact('permohonans'));
        }

        // --- DATA UNTUK DASHBOARD UTAMA ---
        $stats = [];
        $tugasTerbaru = collect();
        $ruteTugas = '';

        if ($user->role === 'kepala_admin') {
            $stats['total_semua'] = Permohonan::count();
            $stats['menunggu_validasi'] = Permohonan::where('status_permohonan', 'Menunggu Validasi Admin')->count();
            $stats['menunggu_finalisasi'] = Permohonan::where('status_permohonan', 'Menunggu Finalisasi')->count();
            $tugasTerbaru = Permohonan::whereIn('status_permohonan', ['Menunggu Validasi Admin', 'Menunggu Finalisasi'])
                ->orderBy('updated_at', 'desc')->take(10)->get();
            $ruteTugas = route('admin.validasi');
        } elseif ($user->role === 'spsi') {
            $stats['menunggu_alokasi'] = Permohonan::where('status_permohonan', 'Menunggu Proses SPSI')->count();
            $stats['mobil_tersedia'] = Kendaraan::where('status_kendaraan', 'Tersedia')->count();
            $stats['supir_tersedia'] = Pengemudi::where('status_pengemudi', 'Tersedia')->count();
            $tugasTerbaru = Permohonan::where('status_permohonan', 'Menunggu Proses SPSI')
                ->orderBy('updated_at', 'desc')->take(10)->get();
            $ruteTugas = route('spsi.alokasi');
        } elseif ($user->role === 'keuangan') {
            $stats['menunggu_rab'] = Permohonan::where('status_permohonan', 'Menunggu Proses Keuangan')->count();
            $stats['rab_disetujui'] = Permohonan::whereIn('status_permohonan', ['Disetujui', 'Selesai'])->sum('rab_disetujui');
            $tugasTerbaru = Permohonan::where('status_permohonan', 'Menunggu Proses Keuangan')
                ->orderBy('updated_at', 'desc')->take(10)->get();
            $ruteTugas = route('keuangan.rab');
        }

        return view('dashboard', compact('stats', 'tugasTerbaru', 'ruteTugas'));
    }

    public function create()
    {
        // Papan Jadwal: HANYA menampilkan mobil kampus fisik yang sudah dialokasikan SPSI
        $jadwalBooking = Permohonan::with('kendaraan')
            ->whereIn('status_permohonan', ['Disetujui', 'Menunggu Finalisasi'])
            ->where('waktu_kembali', '>=', now())
            ->whereNotNull('kendaraan_id') // Syarat mutlak: Sudah ada mobil fisik (Aset Kampus)
            ->orderBy('waktu_berangkat', 'asc')
            ->get();

        return view('permohonan.create', compact('jadwalBooking'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pic' => 'required|string|max:100',
            'kontak_pic' => ['required', 'string', 'regex:/^\+62[0-9]{8,15}$/'],
            'kendaraan_dibutuhkan' => 'required|string|max:255', // Hasil Select2 (Kampus/Vendor)
            'titik_jemput' => 'required|string|max:150',
            'tujuan' => 'required|string|max:150',
            'waktu_berangkat' => 'required|date',
            'waktu_kembali' => 'required|date|after:waktu_berangkat',
            'jumlah_penumpang' => 'required|integer|min:1|max:60',
            'anggaran_diajukan' => 'nullable|numeric|min:0|max:500000000', // Nullable untuk Non-Dinas
            'catatan_pemohon' => 'nullable|string|max:500', // Kolom opsional baru
            'file_surat' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ], [
            'waktu_kembali.after' => 'Waktu kembali harus lebih lambat dari waktu keberangkatan.',
            'kontak_pic.regex' => 'Format nomor kontak harus diawali +62 dan berisi 8-15 angka.',
        ]);

        $filePath = $request->file('file_surat')->store('surat_penugasan', 'public');

        // Jika anggaran kosong (karena Non-Dinas), paksa jadi 0
        $anggaran = $request->anggaran_diajukan ?: 0;

        $permohonan = Permohonan::create([
            'user_id' => Auth::id(),
            'nama_pic' => $request->nama_pic,
            'kontak_pic' => $request->kontak_pic,
            'kendaraan_dibutuhkan' => $request->kendaraan_dibutuhkan,
            'titik_jemput' => $request->titik_jemput,
            'tujuan' => $request->tujuan,
            'waktu_berangkat' => $request->waktu_berangkat,
            'waktu_kembali' => $request->waktu_kembali,
            'jumlah_penumpang' => $request->jumlah_penumpang,
            'anggaran_diajukan' => $anggaran,
            'catatan_pemohon' => $request->catatan_pemohon,
            'file_surat_penugasan' => $filePath,
            'status_permohonan' => 'Menunggu Validasi Admin',
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
            'status_permohonan' => 'required|in:Menunggu Proses SPSI,Ditolak',
            'kategori_kegiatan' => 'required_if:status_permohonan,Menunggu Proses SPSI',
            'rekomendasi_admin' => 'nullable|string',
        ]);

        // LOGIKA VETO ADMIN: Jika kategori adalah "Non SITH", paksa anggaran jadi 0
        $anggaranAktual = $permohonan->anggaran_diajukan;
        if ($request->status_permohonan === 'Menunggu Proses SPSI' && $request->kategori_kegiatan === 'Non SITH') {
            $anggaranAktual = 0;
        }

        $permohonan->update([
            'status_permohonan' => $request->status_permohonan,
            'kategori_kegiatan' => $request->kategori_kegiatan,
            'rekomendasi_admin' => $request->rekomendasi_admin,
            'anggaran_diajukan' => $anggaranAktual, // Update anggaran hasil Veto
        ]);

        if ($request->status_permohonan === 'Menunggu Proses SPSI') {
            foreach (User::where('role', 'spsi')->get() as $spsi) {
                $spsi->notify(new StatusPermohonanNotification($permohonan, 'Butuh alokasi armada.'));
            }
            if ($permohonan->user) $permohonan->user->notify(new StatusPermohonanNotification($permohonan, 'Disetujui Admin, sedang diproses SPSI.'));
        } elseif ($request->status_permohonan === 'Ditolak') {
            if ($permohonan->user) $permohonan->user->notify(new StatusPermohonanNotification($permohonan, 'Mohon maaf, permohonan Anda ditolak oleh Admin.'));
        }

        return redirect()->route('admin.validasi')->with('success', 'Permohonan divalidasi.');
    }

    public function prosesSpsiForm($id)
    {
        return view('permohonan.proses_spsi', [
            'permohonan' => Permohonan::findOrFail($id),
            'kendaraans' => Kendaraan::where('status_kendaraan', 'Tersedia')->get(),
            'pengemudis' => Pengemudi::where('status_pengemudi', 'Tersedia')->get(),
        ]);
    }

    public function prosesSpsiSubmit(Request $request, $id)
    {
        $permohonan = Permohonan::findOrFail($id);
        $isVendor = $request->sumber_armada === 'Vendor';

        $request->validate([
            'kendaraan_id' => $isVendor ? 'nullable' : 'required|exists:kendaraans,id',
            'kendaraan_vendor' => $isVendor ? 'required|string' : 'nullable',
            'pengemudi_id' => 'nullable|exists:pengemudis,id',
            'estimasi_biaya_operasional' => 'required|numeric|min:0',
        ]);

        $statusLanjut = ($permohonan->kategori_kegiatan !== 'Non SITH') ? 'Menunggu Proses Keuangan' : 'Menunggu Finalisasi';

        $permohonan->update([
            'kendaraan_id' => $request->kendaraan_id,
            'kendaraan_vendor' => $request->kendaraan_vendor,
            'pengemudi_id' => $request->pengemudi_id,
            'estimasi_biaya_operasional' => $request->estimasi_biaya_operasional,
            'status_permohonan' => $statusLanjut,
        ]);

        if ($statusLanjut === 'Menunggu Proses Keuangan') {
            foreach (User::where('role', 'keuangan')->get() as $keuangan) {
                $keuangan->notify(new StatusPermohonanNotification($permohonan, 'Armada dialokasikan, cek RAB.'));
            }
            if ($permohonan->user) $permohonan->user->notify(new StatusPermohonanNotification($permohonan, 'Armada siap, menunggu verifikasi Keuangan.'));
        } else {
            foreach (User::where('role', 'kepala_admin')->get() as $admin) {
                $admin->notify(new StatusPermohonanNotification($permohonan, 'Non-Dinas: Armada siap, menunggu Finalisasi.'));
            }
            if ($permohonan->user) $permohonan->user->notify(new StatusPermohonanNotification($permohonan, 'Armada dialokasikan! Menunggu finalisasi akhir Admin.'));
        }
        return redirect()->route('spsi.alokasi')->with('success', 'Armada dialokasikan.');
    }



    public function prosesKeuanganForm($id)
    {
        return view('permohonan.proses_keuangan', ['permohonan' => Permohonan::with(['kendaraan', 'pengemudi'])->findOrFail($id)]);
    }

    public function prosesKeuanganSubmit(Request $request, $id)
    {
        $permohonan = Permohonan::findOrFail($id);

        $request->validate([
            'rab_disetujui' => 'required|numeric|min:0',
            'mekanisme_pembayaran' => 'required|string|max:255',
        ]);

        $permohonan->update([
            'rab_disetujui' => $request->rab_disetujui,
            'mekanisme_pembayaran' => $request->mekanisme_pembayaran,
            'status_permohonan' => 'Menunggu Finalisasi',
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
        return view('permohonan.finalisasi_admin', ['permohonan' => Permohonan::with(['kendaraan', 'pengemudi', 'user'])->findOrFail($id)]);
    }

    public function finalisasiAdminSubmit(Request $request, $id)
    {
        $permohonan = Permohonan::findOrFail($id);
        $permohonan->update(['status_permohonan' => 'Disetujui']);
        $permohonan->user->notify(new StatusPermohonanNotification($permohonan, 'Hore! Permohonan Anda DISETUJUI.'));

        return redirect()->route('admin.finalisasi')->with('success', 'Permohonan difinalisasi.');
    }

    public function show($id)
    {
        $permohonan = Permohonan::with(['kendaraan', 'pengemudi', 'user'])->findOrFail($id);
        return view('permohonan.show', compact('permohonan'));
    }

    public function selesaikanSewa(Request $request, $id)
    {
        $permohonan = Permohonan::where('user_id', Auth::id())->findOrFail($id);
        
        // Jika Non-Dinas, langsung selesai.
        if($permohonan->kategori_kegiatan === 'Non SITH') {
            $permohonan->update(['status_permohonan' => 'Selesai']);
            return redirect()->route('dashboard')->with('success', 'Perjalanan Non-Dinas selesai.');
        }

        // Jika Dinas SITH, hitung LPJ
        $request->validate([
            'biaya_aktual' => 'required|numeric|min:0',
            'bukti_lpj' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $pathLpj = $request->file('bukti_lpj')->store('bukti_lpj', 'public');
        $selisih = $permohonan->rab_disetujui - $request->biaya_aktual;

        $statusAkhir = ($selisih > 0) ? 'Menunggu Pengembalian Dana' : 'Selesai';

        $permohonan->update([
            'biaya_aktual' => $request->biaya_aktual,
            'bukti_lpj' => $pathLpj,
            'status_permohonan' => $statusAkhir
        ]);

        return redirect()->back()->with('success', 'LPJ tersimpan. Status: ' . $statusAkhir);
    }

    public function submitPengembalian(Request $request, $id)
    {
        $permohonan = Permohonan::where('user_id', Auth::id())->findOrFail($id);
        $request->validate(['bukti_pengembalian' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048']);
        
        $pathBukti = $request->file('bukti_pengembalian')->store('bukti_pengembalian', 'public');
        $permohonan->update([
            'bukti_pengembalian' => $pathBukti,
            'status_permohonan' => 'Menunggu Verifikasi Pengembalian'
        ]);

        return redirect()->back()->with('success', 'Bukti transfer berhasil diunggah. Menunggu verifikasi Keuangan.');
    }

    public function verifikasiPengembalian($id)
    {
        $permohonan = Permohonan::findOrFail($id);
        $permohonan->update(['status_permohonan' => 'Selesai']);
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
        return view('dashboard.admin', ['permohonans' => Permohonan::where('status_permohonan', 'Menunggu Validasi Admin')->orderBy('created_at', 'desc')->get()])->with('judul', 'Validasi Permohonan Masuk');
    }
    public function adminFinalisasi()
    {
        return view('dashboard.admin', ['permohonans' => Permohonan::where('status_permohonan', 'Menunggu Finalisasi')->orderBy('updated_at', 'desc')->get()])->with('judul', 'Finalisasi Penerbitan');
    }
    public function adminRiwayat()
    {
        return view('dashboard.admin', ['permohonans' => Permohonan::whereIn('status_permohonan', ['Disetujui', 'Selesai', 'Ditolak'])->orderBy('updated_at', 'desc')->get()])->with('judul', 'Arsip & Riwayat');
    }

    public function spsiAlokasi()
    {
        return view('dashboard.spsi', ['permohonans' => Permohonan::where('status_permohonan', 'Menunggu Proses SPSI')->orderBy('updated_at', 'desc')->get()])->with('judul', 'Penugasan Armada');
    }
    public function spsiMonitoring()
    {
        return view('dashboard.spsi', ['permohonans' => Permohonan::whereIn('status_permohonan', ['Menunggu Proses Keuangan', 'Menunggu Finalisasi', 'Disetujui', 'Selesai'])->orderBy('updated_at', 'desc')->get()])->with('judul', 'Pantauan & Riwayat Armada');
    }

    public function keuanganRab()
    {
        return view('dashboard.keuangan', ['permohonans' => Permohonan::where('status_permohonan', 'Menunggu Proses Keuangan')->orderBy('updated_at', 'desc')->get()])->with('judul', 'Persetujuan Anggaran (RAB)');
    }
    public function keuanganMonitoring()
    {
        return view('dashboard.keuangan', ['permohonans' => Permohonan::whereIn('status_permohonan', ['Menunggu Finalisasi', 'Disetujui', 'Selesai'])->orderBy('updated_at', 'desc')->get()])->with('judul', 'Pantauan Anggaran');
    }

    public function cetakSuratJalan($id)
    {
        $permohonan = Permohonan::with(['kendaraan', 'pengemudi', 'user'])->findOrFail($id);
        if (!in_array($permohonan->status_permohonan, ['Disetujui', 'Selesai'])) {
            return redirect()->back()->with('error', 'Dokumen belum tersedia untuk dicetak.');
        }
        return view('permohonan.cetak', compact('permohonan'));
    }
}

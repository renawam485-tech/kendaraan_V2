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

        // --- DATA UNTUK DASHBOARD UTAMA (Admin, SPSI, Keuangan) ---
        $stats = [];
        $tugasTerbaru = collect();
        $ruteTugas = '';

        if ($user->role === 'kepala_admin') {
            // Statistik Admin
            $stats['total_semua'] = Permohonan::count();
            $stats['menunggu_validasi'] = Permohonan::where('status_permohonan', 'Menunggu Validasi Admin')->count();
            $stats['menunggu_finalisasi'] = Permohonan::where('status_permohonan', 'Menunggu Finalisasi')->count();

            // Tugas Admin (Maksimal 10)
            $tugasTerbaru = Permohonan::whereIn('status_permohonan', ['Menunggu Validasi Admin', 'Menunggu Finalisasi'])
                ->orderBy('updated_at', 'desc')->take(10)->get();
            $ruteTugas = route('admin.validasi');
        } elseif ($user->role === 'spsi') {
            // Statistik SPSI
            $stats['menunggu_alokasi'] = Permohonan::where('status_permohonan', 'Menunggu Proses SPSI')->count();
            $stats['mobil_tersedia'] = Kendaraan::where('status_kendaraan', 'Tersedia')->count();
            $stats['supir_tersedia'] = Pengemudi::where('status_pengemudi', 'Tersedia')->count();

            // Tugas SPSI (Maksimal 10)
            $tugasTerbaru = Permohonan::where('status_permohonan', 'Menunggu Proses SPSI')
                ->orderBy('updated_at', 'desc')->take(10)->get();
            $ruteTugas = route('spsi.alokasi');
        } elseif ($user->role === 'keuangan') {
            // Statistik Keuangan
            $stats['menunggu_rab'] = Permohonan::where('status_permohonan', 'Menunggu Proses Keuangan')->count();
            $stats['rab_disetujui'] = Permohonan::whereIn('status_permohonan', ['Disetujui', 'Selesai'])->sum('rab_disetujui');

            // Tugas Keuangan (Maksimal 10)
            $tugasTerbaru = Permohonan::where('status_permohonan', 'Menunggu Proses Keuangan')
                ->orderBy('updated_at', 'desc')->take(10)->get();
            $ruteTugas = route('keuangan.rab');
        }

        return view('dashboard', compact('stats', 'tugasTerbaru', 'ruteTugas'));
    }

    public function create()
    {
        // Fitur Cerdas: Ambil jadwal mobil KAMPUS yang sudah dibooking (Disetujui/Finalisasi) 
        // dan waktunya belum lewat (masih akan datang atau sedang dipakai)
        $jadwalBooking = Permohonan::whereIn('status_permohonan', ['Disetujui', 'Menunggu Finalisasi'])
            ->where('waktu_kembali', '>=', now())
            ->where('kendaraan_dibutuhkan', 'like', '%Kampus%') // Hanya melacak mobil kampus
            ->orderBy('waktu_berangkat', 'asc')
            ->get();

        return view('permohonan.create', compact('jadwalBooking'));
    }

    public function store(Request $request)
    {
        // PERTAHANAN BELAKANG KETAT: Membatasi panjang teks & memvalidasi awalan +62
        $request->validate([
            'nama_pic' => 'required|string|max:100', // Maksimal 100 huruf
            'kontak_pic' => ['required', 'string', 'regex:/^\+62[0-9]{8,15}$/'], // Wajib diawali +62 dan hanya angka setelahnya
            'kendaraan_dibutuhkan' => 'required|string|max:100',
            'titik_jemput' => 'required|string|max:150',
            'tujuan' => 'required|string|max:150',
            'waktu_berangkat' => 'required|date|after_or_equal:now', // Minimal menit ini
            'waktu_kembali' => 'required|date|after:waktu_berangkat', // Wajib setelah berangkat
            'jumlah_penumpang' => 'required|integer|min:1|max:60', // Maksimal bus 60 orang
            'file_surat' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'anggaran_diajukan' => 'required|numeric|min:0|max:500000000', // Maks 500 juta agar tidak error database
        ], [
            'waktu_berangkat.after_or_equal' => 'Waktu keberangkatan tidak boleh mendahului jam saat ini.',
            'waktu_kembali.after' => 'Waktu kembali harus lebih lambat dari waktu keberangkatan.',
            'kontak_pic.regex' => 'Format nomor kontak harus diawali +62 dan berisi 8-15 angka.',
        ]);

        $filePath = $request->file('file_surat')->store('surat_penugasan', 'public');

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
            'file_surat_penugasan' => $filePath,
            'anggaran_diajukan' => $request->anggaran_diajukan,
            'status_permohonan' => 'Menunggu Validasi Admin',
        ]);

        $admins = User::where('role', 'kepala_admin')->get();
        foreach ($admins as $admin) {
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
        
        // Wajib divalidasi agar tidak error
        $request->validate([
            'status_permohonan' => 'required|in:Menunggu Proses SPSI,Ditolak',
            'kategori_kegiatan' => 'required_if:status_permohonan,Menunggu Proses SPSI',
            'rekomendasi_admin' => 'nullable|string',
        ]);

        $permohonan->update([
            'status_permohonan' => $request->status_permohonan,
            'kategori_kegiatan' => $request->kategori_kegiatan,
            'rekomendasi_admin' => $request->rekomendasi_admin,
        ]);

        if ($request->status_permohonan === 'Menunggu Proses SPSI') {
            $spsis = User::where('role', 'spsi')->get();
            foreach ($spsis as $spsi) {
                $spsi->notify(new StatusPermohonanNotification($permohonan, 'Butuh alokasi armada.'));
            }
            if($permohonan->user) $permohonan->user->notify(new StatusPermohonanNotification($permohonan, 'Disetujui Admin, sedang diproses SPSI.'));
        } elseif ($request->status_permohonan === 'Ditolak') {
            // Notifikasi saat ditolak
            if($permohonan->user) $permohonan->user->notify(new StatusPermohonanNotification($permohonan, 'Mohon maaf, permohonan Anda ditolak oleh Admin.'));
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
        
        // Wajib divalidasi agar memastikan mobil/supir dipilih dengan benar
        $request->validate([
            'kendaraan_id' => 'required|exists:kendaraans,id',
            'pengemudi_id' => 'nullable|exists:pengemudis,id',
            'estimasi_biaya_operasional' => 'required|numeric|min:0',
        ]);

        $permohonan->update([
            'kendaraan_id' => $request->kendaraan_id,
            'pengemudi_id' => $request->pengemudi_id,
            'estimasi_biaya_operasional' => $request->estimasi_biaya_operasional,
            'status_permohonan' => 'Menunggu Proses Keuangan',
        ]);

        foreach (User::where('role', 'keuangan')->get() as $keuangan) {
            $keuangan->notify(new StatusPermohonanNotification($permohonan, 'Armada dialokasikan, cek RAB.'));
        }
        if($permohonan->user) $permohonan->user->notify(new StatusPermohonanNotification($permohonan, 'Armada siap, menunggu verifikasi keuangan.'));
        
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

        $admins = User::where('role', 'kepala_admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new StatusPermohonanNotification($permohonan, 'RAB disetujui. Siap difinalisasi.'));
        }

        if ($permohonan->user) {
            $permohonan->user->notify(new StatusPermohonanNotification($permohonan, 'Anggaran disetujui Keuangan! Tinggal menunggu finalisasi akhir dari Admin.'));
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
        // Pengecekan Auth::id() dihapus agar Admin/SPSI/Keuangan juga bisa buka detailnya
        $permohonan = Permohonan::with(['kendaraan', 'pengemudi', 'user'])->findOrFail($id);
        
        return view('permohonan.show', compact('permohonan'));
    }

    public function selesaikanSewa($id)
    {
        // Pengecekan Auth::id() DIBIARKAN di sini, karena memang hanya pengguna yang berhak mengklik tombol "Selesai"
        $permohonan = Permohonan::where('user_id', Auth::id())->findOrFail($id);
        $permohonan->update(['status_permohonan' => 'Selesai']);
        
        return redirect()->route('dashboard')->with('success', 'Terima kasih, perjalanan telah diselesaikan.');
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

    // --- ADMIN ---
    public function adminValidasi()
    {
        $permohonans = Permohonan::where('status_permohonan', 'Menunggu Validasi Admin')->orderBy('created_at', 'desc')->get();
        return view('dashboard.admin', compact('permohonans'))->with('judul', 'Validasi Permohonan Masuk');
    }

    public function adminFinalisasi()
    {
        $permohonans = Permohonan::where('status_permohonan', 'Menunggu Finalisasi')->orderBy('updated_at', 'desc')->get();
        return view('dashboard.admin', compact('permohonans'))->with('judul', 'Finalisasi Penerbitan');
    }

    public function adminRiwayat()
    {
        $permohonans = Permohonan::whereIn('status_permohonan', ['Disetujui', 'Selesai', 'Ditolak'])->orderBy('updated_at', 'desc')->get();
        return view('dashboard.admin', compact('permohonans'))->with('judul', 'Arsip & Riwayat');
    }

    // --- SPSI ---
    public function spsiAlokasi()
    {
        $permohonans = Permohonan::where('status_permohonan', 'Menunggu Proses SPSI')->orderBy('updated_at', 'desc')->get();
        return view('dashboard.spsi', compact('permohonans'))->with('judul', 'Penugasan Armada');
    }

    public function spsiMonitoring()
    {
        $permohonans = Permohonan::whereIn('status_permohonan', ['Menunggu Proses Keuangan', 'Menunggu Finalisasi', 'Disetujui', 'Selesai'])
            ->orderBy('updated_at', 'desc')->get();
        return view('dashboard.spsi', compact('permohonans'))->with('judul', 'Pantauan & Riwayat Armada');
    }

    // --- KEUANGAN ---
    public function keuanganRab()
    {
        $permohonans = Permohonan::where('status_permohonan', 'Menunggu Proses Keuangan')->orderBy('updated_at', 'desc')->get();
        return view('dashboard.keuangan', compact('permohonans'))->with('judul', 'Persetujuan Anggaran (RAB)');
    }

    public function keuanganMonitoring()
    {
        $permohonans = Permohonan::whereIn('status_permohonan', ['Menunggu Finalisasi', 'Disetujui', 'Selesai'])
            ->orderBy('updated_at', 'desc')->get();
        return view('dashboard.keuangan', compact('permohonans'))->with('judul', 'Pantauan Anggaran');
    }

    public function cetakSuratJalan($id)
    {
        $permohonan = Permohonan::with(['kendaraan', 'pengemudi', 'user'])->findOrFail($id);
        
        // Keamanan: Hanya status Disetujui/Selesai yang bisa dicetak
        if (!in_array($permohonan->status_permohonan, ['Disetujui', 'Selesai'])) {
            return redirect()->back()->with('error', 'Dokumen belum tersedia untuk dicetak.');
        }

        return view('permohonan.cetak', compact('permohonan'));
    }
}
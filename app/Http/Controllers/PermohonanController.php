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
        $user = Auth::user();

        if ($user->role === 'pengguna') {
            // Pengguna tetap melihat riwayatnya di dashboard (atau bisa dipisah nanti)
            $permohonans = Permohonan::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')->get();
            return view('dashboard.pengguna', compact('permohonans'));
        }

        // Untuk Admin, SPSI, dan Keuangan akan diarahkan ke halaman Dashboard Umum
        // (Nanti bisa kita isi dengan grafik / rekap data)
        return view('dashboard');
    }

    // --- HALAMAN TUGAS MASING-MASING DIVISI ---

    public function tugasAdmin()
    {
        // Admin melihat semua permohonan
        $permohonans = Permohonan::orderBy('created_at', 'desc')->get();
        return view('dashboard.admin', compact('permohonans')); // Kita gunakan view yang sama (yang sudah responsive)
    }

    public function tugasSpsi()
    {
        // Filter khusus SPSI
        $permohonans = Permohonan::whereIn('status_permohonan', [
            'Menunggu Proses SPSI',
            'Menunggu Proses Keuangan',
            'Menunggu Finalisasi',
            'Disetujui',
            'Selesai'
        ])->orderBy('updated_at', 'desc')->get();
        return view('dashboard.spsi', compact('permohonans'));
    }

    public function tugasKeuangan()
    {
        // Filter khusus Keuangan
        $permohonans = Permohonan::whereIn('status_permohonan', [
            'Menunggu Proses Keuangan',
            'Menunggu Finalisasi',
            'Disetujui',
            'Selesai'
        ])->orderBy('updated_at', 'desc')->get();
        return view('dashboard.keuangan', compact('permohonans'));
    }

    public function create()
    {
        return view('permohonan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pic' => 'required',
            'kontak_pic' => 'required',
            'kendaraan_dibutuhkan' => 'required',
            'titik_jemput' => 'required',
            'tujuan' => 'required',
            'waktu_berangkat' => 'required',
            'waktu_kembali' => 'required',
            'jumlah_penumpang' => 'required',
            'file_surat' => 'required|mimes:pdf,jpg,jpeg,png|max:2048',
            'anggaran_diajukan' => 'required',
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
        $permohonan->update($request->all());

        if ($request->status_permohonan === 'Menunggu Proses SPSI') {
            $spsis = User::where('role', 'spsi')->get();
            foreach ($spsis as $spsi) {
                $spsi->notify(new StatusPermohonanNotification($permohonan, 'Butuh alokasi armada.'));
            }
            $permohonan->user->notify(new StatusPermohonanNotification($permohonan, 'Disetujui Admin, sedang diproses SPSI.'));
        }
        return redirect()->route('admin.tugas')->with('success', 'Permohonan divalidasi.');
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
        $permohonan->update($request->all() + ['status_permohonan' => 'Menunggu Proses Keuangan']);

        foreach (User::where('role', 'keuangan')->get() as $keuangan) {
            $keuangan->notify(new StatusPermohonanNotification($permohonan, 'Armada dialokasikan, cek RAB.'));
        }
        $permohonan->user->notify(new StatusPermohonanNotification($permohonan, 'Armada siap, menunggu verifikasi keuangan.'));
        return redirect()->route('spsi.tugas')->with('success', 'Armada dialokasikan.');
    }

    public function prosesKeuanganForm($id)
    {
        return view('permohonan.proses_keuangan', ['permohonan' => Permohonan::with(['kendaraan', 'pengemudi'])->findOrFail($id)]);
    }

    public function prosesKeuanganSubmit(Request $request, $id)
    {
        $permohonan = Permohonan::findOrFail($id);
        $permohonan->update($request->all() + ['status_permohonan' => 'Menunggu Finalisasi']);

        foreach (User::where('role', 'kepala_admin')->get() as $admin) {
            $admin->notify(new StatusPermohonanNotification($permohonan, 'RAB disetujui, siap finalisasi.'));
        }
        return redirect()->route('keuangan.tugas')->with('success', 'Anggaran disetujui.');
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
        return redirect()->route('admin.tugas')->with('success', 'Permohonan difinalisasi.');
    }

    public function show($id)
    {
        return view('permohonan.show', ['permohonan' => Permohonan::with(['kendaraan', 'pengemudi'])->where('user_id', Auth::id())->findOrFail($id)]);
    }

    public function selesaikanSewa($id)
    {
        Permohonan::where('user_id', Auth::id())->findOrFail($id)->update(['status_permohonan' => 'Selesai']);
        return redirect()->route('dashboard');
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
}

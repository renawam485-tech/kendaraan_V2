<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\Pengemudi;
use App\Models\Permohonan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SuperAdminController extends Controller
{
    // =========================================================
    // DASHBOARD SUPER ADMIN
    // =========================================================
    public function dashboard()
    {
        $stats = [
            'total_pengguna'    => User::where('role', 'pengguna')->count(),
            'total_kendaraan'   => Kendaraan::count(),
            'kendaraan_tersedia'=> Kendaraan::where('status_kendaraan', 'Tersedia')->count(),
            'kendaraan_dipinjam'=> Kendaraan::where('status_kendaraan', 'Dipinjam')->count(),
            'kendaraan_maintenance' => Kendaraan::where('status_kendaraan', 'Maintenance')->count(),
            'total_pengemudi'   => Pengemudi::count(),
            'pengemudi_tersedia'=> Pengemudi::where('status_pengemudi', 'Tersedia')->count(),
            'total_permohonan'  => Permohonan::count(),
            'permohonan_aktif'  => Permohonan::whereIn('status_permohonan', [
                'Menunggu Validasi Admin', 'Menunggu Proses SPSI',
                'Menunggu Proses Keuangan', 'Menunggu Finalisasi', 'Disetujui',
                'Menunggu Pengembalian Dana', 'Menunggu Verifikasi Pengembalian',
            ])->count(),
            'permohonan_selesai'=> Permohonan::where('status_permohonan', 'Selesai')->count(),
            'permohonan_ditolak'=> Permohonan::where('status_permohonan', 'Ditolak')->count(),
            'total_rab'         => Permohonan::whereIn('status_permohonan', ['Disetujui','Selesai'])->sum('rab_disetujui'),
        ];

        $permohonanTerbaru  = Permohonan::with('user')->latest()->take(8)->get();
        $kendaraanList      = Kendaraan::orderBy('status_kendaraan')->get();

        return view('superadmin.dashboard', compact('stats', 'permohonanTerbaru', 'kendaraanList'));
    }

    // =========================================================
    // CRUD KENDARAAN
    // =========================================================
    public function kendaraanIndex()
    {
        $kendaraans = Kendaraan::orderBy('nama_kendaraan')->paginate(15);
        return view('superadmin.kendaraan.index', compact('kendaraans'));
    }

    public function kendaraanCreate()
    {
        return view('superadmin.kendaraan.form', ['kendaraan' => null]);
    }

    public function kendaraanStore(Request $request)
    {
        $request->validate([
            'nama_kendaraan'       => 'required|string|max:100',
            'plat_nomor'           => 'required|string|max:15|unique:kendaraans,plat_nomor',
            'kapasitas_penumpang'  => 'required|integer|min:1|max:80',
            'status_kendaraan'     => 'required|in:Tersedia,Maintenance,Dipinjam',
        ]);

        Kendaraan::create($request->only(['nama_kendaraan','plat_nomor','kapasitas_penumpang','status_kendaraan']));

        return redirect()->route('superadmin.kendaraan.index')
            ->with('success', 'Kendaraan berhasil ditambahkan.');
    }

    public function kendaraanEdit($id)
    {
        $kendaraan = Kendaraan::findOrFail($id);
        return view('superadmin.kendaraan.form', compact('kendaraan'));
    }

    public function kendaraanUpdate(Request $request, $id)
    {
        $kendaraan = Kendaraan::findOrFail($id);

        $request->validate([
            'nama_kendaraan'       => 'required|string|max:100',
            'plat_nomor'           => 'required|string|max:15|unique:kendaraans,plat_nomor,' . $id,
            'kapasitas_penumpang'  => 'required|integer|min:1|max:80',
            'status_kendaraan'     => 'required|in:Tersedia,Maintenance,Dipinjam',
        ]);

        $kendaraan->update($request->only(['nama_kendaraan','plat_nomor','kapasitas_penumpang','status_kendaraan']));

        return redirect()->route('superadmin.kendaraan.index')
            ->with('success', 'Data kendaraan berhasil diperbarui.');
    }

    public function kendaraanDestroy($id)
    {
        $kendaraan = Kendaraan::findOrFail($id);

        // Cegah hapus jika sedang dipinjam/bertugas
        if ($kendaraan->status_kendaraan === 'Dipinjam') {
            return redirect()->route('superadmin.kendaraan.index')
                ->with('error', 'Kendaraan sedang dalam status Dipinjam dan tidak dapat dihapus.');
        }

        $kendaraan->delete();
        return redirect()->route('superadmin.kendaraan.index')
            ->with('success', 'Kendaraan berhasil dihapus.');
    }

    // =========================================================
    // CRUD PENGEMUDI
    // =========================================================
    public function pengemudiIndex()
    {
        $pengemudis = Pengemudi::orderBy('nama_pengemudi')->paginate(15);
        return view('superadmin.pengemudi.index', compact('pengemudis'));
    }

    public function pengemudiCreate()
    {
        return view('superadmin.pengemudi.form', ['pengemudi' => null]);
    }

    public function pengemudiStore(Request $request)
    {
        $request->validate([
            'nama_pengemudi'  => 'required|string|max:100',
            'kontak'          => ['required','string','regex:/^\+62[0-9]{8,15}$/'],
            'status_pengemudi'=> 'required|in:Tersedia,Bertugas',
        ], [
            'kontak.regex' => 'Format kontak harus diawali +62 dan berisi 8-15 angka.',
        ]);

        Pengemudi::create($request->only(['nama_pengemudi','kontak','status_pengemudi']));

        return redirect()->route('superadmin.pengemudi.index')
            ->with('success', 'Pengemudi berhasil ditambahkan.');
    }

    public function pengemudiEdit($id)
    {
        $pengemudi = Pengemudi::findOrFail($id);
        return view('superadmin.pengemudi.form', compact('pengemudi'));
    }

    public function pengemudiUpdate(Request $request, $id)
    {
        $pengemudi = Pengemudi::findOrFail($id);

        $request->validate([
            'nama_pengemudi'  => 'required|string|max:100',
            'kontak'          => ['required','string','regex:/^\+62[0-9]{8,15}$/'],
            'status_pengemudi'=> 'required|in:Tersedia,Bertugas',
        ], [
            'kontak.regex' => 'Format kontak harus diawali +62 dan berisi 8-15 angka.',
        ]);

        $pengemudi->update($request->only(['nama_pengemudi','kontak','status_pengemudi']));

        return redirect()->route('superadmin.pengemudi.index')
            ->with('success', 'Data pengemudi berhasil diperbarui.');
    }

    public function pengemudiDestroy($id)
    {
        $pengemudi = Pengemudi::findOrFail($id);

        if ($pengemudi->status_pengemudi === 'Bertugas') {
            return redirect()->route('superadmin.pengemudi.index')
                ->with('error', 'Pengemudi sedang Bertugas dan tidak dapat dihapus.');
        }

        $pengemudi->delete();
        return redirect()->route('superadmin.pengemudi.index')
            ->with('success', 'Pengemudi berhasil dihapus.');
    }

    // =========================================================
    // CRUD MANAJEMEN PENGGUNA & ROLE
    // =========================================================
    public function usersIndex(Request $request)
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->orderBy('role')->orderBy('name')->paginate(15)->withQueryString();
        return view('superadmin.users.index', compact('users'));
    }

    public function usersCreate()
    {
        return view('superadmin.users.form', ['user' => null]);
    }

    public function usersStore(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|in:pengguna,kepala_admin,spsi,keuangan,super_admin',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        return redirect()->route('superadmin.users.index')
            ->with('success', 'Pengguna berhasil dibuat.');
    }

    public function usersEdit($id)
    {
        $user = User::findOrFail($id);
        return view('superadmin.users.form', compact('user'));
    }

    public function usersUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'role'     => 'required|in:pengguna,kepala_admin,spsi,keuangan,super_admin',
        ]);

        $data = [
            'name'  => $request->name,
            'email' => $request->email,
            'role'  => $request->role,
        ];
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('superadmin.users.index')
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function usersDestroy($id)
    {
        $user = User::findOrFail($id);

        // Cegah hapus akun sendiri
        if ($user->id === auth()->id()) {
            return redirect()->route('superadmin.users.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();
        return redirect()->route('superadmin.users.index')
            ->with('success', 'Pengguna berhasil dihapus.');
    }
}
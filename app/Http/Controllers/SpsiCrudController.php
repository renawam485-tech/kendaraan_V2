<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\KendaraanVendor;
use App\Models\Pengemudi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SpsiCrudController extends Controller
{
    public function usersIndex(Request $request)
    {
        $query = User::query();
        
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        $users = $query->orderBy('role')->orderBy('name')->paginate(15)->withQueryString();
        
        return view('crud.users.index', compact('users'));
    }
    
    /**
     * SPSI hanya bisa MELIHAT detail user
     */
    public function usersShow($id)
    {
        $user = User::findOrFail($id);
        return view('crud.users.show', compact('user'));
    }
    
    // =========================================================
    // CRUD KENDARAAN KAMPUS (FULL untuk SPSI)
    // =========================================================
    
    public function kendaraanIndex(Request $request)
    {
        $query = Kendaraan::query();
        
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_kendaraan', 'like', "%{$search}%")
                    ->orWhere('plat_nomor', 'like', "%{$search}%");
            });
        }
        
        $kendaraans = $query->orderBy('nama_kendaraan')->paginate(15)->withQueryString();
        
        return view('crud.kendaraan.index', compact('kendaraans'));
    }
    
    public function kendaraanCreate()
    {
        return view('crud.kendaraan.form', ['kendaraan' => null]);
    }
    
    public function kendaraanStore(Request $request)
    {
        $request->validate([
            'nama_kendaraan'      => 'required|string|max:100',
            'plat_nomor'          => 'required|string|max:15|unique:kendaraans,plat_nomor',
            'kapasitas_penumpang' => 'required|integer|min:1|max:80',
            'status_kendaraan'    => 'required|in:Tersedia,Maintenance,Dipinjam',
        ]);
        
        Kendaraan::create($request->only(['nama_kendaraan', 'plat_nomor', 'kapasitas_penumpang', 'status_kendaraan']));
        
        return redirect()->route('crud.kendaraan.index')->with('success', 'Kendaraan berhasil ditambahkan.');
    }
    
    public function kendaraanEdit($id)
    {
        return view('crud.kendaraan.form', ['kendaraan' => Kendaraan::findOrFail($id)]);
    }
    
    public function kendaraanUpdate(Request $request, $id)
    {
        $kendaraan = Kendaraan::findOrFail($id);
        
        $request->validate([
            'nama_kendaraan'      => 'required|string|max:100',
            'plat_nomor'          => 'required|string|max:15|unique:kendaraans,plat_nomor,' . $id,
            'kapasitas_penumpang' => 'required|integer|min:1|max:80',
            'status_kendaraan'    => 'required|in:Tersedia,Maintenance,Dipinjam',
        ]);
        
        $kendaraan->update($request->only(['nama_kendaraan', 'plat_nomor', 'kapasitas_penumpang', 'status_kendaraan']));
        
        return redirect()->route('crud.kendaraan.index')->with('success', 'Data kendaraan berhasil diperbarui.');
    }
    
    public function kendaraanDestroy($id)
    {
        $kendaraan = Kendaraan::findOrFail($id);
        
        if ($kendaraan->status_kendaraan === 'Dipinjam') {
            return redirect()->route('crud.kendaraan.index')
                ->with('error', 'Kendaraan sedang Dipinjam dan tidak dapat dihapus.');
        }
        
        $kendaraan->delete();
        return redirect()->route('crud.kendaraan.index')->with('success', 'Kendaraan berhasil dihapus.');
    }
    
    // =========================================================
    // CRUD KENDARAAN VENDOR (FULL untuk SPSI)
    // =========================================================
    
    public function kendaraanVendorIndex(Request $request)
    {
        $query = KendaraanVendor::query();
        
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_kendaraan', 'like', "%{$search}%")
                    ->orWhere('plat_nomor', 'like', "%{$search}%")
                    ->orWhere('nama_vendor', 'like', "%{$search}%");
            });
        }
        
        $vendors = $query->orderBy('nama_vendor')->paginate(15)->withQueryString();
        
        return view('crud.kendaraan_vendor.index', compact('vendors'));
    }
    
    public function kendaraanVendorCreate()
    {
        return view('crud.kendaraan_vendor.form', ['vendor' => null]);
    }
    
    public function kendaraanVendorStore(Request $request)
    {
        $request->validate([
            'nama_vendor'         => 'required|string|max:100',
            'nama_kendaraan'      => 'required|string|max:100',
            'plat_nomor'          => 'required|string|max:15|unique:kendaraan_vendors,plat_nomor',
            'kapasitas_penumpang' => 'required|integer|min:1|max:80',
            'status_kendaraan'    => 'required|in:Tersedia,Tidak Tersedia',
        ]);
        
        KendaraanVendor::create($request->only([
            'nama_vendor', 'nama_kendaraan', 'plat_nomor', 'kapasitas_penumpang', 'status_kendaraan'
        ]));
        
        return redirect()->route('crud.kendaraan_vendor.index')->with('success', 'Kendaraan vendor berhasil ditambahkan.');
    }
    
    public function kendaraanVendorEdit($id)
    {
        return view('crud.kendaraan_vendor.form', ['vendor' => KendaraanVendor::findOrFail($id)]);
    }
    
    public function kendaraanVendorUpdate(Request $request, $id)
    {
        $vendor = KendaraanVendor::findOrFail($id);
        
        $request->validate([
            'nama_vendor'         => 'required|string|max:100',
            'nama_kendaraan'      => 'required|string|max:100',
            'plat_nomor'          => 'required|string|max:15|unique:kendaraan_vendors,plat_nomor,' . $id,
            'kapasitas_penumpang' => 'required|integer|min:1|max:80',
            'status_kendaraan'    => 'required|in:Tersedia,Tidak Tersedia',
        ]);
        
        $vendor->update($request->only([
            'nama_vendor', 'nama_kendaraan', 'plat_nomor', 'kapasitas_penumpang', 'status_kendaraan'
        ]));
        
        return redirect()->route('crud.kendaraan_vendor.index')->with('success', 'Data kendaraan vendor berhasil diperbarui.');
    }
    
    public function kendaraanVendorDestroy($id)
    {
        $vendor = KendaraanVendor::findOrFail($id);
        
        // Cek apakah vendor sedang dipinjam (jika ada relasi ke permohonan)
        $isUsed = \App\Models\Permohonan::where('kendaraan_vendor_id', $id)
            ->whereNotIn('status_permohonan', ['selesai', 'ditolak'])
            ->exists();
        
        if ($isUsed) {
            return redirect()->route('crud.kendaraan_vendor.index')
                ->with('error', 'Kendaraan vendor sedang digunakan dan tidak dapat dihapus.');
        }
        
        $vendor->delete();
        return redirect()->route('crud.kendaraan_vendor.index')->with('success', 'Kendaraan vendor berhasil dihapus.');
    }
    
    // =========================================================
    // CRUD PENGEMUDI (FULL untuk SPSI)
    // =========================================================
    
    public function pengemudiIndex(Request $request)
    {
        $query = Pengemudi::query();
        
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_pengemudi', 'like', "%{$search}%")
                    ->orWhere('kontak', 'like', "%{$search}%");
            });
        }
        
        $pengemudis = $query->orderBy('nama_pengemudi')->paginate(15)->withQueryString();
        
        return view('crud.pengemudi.index', compact('pengemudis'));
    }
    
    public function pengemudiCreate()
    {
        return view('crud.pengemudi.form', ['pengemudi' => null]);
    }
    
    public function pengemudiStore(Request $request)
    {
        $request->validate([
            'nama_pengemudi'   => 'required|string|max:100',
            'kontak'           => ['required', 'string', 'regex:/^\+62[0-9]{8,15}$/'],
            'status_pengemudi' => 'required|in:Tersedia,Bertugas',
        ], ['kontak.regex' => 'Format kontak harus diawali +62 dan berisi 8-15 angka.']);
        
        Pengemudi::create($request->only(['nama_pengemudi', 'kontak', 'status_pengemudi']));
        
        return redirect()->route('crud.pengemudi.index')->with('success', 'Pengemudi berhasil ditambahkan.');
    }
    
    public function pengemudiEdit($id)
    {
        return view('crud.pengemudi.form', ['pengemudi' => Pengemudi::findOrFail($id)]);
    }
    
    public function pengemudiUpdate(Request $request, $id)
    {
        $pengemudi = Pengemudi::findOrFail($id);
        
        $request->validate([
            'nama_pengemudi'   => 'required|string|max:100',
            'kontak'           => ['required', 'string', 'regex:/^\+62[0-9]{8,15}$/'],
            'status_pengemudi' => 'required|in:Tersedia,Bertugas',
        ], ['kontak.regex' => 'Format kontak harus diawali +62 dan berisi 8-15 angka.']);
        
        $pengemudi->update($request->only(['nama_pengemudi', 'kontak', 'status_pengemudi']));
        
        return redirect()->route('crud.pengemudi.index')->with('success', 'Data pengemudi berhasil diperbarui.');
    }
    
    public function pengemudiDestroy($id)
    {
        $pengemudi = Pengemudi::findOrFail($id);
        
        if ($pengemudi->status_pengemudi === 'Bertugas') {
            return redirect()->route('crud.pengemudi.index')
                ->with('error', 'Pengemudi sedang Bertugas dan tidak dapat dihapus.');
        }
        
        $pengemudi->delete();
        return redirect()->route('crud.pengemudi.index')->with('success', 'Pengemudi berhasil dihapus.');
    }
}
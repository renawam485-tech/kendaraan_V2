<?php

namespace App\Http\Controllers;

use App\Models\KendaraanVendor;
use Illuminate\Http\Request;

class KendaraanVendorController extends Controller
{
    public function index()
    {
        $vendors = KendaraanVendor::orderBy('nama_vendor', 'asc')->paginate(10);
        return view('superadmin.kendaraan_vendor.index', compact('vendors'));
    }

    public function create()
    {
        $vendor = null;
        return view('superadmin.kendaraan_vendor.form', compact('vendor'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_vendor' => 'required|string|max:255',
            'nama_kendaraan' => 'required|string|max:255',
            'plat_nomor' => 'nullable|string|max:255|unique:kendaraan_vendors,plat_nomor',
            'kapasitas_penumpang' => 'nullable|integer|min:1',
            'status_kendaraan' => 'required|in:Tersedia,Tidak Tersedia',
        ]);

        KendaraanVendor::create($request->all());

        return redirect()->route('superadmin.kendaraan_vendor.index')
                         ->with('success', 'Data mobil vendor berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $vendor = KendaraanVendor::findOrFail($id);
        return view('superadmin.kendaraan_vendor.form', compact('vendor'));
    }

    public function update(Request $request, $id)
    {
        $vendor = KendaraanVendor::findOrFail($id);

        $request->validate([
            'nama_vendor' => 'required|string|max:255',
            'nama_kendaraan' => 'required|string|max:255',
            'plat_nomor' => 'nullable|string|max:255|unique:kendaraan_vendors,plat_nomor,' . $id,
            'kapasitas_penumpang' => 'nullable|integer|min:1',
            'status_kendaraan' => 'required|in:Tersedia,Tidak Tersedia',
        ]);

        $vendor->update($request->all());

        return redirect()->route('superadmin.kendaraan_vendor.index')
                         ->with('success', 'Data mobil vendor berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $vendor = KendaraanVendor::findOrFail($id);
        $vendor->delete();

        return redirect()->route('superadmin.kendaraan_vendor.index')
                         ->with('success', 'Data mobil vendor berhasil dihapus.');
    }
}
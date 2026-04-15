@php
    $userRole = Auth::user()->role;
    $isSuperAdmin = $userRole === 'super_admin';
    $isSpsi = $userRole === 'spsi';
    $actionRoute = $vendor 
        ? ($isSuperAdmin ? route('superadmin.kendaraan_vendor.update', $vendor->id) : route('spsi.kendaraan_vendor.update', $vendor->id))
        : ($isSuperAdmin ? route('superadmin.kendaraan_vendor.store') : route('spsi.kendaraan_vendor.store'));
    $indexRoute = $isSuperAdmin ? route('superadmin.kendaraan_vendor.index') : route('spsi.kendaraan_vendor.index');
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            {{ $vendor ? 'Edit Mobil Vendor: ' . $vendor->nama_vendor : 'Tambah Mobil Vendor Baru' }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 md:p-8">

                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 p-4 rounded-lg flex gap-3 items-start">
                        <i class="bi bi-exclamation-triangle-fill text-red-500 mt-0.5"></i>
                        <div>
                            <p class="text-red-700 font-bold text-sm mb-1">Mohon perbaiki kesalahan berikut:</p>
                            <ul class="text-sm text-red-600 list-disc list-inside">
                                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form action="{{ $actionRoute }}" method="POST" class="space-y-6">
                    @csrf
                    @if($vendor) @method('PUT') @endif

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nama Perusahaan Vendor <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_vendor" value="{{ old('nama_vendor', $vendor->nama_vendor ?? '') }}" required 
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nama / Merk Kendaraan <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_kendaraan" value="{{ old('nama_kendaraan', $vendor->nama_kendaraan ?? '') }}" required 
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Plat Nomor <span class="text-red-500">*</span></label>
                            <input type="text" name="plat_nomor" value="{{ old('plat_nomor', $vendor->plat_nomor ?? '') }}" required 
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50 uppercase" 
                                   placeholder="D 1234 ABC">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Kapasitas Penumpang <span class="text-red-500">*</span></label>
                            <input type="number" name="kapasitas_penumpang" value="{{ old('kapasitas_penumpang', $vendor->kapasitas_penumpang ?? '') }}" 
                                   required min="1" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Status Ketersediaan <span class="text-red-500">*</span></label>
                        <select name="status_kendaraan" required class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                            <option value="Tersedia" {{ old('status_kendaraan', $vendor->status_kendaraan ?? 'Tersedia') === 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                            <option value="Tidak Tersedia" {{ old('status_kendaraan', $vendor->status_kendaraan ?? '') === 'Tidak Tersedia' ? 'selected' : '' }}>Tidak Tersedia</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-3 pt-6 border-t border-gray-100">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-lg shadow-sm transition flex items-center gap-2">
                            <i class="bi bi-save"></i> {{ $vendor ? 'Simpan Perubahan' : 'Tambah Mobil Vendor' }}
                        </button>
                        <a href="{{ $indexRoute }}" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 font-bold py-2.5 px-6 rounded-lg transition">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
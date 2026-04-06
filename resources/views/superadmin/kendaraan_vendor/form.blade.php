<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            {{ $vendor ? 'Edit Mobil Vendor: ' . $vendor->nama_vendor : 'Tambah Mobil Vendor Baru' }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-8">

                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 p-4 rounded-md">
                        <p class="text-red-700 font-bold text-sm mb-1">Mohon perbaiki kesalahan:</p>
                        <ul class="text-sm text-red-600 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ $vendor ? route('superadmin.kendaraan_vendor.update', $vendor->id) : route('superadmin.kendaraan_vendor.store') }}"
                      method="POST" class="space-y-6">
                    @csrf
                    @if($vendor) @method('PUT') @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Nama Vendor / Rental <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_vendor" value="{{ old('nama_vendor', $vendor->nama_vendor ?? '') }}" required placeholder="Contoh: TRAC, Gocar" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Nama Mobil <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_kendaraan" value="{{ old('nama_kendaraan', $vendor->nama_kendaraan ?? '') }}" required placeholder="Contoh: Toyota Hiace" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Plat Nomor (Opsional)</label>
                            <input type="text" name="plat_nomor" value="{{ old('plat_nomor', $vendor->plat_nomor ?? '') }}" placeholder="Contoh: B 1234 CD" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500">
                            <p class="text-xs text-gray-500 mt-1">Kosongkan jika sistem pesanan acak seperti Taksi Online.</p>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Kapasitas Penumpang</label>
                            <input type="number" name="kapasitas_penumpang" value="{{ old('kapasitas_penumpang', $vendor->kapasitas_penumpang ?? '') }}" min="1" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Status Ketersediaan <span class="text-red-500">*</span></label>
                        <select name="status_kendaraan" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500">
                            <option value="Tersedia" {{ old('status_kendaraan', $vendor->status_kendaraan ?? 'Tersedia') === 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                            <option value="Tidak Tersedia" {{ old('status_kendaraan', $vendor->status_kendaraan ?? '') === 'Tidak Tersedia' ? 'selected' : '' }}>Tidak Tersedia</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Ubah ke <strong>Tidak Tersedia</strong> jika kontrak dengan vendor ini sedang dihentikan.</p>
                    </div>

                    <div class="flex gap-4 pt-4 border-t border-gray-100">
                        <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2.5 px-6 rounded-md shadow-sm transition">
                            {{ $vendor ? 'Simpan Perubahan' : 'Tambah Mobil Vendor' }}
                        </button>
                        <a href="{{ route('superadmin.kendaraan_vendor.index') }}" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 font-bold py-2.5 px-6 rounded-md transition">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
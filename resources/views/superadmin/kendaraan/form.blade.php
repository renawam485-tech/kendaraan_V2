<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            {{ $kendaraan ? 'Edit Kendaraan: ' . $kendaraan->nama_kendaraan : 'Tambah Kendaraan Baru' }}
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

                <form action="{{ $kendaraan ? route('superadmin.kendaraan.update', $kendaraan->id) : route('superadmin.kendaraan.store') }}"
                      method="POST" class="space-y-6">
                    @csrf
                    @if($kendaraan) @method('PUT') @endif

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nama Kendaraan <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_kendaraan" value="{{ old('nama_kendaraan', $kendaraan->nama_kendaraan ?? '') }}"
                            required maxlength="100" placeholder="Contoh: Toyota HiAce Commuter"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Plat Nomor <span class="text-red-500">*</span></label>
                            <input type="text" name="plat_nomor" value="{{ old('plat_nomor', $kendaraan->plat_nomor ?? '') }}"
                                required maxlength="15" placeholder="Contoh: B 1234 ABC"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500 font-mono uppercase">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Kapasitas Penumpang <span class="text-red-500">*</span></label>
                            <input type="number" name="kapasitas_penumpang" value="{{ old('kapasitas_penumpang', $kendaraan->kapasitas_penumpang ?? '') }}"
                                required min="1" max="80" placeholder="Jumlah kursi"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Status Kendaraan <span class="text-red-500">*</span></label>
                        <select name="status_kendaraan" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500">
                            @foreach(['Tersedia','Maintenance','Dipinjam'] as $s)
                                <option value="{{ $s }}" {{ old('status_kendaraan', $kendaraan->status_kendaraan ?? 'Tersedia') === $s ? 'selected' : '' }}>
                                    {{ $s }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Gunakan <strong>Maintenance</strong> untuk kendaraan yang sedang dalam perawatan dan tidak bisa dipinjam.</p>
                    </div>

                    <div class="flex gap-4 pt-4 border-t border-gray-100">
                        <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2.5 px-6 rounded-md shadow-sm transition">
                            {{ $kendaraan ? 'Simpan Perubahan' : 'Tambah Kendaraan' }}
                        </button>
                        <a href="{{ route('superadmin.kendaraan.index') }}" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 font-bold py-2.5 px-6 rounded-md transition">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
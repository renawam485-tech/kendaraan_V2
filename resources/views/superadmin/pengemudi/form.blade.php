<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            {{ $pengemudi ? 'Edit Pengemudi: ' . $pengemudi->nama_pengemudi : 'Tambah Pengemudi Baru' }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8">
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

                <form action="{{ $pengemudi ? route('superadmin.pengemudi.update', $pengemudi->id) : route('superadmin.pengemudi.store') }}" method="POST" class="space-y-6">
                    @csrf
                    @if($pengemudi) @method('PUT') @endif

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nama Lengkap Pengemudi <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_pengemudi" value="{{ old('nama_pengemudi', $pengemudi->nama_pengemudi ?? '') }}" required class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nomor Kontak / WhatsApp <span class="text-red-500">*</span></label>
                        <input type="text" id="kontak_input" name="kontak" value="{{ old('kontak', $pengemudi->kontak ?? '+62') }}" required class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                        <p class="text-xs text-gray-500 mt-1">Harus diawali dengan +62</p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Status Saat Ini <span class="text-red-500">*</span></label>
                        <select name="status_pengemudi" required class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                            @foreach(['Tersedia','Bertugas'] as $s)
                                <option value="{{ $s }}" {{ old('status_pengemudi', $pengemudi->status_pengemudi ?? 'Tersedia') === $s ? 'selected' : '' }}>{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center gap-3 pt-6 border-t border-gray-100">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-lg shadow-sm transition flex items-center gap-2">
                            <i class="bi bi-save"></i> {{ $pengemudi ? 'Simpan Perubahan' : 'Tambah Pengemudi' }}
                        </button>
                        <a href="{{ route('superadmin.pengemudi.index') }}" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 font-bold py-2.5 px-6 rounded-lg transition">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('kontak_input').addEventListener('input', function () {
            let val = this.value;
            if (!val.startsWith('+62')) { val = '+62' + val.replace(/[^0-9]/g, ''); }
            else { val = '+62' + val.substring(3).replace(/[^0-9]/g, ''); }
            this.value = val;
        });
    </script>
</x-app-layout>
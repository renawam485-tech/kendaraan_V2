<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            {{ $pengemudi ? 'Edit Pengemudi: ' . $pengemudi->nama_pengemudi : 'Tambah Pengemudi Baru' }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-8">

                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 p-4 rounded-md">
                        <p class="text-red-700 font-bold text-sm mb-1">Mohon perbaiki kesalahan:</p>
                        <ul class="text-sm text-red-600 list-disc list-inside">
                            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ $pengemudi ? route('superadmin.pengemudi.update', $pengemudi->id) : route('superadmin.pengemudi.store') }}"
                      method="POST" class="space-y-6">
                    @csrf
                    @if($pengemudi) @method('PUT') @endif

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nama Pengemudi <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_pengemudi" value="{{ old('nama_pengemudi', $pengemudi->nama_pengemudi ?? '') }}"
                            required maxlength="100" placeholder="Nama lengkap pengemudi"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nomor Kontak <span class="text-red-500">*</span></label>
                        <input type="text" name="kontak" id="kontak_input"
                            value="{{ old('kontak', $pengemudi->kontak ?? '+62') }}"
                            required placeholder="+62812XXXXXXX"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500 font-mono">
                        <p class="text-xs text-gray-500 mt-1">Format: diawali +62 (contoh: +6281234567890)</p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Status Pengemudi <span class="text-red-500">*</span></label>
                        <select name="status_pengemudi" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500">
                            @foreach(['Tersedia','Bertugas'] as $s)
                                <option value="{{ $s }}" {{ old('status_pengemudi', $pengemudi->status_pengemudi ?? 'Tersedia') === $s ? 'selected' : '' }}>{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex gap-4 pt-4 border-t border-gray-100">
                        <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2.5 px-6 rounded-md shadow-sm transition">
                            {{ $pengemudi ? 'Simpan Perubahan' : 'Tambah Pengemudi' }}
                        </button>
                        <a href="{{ route('superadmin.pengemudi.index') }}" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 font-bold py-2.5 px-6 rounded-md transition">
                            Batal
                        </a>
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
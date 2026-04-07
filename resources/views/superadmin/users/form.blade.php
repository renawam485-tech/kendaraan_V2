<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            {{ $user ? 'Edit Pengguna: ' . $user->name : 'Tambah Pengguna Baru' }}
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

                <form action="{{ $user ? route('superadmin.users.update', $user->id) : route('superadmin.users.store') }}"
                      method="POST" class="space-y-6">
                    @csrf
                    @if($user) @method('PUT') @endif

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" required
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Alamat Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" required
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">
                            Password {{ $user ? '(Kosongkan jika tidak ingin mengubah)' : '*' }}
                        </label>
                        <input type="password" name="password" {{ !$user ? 'required' : '' }}
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                        <p class="text-xs text-gray-500 mt-1">Minimal 8 karakter.</p>
                    </div>

                    <div class="pt-4 border-t border-gray-100">
                        <label class="block text-sm font-bold text-gray-700 mb-3">Hak Akses (Role) <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @php
                                $roles = [
                                    'pengguna' => ['Pemohon Biasa', 'Dapat membuat pengajuan kendaraan baru.'],
                                    'kepala_admin' => ['Kepala Admin', 'Validasi dan finalisasi surat jalan.'],
                                    'spsi' => ['Kasubag SPSI', 'Mengatur alokasi mobil dan supir.'],
                                    'keuangan' => ['Kasubag Keuangan', 'Mengecek anggaran RAB dan sisa dana.'],
                                    'super_admin' => ['Super Admin', 'Akses penuh ke master data aplikasi.']
                                ];
                            @endphp

                            @foreach($roles as $value => [$label, $desc])
                                <label class="flex items-start gap-3 p-3 border rounded-lg cursor-pointer transition hover:bg-gray-50 {{ old('role', $user->role ?? '') === $value ? 'border-blue-500 bg-blue-50/50' : 'border-gray-200 bg-white' }}">
                                    <input type="radio" name="role" value="{{ $value }}"
                                        {{ old('role', $user->role ?? 'pengguna') === $value ? 'checked' : '' }}
                                        class="mt-1 text-blue-600 focus:ring-blue-500">
                                    <div>
                                        <span class="font-bold text-gray-800 text-sm block">{{ $label }}</span>
                                        <span class="text-xs text-gray-500 leading-tight block mt-0.5">{{ $desc }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex items-center gap-3 pt-6 border-t border-gray-100">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-lg shadow-sm transition flex items-center gap-2">
                            <i class="bi bi-save"></i> {{ $user ? 'Simpan Perubahan' : 'Buat Pengguna Baru' }}
                        </button>
                        <a href="{{ route('superadmin.users.index') }}" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 font-bold py-2.5 px-6 rounded-lg transition">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
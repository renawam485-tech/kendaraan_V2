<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            {{ $user ? 'Edit Pengguna: ' . $user->name : 'Tambah Pengguna Baru' }}
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

                <form action="{{ $user ? route('superadmin.users.update', $user->id) : route('superadmin.users.store') }}"
                      method="POST" class="space-y-5">
                    @csrf
                    @if($user) @method('PUT') @endif

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}"
                            required maxlength="100" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}"
                            required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">
                            Password {{ $user ? '(Kosongkan jika tidak diubah)' : '' }}
                            @if(!$user) <span class="text-red-500">*</span> @endif
                        </label>
                        <input type="password" name="password" autocomplete="new-password"
                            {{ !$user ? 'required' : '' }} minlength="8"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Hak Akses (Role) <span class="text-red-500">*</span></label>
                        <div class="space-y-2">
                            @php
                                $roles = [
                                    'pengguna'     => ['Pengguna',       'Dapat mengajukan permohonan kendaraan.'],
                                    'kepala_admin' => ['Kepala Admin',   'Memvalidasi permohonan & finalisasi surat.'],
                                    'spsi'         => ['SPSI',           'Mengalokasikan armada dan pengemudi.'],
                                    'keuangan'     => ['Keuangan',       'Menyetujui RAB dan memverifikasi pengembalian.'],
                                    'super_admin'  => ['Super Admin',    'Akses penuh: CRUD master data, laporan, dan manajemen pengguna.'],
                                ];
                            @endphp
                            @foreach($roles as $value => [$label, $desc])
                                <label class="flex items-start gap-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50 transition {{ old('role', $user->role ?? '') === $value ? 'border-purple-400 bg-purple-50' : 'border-gray-200' }}">
                                    <input type="radio" name="role" value="{{ $value }}"
                                        {{ old('role', $user->role ?? 'pengguna') === $value ? 'checked' : '' }}
                                        class="mt-0.5 text-purple-600 focus:ring-purple-500">
                                    <div>
                                        <span class="font-bold text-gray-800 text-sm block">{{ $label }}</span>
                                        <span class="text-xs text-gray-500">{{ $desc }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex gap-4 pt-4 border-t border-gray-100">
                        <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2.5 px-6 rounded-md shadow-sm transition">
                            {{ $user ? 'Simpan Perubahan' : 'Buat Pengguna' }}
                        </button>
                        <a href="{{ route('superadmin.users.index') }}" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 font-bold py-2.5 px-6 rounded-md transition">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
@php
    $userRole = Auth::user()->role;
    $isSuperAdmin = $userRole === 'super_admin';
    $isSpsi = $userRole === 'spsi';
    $canModify = $isSuperAdmin; // HANYA Super Admin yang bisa modify users
    $isReadOnly = $isSpsi; // SPSI hanya bisa melihat
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">
                Manajemen Pengguna & Hak Akses
                @if($isSpsi)
                    <span class="text-sm text-gray-500 font-normal ml-2">(View Only - Tidak Dapat Edit/Hapus)</span>
                @endif
            </h2>
            @if($canModify)
                <a href="{{ route('superadmin.users.create') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-5 rounded-lg shadow-sm text-sm transition flex items-center gap-2">
                    <i class="bi bi-plus-circle"></i> Tambah Pengguna
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow-sm flex items-center gap-3">
                    <i class="bi bi-check-circle-fill text-xl"></i>
                    <p class="text-sm font-bold">{{ session('success') }}</p>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg shadow-sm flex items-center gap-3">
                    <i class="bi bi-exclamation-triangle-fill text-xl"></i>
                    <p class="text-sm font-bold">{{ session('error') }}</p>
                </div>
            @endif

            {{-- FILTER PENCARIAN --}}
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6 flex flex-col md:flex-row justify-between items-center gap-4">
                <form action="{{ $isSuperAdmin ? route('superadmin.users.index') : route('spsi.users.index') }}" method="GET" class="w-full md:w-2/3 flex flex-wrap gap-2">
                    <div class="relative flex-1 min-w-[200px]">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="bi bi-search text-gray-400"></i>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..." 
                               class="pl-10 w-full border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50">
                    </div>
                    <select name="role" class="border border-gray-300 rounded-lg px-4 py-2 text-sm bg-gray-50">
                        <option value="">Semua Role</option>
                        <option value="pengguna" {{ request('role') == 'pengguna' ? 'selected' : '' }}>Pengguna</option>
                        <option value="kepala_admin" {{ request('role') == 'kepala_admin' ? 'selected' : '' }}>Kepala Admin</option>
                        <option value="spsi" {{ request('role') == 'spsi' ? 'selected' : '' }}>SPSI</option>
                        <option value="keuangan" {{ request('role') == 'keuangan' ? 'selected' : '' }}>Keuangan</option>
                        <option value="super_admin" {{ request('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                    </select>
                    <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-bold transition">Filter</button>
                    @if(request('search') || request('role'))
                        <a href="{{ $isSuperAdmin ? route('superadmin.users.index') : route('spsi.users.index') }}" 
                           class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-bold transition">Reset</a>
                    @endif
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-gray-50 text-gray-500 uppercase text-xs border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-4">Nama Pengguna</th>
                                <th class="px-6 py-4">Email / Kontak</th>
                                <th class="px-6 py-4">Hak Akses (Role)</th>
                                <th class="px-6 py-4">Tanggal Terdaftar</th>
                                @if($canModify)
                                    <th class="px-6 py-4 text-center">Aksi</th>
                                @else
                                    <th class="px-6 py-4 text-center">Detail</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $u)
                                @php
                                    $roleBadge = match($u->role) {
                                        'super_admin'  => 'bg-purple-50 text-purple-700 border-purple-200',
                                        'kepala_admin' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'spsi'         => 'bg-orange-50 text-orange-700 border-orange-200',
                                        'keuangan'     => 'bg-green-50 text-green-700 border-green-200',
                                        default        => 'bg-gray-50 text-gray-700 border-gray-200',
                                    };
                                @endphp
                                <tr class="border-b border-gray-50 hover:bg-blue-50/30 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-xs shrink-0">
                                                {{ strtoupper(substr($u->name, 0, 1)) }}
                                            </div>
                                            <span class="font-bold text-gray-800">{{ $u->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">{{ $u->email }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-1 text-[11px] font-bold rounded-full border {{ $roleBadge }}">
                                            {{ ucfirst(str_replace('_', ' ', $u->role)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-xs">{{ $u->created_at->format('d M Y') }}</td>
                                    @if($canModify)
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex justify-center items-center gap-3">
                                                <a href="{{ route('superadmin.users.edit', $u->id) }}" 
                                                   class="text-blue-600 hover:text-blue-800 font-bold text-xs hover:underline flex items-center gap-1">
                                                    <i class="bi bi-pencil-square"></i> Edit
                                                </a>
                                                @if($u->id !== auth()->id())
                                                    <span class="text-gray-300">|</span>
                                                    <form action="{{ route('superadmin.users.destroy', $u->id) }}" method="POST" 
                                                          onsubmit="return confirm('Yakin ingin menghapus akun ini? Semua permohonan terkait juga akan ikut terhapus.')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-800 font-bold text-xs hover:underline flex items-center gap-1">
                                                            <i class="bi bi-trash"></i> Hapus
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    @else
                                        <td class="px-6 py-4 text-center">
                                            <a href="{{ route('spsi.users.show', $u->id) }}" 
                                               class="text-blue-600 hover:text-blue-800 font-bold text-xs hover:underline flex items-center gap-1 justify-center">
                                                <i class="bi bi-eye"></i> Detail
                                            </a>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $canModify ? 5 : 5 }}" class="text-center py-12 text-gray-400">
                                        <i class="bi bi-people text-4xl block mb-3 text-gray-300"></i>
                                        Tidak ada pengguna ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($users->hasPages())
                    <div class="p-4 border-t border-gray-100 bg-gray-50">{{ $users->withQueryString()->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
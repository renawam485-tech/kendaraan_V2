<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">Manajemen Pengguna & Hak Akses</h2>
            <a href="{{ route('superadmin.users.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-5 rounded-lg shadow-sm text-sm transition flex items-center gap-2">
                <i class="bi bi-plus-circle"></i> Tambah Pengguna
            </a>
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
                <form action="{{ route('superadmin.users.index') }}" method="GET" class="w-full md:w-1/2 flex gap-2">
                    <div class="relative w-full">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="bi bi-search text-gray-400"></i>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..." 
                               class="pl-10 w-full border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50">
                    </div>
                    <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-bold transition">Cari</button>
                    @if(request('search'))
                        <a href="{{ route('superadmin.users.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-bold transition">Reset</a>
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
                                <th class="px-6 py-4 text-center">Aksi</th>
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
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center items-center gap-3">
                                            <a href="{{ route('superadmin.users.edit', $u->id) }}" class="text-blue-600 hover:text-blue-800 font-bold text-xs hover:underline flex items-center gap-1">
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </a>
                                            @if($u->id !== auth()->id())
                                                <span class="text-gray-300">|</span>
                                                <form action="{{ route('superadmin.users.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus akun ini? Semua permohonan terkait juga akan ikut terhapus.')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800 font-bold text-xs hover:underline flex items-center gap-1">
                                                        <i class="bi bi-trash"></i> Hapus
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-12 text-gray-400">
                                        <i class="bi bi-people text-4xl block mb-3 text-gray-300"></i>
                                        Tidak ada pengguna ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($users->hasPages())
                    <div class="p-4 border-t border-gray-100 bg-gray-50">{{ $users->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
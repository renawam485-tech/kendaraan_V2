<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">Manajemen Pengguna & Hak Akses</h2>
            <a href="{{ route('superadmin.users.create') }}" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-5 rounded-lg shadow text-sm transition">
                + Tambah Pengguna
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm">
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm">
                    <p class="text-sm font-medium">{{ session('error') }}</p>
                </div>
            @endif

            {{-- FILTER --}}
            <form method="GET" action="{{ route('superadmin.users.index') }}" class="bg-white border border-gray-200 rounded-xl p-4 mb-6 shadow-sm flex flex-col md:flex-row gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..."
                    class="flex-1 border-gray-300 rounded-md text-sm focus:ring-purple-500 focus:border-purple-500">
                <select name="role" class="border-gray-300 rounded-md text-sm focus:ring-purple-500 focus:border-purple-500">
                    <option value="">Semua Role</option>
                    @foreach(['pengguna','kepala_admin','spsi','keuangan','super_admin'] as $r)
                        <option value="{{ $r }}" {{ request('role') === $r ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $r)) }}</option>
                    @endforeach
                </select>
                <button type="submit" class="bg-purple-600 text-white font-bold py-2 px-5 rounded-md text-sm hover:bg-purple-700 transition">Filter</button>
                <a href="{{ route('superadmin.users.index') }}" class="text-gray-600 border border-gray-300 font-bold py-2 px-4 rounded-md text-sm hover:bg-gray-50 transition text-center">Reset</a>
            </form>

            <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-100 text-xs uppercase text-gray-600">
                            <tr>
                                <th class="px-6 py-3">Nama</th>
                                <th class="px-6 py-3">Email</th>
                                <th class="px-6 py-3">Role / Hak Akses</th>
                                <th class="px-6 py-3">Bergabung</th>
                                <th class="px-6 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $u)
                                @php
                                    $roleLabel = [
                                        'pengguna'     => ['label' => 'Pengguna',       'class' => 'bg-gray-100 text-gray-800'],
                                        'kepala_admin' => ['label' => 'Kepala Admin',   'class' => 'bg-purple-100 text-purple-800'],
                                        'spsi'         => ['label' => 'SPSI',           'class' => 'bg-green-100 text-green-800'],
                                        'keuangan'     => ['label' => 'Keuangan',       'class' => 'bg-orange-100 text-orange-800'],
                                        'super_admin'  => ['label' => 'Super Admin',    'class' => 'bg-red-100 text-red-800'],
                                    ][$u->role] ?? ['label' => $u->role, 'class' => 'bg-gray-100 text-gray-800'];
                                @endphp
                                <tr class="border-b hover:bg-gray-50 transition {{ $u->id === auth()->id() ? 'bg-purple-50/30' : '' }}">
                                    <td class="px-6 py-4 font-semibold text-gray-800">
                                        {{ $u->name }}
                                        @if($u->id === auth()->id())
                                            <span class="ml-1 text-[10px] bg-purple-200 text-purple-800 font-bold px-1.5 py-0.5 rounded">Anda</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-gray-600">{{ $u->email }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $roleLabel['class'] }}">{{ $roleLabel['label'] }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-gray-500">{{ $u->created_at->format('d M Y') }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex justify-center gap-3">
                                            <a href="{{ route('superadmin.users.edit', $u->id) }}" class="text-blue-600 hover:text-blue-800 font-bold text-xs hover:underline">Edit</a>
                                            @if($u->id !== auth()->id())
                                                <span class="text-gray-300">|</span>
                                                <form action="{{ route('superadmin.users.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus akun ini? Semua permohonan terkait juga akan ikut terhapus.')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800 font-bold text-xs hover:underline">Hapus</button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center py-12 text-gray-400">Tidak ada pengguna ditemukan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($users->hasPages())
                    <div class="p-4 border-t">{{ $users->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
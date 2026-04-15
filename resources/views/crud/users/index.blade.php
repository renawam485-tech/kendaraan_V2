@php
    $userRole = Auth::user()->role;
    $isSuperAdmin = $userRole === 'super_admin';
    $isSpsi = $userRole === 'spsi';
    $canModify = $isSuperAdmin; 
    $isReadOnly = $isSpsi;
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manajemen Pengguna
            </h2>
            @if($canModify)
                <a href="{{ route('superadmin.users.create') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-5 rounded-lg shadow-sm text-sm transition flex items-center gap-2">
                    <i class="bi bi-plus-circle"></i> Tambah 
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-6 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg shadow-sm flex items-center gap-3">
                    <i class="bi bi-exclamation-triangle-fill text-xl"></i>
                    <p class="text-sm font-bold">{{ session('error') }}</p>
                </div>
            @endif

            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2 text-sm">
                        <i class="bi bi-people-fill text-blue-600"></i>
                        Daftar Pengguna
                        <span class="ml-1 text-xs font-semibold text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full">
                            {{ $users->total() }} data
                        </span>
                    </h3>
                    <div class="relative w-full sm:w-72">
                        <form action="{{ $isSuperAdmin ? route('superadmin.users.index') : route('spsi.users.index') }}" method="GET" class="relative">
                            <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="Cari nama atau email..."
                                   autocomplete="off"
                                   class="w-full pl-9 pr-9 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition bg-gray-50 focus:bg-white">
                            @if(request('search') || request('role'))
                                <a href="{{ $isSuperAdmin ? route('superadmin.users.index') : route('spsi.users.index') }}" 
                                   class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-red-500 transition">
                                    <i class="bi bi-x-circle-fill"></i>
                                </a>
                            @endif
                        </form>
                    </div>
                </div>

                {{-- FILTER ROLE --}}
                <div class="px-5 py-3 border-b border-gray-100 bg-gray-50/50 flex flex-wrap gap-2">
                    <form action="{{ $isSuperAdmin ? route('superadmin.users.index') : route('spsi.users.index') }}" method="GET" class="flex flex-wrap gap-2">
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        <select name="role" class="border border-gray-200 rounded-lg px-4 py-1.5 text-xs font-medium bg-white focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Role</option>
                            <option value="pengguna" {{ request('role') == 'pengguna' ? 'selected' : '' }}>Pengguna</option>
                            <option value="kepala_admin" {{ request('role') == 'kepala_admin' ? 'selected' : '' }}>Kepala Admin</option>
                            <option value="spsi" {{ request('role') == 'spsi' ? 'selected' : '' }}>SPSI</option>
                            <option value="keuangan" {{ request('role') == 'keuangan' ? 'selected' : '' }}>Keuangan</option>
                            <option value="super_admin" {{ request('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                        </select>
                        <button type="submit" class="bg-blue-800 hover:bg-blue-900 text-white px-3 py-1.5 rounded-lg text-xs font-bold transition">Cari</button>
                        @if(request('role'))
                            <a href="{{ $isSuperAdmin ? route('superadmin.users.index') : route('spsi.users.index') }}{{ request('search') ? '?search='.request('search') : '' }}" 
                               class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 py-1.5 rounded-lg text-xs font-bold transition">Reset </a>
                        @endif
                    </form>
                </div>

                {{-- MOBILE VIEW --}}
                <div class="block md:hidden divide-y divide-gray-100">
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
                        <div class="p-4 hover:bg-slate-50 transition">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-sm shrink-0">
                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                </div>
                                <div class="flex-1">
                                    <p class="font-semibold text-sm text-gray-800">{{ $u->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $u->email }}</p>
                                    <div class="mt-1">
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full border {{ $roleBadge }}">
                                            {{ ucfirst(str_replace('_', ' ', $u->role)) }}
                                        </span>
                                    </div>
                                    <p class="text-[10px] text-gray-400 mt-1">Terdaftar: {{ $u->created_at->format('d M Y') }}</p>
                                </div>
                            </div>
                            @if($canModify)
                                <div class="mt-3 flex items-center gap-2 justify-end">
                                    <a href="{{ route('superadmin.users.edit', $u->id) }}" 
                                       class="text-blue-600 hover:text-blue-800 text-xs font-bold flex items-center gap-1">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                    @if($u->id !== auth()->id())
                                        <span class="text-gray-300">|</span>
                                        <form action="{{ route('superadmin.users.destroy', $u->id) }}" method="POST" 
                                              onsubmit="return confirm('Yakin ingin menghapus akun ini? Semua permohonan terkait juga akan ikut terhapus.')" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-bold flex items-center gap-1">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @else
                                <div class="mt-3 flex justify-end">
                                    <a href="{{ route('spsi.users.show', $u->id) }}" 
                                       class="text-blue-600 hover:text-blue-800 text-xs font-bold flex items-center gap-1">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="py-16 text-center text-gray-400">
                            <i class="bi bi-people text-5xl block mb-3 text-gray-300"></i>
                            <p class="font-medium text-gray-500">Tidak ada pengguna ditemukan</p>
                        </div>
                    @endforelse
                </div>

                {{-- DESKTOP TABLE --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-slate-50 border-b border-gray-200">
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-12">No</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Pengguna</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Hak Akses</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Terdaftar</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($users as $i => $u)
                                @php
                                    $roleBadge = match($u->role) {
                                        'super_admin'  => 'bg-purple-50 text-purple-700 border-purple-200',
                                        'kepala_admin' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'spsi'         => 'bg-orange-50 text-orange-700 border-orange-200',
                                        'keuangan'     => 'bg-green-50 text-green-700 border-green-200',
                                        default        => 'bg-gray-50 text-gray-700 border-gray-200',
                                    };
                                @endphp
                                <tr class="hover:bg-blue-50/20 transition-colors">
                                    <td class="px-4 py-3.5 text-center text-xs text-gray-400 font-semibold">
                                        {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                                    </td>
                                    <td class="px-4 py-3.5">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-xs shrink-0">
                                                {{ strtoupper(substr($u->name, 0, 1)) }}
                                            </div>
                                            <span class="font-semibold text-gray-800">{{ $u->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3.5 text-xs">{{ $u->email }}</td>
                                    <td class="px-4 py-3.5">
                                        <span class="inline-block text-[11px] font-bold px-2.5 py-1 rounded-full border {{ $roleBadge }}">
                                            {{ ucfirst(str_replace('_', ' ', $u->role)) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3.5 text-xs text-gray-500">{{ $u->created_at->format('d M Y') }}</td>
                                    <td class="px-4 py-3.5 text-center">
                                        @if($canModify)
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="{{ route('superadmin.users.edit', $u->id) }}" 
                                                   class="text-blue-600 hover:text-blue-800 text-xs font-bold flex items-center gap-1">
                                                    <i class="bi bi-pencil-square"></i> Edit
                                                </a>
                                                @if($u->id !== auth()->id())
                                                    <span class="text-gray-300">|</span>
                                                    <form action="{{ route('superadmin.users.destroy', $u->id) }}" method="POST" 
                                                          onsubmit="return confirm('Yakin ingin menghapus akun ini?')" class="inline">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-bold flex items-center gap-1">
                                                            <i class="bi bi-trash"></i> Hapus
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        @else
                                            <a href="{{ route('spsi.users.show', $u->id) }}" 
                                               class="text-blue-600 hover:text-blue-800 text-xs font-bold flex items-center gap-1 justify-center">
                                                <i class="bi bi-eye"></i> Detail
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-16 text-center">
                                        <i class="bi bi-people text-5xl block mb-3 text-gray-300"></i>
                                        <p class="font-medium text-gray-500">Tidak ada pengguna ditemukan</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION --}}
                @if($users->hasPages())
                    <div class="px-5 py-3 border-t border-gray-100 flex items-center justify-between">
                        <p class="text-xs text-gray-400">
                            Menampilkan {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }}
                            dari {{ $users->total() }} data
                        </p>
                        <div class="flex items-center gap-2">
                            {{ $users->withQueryString()->links() }}
                        </div>
                        <p class="text-xs text-gray-400" id="realtimeClock"></p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function updateClock() {
            const now = new Date();
            const formatted = now.toLocaleString('id-ID', {
                day: '2-digit',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            }).replace(/\./g, ':');
            const el = document.getElementById('realtimeClock');
            if (el) el.textContent = formatted + ' WIB';
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>
</x-app-layout>
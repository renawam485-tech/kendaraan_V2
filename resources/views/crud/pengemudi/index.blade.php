@php
    $userRole = Auth::user()->role;
    $isSuperAdmin = $userRole === 'super_admin';
    $isSpsi = $userRole === 'spsi';
    $canModify = $isSuperAdmin || $isSpsi;
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <i class="bi bi-person-vcard text-blue-600 mr-2"></i> Manajemen Pengemudi
                @if($isSpsi)
                    <span class="text-sm text-gray-500 font-normal ml-2">(SPSI - Full Akses)</span>
                @endif
            </h2>
            @if($canModify)
                <a href="{{ $isSuperAdmin ? route('superadmin.pengemudi.create') : route('spsi.pengemudi.create') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-5 rounded-lg shadow-sm text-sm transition flex items-center gap-2">
                    <i class="bi bi-plus-circle"></i> Tambah Pengemudi
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-6 bg-slate-50 min-h-screen">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

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

            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2 text-sm">
                        <i class="bi bi-person-vcard-fill text-blue-600"></i>
                        Daftar Pengemudi
                        <span class="ml-1 text-xs font-semibold text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full">
                            {{ $pengemudis->total() }} data
                        </span>
                    </h3>
                    <div class="relative w-full sm:w-72">
                        <form action="{{ url()->current() }}" method="GET" class="relative">
                            <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="Cari nama pengemudi atau nomor kontak..."
                                   autocomplete="off"
                                   class="w-full pl-9 pr-9 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition bg-gray-50 focus:bg-white">
                            @if(request('search'))
                                <a href="{{ url()->current() }}" 
                                   class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-red-500 transition">
                                    <i class="bi bi-x-circle-fill"></i>
                                </a>
                            @endif
                        </form>
                    </div>
                </div>

                {{-- MOBILE VIEW --}}
                <div class="block md:hidden divide-y divide-gray-100">
                    @forelse($pengemudis as $p)
                        @php
                            $sc = match($p->status_pengemudi) {
                                'Tersedia' => 'bg-green-50 text-green-700 border-green-200',
                                'Bertugas' => 'bg-blue-50 text-blue-700 border-blue-200',
                                default    => 'bg-gray-50 text-gray-700 border-gray-200',
                            };
                        @endphp
                        <div class="p-4 hover:bg-slate-50 transition">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <p class="font-semibold text-sm text-gray-800">
                                        <i class="bi bi-person-vcard text-gray-400 mr-1"></i> {{ $p->nama_pengemudi }}
                                    </p>
                                    <p class="text-xs font-mono mt-0.5">
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $p->kontak) }}" target="_blank" 
                                           class="text-blue-600 hover:underline">
                                            <i class="bi bi-whatsapp mr-1 text-green-500"></i> {{ $p->kontak }}
                                        </a>
                                    </p>
                                </div>
                                <span class="text-[10px] font-bold px-2 py-1 rounded-md border {{ $sc }}">{{ $p->status_pengemudi }}</span>
                            </div>
                            @if($canModify)
                                <div class="mt-3 flex items-center gap-2 justify-end">
                                    <a href="{{ $isSuperAdmin ? route('superadmin.pengemudi.edit', $p->id) : route('spsi.pengemudi.edit', $p->id) }}" 
                                       class="text-blue-600 hover:text-blue-800 text-xs font-bold flex items-center gap-1">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                    <span class="text-gray-300">|</span>
                                    <form action="{{ $isSuperAdmin ? route('superadmin.pengemudi.destroy', $p->id) : route('spsi.pengemudi.destroy', $p->id) }}" 
                                          method="POST" onsubmit="return confirm('Yakin ingin menghapus pengemudi ini?')" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-bold flex items-center gap-1">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="py-16 text-center text-gray-400">
                            <i class="bi bi-person-badge text-5xl block mb-3 text-gray-300"></i>
                            <p class="font-medium text-gray-500">Belum ada data pengemudi</p>
                        </div>
                    @endforelse
                </div>

                {{-- DESKTOP TABLE --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-slate-50 border-b border-gray-200">
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-12">No</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Pengemudi</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nomor Kontak</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                @if($canModify)
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($pengemudis as $i => $p)
                                @php
                                    $sc = match($p->status_pengemudi) {
                                        'Tersedia' => 'bg-green-50 text-green-700 border-green-200',
                                        'Bertugas' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        default    => 'bg-gray-50 text-gray-700 border-gray-200',
                                    };
                                @endphp
                                <tr class="hover:bg-blue-50/20 transition-colors">
                                    <td class="px-4 py-3.5 text-center text-xs text-gray-400 font-semibold">
                                        {{ ($pengemudis->currentPage() - 1) * $pengemudis->perPage() + $loop->iteration }}
                                    </td>
                                    <td class="px-4 py-3.5 font-semibold text-gray-800">{{ $p->nama_pengemudi }}</td>
                                    <td class="px-4 py-3.5 font-mono text-xs">
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $p->kontak) }}" target="_blank" 
                                           class="text-blue-600 hover:underline">
                                            <i class="bi bi-whatsapp mr-1 text-green-500"></i> {{ $p->kontak }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-3.5">
                                        <span class="inline-block text-[11px] font-bold px-2.5 py-1 rounded-md border {{ $sc }}">{{ $p->status_pengemudi }}</span>
                                    </td>
                                    @if($canModify)
                                        <td class="px-4 py-3.5 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="{{ $isSuperAdmin ? route('superadmin.pengemudi.edit', $p->id) : route('spsi.pengemudi.edit', $p->id) }}" 
                                                   class="text-blue-600 hover:text-blue-800 text-xs font-bold flex items-center gap-1">
                                                    <i class="bi bi-pencil-square"></i> Edit
                                                </a>
                                                <span class="text-gray-300">|</span>
                                                <form action="{{ $isSuperAdmin ? route('superadmin.pengemudi.destroy', $p->id) : route('spsi.pengemudi.destroy', $p->id) }}" 
                                                      method="POST" onsubmit="return confirm('Yakin ingin menghapus pengemudi ini?')" class="inline">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-bold flex items-center gap-1">
                                                        <i class="bi bi-trash"></i> Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $canModify ? 5 : 4 }}" class="py-16 text-center">
                                        <i class="bi bi-person-badge text-5xl block mb-3 text-gray-300"></i>
                                        <p class="font-medium text-gray-500">Belum ada data pengemudi</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION --}}
                @if($pengemudis->hasPages())
                    <div class="px-5 py-3 border-t border-gray-100 flex items-center justify-between">
                        <p class="text-xs text-gray-400">
                            Menampilkan {{ $pengemudis->firstItem() ?? 0 }} - {{ $pengemudis->lastItem() ?? 0 }}
                            dari {{ $pengemudis->total() }} data
                        </p>
                        <div class="flex items-center gap-2">
                            {{ $pengemudis->withQueryString()->links() }}
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
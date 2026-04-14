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
                <i class="bi bi-buildings text-blue-600 mr-2"></i> Manajemen Kendaraan Vendor
                @if($isSpsi)
                    <span class="text-sm text-gray-500 font-normal ml-2">(SPSI - Full Akses)</span>
                @endif
            </h2>
            @if($canModify)
                <a href="{{ $isSuperAdmin ? route('superadmin.kendaraan_vendor.create') : route('spsi.kendaraan_vendor.create') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-5 rounded-lg shadow-sm text-sm transition flex items-center gap-2">
                    <i class="bi bi-plus-circle"></i> Tambah Vendor
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-6 bg-slate-50 min-h-screen">
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

            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2 text-sm">
                        <i class="bi bi-buildings-fill text-blue-600"></i>
                        Daftar Armada Vendor
                        <span class="ml-1 text-xs font-semibold text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full">
                            {{ $vendors->total() }} data
                        </span>
                    </h3>
                    <div class="relative w-full sm:w-72">
                        <form action="{{ url()->current() }}" method="GET" class="relative">
                            <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="Cari nama vendor, kendaraan, atau plat nomor..."
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
                    @forelse($vendors as $v)
                        @php
                            $sc = match($v->status_kendaraan) {
                                'Tersedia'       => 'bg-green-50 text-green-700 border-green-200',
                                'Tidak Tersedia' => 'bg-red-50 text-red-700 border-red-200',
                                default          => 'bg-gray-50 text-gray-700 border-gray-200',
                            };
                        @endphp
                        <div class="p-4 hover:bg-slate-50 transition">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <p class="font-semibold text-sm text-gray-800">
                                        <i class="bi bi-buildings text-gray-400 mr-1"></i> {{ $v->nama_vendor }}
                                    </p>
                                    <p class="text-xs font-medium text-gray-700 mt-0.5">{{ $v->nama_kendaraan }}</p>
                                    <p class="text-xs font-mono text-gray-500">{{ $v->plat_nomor }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5"><i class="bi bi-people mr-0.5"></i>{{ $v->kapasitas_penumpang }} Orang</p>
                                </div>
                                <span class="text-[10px] font-bold px-2 py-1 rounded-md border {{ $sc }}">{{ $v->status_kendaraan }}</span>
                            </div>
                            @if($canModify)
                                <div class="mt-3 flex items-center gap-2 justify-end">
                                    <a href="{{ $isSuperAdmin ? route('superadmin.kendaraan_vendor.edit', $v->id) : route('spsi.kendaraan_vendor.edit', $v->id) }}" 
                                       class="text-blue-600 hover:text-blue-800 text-xs font-bold flex items-center gap-1">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                    <span class="text-gray-300">|</span>
                                    <form action="{{ $isSuperAdmin ? route('superadmin.kendaraan_vendor.destroy', $v->id) : route('spsi.kendaraan_vendor.destroy', $v->id) }}" 
                                          method="POST" onsubmit="return confirm('Yakin ingin menghapus armada vendor ini?')" class="inline">
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
                            <i class="bi bi-buildings text-5xl block mb-3 text-gray-300"></i>
                            <p class="font-medium text-gray-500">Belum ada data armada vendor</p>
                        </div>
                    @endforelse
                </div>

                {{-- DESKTOP TABLE --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-slate-50 border-b border-gray-200">
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-12">No</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Vendor</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Armada</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kapasitas</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                @if($canModify)
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($vendors as $i => $v)
                                @php
                                    $sc = match($v->status_kendaraan) {
                                        'Tersedia'       => 'bg-green-50 text-green-700 border-green-200',
                                        'Tidak Tersedia' => 'bg-red-50 text-red-700 border-red-200',
                                        default          => 'bg-gray-50 text-gray-700 border-gray-200',
                                    };
                                @endphp
                                <tr class="hover:bg-blue-50/20 transition-colors">
                                    <td class="px-4 py-3.5 text-center text-xs text-gray-400 font-semibold">
                                        {{ ($vendors->currentPage() - 1) * $vendors->perPage() + $loop->iteration }}
                                    </td>
                                    <td class="px-4 py-3.5 font-semibold text-gray-800">{{ $v->nama_vendor }}</td>
                                    <td class="px-4 py-3.5">
                                        <p class="font-medium text-gray-800">{{ $v->nama_kendaraan }}</p>
                                        <p class="text-xs font-mono text-gray-500">{{ $v->plat_nomor }}</p>
                                    </td>
                                    <td class="px-4 py-3.5 text-xs">{{ $v->kapasitas_penumpang }} Orang</td>
                                    <td class="px-4 py-3.5">
                                        <span class="inline-block text-[11px] font-bold px-2.5 py-1 rounded-md border {{ $sc }}">{{ $v->status_kendaraan }}</span>
                                    </td>
                                    @if($canModify)
                                        <td class="px-4 py-3.5 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="{{ $isSuperAdmin ? route('superadmin.kendaraan_vendor.edit', $v->id) : route('spsi.kendaraan_vendor.edit', $v->id) }}" 
                                                   class="text-blue-600 hover:text-blue-800 text-xs font-bold flex items-center gap-1">
                                                    <i class="bi bi-pencil-square"></i> Edit
                                                </a>
                                                <span class="text-gray-300">|</span>
                                                <form action="{{ $isSuperAdmin ? route('superadmin.kendaraan_vendor.destroy', $v->id) : route('spsi.kendaraan_vendor.destroy', $v->id) }}" 
                                                      method="POST" onsubmit="return confirm('Yakin ingin menghapus armada vendor ini?')" class="inline">
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
                                    <td colspan="{{ $canModify ? 6 : 5 }}" class="py-16 text-center">
                                        <i class="bi bi-buildings text-5xl block mb-3 text-gray-300"></i>
                                        <p class="font-medium text-gray-500">Belum ada data armada vendor</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION --}}
                @if($vendors->hasPages())
                    <div class="px-5 py-3 border-t border-gray-100 flex items-center justify-between">
                        <p class="text-xs text-gray-400">
                            Menampilkan {{ $vendors->firstItem() ?? 0 }} - {{ $vendors->lastItem() ?? 0 }}
                            dari {{ $vendors->total() }} data
                        </p>
                        <div class="flex items-center gap-2">
                            {{ $vendors->withQueryString()->links() }}
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
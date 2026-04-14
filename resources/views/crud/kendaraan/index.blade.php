@php
    $userRole = Auth::user()->role;
    $isSuperAdmin = $userRole === 'super_admin';
    $isSpsi = $userRole === 'spsi';
    $canModify = $isSuperAdmin || $isSpsi; // SPSI dan Super Admin bisa modify
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">
                Manajemen Kendaraan Internal
                @if($isSpsi)
                    <span class="text-sm text-gray-500 font-normal ml-2">(SPSI - Full Akses)</span>
                @endif
            </h2>
            @if($canModify)
                <a href="{{ $isSuperAdmin ? route('superadmin.kendaraan.create') : route('spsi.kendaraan.create') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-5 rounded-lg shadow-sm text-sm transition flex items-center gap-2">
                    <i class="bi bi-plus-circle"></i> Tambah Kendaraan
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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-gray-50 text-gray-500 uppercase text-xs border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-4">Nama Kendaraan</th>
                                <th class="px-6 py-4">Plat Nomor</th>
                                <th class="px-6 py-4">Kapasitas</th>
                                <th class="px-6 py-4">Status</th>
                                @if($canModify)
                                    <th class="px-6 py-4 text-center">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kendaraans as $k)
                                @php
                                    $sc = match($k->status_kendaraan) {
                                        'Tersedia'    => 'bg-green-50 text-green-700 border-green-200',
                                        'Dipinjam'    => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'Maintenance' => 'bg-orange-50 text-orange-700 border-orange-200',
                                        default       => 'bg-gray-50 text-gray-700 border-gray-200',
                                    };
                                @endphp
                                <tr class="border-b border-gray-50 hover:bg-blue-50/30 transition">
                                    <td class="px-6 py-4 font-bold text-gray-800"><i class="bi bi-car-front text-gray-400 mr-2"></i> {{ $k->nama_kendaraan }}</td>
                                    <td class="px-6 py-4 font-mono">{{ $k->plat_nomor }}</td>
                                    <td class="px-6 py-4">{{ $k->kapasitas_penumpang }} Orang</td>
                                    <td class="px-6 py-4"><span class="px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $sc }}">{{ $k->status_kendaraan }}</span></td>
                                    @if($canModify)
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex justify-center items-center gap-3">
                                                <a href="{{ $isSuperAdmin ? route('superadmin.kendaraan.edit', $k->id) : route('spsi.kendaraan.edit', $k->id) }}" 
                                                   class="text-blue-600 hover:text-blue-800 font-bold text-xs hover:underline flex items-center gap-1">
                                                    <i class="bi bi-pencil-square"></i> Edit
                                                </a>
                                                <span class="text-gray-300">|</span>
                                                <form action="{{ $isSuperAdmin ? route('superadmin.kendaraan.destroy', $k->id) : route('spsi.kendaraan.destroy', $k->id) }}" 
                                                      method="POST" onsubmit="return confirm('Yakin ingin menghapus kendaraan ini?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800 font-bold text-xs hover:underline flex items-center gap-1">
                                                        <i class="bi bi-trash"></i> Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $canModify ? 5 : 4 }}" class="text-center py-12 text-gray-400">
                                        <i class="bi bi-car-front text-4xl block mb-3 text-gray-300"></i>
                                        <p>Belum ada data kendaraan internal.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($kendaraans->hasPages())
                    <div class="p-4 border-t border-gray-100 bg-gray-50">{{ $kendaraans->links() }}</div>
                @endif
            </div>
            <div class="mt-4 bg-blue-50 border border-blue-100 rounded-lg p-4 text-sm text-blue-700 flex gap-3">
                <i class="bi bi-info-circle-fill text-lg"></i>
                <p><strong>Catatan:</strong> Status kendaraan dikelola otomatis oleh sistem saat SPSI mengalokasikan armada. Anda dapat mengubahnya manual via tombol Edit (misal untuk set ke Maintenance).</p>
            </div>
        </div>
    </div>
</x-app-layout>
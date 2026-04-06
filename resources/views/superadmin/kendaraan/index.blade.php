<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">Manajemen Kendaraan</h2>
            <a href="{{ route('superadmin.kendaraan.create') }}" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-5 rounded-lg shadow text-sm transition">
                + Tambah Kendaraan
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

            {{-- SUMMARY BADGES --}}
            <div class="grid grid-cols-3 gap-4 mb-6">
                @php
                    $tersedia    = $kendaraans->where('status_kendaraan', 'Tersedia')->count();
                    $dipinjam    = $kendaraans->where('status_kendaraan', 'Dipinjam')->count();
                    $maintenance = $kendaraans->where('status_kendaraan', 'Maintenance')->count();
                @endphp
                <div class="bg-white border border-green-200 rounded-lg p-4 text-center shadow-sm">
                    <p class="text-2xl font-black text-green-700">{{ $tersedia }}</p>
                    <p class="text-xs text-gray-500 font-medium">Tersedia</p>
                </div>
                <div class="bg-white border border-blue-200 rounded-lg p-4 text-center shadow-sm">
                    <p class="text-2xl font-black text-blue-700">{{ $dipinjam }}</p>
                    <p class="text-xs text-gray-500 font-medium">Dipinjam</p>
                </div>
                <div class="bg-white border border-orange-200 rounded-lg p-4 text-center shadow-sm">
                    <p class="text-2xl font-black text-orange-700">{{ $maintenance }}</p>
                    <p class="text-xs text-gray-500 font-medium">Maintenance</p>
                </div>
            </div>

            <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-100 text-xs uppercase text-gray-600">
                            <tr>
                                <th class="px-6 py-3">Nama Kendaraan</th>
                                <th class="px-6 py-3">Plat Nomor</th>
                                <th class="px-6 py-3">Kapasitas</th>
                                <th class="px-6 py-3">Status</th>
                                <th class="px-6 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kendaraans as $k)
                                @php
                                    $sc = match($k->status_kendaraan) {
                                        'Tersedia'    => 'bg-green-100 text-green-800',
                                        'Dipinjam'    => 'bg-blue-100 text-blue-800',
                                        'Maintenance' => 'bg-orange-100 text-orange-800',
                                    };
                                @endphp
                                <tr class="border-b hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 font-semibold text-gray-800">{{ $k->nama_kendaraan }}</td>
                                    <td class="px-6 py-4 font-mono font-bold">{{ $k->plat_nomor }}</td>
                                    <td class="px-6 py-4">{{ $k->kapasitas_penumpang }} Penumpang</td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $sc }}">{{ $k->status_kendaraan }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex justify-center gap-3">
                                            <a href="{{ route('superadmin.kendaraan.edit', $k->id) }}" class="text-blue-600 hover:text-blue-800 font-bold text-xs hover:underline">Edit</a>
                                            <span class="text-gray-300">|</span>
                                            <form action="{{ route('superadmin.kendaraan.destroy', $k->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kendaraan ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 font-bold text-xs hover:underline">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-12 text-gray-400">
                                        <div class="text-4xl mb-2">🚗</div>
                                        <p>Belum ada data kendaraan.</p>
                                        <a href="{{ route('superadmin.kendaraan.create') }}" class="text-purple-600 font-bold hover:underline text-sm mt-1 inline-block">Tambah sekarang →</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($kendaraans->hasPages())
                    <div class="p-4 border-t">{{ $kendaraans->links() }}</div>
                @endif
            </div>

            {{-- INFO: dimana status disimpan --}}
            <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4 text-sm text-blue-700">
                <strong>ℹ️ Catatan:</strong> Status kendaraan (<em>Tersedia / Dipinjam / Maintenance</em>) dikelola secara otomatis oleh sistem saat SPSI mengalokasikan armada dan saat pengguna menyelesaikan perjalanan. Super Admin dapat mengubah status secara manual melalui tombol <strong>Edit</strong> di atas (misalnya untuk set status <em>Maintenance</em>).
            </div>
        </div>
    </div>
</x-app-layout>
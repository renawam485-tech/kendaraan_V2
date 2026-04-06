<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">Manajemen Mobil Vendor Luar</h2>
            <a href="{{ route('superadmin.kendaraan_vendor.create') }}" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-5 rounded-lg shadow text-sm transition">
                + Tambah Mobil Vendor
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm">
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-100 text-gray-600 uppercase text-xs tracking-wider border-b border-gray-200">
                                <th class="px-4 py-3 font-semibold">Nama Vendor</th>
                                <th class="px-4 py-3 font-semibold">Nama Mobil</th>
                                <th class="px-4 py-3 font-semibold">Plat Nomor</th>
                                <th class="px-4 py-3 font-semibold text-center">Kapasitas</th>
                                <th class="px-4 py-3 font-semibold">Status</th>
                                <th class="px-4 py-3 font-semibold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @forelse($vendors as $v)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-3 font-bold text-gray-800">{{ $v->nama_vendor }}</td>
                                    <td class="px-4 py-3">{{ $v->nama_kendaraan }}</td>
                                    <td class="px-4 py-3 font-mono text-gray-600">{{ $v->plat_nomor ?? '-' }}</td>
                                    <td class="px-4 py-3 text-center">{{ $v->kapasitas_penumpang ?? '-' }} Org</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 rounded-full text-xs font-bold {{ $v->status_kendaraan === 'Tersedia' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $v->status_kendaraan }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <div class="flex justify-center items-center gap-3">
                                            <a href="{{ route('superadmin.kendaraan_vendor.edit', $v->id) }}" class="text-blue-600 hover:text-blue-800 font-bold text-xs hover:underline">Edit</a>
                                            <span class="text-gray-300">|</span>
                                            <form action="{{ route('superadmin.kendaraan_vendor.destroy', $v->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus mobil vendor ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 font-bold text-xs hover:underline">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-12 text-gray-400">
                                        <div class="text-4xl mb-2">🏢</div>
                                        <p>Belum ada data mobil vendor luar.</p>
                                        <a href="{{ route('superadmin.kendaraan_vendor.create') }}" class="text-purple-600 font-bold hover:underline text-sm mt-1 inline-block">Tambah sekarang →</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($vendors->hasPages())
                    <div class="p-4 border-t">{{ $vendors->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
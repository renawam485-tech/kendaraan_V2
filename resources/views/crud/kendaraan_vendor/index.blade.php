<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">Manajemen Mobil Vendor Luar</h2>
            <a href="{{ route('superadmin.kendaraan_vendor.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-5 rounded-lg shadow-sm text-sm transition flex items-center gap-2">
                <i class="bi bi-plus-circle"></i> Tambah Vendor
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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-gray-50 text-gray-500 uppercase text-xs border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-4">Nama Vendor</th>
                                <th class="px-6 py-4">Armada</th>
                                <th class="px-6 py-4">Kapasitas</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($vendors as $v)
                                @php
                                    $sc = match($v->status_kendaraan) {
                                        'Tersedia'       => 'bg-green-50 text-green-700 border-green-200',
                                        'Tidak Tersedia' => 'bg-red-50 text-red-700 border-red-200',
                                        default          => 'bg-gray-50 text-gray-700 border-gray-200',
                                    };
                                @endphp
                                <tr class="border-b border-gray-50 hover:bg-blue-50/30 transition">
                                    <td class="px-6 py-4 font-bold text-gray-800"><i class="bi bi-buildings text-gray-400 mr-2"></i> {{ $v->nama_vendor }}</td>
                                    <td class="px-6 py-4">
                                        <span class="block text-gray-800 font-medium">{{ $v->nama_kendaraan }}</span>
                                        <span class="block text-xs font-mono text-gray-500">{{ $v->plat_nomor }}</span>
                                    </td>
                                    <td class="px-6 py-4">{{ $v->kapasitas_penumpang }} Orang</td>
                                    <td class="px-6 py-4"><span class="px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $sc }}">{{ $v->status_kendaraan }}</span></td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center items-center gap-3">
                                            <a href="{{ route('superadmin.kendaraan_vendor.edit', $v->id) }}" class="text-blue-600 hover:text-blue-800 font-bold text-xs hover:underline flex items-center gap-1"><i class="bi bi-pencil-square"></i> Edit</a>
                                            <span class="text-gray-300">|</span>
                                            <form action="{{ route('superadmin.kendaraan_vendor.destroy', $v->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus armada vendor ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 font-bold text-xs hover:underline flex items-center gap-1"><i class="bi bi-trash"></i> Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-12 text-gray-400">
                                        <i class="bi bi-buildings text-4xl block mb-3 text-gray-300"></i>
                                        <p>Belum ada data armada vendor.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($vendors->hasPages())
                    <div class="p-4 border-t border-gray-100 bg-gray-50">{{ $vendors->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
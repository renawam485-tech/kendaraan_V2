<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">Manajemen Pengemudi</h2>
            <a href="{{ route('superadmin.pengemudi.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-5 rounded-lg shadow-sm text-sm transition flex items-center gap-2">
                <i class="bi bi-plus-circle"></i> Tambah Pengemudi
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

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
                                <th class="px-6 py-4">Nama Pengemudi</th>
                                <th class="px-6 py-4">Nomor Kontak</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pengemudis as $p)
                                @php
                                    $sc = match($p->status_pengemudi) {
                                        'Tersedia' => 'bg-green-50 text-green-700 border-green-200',
                                        'Bertugas' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        default    => 'bg-gray-50 text-gray-700 border-gray-200',
                                    };
                                @endphp
                                <tr class="border-b border-gray-50 hover:bg-blue-50/30 transition">
                                    <td class="px-6 py-4 font-bold text-gray-800"><i class="bi bi-person-vcard text-gray-400 mr-2"></i> {{ $p->nama_pengemudi }}</td>
                                    <td class="px-6 py-4 font-mono"><a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $p->kontak) }}" target="_blank" class="text-blue-600 hover:underline"><i class="bi bi-whatsapp mr-1 text-green-500"></i> {{ $p->kontak }}</a></td>
                                    <td class="px-6 py-4"><span class="px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $sc }}">{{ $p->status_pengemudi }}</span></td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center items-center gap-3">
                                            <a href="{{ route('superadmin.pengemudi.edit', $p->id) }}" class="text-blue-600 hover:text-blue-800 font-bold text-xs hover:underline flex items-center gap-1"><i class="bi bi-pencil-square"></i> Edit</a>
                                            <span class="text-gray-300">|</span>
                                            <form action="{{ route('superadmin.pengemudi.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pengemudi ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 font-bold text-xs hover:underline flex items-center gap-1"><i class="bi bi-trash"></i> Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-12 text-gray-400">
                                        <i class="bi bi-person-badge text-4xl block mb-3 text-gray-300"></i>
                                        <p>Belum ada data pengemudi.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($pengemudis->hasPages())
                    <div class="p-4 border-t border-gray-100 bg-gray-50">{{ $pengemudis->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
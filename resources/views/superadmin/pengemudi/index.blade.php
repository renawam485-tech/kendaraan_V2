<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">Manajemen Pengemudi</h2>
            <a href="{{ route('superadmin.pengemudi.create') }}" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-5 rounded-lg shadow text-sm transition">
                + Tambah Pengemudi
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

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

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="bg-white border border-green-200 rounded-lg p-4 text-center shadow-sm">
                    <p class="text-2xl font-black text-green-700">{{ $pengemudis->where('status_pengemudi', 'Tersedia')->count() }}</p>
                    <p class="text-xs text-gray-500 font-medium">Tersedia</p>
                </div>
                <div class="bg-white border border-blue-200 rounded-lg p-4 text-center shadow-sm">
                    <p class="text-2xl font-black text-blue-700">{{ $pengemudis->where('status_pengemudi', 'Bertugas')->count() }}</p>
                    <p class="text-xs text-gray-500 font-medium">Sedang Bertugas</p>
                </div>
            </div>

            <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-100 text-xs uppercase text-gray-600">
                            <tr>
                                <th class="px-6 py-3">Nama Pengemudi</th>
                                <th class="px-6 py-3">Kontak</th>
                                <th class="px-6 py-3">Status</th>
                                <th class="px-6 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pengemudis as $p)
                                <tr class="border-b hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 font-semibold text-gray-800">{{ $p->nama_pengemudi }}</td>
                                    <td class="px-6 py-4 font-mono text-sm">{{ $p->kontak }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $p->status_pengemudi === 'Tersedia' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ $p->status_pengemudi }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex justify-center gap-3">
                                            <a href="{{ route('superadmin.pengemudi.edit', $p->id) }}" class="text-blue-600 hover:text-blue-800 font-bold text-xs hover:underline">Edit</a>
                                            <span class="text-gray-300">|</span>
                                            <form action="{{ route('superadmin.pengemudi.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pengemudi ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 font-bold text-xs hover:underline">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-12 text-gray-400">
                                        <div class="text-4xl mb-2">👤</div>
                                        <p>Belum ada data pengemudi.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($pengemudis->hasPages())
                    <div class="p-4 border-t">{{ $pengemudis->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
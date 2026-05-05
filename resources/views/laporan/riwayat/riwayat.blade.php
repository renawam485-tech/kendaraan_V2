<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 flex items-center gap-2">
                    <i class="bi bi-clock-history text-indigo-600"></i>
                    Riwayat
                </h2>
            </div> 
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="w-full px-4 sm:px-6 lg:px-8 space-y-4">

            {{-- PENCARIAN --}}
            <div class="flex justify-end">
                <form method="GET" class="relative w-full sm:w-64">
                    <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" name="search" value="{{ request('search') }}" 
                        placeholder="Cari kode, PIC, tujuan..."
                        class="w-full pl-9 pr-9 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none">
                    @if(request('search'))
                        <a href="{{ route('laporan.riwayat') }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-red-500">
                            <i class="bi bi-x-circle-fill"></i>
                        </a>
                    @endif
                </form>
            </div>

            {{-- TABEL --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-600">
                        <thead class="bg-gray-50 border-b border-gray-100 text-xs uppercase text-gray-500">
                            <tr>
                                <th class="px-4 py-3">No</th>
                                <th class="px-4 py-3">Kode</th>
                                <th class="px-4 py-3">PIC</th>
                                <th class="px-4 py-3">Tujuan</th>
                                <th class="px-4 py-3">Berangkat</th>
                                <th class="px-4 py-3">Kembali</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $i => $p)
                                @php $sc = $p->status_permohonan->badgeClass() ?? ''; @endphp
                                <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                                    <td class="px-4 py-3 text-gray-400">{{ $i + 1 }}</td>
                                    <td class="px-4 py-3 font-medium text-indigo-600">{{ $p->kode_permohonan ?? '-' }}</td>
                                    <td class="px-4 py-3 text-gray-800">{{ $p->nama_pic }}</td>
                                    <td class="px-4 py-3">{{ $p->tujuan }}</td>
                                    <td class="px-4 py-3 text-xs">{{ \Carbon\Carbon::parse($p->waktu_berangkat)->format('d/m/Y H:i') }}</td>
                                    <td class="px-4 py-3 text-xs">{{ \Carbon\Carbon::parse($p->waktu_kembali)->format('d/m/Y H:i') }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold border {{ $sc }}">{{ $p->status_permohonan->value }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <a href="{{ route('permohonan.show', $p->id) }}" class="text-indigo-600 hover:text-indigo-800 hover:underline font-bold text-xs">
                                            <i class="bi bi-search"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-16 text-gray-400">
                                        <i class="bi bi-inbox text-4xl block mb-2"></i>
                                        Tidak ada data riwayat
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <p class="text-xs text-gray-400 text-center font-mono mt-4">
                Data diakses pada: {{ now()->translatedFormat('d F Y, H:i') }} WIB
            </p>
        </div>
    </div>
</x-app-layout>
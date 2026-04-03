<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Pengguna') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-4 flex justify-end px-4 sm:px-0">
                <a href="{{ route('permohonan.create') }}" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-6 rounded-lg shadow-md transition transform hover:scale-105">
                    + Buat Pengajuan Baru
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="font-bold text-lg mb-6 border-b pb-2">Riwayat Pengajuan Saya</h3>
                    
                    <div class="block md:hidden space-y-4">
                        @forelse($permohonans as $p)
                            <div class="border rounded-lg p-4 shadow-sm {{ $p->status_permohonan === 'Disetujui' ? 'border-green-400 bg-green-50' : 'border-gray-200' }}">
                                <div class="flex justify-between items-start mb-2">
                                    <span class="font-bold text-sm text-gray-800">{{ $p->tujuan }}</span>
                                    <span class="text-[10px] font-bold px-2 py-1 rounded-full {{ $p->status_permohonan === 'Disetujui' ? 'bg-green-200 text-green-800' : 'bg-gray-200 text-gray-800' }}">
                                        {{ $p->status_permohonan }}
                                    </span>
                                </div>
                                <div class="text-xs text-gray-600 mb-4">
                                    Berangkat: {{ \Carbon\Carbon::parse($p->waktu_berangkat)->format('d M Y H:i') }}
                                </div>
                                
                                <div class="flex gap-2">
                                    <a href="{{ route('permohonan.show', $p->id) }}" class="flex-1 text-center bg-indigo-50 text-indigo-700 border border-indigo-200 font-bold py-2 rounded-md text-sm hover:bg-indigo-100">
                                        Detail
                                    </a>
                                    @if($p->status_permohonan === 'Disetujui')
                                        <a href="{{ route('permohonan.cetak', $p->id) }}" target="_blank" class="flex-1 text-center bg-green-600 text-white hover:bg-green-700 font-bold py-2 rounded-md text-sm shadow-sm">
                                            🖨️ Cetak SPJ
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-6 text-gray-500 text-sm border border-dashed rounded-lg">Belum ada riwayat pengajuan.</div>
                        @endforelse
                    </div>

                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-50 text-gray-700 uppercase">
                                <tr>
                                    <th class="px-6 py-4">Tujuan Kegiatan</th>
                                    <th class="px-6 py-4">Waktu Keberangkatan</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4 text-center">Aksi Dokumen</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($permohonans as $p)
                                    <tr class="border-b hover:bg-gray-50 {{ $p->status_permohonan === 'Disetujui' ? 'bg-green-50/30' : '' }}">
                                        <td class="px-6 py-4 font-bold text-gray-800">{{ $p->tujuan }}</td>
                                        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($p->waktu_berangkat)->format('d M Y H:i') }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $p->status_permohonan === 'Disetujui' ? 'bg-green-200 text-green-800' : 'bg-gray-200 text-gray-800' }}">
                                                {{ $p->status_permohonan }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex justify-center items-center gap-3">
                                                <a href="{{ route('permohonan.show', $p->id) }}" class="text-indigo-600 font-bold hover:underline">Lihat Detail</a>
                                                
                                                @if($p->status_permohonan === 'Disetujui')
                                                    <span class="text-gray-300">|</span>
                                                    <a href="{{ route('permohonan.cetak', $p->id) }}" target="_blank" class="text-green-600 font-bold hover:underline flex items-center gap-1 hover:text-green-800">
                                                        🖨️ Cetak Surat Jalan
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center py-8 text-gray-500 border border-dashed">Belum ada riwayat pengajuan.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
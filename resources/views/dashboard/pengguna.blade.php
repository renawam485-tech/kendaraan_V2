<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Pengguna') }}
        </h2>
    </x-slot>

    <div class="py-6 md:py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- TOMBOL AKSI UTAMA --}}
            <div class="mb-6 flex justify-end px-4 sm:px-0">
                <a href="{{ route('permohonan.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-lg shadow-sm transition flex items-center gap-2">
                    <i class="bi bi-plus-circle text-lg"></i> Buat Pengajuan Baru
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="p-5 md:p-6 text-gray-900">
                    <h3 class="font-bold text-lg mb-6 border-b border-gray-100 pb-3 flex items-center gap-2">
                        <i class="bi bi-clock-history text-blue-600"></i> Riwayat Pengajuan Saya
                    </h3>

                    {{-- MOBILE VIEW --}}
                    <div class="block md:hidden space-y-4">
                        @forelse($permohonans as $p)
                            @php
                                $badgeClass = match($p->status_permohonan) {
                                    'Disetujui'                      => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'Selesai'                        => 'bg-green-50 text-green-700 border-green-200',
                                    'Ditolak'                        => 'bg-red-50 text-red-700 border-red-200',
                                    'Menunggu Pengembalian Dana'     => 'bg-orange-50 text-orange-700 border-orange-200',
                                    'Menunggu Verifikasi Pengembalian' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                    default                          => 'bg-gray-50 text-gray-700 border-gray-200',
                                };
                                $borderClass = in_array($p->status_permohonan, ['Disetujui', 'Selesai']) ? 'border-blue-300 shadow-sm' : 'border-gray-200';
                            @endphp
                            <div class="border rounded-xl p-5 bg-white {{ $borderClass }}">
                                <div class="flex justify-between items-start mb-3">
                                    <span class="font-bold text-sm text-gray-800 leading-tight pr-2"><i class="bi bi-geo-alt text-gray-400 mr-1"></i> {{ $p->tujuan }}</span>
                                    <span class="text-[10px] font-bold px-2 py-1 rounded border whitespace-nowrap {{ $badgeClass }}">
                                        {{ $p->status_permohonan }}
                                    </span>
                                </div>
                                <div class="text-xs text-gray-500 mb-5 flex items-center gap-2">
                                    <i class="bi bi-calendar-event text-gray-400"></i> {{ \Carbon\Carbon::parse($p->waktu_berangkat)->format('d M Y H:i') }}
                                </div>

                                <div class="flex flex-col gap-2">
                                    <a href="{{ route('permohonan.show', $p->id) }}" class="w-full text-center bg-white text-blue-600 border border-blue-200 font-bold py-2 rounded-lg text-sm hover:bg-blue-50 transition flex items-center justify-center gap-2">
                                        <i class="bi bi-file-earmark-text"></i> Detail Pengajuan
                                    </a>
                                    @if(in_array($p->status_permohonan, ['Disetujui', 'Menunggu Pengembalian Dana', 'Menunggu Verifikasi Pengembalian', 'Selesai']))
                                        <a href="{{ route('permohonan.cetak', $p->id) }}" target="_blank" class="w-full text-center bg-gray-800 text-white hover:bg-gray-900 font-bold py-2 rounded-lg text-sm shadow-sm transition flex items-center justify-center gap-2">
                                            <i class="bi bi-printer"></i> Cetak SPJ
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-10 text-gray-500 text-sm border border-dashed border-gray-200 rounded-xl">
                                <i class="bi bi-journal-x text-4xl block mb-3 text-gray-300"></i>
                                Belum ada riwayat pengajuan kendaraan.
                            </div>
                        @endforelse
                    </div>

                    {{-- DESKTOP VIEW --}}
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-600">
                            <thead class="bg-gray-50 text-gray-500 uppercase text-xs border-y border-gray-100">
                                <tr>
                                    <th class="px-6 py-4">Tujuan Kegiatan</th>
                                    <th class="px-6 py-4">Waktu Keberangkatan</th>
                                    <th class="px-6 py-4">Status Pengajuan</th>
                                    <th class="px-6 py-4 text-center">Aksi Dokumen</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($permohonans as $p)
                                    @php
                                        $badgeClass = match($p->status_permohonan) {
                                            'Disetujui'                        => 'bg-blue-50 text-blue-700 border border-blue-200',
                                            'Selesai'                          => 'bg-green-50 text-green-700 border border-green-200',
                                            'Ditolak'                          => 'bg-red-50 text-red-700 border border-red-200',
                                            'Menunggu Pengembalian Dana'       => 'bg-orange-50 text-orange-700 border border-orange-200',
                                            'Menunggu Verifikasi Pengembalian' => 'bg-yellow-50 text-yellow-700 border border-yellow-200',
                                            default                            => 'bg-gray-50 text-gray-700 border border-gray-200',
                                        };
                                        $rowClass = in_array($p->status_permohonan, ['Disetujui', 'Selesai']) ? 'bg-blue-50/20' : '';
                                    @endphp
                                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition {{ $rowClass }}">
                                        <td class="px-6 py-4 font-bold text-gray-800"><i class="bi bi-geo-alt text-gray-400 mr-2"></i>{{ $p->tujuan }}</td>
                                        <td class="px-6 py-4"><i class="bi bi-calendar-event text-gray-400 mr-2"></i>{{ \Carbon\Carbon::parse($p->waktu_berangkat)->format('d M Y H:i') }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2.5 py-1 rounded text-xs font-bold {{ $badgeClass }}">
                                                {{ $p->status_permohonan }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex justify-center items-center gap-3">
                                                <a href="{{ route('permohonan.show', $p->id) }}" class="text-blue-600 font-bold hover:text-blue-800 hover:underline flex items-center gap-1 transition">
                                                    <i class="bi bi-file-earmark-text"></i> Detail
                                                </a>

                                                @if(in_array($p->status_permohonan, ['Disetujui', 'Menunggu Pengembalian Dana', 'Menunggu Verifikasi Pengembalian', 'Selesai']))
                                                    <span class="text-gray-300">|</span>
                                                    <a href="{{ route('permohonan.cetak', $p->id) }}" target="_blank" class="text-gray-600 font-bold hover:text-gray-900 hover:underline flex items-center gap-1 transition">
                                                        <i class="bi bi-printer"></i> Cetak SPJ
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-12 text-gray-500 border border-dashed border-gray-200">
                                            <i class="bi bi-journal-x text-4xl block mb-3 text-gray-300"></i>
                                            Belum ada riwayat pengajuan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
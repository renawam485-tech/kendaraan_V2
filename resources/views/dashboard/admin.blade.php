<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tugas Kepala Administrasi') }}
        </h2>
    </x-slot>

    <div class="py-6 md:py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div
                    class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow-sm flex items-center gap-3">
                    <i class="bi bi-check-circle-fill text-xl"></i>
                    <p class="text-sm font-bold">{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-xl overflow-hidden border border-gray-100">
                <div class="p-4 md:p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-6 flex items-center gap-2 border-b border-gray-100 pb-4">
                        <i class="bi bi-inboxes text-blue-600"></i> {{ $judul ?? 'Daftar Permohonan Kendaraan' }}
                    </h3>

                    {{-- MOBILE VIEW --}}
                    <div class="block md:hidden space-y-4">
                        @forelse($permohonans as $p)
                            @php
                                $badgeClass = match ($p->status_permohonan) {
                                    'Disetujui' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'Menunggu Validasi Admin' => 'bg-gray-50 text-gray-700 border-gray-300',
                                    'Menunggu Finalisasi' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'Ditolak' => 'bg-red-50 text-red-700 border-red-200',
                                    default => 'bg-gray-50 text-gray-700 border-gray-200',
                                };
                            @endphp
                            <div class="border border-gray-200 p-4 rounded-xl shadow-sm bg-white">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-bold text-gray-800 text-base"><i
                                            class="bi bi-person text-gray-400 mr-1"></i> {{ $p->nama_pic }}</h4>
                                    <span
                                        class="px-2 py-1 text-[10px] border font-bold rounded text-center {{ $badgeClass }}">{{ $p->status_permohonan }}</span>
                                </div>
                                <p class="text-xs text-gray-600 mb-1"><i class="bi bi-geo-alt text-gray-400 mr-1"></i>
                                    {{ $p->tujuan }}</p>
                                <p class="text-xs text-gray-600 mb-3"><i
                                        class="bi bi-calendar-event text-gray-400 mr-1"></i>
                                    {{ \Carbon\Carbon::parse($p->waktu_berangkat)->format('d M y H:i') }}</p>

                                @if ($p->status_permohonan === 'Menunggu Validasi Admin')
                                    <a href="{{ route('permohonan.validasi_admin', $p->id) }}"
                                        class="flex justify-center items-center gap-2 w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 rounded-lg text-sm shadow-sm transition"><i
                                            class="bi bi-check-circle"></i> Validasi Data</a>
                                @elseif($p->status_permohonan === 'Menunggu Finalisasi')
                                    <a href="{{ route('permohonan.finalisasi_admin', $p->id) }}"
                                        class="flex justify-center items-center gap-2 w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 rounded-lg text-sm shadow-sm transition"><i
                                            class="bi bi-file-earmark-check"></i> Finalisasi Penerbitan</a>
                                @else
                                    <a href="{{ route('permohonan.show', $p->id) }}"
                                        class="flex justify-center items-center gap-2 w-full bg-gray-50 hover:bg-gray-100 text-gray-700 font-bold py-2 rounded-lg text-sm border border-gray-300 transition"><i
                                            class="bi bi-search"></i> Lihat Detail</a>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500 text-sm border border-dashed rounded-lg"><i
                                    class="bi bi-emoji-smile text-3xl block mb-2 text-gray-300"></i> Tidak ada data.
                            </div>
                        @endforelse
                    </div>

                    {{-- DESKTOP VIEW --}}
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-600">
                            <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-y border-gray-100">
                                <tr>
                                    <th class="px-6 py-4">Pemohon & Tujuan</th>
                                    <th class="px-6 py-4">Waktu Berangkat</th>
                                    <th class="px-6 py-4">Armada / Kendaraan</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($permohonans as $p)
                                    @php
                                        $badgeClass = match ($p->status_permohonan) {
                                            'Disetujui' => 'bg-blue-50 text-blue-700 border-blue-200',
                                            'Menunggu Validasi Admin' => 'bg-gray-50 text-gray-700 border-gray-300',
                                            'Menunggu Finalisasi' => 'bg-blue-50 text-blue-700 border-blue-200',
                                            'Ditolak' => 'bg-red-50 text-red-700 border-red-200',
                                            default => 'bg-gray-50 text-gray-700 border-gray-200',
                                        };
                                    @endphp
                                    <tr class="bg-white border-b border-gray-50 hover:bg-gray-50 transition">
                                        <td class="px-6 py-4">
                                            <strong class="text-gray-800"><i
                                                    class="bi bi-person text-gray-400 mr-1"></i>
                                                {{ $p->nama_pic }}</strong><br>
                                            <span class="text-xs text-gray-500 mt-1 block"><i
                                                    class="bi bi-geo-alt text-gray-400 mr-1"></i>
                                                {{ $p->tujuan }}</span>
                                        </td>
                                        <td class="px-6 py-4"><i class="bi bi-calendar-event text-gray-400 mr-1"></i>
                                            {{ \Carbon\Carbon::parse($p->waktu_berangkat)->format('d/m/Y H:i') }}</td>
                                        <td class="px-6 py-4 text-gray-900 font-medium text-xs">
                                            @if ($p->kendaraan_id)
                                                <i class="bi bi-car-front text-gray-400 mr-1"></i>
                                                {{ $p->kendaraan->nama_kendaraan }}
                                            @elseif($p->kendaraan_vendor)
                                                <i class="bi bi-buildings text-gray-400 mr-1"></i>
                                                {{ $p->kendaraan_vendor }} <br><span
                                                    class="text-[10px] text-orange-600 font-bold bg-orange-50 px-1 rounded border border-orange-200 mt-1 inline-block">VENDOR</span>
                                            @else
                                                <span class="text-gray-400 italic">Belum ada</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="px-2 py-1 text-[11px] font-bold rounded whitespace-nowrap border {{ $badgeClass }}">{{ $p->status_permohonan }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if ($p->status_permohonan === 'Menunggu Validasi Admin')
                                                <a href="{{ route('permohonan.validasi_admin', $p->id) }}"
                                                    class="inline-flex items-center gap-1 text-white bg-blue-600 hover:bg-blue-700 px-3 py-1.5 rounded-lg text-xs font-bold shadow-sm whitespace-nowrap"><i
                                                        class="bi bi-check-circle"></i> Validasi</a>
                                            @elseif($p->status_permohonan === 'Menunggu Finalisasi')
                                                <a href="{{ route('permohonan.finalisasi_admin', $p->id) }}"
                                                    class="inline-flex items-center gap-1 text-white bg-blue-600 hover:bg-blue-700 px-3 py-1.5 rounded-lg text-xs font-bold shadow-sm whitespace-nowrap"><i
                                                        class="bi bi-file-earmark-check"></i> Finalisasi</a>
                                            @else
                                                <a href="{{ route('permohonan.show', $p->id) }}"
                                                    class="inline-flex items-center gap-1 text-gray-600 hover:text-gray-800 border border-gray-300 hover:bg-gray-50 px-3 py-1.5 rounded-lg text-xs font-bold whitespace-nowrap transition"><i
                                                        class="bi bi-search"></i> Detail</a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-10 text-gray-500"><i
                                                class="bi bi-inbox text-3xl block mb-2 text-gray-300"></i> Tidak ada
                                            data.</td>
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

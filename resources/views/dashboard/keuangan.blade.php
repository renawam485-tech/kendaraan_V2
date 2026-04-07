<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tugas Kasubag Keuangan') }}
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
                        <i class="bi bi-wallet2 text-blue-600"></i> {{ $judul ?? 'Daftar Persetujuan Anggaran' }}
                    </h3>

                    {{-- MOBILE VIEW --}}
                    <div class="block md:hidden space-y-4">
                        @forelse($permohonans as $p)
                            @php
                                $badgeClass = match ($p->status_permohonan) {
                                    'Disetujui' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'Menunggu Proses Keuangan' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'Menunggu Pengembalian Dana' => 'bg-orange-50 text-orange-700 border-orange-200',
                                    'Menunggu Verifikasi Pengembalian'
                                        => 'bg-orange-50 text-orange-700 border-orange-200',
                                    'Selesai' => 'bg-green-50 text-green-700 border-green-200',
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

                                @if ($p->status_permohonan === 'Menunggu Proses Keuangan')
                                    <a href="{{ route('permohonan.proses_keuangan', $p->id) }}"
                                        class="flex justify-center items-center gap-2 w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 rounded-lg text-sm shadow-sm transition"><i
                                            class="bi bi-cash-coin"></i> Proses RAB</a>
                                @elseif($p->status_permohonan === 'Menunggu Verifikasi Pengembalian')
                                    <a href="{{ route('permohonan.show', $p->id) }}"
                                        class="flex justify-center items-center gap-2 w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 rounded-lg text-sm shadow-sm transition"><i
                                            class="bi bi-arrow-return-left"></i> Verifikasi Refund</a>
                                @else
                                    <a href="{{ route('permohonan.show', $p->id) }}"
                                        class="flex justify-center items-center gap-2 w-full bg-gray-50 hover:bg-gray-100 text-gray-700 font-bold py-2 rounded-lg text-sm border border-gray-300 transition"><i
                                            class="bi bi-search"></i> Lihat Detail</a>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500 text-sm border border-dashed rounded-lg"><i
                                    class="bi bi-emoji-smile text-3xl block mb-2 text-gray-300"></i> Tidak ada tugas
                                keuangan saat ini.</div>
                        @endforelse
                    </div>

                    {{-- DESKTOP VIEW --}}
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-600">
                            <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-y border-gray-100">
                                <tr>
                                    <th class="px-6 py-4">Pemohon & Tujuan</th>
                                    <th class="px-6 py-4">Waktu Berangkat</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($permohonans as $p)
                                    @php
                                        $badgeClass = match ($p->status_permohonan) {
                                            'Disetujui' => 'bg-blue-50 text-blue-700 border-blue-200',
                                            'Menunggu Proses Keuangan' => 'bg-blue-50 text-blue-700 border-blue-200',
                                            'Menunggu Pengembalian Dana'
                                                => 'bg-orange-50 text-orange-700 border-orange-200',
                                            'Menunggu Verifikasi Pengembalian'
                                                => 'bg-orange-50 text-orange-700 border-orange-200',
                                            'Selesai' => 'bg-green-50 text-green-700 border-green-200',
                                            'Ditolak' => 'bg-red-50 text-red-700 border-red-200',
                                            default => 'bg-gray-50 text-gray-700 border-gray-200',
                                        };
                                    @endphp
                                    <tr class="bg-white border-b border-gray-50 hover:bg-blue-50/30 transition">
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
                                        <td class="px-6 py-4">
                                            <span
                                                class="px-2.5 py-1 text-[11px] border font-bold rounded-full whitespace-nowrap {{ $badgeClass }}">{{ $p->status_permohonan }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if ($p->status_permohonan === 'Menunggu Proses Keuangan')
                                                <a href="{{ route('permohonan.proses_keuangan', $p->id) }}"
                                                    class="inline-flex items-center gap-1 text-white bg-blue-600 hover:bg-blue-700 px-3 py-1.5 rounded-lg text-xs font-bold shadow-sm whitespace-nowrap transition"><i
                                                        class="bi bi-cash-coin"></i> Proses RAB</a>
                                            @elseif($p->status_permohonan === 'Menunggu Verifikasi Pengembalian')
                                                <a href="{{ route('permohonan.show', $p->id) }}"
                                                    class="inline-flex items-center gap-1 text-white bg-orange-500 hover:bg-orange-600 px-3 py-1.5 rounded-lg text-xs font-bold shadow-sm whitespace-nowrap transition"><i
                                                        class="bi bi-arrow-return-left"></i> Verifikasi</a>
                                            @else
                                                <a href="{{ route('permohonan.show', $p->id) }}"
                                                    class="inline-flex items-center gap-1 text-gray-600 hover:text-gray-800 border border-gray-300 hover:bg-gray-50 px-3 py-1.5 rounded-lg text-xs font-bold whitespace-nowrap transition"><i
                                                        class="bi bi-search"></i> Lihat Detail</a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan=\"4\" class=\"text-center py-10 text-gray-500\"><i
                                                class="bi bi-inbox text-3xl block mb-2 text-gray-300"></i> Tidak ada
                                            tugas keuangan saat ini.</td>
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

{{-- ============================================================ --}}
{{-- PARTIAL: _pengguna.blade.php                               --}}
{{-- Role: pengguna                                             --}}
{{-- ============================================================ --}}

<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">

    {{-- HEADER + PENCARIAN --}}
    <div class="p-5 border-b border-gray-100 bg-white flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <h3 class="font-bold text-gray-800">
            <i class="bi bi-list-ul text-blue-600 mr-2"></i> Daftar Riwayat Pengajuan
        </h3>

        <form action="{{ route('laporan.index') }}" method="GET" class="relative w-full sm:w-80">
            <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari kode atau tujuan..."
                class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition bg-gray-50">
            @if (request('search'))
                <a href="{{ route('laporan.index') }}"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-red-500 transition">
                    <i class="bi bi-x-circle-fill"></i>
                </a>
            @endif
        </form>
    </div>

    <div class="overflow-x-auto">

        {{-- MOBILE VIEW --}}
        <div class="block md:hidden space-y-3 p-4 bg-gray-50">
            @forelse($data as $p)
                @php
                    $badgeClass = match ($p->status_permohonan) {
                        'Disetujui' => 'bg-blue-50 text-blue-700 border-blue-200',
                        'Selesai' => 'bg-green-50 text-green-700 border-green-200',
                        'Ditolak', 'Dibatalkan' => 'bg-red-50 text-red-700 border-red-200',
                        'Menunggu Pengembalian Dana' => 'bg-orange-50 text-orange-700 border-orange-200',
                        'Menunggu Verifikasi Pengembalian' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                        default => 'bg-gray-50 text-gray-700 border-gray-200',
                    };
                @endphp
                <div class="bg-white border border-gray-200 p-4 rounded-xl shadow-sm">
                    <div class="flex justify-between items-start mb-3">
                        <span class="font-bold text-gray-800 text-sm leading-tight pr-2">
                            <i class="bi bi-geo-alt text-gray-400 mr-1"></i>
                            {{ $p->tujuan }}
                        </span>
                        <span class="text-[10px] font-bold px-2 py-1 rounded border whitespace-nowrap text-center {{ $badgeClass }}">
                            {{ $p->status_permohonan }}
                        </span>
                    </div>
                    <div class="text-xs text-gray-500 mb-4 flex items-center gap-2">
                        <i class="bi bi-calendar-event text-gray-400"></i> Berangkat:
                        {{ \Carbon\Carbon::parse($p->waktu_berangkat)->format('d M Y, H:i') }}
                    </div>
                    <div class="flex flex-col gap-2 pt-3 border-t border-gray-50">
                        <a href="{{ route('permohonan.show', $p->id) }}"
                            class="w-full text-center bg-blue-50 text-blue-700 border border-blue-100 font-bold py-2 rounded-lg text-sm hover:bg-blue-100 transition flex justify-center items-center gap-2">
                            <i class="bi bi-search"></i> Lihat Detail
                        </a>
                    </div>
                </div>
            @empty
                <div class="text-center py-16 text-gray-500 text-sm border border-dashed border-gray-200 bg-white rounded-xl">
                    <i class="bi bi-journal-x text-5xl block mb-3 text-gray-300"></i>
                    <p class="font-medium text-gray-500">Belum ada riwayat pengajuan.</p>
                    <a href="{{ route('permohonan.create') }}"
                        class="mt-3 inline-block text-sm text-blue-600 hover:underline font-bold">Buat pengajuan baru →</a>
                </div>
            @endforelse
        </div>

        {{-- DESKTOP VIEW --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-600">
                <thead class="bg-gray-50 border-b border-gray-100 text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Kode</th>
                        <th class="px-6 py-4">Tujuan</th>
                        <th class="px-6 py-4">Jadwal Keberangkatan</th>
                        <th class="px-6 py-4">Kendaraan</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $i => $p)
                        @php
                            $badgeClass = match ($p->status_permohonan) {
                                'Disetujui' => 'bg-blue-50 text-blue-700 border-blue-200',
                                'Selesai' => 'bg-green-50 text-green-700 border-green-200',
                                'Ditolak', 'Dibatalkan' => 'bg-red-50 text-red-700 border-red-200',
                                'Menunggu Pengembalian Dana' => 'bg-orange-50 text-orange-700 border-orange-200',
                                'Menunggu Verifikasi Pengembalian' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                default => 'bg-gray-50 text-gray-700 border-gray-200',
                            };
                        @endphp
                        <tr class="border-b border-gray-50 hover:bg-blue-50/20 transition">
                            <td class="px-6 py-4 text-gray-400">
                                {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-6 py-4">
                                @if ($p->kode_permohonan)
                                    <span class="font-black text-blue-700 tracking-wider text-[11px] bg-blue-50 border border-blue-200 px-2 py-0.5 rounded-md whitespace-nowrap">{{ $p->kode_permohonan }}</span>
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <strong class="text-gray-800 text-base block mb-1"><i class="bi bi-geo-alt text-gray-400 mr-1"></i>{{ $p->tujuan }}</strong>
                                <span class="text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded border border-gray-200">{{ $p->kategori_kegiatan ?? 'Tujuan Umum' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <i class="bi bi-calendar-event text-gray-400"></i>
                                    {{ \Carbon\Carbon::parse($p->waktu_berangkat)->format('d M Y') }}
                                </div>
                                <div class="text-xs text-gray-500 mt-1">
                                    <i class="bi bi-clock text-gray-400"></i>
                                    {{ \Carbon\Carbon::parse($p->waktu_berangkat)->format('H:i') }} WIB
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if ($p->kendaraan)
                                    <span class="text-sm font-medium">{{ $p->kendaraan->nama_kendaraan }}</span>
                                @elseif($p->kendaraanVendor)
                                    <span class="text-sm font-medium">{{ $p->kendaraanVendor->nama_kendaraan }}</span>
                                    <span class="text-orange-600 bg-orange-50 px-1 rounded text-[10px] font-bold ml-1">Vendor</span>
                                @else
                                    <span class="text-gray-400 italic">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded text-xs font-bold border whitespace-nowrap {{ $badgeClass }}">{{ $p->status_permohonan }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center items-center gap-2">
                                    <a href="{{ route('permohonan.show', $p->id) }}"
                                        class="inline-flex items-center gap-1 text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 px-3 py-1.5 rounded-lg text-xs font-bold transition">
                                        <i class="bi bi-search"></i> Detail
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-16 text-gray-400 border border-dashed border-gray-100">
                                <i class="bi bi-journal-x text-5xl block mb-3 text-gray-300"></i>
                                <p class="font-medium text-gray-500">Belum ada riwayat.</p>
                                <a href="{{ route('permohonan.create') }}"
                                    class="mt-2 inline-block text-sm text-blue-600 hover:underline font-bold">Buat pengajuan baru →</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    {{-- PAGINATION PENGGUNA (dengan append search) --}}
    @if ($data->hasPages())
        @php $search = request('search'); @endphp
        <div class="px-5 py-3 border-t border-gray-100 flex items-center justify-between">
            <p class="text-xs text-gray-400">
                Menampilkan {{ $data->firstItem() ?? 0 }} - {{ $data->lastItem() ?? 0 }}
                dari {{ $data->total() }} data
            </p>
            <div class="flex items-center gap-2">
                @if ($data->onFirstPage())
                    <span class="px-3 py-2 text-xs font-bold text-gray-300 cursor-not-allowed">
                        <i class="bi bi-chevron-left"></i>
                    </span>
                @else
                    <a href="{{ $data->previousPageUrl() }}&search={{ $search }}"
                        class="px-3 py-2 text-xs font-bold text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                        <i class="bi bi-chevron-left"></i>
                    </a>
                @endif

                <div class="hidden md:flex items-center gap-1">
                    @php
                        $current = $data->currentPage();
                        $last    = $data->lastPage();
                        $start   = max(1, $current - 1);
                        $end     = min($last, $current + 1);
                        if ($current <= 2) { $start = 1; $end = min(3, $last); }
                        if ($current >= $last - 1) { $start = max(1, $last - 2); $end = $last; }
                    @endphp

                    @if ($start > 1)
                        <a href="{{ $data->url(1) }}&search={{ $search }}"
                            class="w-8 h-8 flex items-center justify-center rounded-lg text-xs font-bold text-gray-500 hover:bg-gray-100">1</a>
                        @if ($start > 2)
                            <span class="w-8 h-8 flex items-center justify-center text-gray-400">...</span>
                        @endif
                    @endif

                    @for ($page = $start; $page <= $end; $page++)
                        @if ($page == $current)
                            <span class="w-8 h-8 flex items-center justify-center rounded-lg text-xs font-bold bg-blue-600 text-white shadow-sm">{{ $page }}</span>
                        @else
                            <a href="{{ $data->url($page) }}&search={{ $search }}"
                                class="w-8 h-8 flex items-center justify-center rounded-lg text-xs font-bold text-gray-500 hover:bg-gray-100">{{ $page }}</a>
                        @endif
                    @endfor

                    @if ($end < $last)
                        @if ($end < $last - 1)
                            <span class="w-8 h-8 flex items-center justify-center text-gray-400">...</span>
                        @endif
                        <a href="{{ $data->url($last) }}&search={{ $search }}"
                            class="w-8 h-8 flex items-center justify-center rounded-lg text-xs font-bold text-gray-500 hover:bg-gray-100">{{ $last }}</a>
                    @endif
                </div>

                @if ($data->hasMorePages())
                    <a href="{{ $data->nextPageUrl() }}&search={{ $search }}"
                        class="px-3 py-2 text-xs font-bold text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                        <i class="bi bi-chevron-right"></i>
                    </a>
                @else
                    <span class="px-3 py-2 text-xs font-bold text-gray-300 cursor-not-allowed">
                        <i class="bi bi-chevron-right"></i>
                    </span>
                @endif
            </div>
            <p class="text-xs text-gray-400">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
        </div>
    @endif

</div>

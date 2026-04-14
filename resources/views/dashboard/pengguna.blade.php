<x-app-layout>
    <div class="w-full px-4 sm:px-6 lg:px-8 mb-5">
        <div class="bg-white rounded-xl border border-gray-200 px-5 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <i class="bi bi-emoji-wink text-2xl text-blue-500"></i>
                <span class="font-semibold text-gray-700">Halo, {{ Auth::user()->name ?? 'User' }}!</span>
            </div>
            <div class="text-sm text-gray-500">
                {{ \Carbon\Carbon::now()->translatedFormat('l, d M Y') }}
            </div>
        </div>
    </div>
    <div class="py-6 bg-slate-50 min-h-screen">
        <div class="px-4 sm:px-6 lg:px-8 space-y-5">

            {{-- STATS STRIP — nilai dari controller (DB query), bukan filter per halaman --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                @foreach ([['Total Pengajuan', $stats['total'], 'bi-journals', 'text-blue-600', 'bg-blue-50'], ['Dalam Proses', $stats['proses'], 'bi-hourglass-split', 'text-amber-600', 'bg-amber-50'], ['Selesai', $stats['selesai'], 'bi-patch-check-fill', 'text-emerald-600', 'bg-emerald-50'], ['Ditolak', $stats['ditolak'], 'bi-x-octagon', 'text-red-500', 'bg-red-50']] as [$lbl, $val, $icon, $tc, $bg])
                    <div
                        class="bg-white rounded-xl border border-gray-200 px-4 py-3.5 flex items-center gap-3 shadow-sm">
                        <div
                            class="w-10 h-10 rounded-lg {{ $bg }} flex items-center justify-center flex-shrink-0">
                            <i class="bi {{ $icon }} {{ $tc }} text-lg"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 leading-tight">{{ $lbl }}</p>
                            <p class="text-2xl font-black text-gray-800 leading-tight">{{ $val }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- TABEL --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div
                    class="px-5 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2 text-sm">
                        <i class="bi bi-activity text-blue-600"></i>
                        Pengajuan Aktif Saat Ini
                        <span class="ml-1 text-xs font-semibold text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full"
                            id="countBadge">
                            {{ $permohonans->total() }} data
                        </span>
                    </h3>
                    <form action="{{ route('dashboard') }}" method="GET" class="relative w-full sm:w-72">
                        <i
                            class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>
                        <input type="text" name="search" id="searchInput" value="{{ request('search') }}"
                            placeholder="Cari kode, tujuan..."
                            class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition bg-gray-50">
                        @if (request('search'))
                            <a href="{{ url()->current() }}"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-red-500 transition">
                                <i class="bi bi-x-circle-fill"></i>
                            </a>
                        @endif
                    </form>
                </div>

                {{-- MOBILE CARDS --}}
                <div class="block md:hidden divide-y divide-gray-100" id="mobileList">
                    @forelse($permohonans as $i => $p)
                        @php $sc = $p->status_permohonan->badgeClass(); @endphp
                        <div class="p-4 hover:bg-slate-50 transition-colors"
                            data-search="{{ strtolower($p->tujuan . ' ' . ($p->kode_permohonan ?? '') . ' ' . $p->status_permohonan->value) }}">
                            <div class="flex items-start justify-between gap-2">
                                <div class="flex items-start gap-2 min-w-0">
                                    <span
                                        class="flex-shrink-0 w-6 h-6 bg-slate-100 text-slate-500 text-xs font-bold rounded-md flex items-center justify-center mt-0.5">{{ ($permohonans->currentPage() - 1) * $permohonans->perPage() + $loop->iteration }}</span>
                                    <div class="min-w-0">
                                        @if ($p->kode_permohonan)
                                            <span
                                                class="text-[10px] font-black text-blue-700 tracking-widest bg-blue-50 border border-blue-200 px-1.5 py-0.5 rounded block w-fit mb-1">{{ $p->kode_permohonan }}</span>
                                        @endif
                                        <p class="font-semibold text-sm text-gray-800 truncate">{{ $p->tujuan }}</p>
                                        <p class="text-xs text-gray-500 mt-0.5">
                                            <i
                                                class="bi bi-calendar2-event mr-1"></i>{{ \Carbon\Carbon::parse($p->waktu_berangkat)->format('d M Y, H:i') }}
                                        </p>
                                    </div>
                                </div>
                                <span
                                    class="text-[10px] font-bold px-2 py-1 rounded-md border whitespace-nowrap flex-shrink-0 {{ $sc }}">{{ $p->status_permohonan->value }}</span>
                            </div>
                            <div class="flex gap-2 mt-3 pl-8">
                                <a href="{{ route('permohonan.show', $p->id) }}"
                                    class="flex-1 text-center py-1.5 text-xs font-bold text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-lg transition">
                                    <i class="bi bi-eye mr-1"></i>Detail
                                </a>
                                @if ($p->status_permohonan !== \App\Enums\StatusPermohonan::SELESAI)
                                    <a href="{{ route('permohonan.cetak', $p->id) }}" target="_blank"
                                        class="flex-1 text-center py-1.5 text-xs font-bold text-gray-700 bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-lg transition">
                                        <i class="bi bi-printer mr-1"></i>Cetak SPJ
                                    </a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="py-16 text-center text-gray-400">
                            <i class="bi bi-journal-x text-5xl block mb-3 text-gray-300"></i>
                            <p class="font-medium text-gray-500">Belum ada pengajuan aktif</p>
                            <a href="{{ route('permohonan.create') }}"
                                class="mt-3 inline-block text-sm text-blue-600 hover:underline font-bold">Buat pengajuan
                                pertama →</a>
                        </div>
                    @endforelse
                </div>

                {{-- DESKTOP TABLE --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-slate-50 border-b border-gray-200">
                                <th
                                    class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-12">
                                    No</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Kode</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Tujuan</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Jadwal</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Kendaraan</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($permohonans as $p)
                                @php $sc = $p->status_permohonan->badgeClass(); @endphp
                                <tr class="hover:bg-blue-50/20 transition-colors"
                                    data-search="{{ strtolower($p->tujuan . ' ' . ($p->kode_permohonan ?? '') . ' ' . $p->status_permohonan->value . ' ' . ($p->nama_pic ?? '')) }}">
                                    <td class="px-4 py-3.5 text-center text-xs text-gray-400 font-semibold">
                                        {{ ($permohonans->currentPage() - 1) * $permohonans->perPage() + $loop->iteration }}
                                    </td>
                                    <td class="px-4 py-3.5">
                                        @if ($p->kode_permohonan)
                                            <span
                                                class="font-black text-blue-700 tracking-wider text-[11px] bg-blue-50 border border-blue-200 px-2 py-0.5 rounded-md whitespace-nowrap">{{ $p->kode_permohonan }}</span>
                                        @else
                                            <span class="text-gray-300">—</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3.5">
                                        <p class="font-semibold text-gray-800">{{ $p->tujuan }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5"><i
                                                class="bi bi-geo-alt mr-0.5"></i>{{ $p->titik_jemput }}</p>
                                    </td>
                                    <td class="px-4 py-3.5 whitespace-nowrap">
                                        <p class="text-gray-700 font-medium">
                                            {{ \Carbon\Carbon::parse($p->waktu_berangkat)->format('d M Y') }}</p>
                                        <p class="text-xs text-gray-400">
                                            {{ \Carbon\Carbon::parse($p->waktu_berangkat)->format('H:i') }} WIB</p>
                                    </td>
                                    <td class="px-4 py-3.5 text-gray-600 text-xs">
                                        @if ($p->kendaraan_id)
                                            <p class="font-medium text-gray-700">{{ $p->kendaraan->nama_kendaraan }}
                                            </p>
                                            <p class="text-gray-400">{{ $p->kendaraan->plat_nomor }}</p>
                                        @elseif($p->kendaraanVendor)
                                            <p class="font-medium text-gray-700">
                                                {{ $p->kendaraanVendor->nama_kendaraan }}</p>
                                            <span
                                                class="text-orange-600 bg-orange-50 border border-orange-200 px-1 rounded text-[10px] font-bold">VENDOR</span>
                                        @else
                                            <span class="text-gray-400 italic">Menunggu alokasi</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3.5">
                                        <span
                                            class="inline-block text-[11px] font-bold px-2.5 py-1 rounded-md border {{ $sc }}">{{ $p->status_permohonan->value }}</span>
                                    </td>
                                    <td class="px-4 py-3.5">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <a href="{{ route('permohonan.show', $p->id) }}"
                                                class="inline-flex items-center gap-1 text-xs font-bold text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200 px-2.5 py-1.5 rounded-lg transition">
                                                <i class="bi bi-eye"></i> Detail
                                            </a>
                                            @if ($p->status_permohonan !== \App\Enums\StatusPermohonan::SELESAI)
                                                <a href="{{ route('permohonan.cetak', $p->id) }}" target="_blank"
                                                    class="flex-1 text-center py-1.5 text-xs font-bold text-gray-700 bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-lg transition">
                                                    <i class="bi bi-printer mr-1"></i>Cetak SPJ
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-16 text-center">
                                        <i class="bi bi-journal-x text-5xl block mb-3 text-gray-300"></i>
                                        <p class="font-medium text-gray-500">Belum ada pengajuan aktif</p>
                                        <a href="{{ route('permohonan.create') }}"
                                            class="mt-2 inline-block text-sm text-blue-600 hover:underline font-bold">Buat
                                            pengajuan pertama →</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-5 py-3 border-t border-gray-100 flex items-center justify-between">
                    <p class="text-xs text-gray-400">
                        Menampilkan {{ $permohonans->firstItem() ?? 0 }} - {{ $permohonans->lastItem() ?? 0 }}
                        dari {{ $permohonans->total() }} data
                    </p>
                    <div class="flex items-center gap-2">
                        {{-- Tombol Previous --}}
                        @if ($permohonans->onFirstPage())
                            <span class="px-3 py-2 text-xs font-bold text-gray-300 cursor-not-allowed">
                                <i class="bi bi-chevron-left"></i>
                            </span>
                        @else
                            <a href="{{ $permohonans->previousPageUrl() }}"
                                class="px-3 py-2 text-xs font-bold text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                <i class="bi bi-chevron-left"></i>
                            </a>
                        @endif

                        {{-- Nomor Halaman dengan Elipsis --}}
                        <div class="hidden md:flex items-center gap-1">
                            @php
                                $current = $permohonans->currentPage();
                                $last = $permohonans->lastPage();
                                $start = max(1, $current - 1);
                                $end = min($last, $current + 1);

                                // Tampilkan 3 halaman di awal
                                if ($current <= 2) {
                                    $start = 1;
                                    $end = min(3, $last);
                                }
                                // Tampilkan 3 halaman di akhir
                                if ($current >= $last - 1) {
                                    $start = max(1, $last - 2);
                                    $end = $last;
                                }
                            @endphp

                            {{-- Halaman 1 --}}
                            @if ($start > 1)
                                <a href="{{ $permohonans->url(1) }}"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg text-xs font-bold text-gray-500 hover:bg-gray-100">
                                    1
                                </a>
                                @if ($start > 2)
                                    <span class="w-8 h-8 flex items-center justify-center text-gray-400">...</span>
                                @endif
                            @endif

                            {{-- Range halaman --}}
                            @for ($page = $start; $page <= $end; $page++)
                                @if ($page == $current)
                                    <span
                                        class="w-8 h-8 flex items-center justify-center rounded-lg text-xs font-bold bg-blue-600 text-white shadow-sm">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $permohonans->url($page) }}"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg text-xs font-bold text-gray-500 hover:bg-gray-100">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endfor

                            {{-- Halaman terakhir --}}
                            @if ($end < $last)
                                @if ($end < $last - 1)
                                    <span class="w-8 h-8 flex items-center justify-center text-gray-400">...</span>
                                @endif
                                <a href="{{ $permohonans->url($last) }}"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg text-xs font-bold text-gray-500 hover:bg-gray-100">
                                    {{ $last }}
                                </a>
                            @endif
                        </div>

                        {{-- Tombol Next --}}
                        @if ($permohonans->hasMorePages())
                            <a href="{{ $permohonans->nextPageUrl() }}"
                                class="px-3 py-2 text-xs font-bold text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        @else
                            <span class="px-3 py-2 text-xs font-bold text-gray-300 cursor-not-allowed">
                                <i class="bi bi-chevron-right"></i>
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-gray-400">
                        {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        const searchInput = document.getElementById('searchInput');
        if (searchInput.value.trim() !== '') {
            const total = {{ $permohonans->total() }};
            document.getElementById('tableInfo').textContent =
                `Ditemukan ${total} data untuk "${searchInput.value}"`;
            document.getElementById('countBadge').textContent = `${total} data`;
        }
    </script>
</x-app-layout>

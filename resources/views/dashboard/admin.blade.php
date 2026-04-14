<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tugas Anda
        </h2>
    </x-slot>

    <div class="py-6 bg-slate-50 min-h-screen">
        <div class="w-full px-4 sm:px-6 lg:px-8">

            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div
                    class="px-5 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <h3 class="font-bold text-gray-800 flex items-center gap-2 text-sm">
                            {{ $judul ?? 'Daftar Permohonan' }}
                            <span class="ml-1 text-xs font-semibold text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full">
                                {{ $permohonans->total() }} data
                            </span>
                        </h3>
                    </div>
                    <div class="relative w-full sm:w-72">
                        <form action="{{ url()->current() }}" method="GET" class="relative w-full sm:w-72">
                            <i
                                class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>
                            <input type="text" name="search" id="searchInput" value="{{ request('search') }}"
                                autocomplete="off" placeholder="Cari kode, nama, tujuan..."
                                class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition bg-gray-50 focus:bg-white">
                            @if (request('search'))
                                <a href="{{ url()->current() }}"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-red-500 transition">
                                    <i class="bi bi-x-circle-fill"></i>
                                </a>
                            @endif
                        </form>
                    </div>
                </div>

                {{-- ================================================== --}}
                {{-- MOBILE CARDS (MENGGUNAKAN $permohonans)              --}}
                {{-- ================================================== --}}
                <div class="block md:hidden divide-y divide-gray-100">
                    @forelse($permohonans as $index => $p)
                        @php
                            $status = $p->status_permohonan;
                            $detailUrl = route('dashboard');

                            if (Auth::user()->role === 'kepala_admin') {
                                $detailUrl =
                                    $status === \App\Enums\StatusPermohonan::MENUNGGU_VALIDASI_ADMIN
                                        ? route('permohonan.validasi_admin', $p->id)
                                        : route('permohonan.finalisasi_admin', $p->id);
                            } elseif (Auth::user()->role === 'spsi') {
                                $detailUrl = route('permohonan.proses_spsi', $p->id);
                            } elseif (Auth::user()->role === 'keuangan') {
                                $detailUrl =
                                    $status === \App\Enums\StatusPermohonan::MENUNGGU_PROSES_KEUANGAN
                                        ? route('permohonan.proses_keuangan', $p->id)
                                        : route('permohonan.show', $p->id);
                            }

                            $badgeClass =
                                str_contains($status->value, 'Validasi') || str_contains($status->value, 'RAB')
                                    ? 'bg-blue-50 text-blue-700 border-blue-200'
                                    : 'bg-orange-50 text-orange-700 border-orange-200';
                        @endphp
                        <div class="p-4 hover:bg-slate-50 transition-colors">
                            <div class="flex items-start justify-between gap-2">
                                <div class="flex items-start gap-2 min-w-0">
                                    <span
                                        class="flex-shrink-0 w-6 h-6 bg-slate-100 text-slate-500 text-xs font-bold rounded-md flex items-center justify-center mt-0.5">
                                        {{ ($permohonans->currentPage() - 1) * $permohonans->perPage() + $index + 1 }}
                                    </span>
                                    <div class="min-w-0">
                                        @if ($p->kode_permohonan)
                                            <span
                                                class="text-[10px] font-black text-blue-700 tracking-widest bg-blue-50 border border-blue-200 px-1.5 py-0.5 rounded block w-fit mb-1">{{ $p->kode_permohonan }}</span>
                                        @endif
                                        <p class="font-semibold text-sm text-gray-800 truncate">{{ $p->nama_pic }}</p>
                                        <p class="text-xs text-gray-500 mt-0.5">
                                            <i class="bi bi-geo-alt mr-0.5"></i>{{ $p->tujuan }}
                                        </p>
                                        <p class="text-xs text-gray-400 mt-0.5">
                                            <i
                                                class="bi bi-calendar2-event mr-0.5"></i>{{ \Carbon\Carbon::parse($p->waktu_berangkat)->format('d M Y, H:i') }}
                                        </p>
                                        <p class="text-xs text-gray-400 mt-0.5">
                                            <i class="bi bi-clock-history mr-0.5"></i>
                                            <span class="relative-time-updated"
                                                data-updated="{{ $p->updated_at->toISOString() }}">
                                                {{ $p->updated_at->diffForHumans() }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex gap-2 mt-3 pl-8">
                                @if ($status === \App\Enums\StatusPermohonan::MENUNGGU_VALIDASI_ADMIN)
                                    <a href="{{ route('permohonan.validasi_admin', $p->id) }}"
                                        class="flex-1 text-center py-1.5 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition flex items-center justify-center gap-1">Validasi
                                    </a>
                                @elseif($status === \App\Enums\StatusPermohonan::MENUNGGU_FINALISASI)
                                    <a href="{{ route('permohonan.finalisasi_admin', $p->id) }}"
                                        class="flex-1 text-center py-1.5 text-xs font-bold text-white bg-purple-600 hover:bg-purple-700 rounded-lg transition flex items-center justify-center gap-1">Finalisasi
                                    </a>
                                @else
                                    <a href="{{ route('permohonan.show', $p->id) }}"
                                        class="flex-1 text-center py-1.5 text-xs font-bold text-gray-700 bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-lg transition flex items-center justify-center gap-1">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="py-16 text-center text-gray-400">
                            <i class="bi bi-inbox text-5xl block mb-3 text-gray-300"></i>
                            <p class="font-medium text-gray-500">Tidak ada data</p>
                        </div>
                    @endforelse
                </div>

                {{-- ================================================== --}}
                {{-- DESKTOP TABLE                                       --}}
                {{-- ================================================== --}}
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
                                    Pemohon</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Tujuan</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Waktu Berangkat</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Armada</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($permohonans as $i => $p)
                                <tr class="hover:bg-blue-50/20 transition-colors">
                                    <td class="px-4 py-3.5 text-center text-xs text-gray-400 font-semibold">
                                        {{ ($permohonans->currentPage() - 1) * $permohonans->perPage() + $loop->iteration }}
                                    </td>
                                    <td class="px-4 py-3.5">
                                        @if ($p->kode_permohonan)
                                            <span
                                                class="font-black text-blue-700 tracking-wider text-[11px] bg-blue-50 border border-blue-200 px-2 py-0.5 rounded-md">{{ $p->kode_permohonan }}</span>
                                        @else
                                            <span class="text-gray-300">—</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3.5">
                                        <p class="font-semibold text-gray-800">{{ $p->nama_pic }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5">{{ $p->kontak_pic }}</p>
                                    </td>
                                    <td class="px-4 py-3.5">
                                        <p class="text-gray-700">{{ $p->tujuan }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5"><i
                                                class="bi bi-people mr-0.5"></i>{{ $p->jumlah_penumpang }} orang</p>
                                    </td>
                                    <td class="px-4 py-3.5 whitespace-nowrap">
                                        <p class="text-gray-700 font-medium">
                                            {{ \Carbon\Carbon::parse($p->waktu_berangkat)->format('d M Y') }}</p>
                                        <p class="text-xs text-gray-400">
                                            {{ \Carbon\Carbon::parse($p->waktu_berangkat)->format('H:i') }} WIB</p>
                                    </td>
                                    <td class="px-4 py-3.5 text-xs">
                                        @if ($p->kendaraan_id)
                                            <p class="font-medium text-gray-700">{{ $p->kendaraan->nama_kendaraan }}
                                            </p>
                                        @elseif($p->kendaraanVendor)
                                            <p class="font-medium text-gray-700">
                                                {{ $p->kendaraanVendor->nama_kendaraan }}</p>
                                            <span
                                                class="text-orange-600 bg-orange-50 border border-orange-100 px-1 rounded text-[10px] font-bold">VENDOR</span>
                                        @else
                                            <span class="text-gray-400 italic">Belum ada</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3.5">
                                        <x-status-badge :status="$p->status_permohonan" />
                                    </td>
                                    <td class="px-4 py-3.5 text-center">
                                        @if ($p->status_permohonan === \App\Enums\StatusPermohonan::MENUNGGU_VALIDASI_ADMIN)
                                            <a href="{{ route('permohonan.validasi_admin', $p->id) }}"
                                                class="inline-flex items-center gap-1 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 px-3 py-1.5 rounded-lg shadow-sm transition">
                                                <i class="bi bi-check2-circle"></i> Validasi
                                            </a>
                                        @elseif($p->status_permohonan === \App\Enums\StatusPermohonan::MENUNGGU_FINALISASI)
                                            <a href="{{ route('permohonan.finalisasi_admin', $p->id) }}"
                                                class="inline-flex items-center gap-1 text-xs font-bold text-white bg-purple-600 hover:bg-purple-700 px-3 py-1.5 rounded-lg shadow-sm transition">
                                                <i class="bi bi-file-earmark-check"></i> Finalisasi
                                            </a>
                                        @else
                                            <a href="{{ route('permohonan.show', $p->id) }}"
                                                class="inline-flex items-center gap-1 text-xs font-bold text-gray-600 hover:text-gray-800 bg-white hover:bg-gray-50 border border-gray-300 px-3 py-1.5 rounded-lg transition">
                                                <i class="bi bi-eye"></i> Detail
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-16 text-center">
                                        <i class="bi bi-inbox text-5xl block mb-3 text-gray-300"></i>
                                        <p class="font-medium text-gray-500">Tidak ada data</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- ================================================== --}}
                {{-- FOOTER (Pagination)                                 --}}
                {{-- ================================================== --}}
                <div class="px-5 py-3 border-t border-gray-100 flex items-center justify-between">
                    <p class="text-xs text-gray-400">
                        Menampilkan {{ $permohonans->firstItem() ?? 0 }} - {{ $permohonans->lastItem() ?? 0 }}
                        dari {{ $permohonans->total() }} data
                    </p>
                    <div class="flex items-center gap-2">
                        {{-- Previous --}}
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

                        {{-- Page Numbers --}}
                        <div class="hidden md:flex items-center gap-1">
                            @php
                                $current = $permohonans->currentPage();
                                $last = $permohonans->lastPage();
                                $start = max(1, $current - 1);
                                $end = min($last, $current + 1);

                                if ($current <= 2) {
                                    $start = 1;
                                    $end = min(3, $last);
                                }
                                if ($current >= $last - 1) {
                                    $start = max(1, $last - 2);
                                    $end = $last;
                                }
                            @endphp

                            @if ($start > 1)
                                <a href="{{ $permohonans->url(1) }}"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg text-xs font-bold text-gray-500 hover:bg-gray-100">1</a>
                                @if ($start > 2)
                                    <span class="w-8 h-8 flex items-center justify-center text-gray-400">...</span>
                                @endif
                            @endif

                            @for ($page = $start; $page <= $end; $page++)
                                @if ($page == $current)
                                    <span
                                        class="w-8 h-8 flex items-center justify-center rounded-lg text-xs font-bold bg-blue-600 text-white shadow-sm">{{ $page }}</span>
                                @else
                                    <a href="{{ $permohonans->url($page) }}"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg text-xs font-bold text-gray-500 hover:bg-gray-100">{{ $page }}</a>
                                @endif
                            @endfor

                            @if ($end < $last)
                                @if ($end < $last - 1)
                                    <span class="w-8 h-8 flex items-center justify-center text-gray-400">...</span>
                                @endif
                                <a href="{{ $permohonans->url($last) }}"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg text-xs font-bold text-gray-500 hover:bg-gray-100">{{ $last }}</a>
                            @endif
                        </div>

                        {{-- Next --}}
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
                    <p class="text-xs text-gray-400" id="realtimeClock"></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateRelativeTimes() {
            document.querySelectorAll('.relative-time-updated').forEach(element => {
                const updatedDate = new Date(element.dataset.updated);
                const now = new Date();
                const diffInSeconds = Math.floor((now - updatedDate) / 1000);
                const diffInMinutes = Math.floor(diffInSeconds / 60);
                const diffInHours = Math.floor(diffInMinutes / 60);
                const diffInDays = Math.floor(diffInHours / 24);
                const diffInMonths = Math.floor(diffInDays / 30);
                const diffInYears = Math.floor(diffInDays / 365);

                let relativeText = '';

                if (diffInSeconds < 60) {
                    relativeText = 'baru saja';
                } else if (diffInMinutes < 60) {
                    relativeText = diffInMinutes + ' menit yang lalu';
                } else if (diffInHours < 24) {
                    relativeText = diffInHours + ' jam yang lalu';
                } else if (diffInDays < 30) {
                    relativeText = diffInDays + ' hari yang lalu';
                } else if (diffInMonths < 12) {
                    relativeText = diffInMonths + ' bulan yang lalu';
                } else {
                    relativeText = diffInYears + ' tahun yang lalu';
                }

                element.textContent = relativeText;
            });
        }

        setInterval(updateRelativeTimes, 60000);
        updateRelativeTimes();

        function updateClock() {
            const now = new Date();
            const formatted = now.toLocaleString('id-ID', {
                day: '2-digit',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            }).replace(/\./g, ':');
            const el = document.getElementById('realtimeClock');
            if (el) el.textContent = formatted + ' WIB';
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 flex items-center gap-2">
                @if (Auth::user()->role === 'pengguna')
                    Riwayat Pengajuan
                @else
                    @php
                        $judul = match (Auth::user()->role) {
                            'super_admin' => 'Laporan Rekap Seluruh Permohonan',
                            'kepala_admin' => 'Laporan Aktivitas Validasi & Persetujuan',
                            'spsi' => 'Laporan Penggunaan Armada Kendaraan',
                            'keuangan' => 'Laporan Rekapitulasi Anggaran & RAB',
                            default => 'Laporan',
                        };
                    @endphp
                    {{ $judul }}
                @endif
            </h2>

            {{-- TOMBOL EXPORT (Hanya tampil untuk admin/staf) --}}
            @if (Auth::user()->role !== 'pengguna')
                <div class="flex gap-2 w-full sm:w-auto">
                    <a href="{{ route('laporan.export.excel', request()->query()) }}"
                        class="flex-1 sm:flex-none justify-center bg-white border border-green-600 text-green-700 hover:bg-green-50 font-bold py-2 px-4 rounded-lg text-sm shadow-sm flex items-center gap-2 transition">
                        <i class="bi bi-file-earmark-spreadsheet text-lg"></i> Excel
                    </a>
                    <a href="{{ route('laporan.export.pdf', request()->query()) }}" target="_blank"
                        class="flex-1 sm:flex-none justify-center bg-white border border-red-600 text-red-700 hover:bg-red-50 font-bold py-2 px-4 rounded-lg text-sm shadow-sm flex items-center gap-2 transition">
                        <i class="bi bi-file-earmark-pdf text-lg"></i> PDF
                    </a>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="w-full px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- ========================================================== --}}
            {{-- AREA KHUSUS ADMIN / STAF (Statistik & Filter)              --}}
            {{-- ========================================================== --}}
            @if (Auth::user()->role !== 'pengguna')

                {{-- STATS CARDS --}}
                @if (isset($stats) && count($stats) > 0)
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach ($stats as $label => $nilai)
                            @php
                                $labelMap = [
                                    'total' => ['Total Pengajuan', 'text-gray-800'],
                                    'disetujui' => ['Disetujui/Selesai', 'text-gray-800'],
                                    'selesai' => ['Telah Selesai', 'text-gray-800'],
                                    'ditolak' => ['Ditolak / Batal', 'text-gray-800'],
                                    'proses' => ['Sedang Diproses', 'text-gray-800'],
                                    'total_rab' => ['Total RAB', 'text-blue-700'],
                                    'total_realisasi' => ['Total Realisasi', 'text-green-700'],
                                    'total_sisa' => ['Sisa Anggaran', 'text-orange-700'],
                                    'jumlah_transaksi' => ['Jumlah Transaksi', 'text-gray-800'],
                                    'total_kendaraan' => ['Total Kendaraan', 'text-gray-800'],
                                    'kendaraan_tersedia' => ['Kendaraan Tersedia', 'text-gray-800'],
                                    'kendaraan_dipinjam' => ['Kendaraan Keluar', 'text-gray-800'],
                                    'total_pengemudi' => ['Total Pengemudi', 'text-gray-800'],
                                    'pengemudi_bertugas' => ['Supir Bertugas', 'text-gray-800'],
                                    'total_perjalanan' => ['Total Perjalanan', 'text-gray-800'],
                                ];
                                $lbl = $labelMap[$label][0] ?? $label;
                                $textColor = $labelMap[$label][1] ?? 'text-gray-800';
                                $isRupiah =
                                    str_contains($label, 'rab') ||
                                    str_contains($label, 'realisasi') ||
                                    str_contains($label, 'sisa');
                            @endphp
                            <div
                                class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm hover:shadow transition flex flex-col justify-center">
                                <p class="text-2xl sm:text-3xl font-black {{ $textColor }}">
                                    {{ $isRupiah ? 'Rp ' . number_format($nilai, 0, ',', '.') : $nilai }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1 font-bold uppercase tracking-wide">
                                    {{ $lbl }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- FILTER PENCARIAN --}}
                <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-sm">
                    <div class="flex items-center gap-2 mb-4 border-b border-gray-100 pb-3">
                        <i class="bi bi-funnel text-blue-600 text-lg"></i>
                        <h3 class="font-bold text-gray-800">Filter Data Laporan</h3>
                    </div>
                    <form method="GET" action="{{ route('laporan.index') }}">
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 items-end">
                            <div>
                                <label class="block text-xs font-bold text-gray-600 mb-1">DARI TANGGAL</label>
                                <input type="date" name="dari" value="{{ $request->dari ?? '' }}"
                                    class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-600 mb-1">SAMPAI TANGGAL</label>
                                <input type="date" name="sampai" value="{{ $request->sampai ?? '' }}"
                                    class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-600 mb-1">STATUS</label>
                                <select name="status"
                                    class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                                    <option value="">Semua Status</option>
                                    @foreach (['Menunggu Validasi Admin', 'Menunggu Proses SPSI', 'Menunggu Proses Keuangan', 'Menunggu Finalisasi', 'Disetujui', 'Menunggu Pengembalian Dana', 'Menunggu Verifikasi Pengembalian', 'Selesai', 'Ditolak', 'Dibatalkan'] as $s)
                                        <option value="{{ $s }}"
                                            {{ ($request->status ?? '') === $s ? 'selected' : '' }}>
                                            {{ $s }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if (Auth::user()->role === 'super_admin')
                                <div>
                                    <label class="block text-xs font-bold text-gray-600 mb-1">KATEGORI</label>
                                    <select name="kategori"
                                        class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                                        <option value="">Semua Kategori</option>
                                        <option value="Dinas SITH"
                                            {{ ($request->kategori ?? '') === 'Dinas SITH' ? 'selected' : '' }}>Dinas
                                            SITH</option>
                                        <option value="Non SITH"
                                            {{ ($request->kategori ?? '') === 'Non SITH' ? 'selected' : '' }}>Non SITH
                                        </option>
                                    </select>
                                </div>
                            @endif
                            <div
                                class="flex gap-2 w-full md:col-span-{{ Auth::user()->role === 'super_admin' ? '4' : '1' }} md:justify-end mt-2">
                                <a href="{{ route('laporan.index') }}"
                                    class="bg-white border border-gray-300 text-gray-600 hover:bg-gray-50 font-bold py-2.5 px-5 rounded-lg text-sm transition text-center w-full sm:w-auto">Reset</a>
                                <button type="submit"
                                    class="bg-blue-600 text-white font-bold py-2.5 px-6 rounded-lg text-sm hover:bg-blue-700 shadow-sm transition flex items-center justify-center gap-2 w-full sm:w-auto">
                                    <i class="bi bi-search"></i> Terapkan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            @endif

            {{-- ========================================================== --}}
            {{-- AREA TABEL / DAFTAR DATA                                   --}}
            {{-- ========================================================== --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">

                {{-- HEADER TABEL --}}
                @if (Auth::user()->role !== 'pengguna')
                    <div class="p-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                        <p class="text-sm text-gray-600">Ditemukan <strong
                                class="text-blue-600 text-lg">{{ $data->total() }}</strong> data laporan.</p>
                    </div>
                @else
                    <div
                        class="p-5 border-b border-gray-100 bg-white flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <h3 class="font-bold text-gray-800">
                            <i class="bi bi-list-ul text-blue-600 mr-2"></i> Daftar Riwayat Pengajuan
                        </h3>

                        <form action="{{ route('laporan.index') }}" method="GET" class="relative w-full sm:w-80">
                            <i
                                class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>
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
                @endif

                <div class="overflow-x-auto">

                    {{-- ================================================== --}}
                    {{-- TAMPILAN KHUSUS PENGGUNA (Dengan Pencarian)        --}}
                    {{-- ================================================== --}}
                    @if (Auth::user()->role === 'pengguna')

                        {{-- MOBILE VIEW PENGGUNA --}}
                        <div class="block md:hidden space-y-3 p-4 bg-gray-50">
                            @forelse($data as $p)
                                @php
                                    $badgeClass = match ($p->status_permohonan) {
                                        'Disetujui' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'Selesai' => 'bg-green-50 text-green-700 border-green-200',
                                        'Ditolak', 'Dibatalkan' => 'bg-red-50 text-red-700 border-red-200',
                                        'Menunggu Pengembalian Dana'
                                            => 'bg-orange-50 text-orange-700 border-orange-200',
                                        'Menunggu Verifikasi Pengembalian'
                                            => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                        default => 'bg-gray-50 text-gray-700 border-gray-200',
                                    };
                                @endphp
                                <div class="bg-white border border-gray-200 p-4 rounded-xl shadow-sm">
                                    <div class="flex justify-between items-start mb-3">
                                        <span class="font-bold text-gray-800 text-sm leading-tight pr-2">
                                            <i class="bi bi-geo-alt text-gray-400 mr-1"></i>
                                            {{ $p->tujuan }}
                                        </span>
                                        <span
                                            class="text-[10px] font-bold px-2 py-1 rounded border whitespace-nowrap text-center {{ $badgeClass }}">
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
                                <div
                                    class="text-center py-16 text-gray-500 text-sm border border-dashed border-gray-200 bg-white rounded-xl">
                                    <i class="bi bi-journal-x text-5xl block mb-3 text-gray-300"></i>
                                    <p class="font-medium text-gray-500">Belum ada riwayat pengajuan.</p>
                                    <a href="{{ route('permohonan.create') }}"
                                        class="mt-3 inline-block text-sm text-blue-600 hover:underline font-bold">Buat
                                        pengajuan baru →</a>
                                </div>
                            @endforelse
                        </div>

                        {{-- DESKTOP VIEW PENGGUNA --}}
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
                                                'Menunggu Pengembalian Dana'
                                                    => 'bg-orange-50 text-orange-700 border-orange-200',
                                                'Menunggu Verifikasi Pengembalian'
                                                    => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                                default => 'bg-gray-50 text-gray-700 border-gray-200',
                                            };
                                        @endphp
                                        <tr class="border-b border-gray-50 hover:bg-blue-50/20 transition">
                                            <td class="px-6 py-4 text-gray-400">
                                                {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}
                                            </td>
                                            <td class="px-6 py-4">
                                                @if ($p->kode_permohonan)
                                                    <span
                                                        class="font-black text-blue-700 tracking-wider text-[11px] bg-blue-50 border border-blue-200 px-2 py-0.5 rounded-md whitespace-nowrap">{{ $p->kode_permohonan }}</span>
                                                @else
                                                    <span class="text-gray-300">—</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                <strong class="text-gray-800 text-base block mb-1"><i
                                                        class="bi bi-geo-alt text-gray-400 mr-1"></i>{{ $p->tujuan }}</strong>
                                                <span
                                                    class="text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded border border-gray-200">{{ $p->kategori_kegiatan ?? 'Tujuan Umum' }}</span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-2"><i
                                                        class="bi bi-calendar-event text-gray-400"></i>
                                                    {{ \Carbon\Carbon::parse($p->waktu_berangkat)->format('d M Y') }}
                                                </div>
                                                <div class="text-xs text-gray-500 mt-1"><i
                                                        class="bi bi-clock text-gray-400"></i>
                                                    {{ \Carbon\Carbon::parse($p->waktu_berangkat)->format('H:i') }} WIB
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                @if ($p->kendaraan)
                                                    <span
                                                        class="text-sm font-medium">{{ $p->kendaraan->nama_kendaraan }}</span>
                                                @elseif($p->kendaraanVendor)
                                                    <span
                                                        class="text-sm font-medium">{{ $p->kendaraanVendor->nama_kendaraan }}</span>
                                                    <span
                                                        class="text-orange-600 bg-orange-50 px-1 rounded text-[10px] font-bold ml-1">Vendor</span>
                                                @else
                                                    <span class="text-gray-400 italic">-</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                <span
                                                    class="px-2.5 py-1 rounded text-xs font-bold border whitespace-nowrap {{ $badgeClass }}">{{ $p->status_permohonan }}</span>
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
                                            <td colspan="7"
                                                class="text-center py-16 text-gray-400 border border-dashed border-gray-100">
                                                <i class="bi bi-journal-x text-5xl block mb-3 text-gray-300"></i>
                                                <p class="font-medium text-gray-500">Belum ada riwayat.</p>
                                                <a href="{{ route('permohonan.create') }}"
                                                    class="mt-2 inline-block text-sm text-blue-600 hover:underline font-bold">Buat
                                                    pengajuan baru →</a>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- PAGINATION PENGGUNA --}}
                        @if ($data->hasPages())
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
                                        <a href="{{ $data->previousPageUrl() }}&search={{ request('search') }}"
                                            class="px-3 py-2 text-xs font-bold text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                            <i class="bi bi-chevron-left"></i>
                                        </a>
                                    @endif

                                    {{-- Nomor Halaman dengan Elipsis --}}
                                    <div class="hidden md:flex items-center gap-1">
                                        @php
                                            $current = $data->currentPage();
                                            $last = $data->lastPage();
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

                                        {{-- Halaman 1 --}}
                                        @if ($start > 1)
                                            <a href="{{ $data->url(1) }}&search={{ request('search') }}"
                                                class="w-8 h-8 flex items-center justify-center rounded-lg text-xs font-bold text-gray-500 hover:bg-gray-100">
                                                1
                                            </a>
                                            @if ($start > 2)
                                                <span
                                                    class="w-8 h-8 flex items-center justify-center text-gray-400">...</span>
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
                                                <a href="{{ $data->url($page) }}&search={{ request('search') }}"
                                                    class="w-8 h-8 flex items-center justify-center rounded-lg text-xs font-bold text-gray-500 hover:bg-gray-100">
                                                    {{ $page }}
                                                </a>
                                            @endif
                                        @endfor

                                        {{-- Halaman terakhir --}}
                                        @if ($end < $last)
                                            @if ($end < $last - 1)
                                                <span
                                                    class="w-8 h-8 flex items-center justify-center text-gray-400">...</span>
                                            @endif
                                            <a href="{{ $data->url($last) }}&search={{ request('search') }}"
                                                class="w-8 h-8 flex items-center justify-center rounded-lg text-xs font-bold text-gray-500 hover:bg-gray-100">
                                                {{ $last }}
                                            </a>
                                        @endif
                                    </div>

                                    {{-- Tombol Next --}}
                                    @if ($data->hasMorePages())
                                        <a href="{{ $data->nextPageUrl() }}&search={{ request('search') }}"
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
                                    {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                            </div>
                        @endif

                        {{-- ================================================== --}}
                        {{-- TAMPILAN KHUSUS ADMIN / KEUANGAN / SPSI             --}}
                        {{-- ================================================== --}}
                    @else
                        <table class="w-full text-sm text-left text-gray-600">
                            <thead class="bg-gray-50 border-b border-gray-100 text-xs uppercase text-gray-500">
                                @if (Auth::user()->role === 'keuangan')
                                    <tr>
                                        <th class="px-4 py-4">No</th>
                                        <th class="px-4 py-4">Pemohon</th>
                                        <th class="px-4 py-4">Tujuan</th>
                                        <th class="px-4 py-4">Kategori</th>
                                        <th class="px-4 py-4 text-right">RAB Disetujui</th>
                                        <th class="px-4 py-4 text-right">Biaya Aktual</th>
                                        <th class="px-4 py-4 text-right">Selisih</th>
                                        <th class="px-4 py-4">Status</th>
                                        <th class="px-4 py-4 text-center">Aksi</th>
                                    </tr>
                                @elseif(Auth::user()->role === 'spsi')
                                    <tr>
                                        <th class="px-4 py-4">No</th>
                                        <th class="px-4 py-4">Pemohon & Tujuan</th>
                                        <th class="px-4 py-4">Kendaraan</th>
                                        <th class="px-4 py-4">Pengemudi</th>
                                        <th class="px-4 py-4">Berangkat</th>
                                        <th class="px-4 py-4">Kembali</th>
                                        <th class="px-4 py-4">Status</th>
                                        <th class="px-4 py-4 text-center">Aksi</th>
                                    </tr>
                                @else
                                    <tr>
                                        <th class="px-4 py-4">No</th>
                                        <th class="px-4 py-4">Pemohon & Kategori</th>
                                        <th class="px-4 py-4">Tujuan</th>
                                        <th class="px-4 py-4">Berangkat</th>
                                        <th class="px-4 py-4">Armada</th>
                                        <th class="px-4 py-4 text-right">RAB (Rp)</th>
                                        <th class="px-4 py-4">Status</th>
                                        <th class="px-4 py-4 text-center">Aksi</th>
                                    </tr>
                                @endif
                            </thead>
                            <tbody>
                                @forelse($data as $i => $p)
                                    @php
                                        $sc = $p->status_permohonan->badgeClass() ?? '';
                                    @endphp

                                    @if (Auth::user()->role === 'keuangan')
                                        @php $selisih = ($p->rab_disetujui ?? 0) - ($p->biaya_aktual ?? 0); @endphp
                                        <tr class="border-b border-gray-50 hover:bg-blue-50/30 transition">
                                            <td class="px-4 py-3 text-gray-400">
                                                {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}
                                            </td>
                                            <td class="px-4 py-3 font-bold text-gray-800">{{ $p->nama_pic }}</td>
                                            <td class="px-4 py-3">{{ $p->tujuan }}</td>
                                            <td class="px-4 py-3"><span
                                                    class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs">{{ $p->kategori_kegiatan ?? '-' }}</span>
                                            </td>
                                            <td class="px-4 py-3 text-right font-mono text-gray-800">
                                                {{ $p->rab_disetujui ? 'Rp ' . number_format($p->rab_disetujui, 0, ',', '.') : '-' }}
                                            </td>
                                            <td class="px-4 py-3 text-right font-mono text-gray-800">
                                                {{ $p->biaya_aktual ? 'Rp ' . number_format($p->biaya_aktual, 0, ',', '.') : '-' }}
                                            </td>
                                            <td
                                                class="px-4 py-3 text-right font-mono font-bold {{ $selisih > 0 ? 'text-orange-600' : ($selisih < 0 ? 'text-red-600' : 'text-gray-500') }}">
                                                {{ $p->rab_disetujui ? 'Rp ' . number_format($selisih, 0, ',', '.') : '-' }}
                                            </td>
                                            <td class="px-4 py-3"><span
                                                    class="px-2 py-0.5 rounded text-[10px] font-bold border {{ $sc }}">{{ $p->status_permohonan->value }}</span>
                                            </td>
                                            <td class="px-4 py-3 text-center"><a
                                                    href="{{ route('permohonan.show', $p->id) }}"
                                                    class="text-blue-600 hover:text-blue-800 hover:underline font-bold text-xs"><i
                                                        class="bi bi-search"></i> Detail</a></td>
                                        </tr>
                                    @elseif(Auth::user()->role === 'spsi')
                                        <tr class="border-b border-gray-50 hover:bg-blue-50/30 transition">
                                            <td class="px-4 py-3 text-gray-400">
                                                {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}
                                            </td>
                                            <td class="px-4 py-3">
                                                <strong class="text-gray-800 block">{{ $p->nama_pic }}</strong>
                                                <span class="text-xs text-gray-500">{{ $p->tujuan }}</span>
                                            </td>
                                            <td class="px-4 py-3 font-medium text-gray-800">
                                                {{ $p->kendaraan?->nama_kendaraan ?? ($p->kendaraanVendor?->nama_kendaraan ?? '-') }}
                                            </td>
                                            <td class="px-4 py-3 text-gray-600">
                                                {{ $p->pengemudi?->nama_pengemudi ?? 'Tanpa Supir' }}</td>
                                            <td class="px-4 py-3 text-xs">
                                                {{ \Carbon\Carbon::parse($p->waktu_berangkat)->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="px-4 py-3 text-xs">
                                                {{ \Carbon\Carbon::parse($p->waktu_kembali)->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="px-4 py-3"><span
                                                    class="px-2 py-0.5 rounded text-[10px] font-bold border {{ $sc }}">{{ $p->status_permohonan->value }}</span>
                                            </td>
                                            <td class="px-4 py-3 text-center"><a
                                                    href="{{ route('permohonan.show', $p->id) }}"
                                                    class="text-blue-600 hover:text-blue-800 hover:underline font-bold text-xs"><i
                                                        class="bi bi-search"></i> Detail</a></td>
                                        </tr>
                                    @else
                                        <tr class="border-b border-gray-50 hover:bg-blue-50/30 transition">
                                            <td class="px-4 py-3 text-gray-400">
                                                {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}
                                            </td>
                                            <td class="px-4 py-3">
                                                <strong class="text-gray-800 block">{{ $p->nama_pic }}</strong>
                                                <span
                                                    class="bg-gray-100 text-gray-600 px-1.5 py-0.5 rounded text-[10px] mt-1 inline-block">{{ $p->kategori_kegiatan ?? '-' }}</span>
                                            </td>
                                            <td class="px-4 py-3">{{ $p->tujuan }}</td>
                                            <td class="px-4 py-3 text-xs">
                                                {{ \Carbon\Carbon::parse($p->waktu_berangkat)->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="px-4 py-3 text-xs text-gray-800 font-medium">
                                                {{ $p->kendaraan?->nama_kendaraan ?? ($p->kendaraanVendor?->nama_kendaraan ?? '-') }}
                                            </td>
                                            <td class="px-4 py-3 text-right font-mono">
                                                {{ $p->rab_disetujui ? 'Rp ' . number_format($p->rab_disetujui, 0, ',', '.') : '-' }}
                                            </td>
                                            <td class="px-4 py-3"><span
                                                    class="px-2 py-0.5 rounded text-[10px] font-bold border {{ $sc }}">{{ $p->status_permohonan->value }}</span>
                                            </td>
                                            <td class="px-4 py-3 text-center"><a
                                                    href="{{ route('permohonan.show', $p->id) }}"
                                                    class="text-blue-600 hover:text-blue-800 hover:underline font-bold text-xs"><i
                                                        class="bi bi-search"></i> Detail</a></td>
                                        </tr>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-16 text-gray-400">
                                            <i class="bi bi-clipboard-x text-5xl block mb-3 text-gray-300"></i>
                                            <p class="font-medium text-gray-500">Tidak ada data laporan untuk
                                                ditampilkan sesuai filter.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        {{-- PAGINATION ADMIN --}}
                        @if ($data->hasPages())
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
                                        <a href="{{ $data->previousPageUrl() }}"
                                            class="px-3 py-2 text-xs font-bold text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                            <i class="bi bi-chevron-left"></i>
                                        </a>
                                    @endif

                                    <div class="hidden md:flex items-center gap-1">
                                        @php
                                            $current = $data->currentPage();
                                            $last = $data->lastPage();
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
                                            <a href="{{ $data->url(1) }}"
                                                class="w-8 h-8 flex items-center justify-center rounded-lg text-xs font-bold text-gray-500 hover:bg-gray-100">
                                                1
                                            </a>
                                            @if ($start > 2)
                                                <span
                                                    class="w-8 h-8 flex items-center justify-center text-gray-400">...</span>
                                            @endif
                                        @endif

                                        @for ($page = $start; $page <= $end; $page++)
                                            @if ($page == $current)
                                                <span
                                                    class="w-8 h-8 flex items-center justify-center rounded-lg text-xs font-bold bg-blue-600 text-white shadow-sm">
                                                    {{ $page }}
                                                </span>
                                            @else
                                                <a href="{{ $data->url($page) }}"
                                                    class="w-8 h-8 flex items-center justify-center rounded-lg text-xs font-bold text-gray-500 hover:bg-gray-100">
                                                    {{ $page }}
                                                </a>
                                            @endif
                                        @endfor

                                        @if ($end < $last)
                                            @if ($end < $last - 1)
                                                <span
                                                    class="w-8 h-8 flex items-center justify-center text-gray-400">...</span>
                                            @endif
                                            <a href="{{ $data->url($last) }}"
                                                class="w-8 h-8 flex items-center justify-center rounded-lg text-xs font-bold text-gray-500 hover:bg-gray-100">
                                                {{ $last }}
                                            </a>
                                        @endif
                                    </div>

                                    @if ($data->hasMorePages())
                                        <a href="{{ $data->nextPageUrl() }}"
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
                                    {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            @if (Auth::user()->role !== 'pengguna')
                <p class="text-xs text-gray-400 text-center font-mono mt-4">Data diakses pada:
                    {{ now()->translatedFormat('d F Y, H:i') }} WIB</p>
            @endif
        </div>
    </div>
</x-app-layout>

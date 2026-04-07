<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 flex items-center gap-2">
                @if (Auth::user()->role === 'pengguna')
                    Riwayat Pengajuan Saya
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

            {{-- TOMBOL EXPORT (Hanya tampil untuk admin/staf, disembunyikan untuk pengguna) --}}
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
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- AREA KHUSUS ADMIN / STAF (Tampil Statistik & Filter) --}}
            @if (Auth::user()->role !== 'pengguna')

                {{-- STATS CARDS (Desain Clean Enterprise: Tanpa Border Warna Samping) --}}
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

                {{-- FILTER PENCARIAN AUDIT --}}
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
                                    {{-- PERBAIKAN: Menambahkan 'Dibatalkan' ke dalam pilihan filter --}}
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
                                            {{ ($request->kategori ?? '') === 'Non SITH' ? 'selected' : '' }}>Non
                                            SITH</option>
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

            {{-- AREA TABEL / DAFTAR DATA --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                @if (Auth::user()->role !== 'pengguna')
                    <div class="p-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                        <p class="text-sm text-gray-600">Ditemukan <strong
                                class="text-blue-600 text-lg">{{ $data->count() }}</strong> data laporan.</p>
                    </div>
                @else
                    <div class="p-5 border-b border-gray-100 bg-white flex justify-between items-center">
                        <h3 class="font-bold text-gray-800"><i class="bi bi-list-ul text-blue-600 mr-2"></i> Daftar
                            Seluruh Pengajuan Anda</h3>
                    </div>
                @endif

                <div class="overflow-x-auto">
                    {{-- ================================================================= --}}
                    {{-- TAMPILAN KHUSUS PENGGUNA BIASA (Sederhana & Tanpa Filter)         --}}
                    {{-- ================================================================= --}}
                    @if (Auth::user()->role === 'pengguna')

                        {{-- Mobile View Pengguna --}}
                        <div class="block md:hidden space-y-3 p-4 bg-gray-50">
                            @forelse($data as $p)
                                @php
                                    // PERBAIKAN: Menambahkan Dibatalkan ke dalam Badge (Digabung dengan Ditolak)
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
                                        <span class="font-bold text-gray-800 text-sm leading-tight pr-2"><i
                                                class="bi bi-geo-alt text-gray-400 mr-1"></i>
                                            {{ $p->tujuan }}</span>
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
                                        @if (in_array($p->status_permohonan, [
                                                'Disetujui',
                                                'Menunggu Pengembalian Dana',
                                                'Menunggu Verifikasi Pengembalian',
                                                'Selesai',
                                            ]))
                                            <a href="{{ route('permohonan.cetak', $p->id) }}" target="_blank"
                                                class="w-full text-center bg-gray-800 text-white font-bold py-2 rounded-lg text-sm hover:bg-gray-900 transition shadow-sm flex justify-center items-center gap-2">
                                                <i class="bi bi-printer"></i> Cetak SPJ
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div
                                    class="text-center py-10 text-gray-500 text-sm border border-dashed border-gray-200 bg-white rounded-xl">
                                    <i class="bi bi-journal-x text-4xl block mb-3 text-gray-300"></i>
                                    Belum ada riwayat pengajuan.
                                </div>
                            @endforelse
                        </div>

                        {{-- Desktop View Pengguna --}}
                        <div class="hidden md:block">
                            <table class="w-full text-sm text-left text-gray-600">
                                <thead class="bg-gray-50 border-b border-gray-100 text-xs uppercase text-gray-500">
                                    <tr>
                                        <th class="px-6 py-4">Informasi Kegiatan</th>
                                        <th class="px-6 py-4">Jadwal Keberangkatan</th>
                                        <th class="px-6 py-4">Status Terkini</th>
                                        <th class="px-6 py-4 text-center">Aksi Dokumen</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($data as $p)
                                        @php
                                            // PERBAIKAN: Menambahkan Dibatalkan ke dalam Badge (Digabung dengan Ditolak)
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
                                                <span
                                                    class="px-2.5 py-1 rounded text-xs font-bold border whitespace-nowrap {{ $badgeClass }}">{{ $p->status_permohonan }}</span>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <div class="flex justify-center items-center gap-2">
                                                    <a href="{{ route('permohonan.show', $p->id) }}"
                                                        class="inline-flex items-center gap-1 text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 px-3 py-1.5 rounded-lg text-xs font-bold transition">
                                                        <i class="bi bi-search"></i> Detail
                                                    </a>
                                                    @if (in_array($p->status_permohonan, [
                                                            'Disetujui',
                                                            'Menunggu Pengembalian Dana',
                                                            'Menunggu Verifikasi Pengembalian',
                                                            'Selesai',
                                                        ]))
                                                        <a href="{{ route('permohonan.cetak', $p->id) }}"
                                                            target="_blank"
                                                            class="inline-flex items-center gap-1 text-white bg-gray-800 hover:bg-gray-900 px-3 py-1.5 rounded-lg text-xs font-bold transition shadow-sm">
                                                            <i class="bi bi-printer"></i> Cetak SPJ
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4"
                                                class="text-center py-16 text-gray-400 border border-dashed border-gray-100">
                                                <i class="bi bi-journal-x text-5xl block mb-3 text-gray-300"></i>
                                                <p class="font-medium text-gray-500">Belum ada riwayat pengajuan.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- ================================================================= --}}
                        {{-- TAMPILAN KHUSUS ADMIN / KEUANGAN / SPSI (Tabel Audit Lengkap)     --}}
                        {{-- ================================================================= --}}
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
                                    {{-- Super Admin & Kepala Admin --}}
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
                                        // PERBAIKAN: Menambahkan Dibatalkan ke dalam Badge (Digabung dengan Ditolak)
                                        $sc = match ($p->status_permohonan) {
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

                                    @if (Auth::user()->role === 'keuangan')
                                        @php $selisih = ($p->rab_disetujui ?? 0) - ($p->biaya_aktual ?? 0); @endphp
                                        <tr class="border-b border-gray-50 hover:bg-blue-50/30 transition">
                                            <td class="px-4 py-3 text-gray-400">{{ $i + 1 }}</td>
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
                                                    class="px-2 py-0.5 rounded text-[10px] font-bold border {{ $sc }}">{{ $p->status_permohonan }}</span>
                                            </td>
                                            <td class="px-4 py-3 text-center"><a
                                                    href="{{ route('permohonan.show', $p->id) }}"
                                                    class="text-blue-600 hover:text-blue-800 hover:underline font-bold text-xs"><i
                                                        class="bi bi-search"></i> Detail</a></td>
                                        </tr>
                                    @elseif(Auth::user()->role === 'spsi')
                                        <tr class="border-b border-gray-50 hover:bg-blue-50/30 transition">
                                            <td class="px-4 py-3 text-gray-400">{{ $i + 1 }}</td>
                                            <td class="px-4 py-3">
                                                <strong class="text-gray-800 block">{{ $p->nama_pic }}</strong>
                                                <span class="text-xs text-gray-500">{{ $p->tujuan }}</span>
                                            </td>
                                            <td class="px-4 py-3 font-medium text-gray-800">
                                                {{ $p->kendaraan?->nama_kendaraan ?? ($p->kendaraan_vendor ?? '-') }}
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
                                                    class="px-2 py-0.5 rounded text-[10px] font-bold border {{ $sc }}">{{ $p->status_permohonan }}</span>
                                            </td>
                                            <td class="px-4 py-3 text-center"><a
                                                    href="{{ route('permohonan.show', $p->id) }}"
                                                    class="text-blue-600 hover:text-blue-800 hover:underline font-bold text-xs"><i
                                                        class="bi bi-search"></i> Detail</a></td>
                                        </tr>
                                    @else
                                        {{-- Super Admin & Kepala Admin --}}
                                        <tr class="border-b border-gray-50 hover:bg-blue-50/30 transition">
                                            <td class="px-4 py-3 text-gray-400">{{ $i + 1 }}</td>
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
                                                {{ $p->kendaraan?->nama_kendaraan ?? ($p->kendaraan_vendor ?? '-') }}
                                            </td>
                                            <td class="px-4 py-3 text-right font-mono">
                                                {{ $p->rab_disetujui ? 'Rp ' . number_format($p->rab_disetujui, 0, ',', '.') : '-' }}
                                            </td>
                                            <td class="px-4 py-3"><span
                                                    class="px-2 py-0.5 rounded text-[10px] font-bold border {{ $sc }}">{{ $p->status_permohonan }}</span>
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

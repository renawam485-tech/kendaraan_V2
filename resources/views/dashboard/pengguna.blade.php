<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard Pengguna</h2>
            <a href="{{ route('permohonan.create') }}"
                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold py-2 px-4 rounded-lg shadow-sm transition-all duration-150 active:scale-95">
                <i class="bi bi-plus-lg"></i> Buat Pengajuan
            </a>
        </div>
    </x-slot>

    <div class="py-6 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">

            {{-- STATS STRIP --}}
            @php
                $total = $permohonans->count();
                $proses = $permohonans->whereNotIn('status_permohonan', ['Disetujui', 'Selesai', 'Ditolak'])->count();
                $selesai = $permohonans->where('status_permohonan', 'Selesai')->count();
                $ditolak = $permohonans->where('status_permohonan', 'Ditolak')->count();
            @endphp
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                @foreach ([['Total Pengajuan', $total, 'bi-journals', 'text-blue-600', 'bg-blue-50'], ['Dalam Proses', $proses, 'bi-hourglass-split', 'text-amber-600', 'bg-amber-50'], ['Selesai', $selesai, 'bi-patch-check-fill', 'text-emerald-600', 'bg-emerald-50'], ['Ditolak', $ditolak, 'bi-x-octagon', 'text-red-500', 'bg-red-50']] as [$lbl, $val, $icon, $tc, $bg])
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
                {{-- TOOLBAR --}}
                <div
                    class="px-5 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2 text-sm">
                        <i class="bi bi-clock-history text-blue-600"></i>
                        Riwayat Pengajuan Saya
                        <span class="ml-1 text-xs font-semibold text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full"
                            id="countBadge">{{ $total }} data</span>
                    </h3>
                    <div class="relative w-full sm:w-72">
                        <i
                            class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>
                        <input type="text" id="searchInput" placeholder="Cari tujuan, kode, status..."
                            class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition bg-gray-50 focus:bg-white">
                    </div>
                </div>

                {{-- MOBILE CARDS --}}
                <div class="block md:hidden divide-y divide-gray-100" id="mobileList">
                    @forelse($permohonans as $i => $p)
                        @php
                            $sc = match ($p->status_permohonan) {
                                'Disetujui' => 'bg-blue-50 text-blue-700 border-blue-200',
                                'Selesai' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'Ditolak' => 'bg-red-50 text-red-700 border-red-200',
                                'Menunggu Pengembalian Dana' => 'bg-orange-50 text-orange-700 border-orange-200',
                                'Menunggu Verifikasi Pengembalian' => 'bg-amber-50 text-amber-700 border-amber-200',
                                'Menunggu Mulai Perjalanan' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                'Perjalanan Berlangsung' => 'bg-teal-50 text-teal-700 border-teal-200',
                                'Menunggu Konfirmasi Kembali' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
'Menunggu Penyelesaian'       => 'bg-purple-50 text-purple-700 border-purple-200',
                                default => 'bg-slate-50 text-slate-600 border-slate-200',
                            };
                        @endphp
                        <div class="p-4 hover:bg-slate-50 transition-colors searchable-row"
                            data-search="{{ strtolower($p->tujuan . ' ' . ($p->kode_permohonan ?? '') . ' ' . $p->status_permohonan) }}">
                            <div class="flex items-start justify-between gap-2">
                                <div class="flex items-start gap-2 min-w-0">
                                    <span
                                        class="flex-shrink-0 w-6 h-6 bg-slate-100 text-slate-500 text-xs font-bold rounded-md flex items-center justify-center mt-0.5">{{ $i + 1 }}</span>
                                    <div class="min-w-0">
                                        @if ($p->kode_permohonan)
                                            <span
                                                class="text-[10px] font-black text-blue-700 tracking-widest bg-blue-50 border border-blue-200 px-1.5 py-0.5 rounded block w-fit mb-1">{{ $p->kode_permohonan }}</span>
                                        @endif
                                        <p class="font-semibold text-sm text-gray-800 truncate">{{ $p->tujuan }}</p>
                                        <p class="text-xs text-gray-500 mt-0.5"><i
                                                class="bi bi-calendar2-event mr-1"></i>{{ \Carbon\Carbon::parse($p->waktu_berangkat)->format('d M Y, H:i') }}
                                        </p>
                                    </div>
                                </div>
                                <span
                                    class="text-[10px] font-bold px-2 py-1 rounded-md border whitespace-nowrap flex-shrink-0 {{ $sc }}">{{ $p->status_permohonan }}</span>
                            </div>
                            <div class="flex gap-2 mt-3 pl-8">
                                <a href="{{ route('permohonan.show', $p->id) }}"
                                    class="flex-1 text-center py-1.5 text-xs font-bold text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-lg transition">
                                    <i class="bi bi-eye mr-1"></i>Detail
                                </a>
                                @if (in_array($p->status_permohonan, [
                                        'Disetujui',
                                        'Menunggu Pengembalian Dana',
                                        'Menunggu Verifikasi Pengembalian',
                                        'Selesai',
                                    ]))
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
                            <p class="font-medium text-gray-500">Belum ada riwayat pengajuan</p>
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
                                    Tujuan Kegiatan</th>
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
                            @forelse($permohonans as $i => $p)
                                @php
                                    $sc = match ($p->status_permohonan) {
                                        'Disetujui' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'Selesai' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'Ditolak' => 'bg-red-50 text-red-700 border-red-200',
                                        'Menunggu Pengembalian Dana'
                                            => 'bg-orange-50 text-orange-700 border-orange-200',
                                        'Menunggu Verifikasi Pengembalian'
                                            => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'Menunggu Mulai Perjalanan' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                        'Perjalanan Berlangsung' => 'bg-teal-50 text-teal-700 border-teal-200',
                                        'Menunggu Konfirmasi Kembali' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
'Menunggu Penyelesaian'       => 'bg-purple-50 text-purple-700 border-purple-200',
                                        default => 'bg-slate-50 text-slate-600 border-slate-200',
                                    };
                                @endphp
                                <tr class="hover:bg-blue-50/20 transition-colors searchable-row"
                                    data-search="{{ strtolower($p->tujuan . ' ' . ($p->kode_permohonan ?? '') . ' ' . $p->status_permohonan . ' ' . ($p->nama_pic ?? '')) }}">
                                    <td class="px-4 py-3.5 text-center text-xs text-gray-400 font-semibold">
                                        {{ $i + 1 }}</td>
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
                                            class="inline-block text-[11px] font-bold px-2.5 py-1 rounded-md border {{ $sc }}">{{ $p->status_permohonan }}</span>
                                    </td>
                                    <td class="px-4 py-3.5">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <a href="{{ route('permohonan.show', $p->id) }}"
                                                class="inline-flex items-center gap-1 text-xs font-bold text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200 px-2.5 py-1.5 rounded-lg transition">
                                                <i class="bi bi-eye"></i> Detail
                                            </a>
                                            @if (in_array($p->status_permohonan, [
                                                    'Disetujui',
                                                    'Menunggu Pengembalian Dana',
                                                    'Menunggu Verifikasi Pengembalian',
                                                    'Selesai',
                                                ]))
                                                <a href="{{ route('permohonan.cetak', $p->id) }}" target="_blank"
                                                    class="inline-flex items-center gap-1 text-xs font-bold text-gray-700 bg-white hover:bg-gray-50 border border-gray-300 px-2.5 py-1.5 rounded-lg transition">
                                                    <i class="bi bi-printer"></i> Cetak
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-16 text-center">
                                        <i class="bi bi-journal-x text-5xl block mb-3 text-gray-300"></i>
                                        <p class="font-medium text-gray-500">Belum ada riwayat pengajuan</p>
                                        <a href="{{ route('permohonan.create') }}"
                                            class="mt-2 inline-block text-sm text-blue-600 hover:underline font-bold">Buat
                                            pengajuan pertama →</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- TABLE FOOTER --}}
                <div class="px-5 py-3 border-t border-gray-100 flex items-center justify-between">
                    <p class="text-xs text-gray-400" id="tableInfo">Menampilkan {{ $total }} data</p>
                    <p class="text-xs text-gray-400">{{ now()->format('d M Y, H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('searchInput').addEventListener('input', function() {
            const q = this.value.toLowerCase().trim();
            let vis = 0;
            document.querySelectorAll('.searchable-row').forEach(r => {
                const show = !q || r.dataset.search.includes(q);
                r.style.display = show ? '' : 'none';
                if (show) vis++;
            });
            const total = {{ $total }};
            document.getElementById('tableInfo').textContent = q ?
                `Menampilkan ${vis} dari ${total} data` :
                `Menampilkan ${total} data`;
            document.getElementById('countBadge').textContent = q ? `${vis} data` : `${total} data`;
        });
    </script>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <i class="bi bi-truck-front text-blue-600 mr-2"></i>Tugas Kasubag SPSI
        </h2>
    </x-slot>

    <div class="py-6 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div
                    class="mb-5 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex items-center gap-3 shadow-sm">
                    <i class="bi bi-check-circle-fill text-emerald-500 text-lg flex-shrink-0"></i>
                    <p class="text-sm font-semibold">{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div
                    class="px-5 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2 text-sm">
                        <i class="bi bi-truck-front-fill text-blue-600"></i>
                        {{ $judul ?? 'Daftar Penugasan Kendaraan' }}
                        <span class="ml-1 text-xs font-semibold text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full"
                            id="countBadge">{{ $permohonans->count() }} data</span>
                    </h3>
                    <div class="relative w-full sm:w-72">
                        <i
                            class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>
                        <input type="text" id="searchInput" placeholder="Cari nama, tujuan, kategori..."
                            class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition bg-gray-50 focus:bg-white">
                    </div>
                </div>

                {{-- MOBILE --}}
                <div class="block md:hidden divide-y divide-gray-100">
                    @forelse($permohonans as $i => $p)
                        <x-status-badge :status="$p->status_permohonan" />
                        <div class="p-4 hover:bg-slate-50 transition searchable-row"
                            data-search="{{ strtolower($p->nama_pic . ' ' . $p->tujuan . ' ' . ($p->kode_permohonan ?? '') . ' ' . ($p->kategori_kegiatan ?? '')) }}">
                            <div class="flex items-start justify-between gap-2">
                                <div class="flex items-start gap-2">
                                    <span
                                        class="w-6 h-6 bg-slate-100 text-slate-500 text-xs font-bold rounded-md flex items-center justify-center flex-shrink-0 mt-0.5">{{ $i + 1 }}</span>
                                    <div>
                                        @if ($p->kode_permohonan)
                                            <span
                                                class="text-[10px] font-black text-blue-700 tracking-widest bg-blue-50 border border-blue-200 px-1.5 py-0.5 rounded block w-fit mb-1">{{ $p->kode_permohonan }}</span>
                                        @endif
                                        <p class="font-semibold text-sm text-gray-800">{{ $p->nama_pic }}</p>
                                        <p class="text-xs text-gray-500"><i
                                                class="bi bi-geo-alt mr-0.5"></i>{{ $p->tujuan }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5"><i
                                                class="bi bi-calendar2-event mr-0.5"></i>{{ \Carbon\Carbon::parse($p->waktu_berangkat)->format('d M Y, H:i') }}
                                        </p>
                                        @if ($p->kategori_kegiatan)
                                            <span
                                                class="text-[10px] font-bold text-gray-600 bg-gray-100 px-1.5 py-0.5 rounded mt-1 inline-block">{{ $p->kategori_kegiatan }}</span>
                                        @endif
                                    </div>
                                </div>
                                <span
                                    class="text-[10px] font-bold px-2 py-1 rounded-md border whitespace-nowrap {{ $sc }}">{{ $p->status_permohonan }}</span>
                            </div>
                            <div class="mt-3 pl-8">
                                @if ($p->status_permohonan === 'Menunggu Proses SPSI')
                                    <a href="{{ route('permohonan.proses_spsi', $p->id) }}"
                                        class="w-full flex justify-center items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 rounded-lg text-xs transition">
                                        <i class="bi bi-signpost-split"></i> Alokasi Armada
                                    </a>
                                @else
                                    <a href="{{ route('permohonan.show', $p->id) }}"
                                        class="w-full flex justify-center items-center gap-2 bg-gray-50 hover:bg-gray-100 text-gray-700 font-bold py-2 rounded-lg text-xs border border-gray-200 transition">
                                        <i class="bi bi-eye"></i> Lihat Detail
                                    </a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="py-16 text-center text-gray-400">
                            <i class="bi bi-inbox text-5xl block mb-3 text-gray-300"></i>
                            <p class="font-medium text-gray-500">Tidak ada tugas logistik saat ini</p>
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
                                    Pemohon & Tujuan</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Jadwal</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Kategori</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Instruksi Admin</th>
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
                                <x-status-badge :status="$p->status_permohonan" />
                                <tr class="hover:bg-blue-50/20 transition-colors searchable-row"
                                    data-search="{{ strtolower($p->nama_pic . ' ' . $p->tujuan . ' ' . ($p->kode_permohonan ?? '') . ' ' . ($p->kategori_kegiatan ?? '')) }}">
                                    <td class="px-4 py-3.5 text-center text-xs text-gray-400 font-semibold">
                                        {{ $i + 1 }}</td>
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
                                        <p class="text-xs text-gray-500 mt-0.5"><i
                                                class="bi bi-geo-alt mr-0.5"></i>{{ $p->tujuan }}</p>
                                        <p class="text-xs text-gray-400"><i
                                                class="bi bi-people mr-0.5"></i>{{ $p->jumlah_penumpang }} orang</p>
                                    </td>
                                    <td class="px-4 py-3.5 whitespace-nowrap">
                                        <p class="text-gray-700 font-medium">
                                            {{ \Carbon\Carbon::parse($p->waktu_berangkat)->format('d M Y') }}</p>
                                        <p class="text-xs text-gray-400">s/d
                                            {{ \Carbon\Carbon::parse($p->waktu_kembali)->format('d M Y') }}</p>
                                    </td>
                                    <td class="px-4 py-3.5">
                                        @if ($p->kategori_kegiatan)
                                            <span
                                                class="text-xs font-bold px-2 py-1 rounded-md border {{ $p->kategori_kegiatan === 'Dinas SITH' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-gray-50 text-gray-600 border-gray-200' }}">{{ $p->kategori_kegiatan }}</span>
                                        @else
                                            <span class="text-gray-400 text-xs">—</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3.5 text-xs text-gray-500 max-w-xs">
                                        <p class="line-clamp-2">{{ $p->rekomendasi_admin ?? '—' }}</p>
                                    </td>
                                    <td class="px-4 py-3.5">
                                        <span
                                            class="inline-block text-[11px] font-bold px-2.5 py-1 rounded-md border {{ $sc }}">{{ $p->status_permohonan }}</span>
                                    </td>
                                    <td class="px-4 py-3.5 text-center">
                                        @if ($p->status_permohonan === 'Menunggu Proses SPSI')
                                            <a href="{{ route('permohonan.proses_spsi', $p->id) }}"
                                                class="inline-flex items-center gap-1 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 px-3 py-1.5 rounded-lg shadow-sm transition">
                                                <i class="bi bi-signpost-split"></i> Alokasi
                                            </a>
                                        @else
                                            <a href="{{ route('permohonan.show', $p->id) }}"
                                                class="inline-flex items-center gap-1 text-xs font-bold text-gray-600 bg-white hover:bg-gray-50 border border-gray-300 px-3 py-1.5 rounded-lg transition">
                                                <i class="bi bi-eye"></i> Detail
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-16 text-center">
                                        <i class="bi bi-inbox text-5xl block mb-3 text-gray-300"></i>
                                        <p class="font-medium text-gray-500">Tidak ada tugas logistik</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-5 py-3 border-t border-gray-100 flex items-center justify-between">
                    <p class="text-xs text-gray-400" id="tableInfo">Menampilkan {{ $permohonans->count() }} data</p>
                    <p class="text-xs text-gray-400">{{ now()->format('d M Y, H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('searchInput').addEventListener('input', function() {
            const q = this.value.toLowerCase().trim();
            let vis = 0;
            const total = {{ $permohonans->count() }};
            document.querySelectorAll('.searchable-row').forEach(r => {
                const s = !q || r.dataset.search.includes(q);
                r.style.display = s ? '' : 'none';
                if (s) vis++;
            });
            document.getElementById('tableInfo').textContent = q ? `Menampilkan ${vis} dari ${total} data` :
                `Menampilkan ${total} data`;
            document.getElementById('countBadge').textContent = q ? `${vis} data` : `${total} data`;
        });
    </script>
</x-app-layout>

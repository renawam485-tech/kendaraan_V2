{{-- ============================================================ --}}
{{-- PARTIAL: _keuangan.blade.php                               --}}
{{-- Role: keuangan                                             --}}
{{-- ============================================================ --}}

{{-- STATS --}}
@include('laporan.partials.stats')

{{-- FILTER --}}
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
                        <option value="{{ $s }}" {{ ($request->status ?? '') === $s ? 'selected' : '' }}>
                            {{ $s }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2 w-full md:justify-end mt-2">
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

{{-- TABEL --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="p-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
        <p class="text-sm text-gray-600">Ditemukan <strong class="text-blue-600 text-lg">{{ $data->total() }}</strong> data laporan.</p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-600">
            <thead class="bg-gray-50 border-b border-gray-100 text-xs uppercase text-gray-500">
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
            </thead>
            <tbody>
                @forelse($data as $i => $p)
                    @php
                        $sc      = $p->status_permohonan->badgeClass() ?? '';
                        $selisih = ($p->rab_disetujui ?? 0) - ($p->biaya_aktual ?? 0);
                    @endphp
                    <tr class="border-b border-gray-50 hover:bg-blue-50/30 transition">
                        <td class="px-4 py-3 text-gray-400">
                            {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}
                        </td>
                        <td class="px-4 py-3 font-bold text-gray-800">{{ $p->nama_pic }}</td>
                        <td class="px-4 py-3">{{ $p->tujuan }}</td>
                        <td class="px-4 py-3">
                            <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs">{{ $p->kategori_kegiatan ?? '-' }}</span>
                        </td>
                        <td class="px-4 py-3 text-right font-mono text-gray-800">
                            {{ $p->rab_disetujui ? 'Rp ' . number_format($p->rab_disetujui, 0, ',', '.') : '-' }}
                        </td>
                        <td class="px-4 py-3 text-right font-mono text-gray-800">
                            {{ $p->biaya_aktual ? 'Rp ' . number_format($p->biaya_aktual, 0, ',', '.') : '-' }}
                        </td>
                        <td class="px-4 py-3 text-right font-mono font-bold {{ $selisih > 0 ? 'text-orange-600' : ($selisih < 0 ? 'text-red-600' : 'text-gray-500') }}">
                            {{ $p->rab_disetujui ? 'Rp ' . number_format($selisih, 0, ',', '.') : '-' }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold border {{ $sc }}">{{ $p->status_permohonan->value }}</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('permohonan.show', $p->id) }}"
                                class="text-blue-600 hover:text-blue-800 hover:underline font-bold text-xs">
                                <i class="bi bi-search"></i> Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-16 text-gray-400">
                            <i class="bi bi-clipboard-x text-5xl block mb-3 text-gray-300"></i>
                            <p class="font-medium text-gray-500">Tidak ada data laporan untuk ditampilkan sesuai filter.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @include('laporan.partials.pagination')
</div>

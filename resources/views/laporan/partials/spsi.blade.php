{{-- ============================================================ --}}
{{-- PARTIAL: _spsi.blade.php                                   --}}
{{-- Role: spsi                                                 --}}
{{-- ============================================================ --}}

@php
    use App\Enums\StatusPermohonan;

    // Override $data dengan query khusus SPSI
    $query = \App\Models\Permohonan::with(['user', 'kendaraan', 'pengemudi', 'kendaraanVendor'])
        ->whereIn('status_permohonan', [
            StatusPermohonan::MENUNGGU_PROSES_SPSI->value,
            StatusPermohonan::DISETUJUI->value,
            StatusPermohonan::MENUNGGU_MULAI_PERJALANAN->value,
            StatusPermohonan::PERJALANAN_BERLANGSUNG->value,
            StatusPermohonan::MENUNGGU_KONFIRMASI_KEMBALI->value,
            StatusPermohonan::MENUNGGU_PENYELESAIAN->value,
            StatusPermohonan::SELESAI->value,
        ])
        ->where(function ($q) {
            $q->whereNotNull('kendaraan_id')->orWhereNotNull('kendaraan_vendor_id');
        });

    // Filter tanggal
    if (request()->filled('dari')) {
        $query->whereDate('created_at', '>=', request('dari'));
    }
    if (request()->filled('sampai')) {
        $query->whereDate('created_at', '<=', request('sampai'));
    }

    // Filter kendaraan
    if (request()->filled('kendaraan')) {
        $query->where('kendaraan_id', request('kendaraan'));
    }

    // Filter status
    if (request()->filled('status')) {
        $query->where('status_permohonan', request('status'));
    }

    // Overwrite $data variable
    $data = $query->orderBy('waktu_berangkat', 'desc')->paginate(15);
@endphp

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
                    @foreach ([\App\Enums\StatusPermohonan::MENUNGGU_PROSES_SPSI->value, \App\Enums\StatusPermohonan::DISETUJUI->value, \App\Enums\StatusPermohonan::MENUNGGU_MULAI_PERJALANAN->value, \App\Enums\StatusPermohonan::PERJALANAN_BERLANGSUNG->value, \App\Enums\StatusPermohonan::MENUNGGU_KONFIRMASI_KEMBALI->value, \App\Enums\StatusPermohonan::MENUNGGU_PENYELESAIAN->value, \App\Enums\StatusPermohonan::SELESAI->value] as $s)
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
        <p class="text-sm text-gray-600">Ditemukan <strong class="text-blue-600 text-lg">{{ $data->total() }}</strong>
            data laporan.</p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-600">
            <thead class="bg-gray-50 border-b border-gray-100 text-xs uppercase text-gray-500">
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
            </thead>
            <tbody>
                @forelse($data as $i => $p)
                    @php $sc = $p->status_permohonan->badgeClass() ?? ''; @endphp
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
                            {{ $p->pengemudi?->nama_pengemudi ?? 'Tanpa Supir' }}
                        </td>
                        <td class="px-4 py-3 text-xs">
                            {{ \Carbon\Carbon::parse($p->waktu_berangkat)->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-4 py-3 text-xs">
                            {{ \Carbon\Carbon::parse($p->waktu_kembali)->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-4 py-3">
                            <span
                                class="px-2 py-0.5 rounded text-[10px] font-bold border {{ $sc }}">{{ $p->status_permohonan->value }}</span>
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
                        <td colspan="8" class="text-center py-16 text-gray-400">
                            <i class="bi bi-clipboard-x text-5xl block mb-3 text-gray-300"></i>
                            <p class="font-medium text-gray-500">Tidak ada data laporan untuk ditampilkan sesuai filter.
                            </p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @include('laporan.partials.pagination')
</div>

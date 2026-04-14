@props([
    'items',
    'type',
    'showActions' => false,
    'actionRoute' => null,
    'actionText' => null,
    'actionColor' => '',
    'search' => '',
    'tab' => '',
])

@php
    // Build paginator with correct appended params so all page links carry tab + search
    $paginated = $items->appends(['tab' => $tab, 'search' => $search]);

    $pageParamMap = [
        'pending' => 'pending_page',
        'menunggu' => 'menunggu_page',
        'berlangsung' => 'berlangsung_page',
        'konfirmasi' => 'konfirmasi_page',
        'riwayat' => 'riwayat_page',
    ];
    $pageParam = $pageParamMap[$tab] ?? 'page';
@endphp

<div class="overflow-x-auto">
    @if ($items->isEmpty())
        <div class="py-16 text-center text-gray-400">
            <i class="bi bi-inbox text-5xl block mb-3 text-gray-300"></i>
            <p class="font-medium text-gray-500">Tidak ada data</p>
        </div>
    @else
        {{-- ── MOBILE CARDS ── --}}
        <div class="block md:hidden divide-y divide-gray-100">
            @foreach ($items as $p)
                @php $isUrgent = $type === 'pending' && \Carbon\Carbon::parse($p->waktu_berangkat)->diffInHours(now()) <= 2; @endphp
                <div class="p-4 hover:bg-slate-50 transition-colors {{ $isUrgent ? 'bg-red-50/20' : '' }}">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span
                                    class="text-[10px] font-black text-blue-700 tracking-widest bg-blue-50 border border-blue-200 px-1.5 py-0.5 rounded">
                                    {{ $p->kode_permohonan ?? '—' }}
                                </span>
                                @if ($isUrgent)
                                    <span
                                        class="text-[10px] font-bold text-red-600 bg-red-50 border border-red-200 px-1.5 py-0.5 rounded">SEGERA</span>
                                @endif
                            </div>
                            <p class="font-semibold text-sm text-gray-800">{{ $p->nama_pic }}</p>
                            <p class="text-xs text-gray-500"><i class="bi bi-geo-alt mr-0.5"></i>{{ $p->tujuan }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">
                                @if ($p->kendaraan)
                                    <i class="bi bi-car-front mr-0.5"></i>{{ $p->kendaraan->nama_kendaraan }}
                                @elseif($p->kendaraanVendor)
                                    <i class="bi bi-car-front mr-0.5"></i>{{ $p->kendaraanVendor->nama_kendaraan }}
                                    <span class="text-orange-600 font-bold">(Vendor)</span>
                                @endif
                            </p>

                            @if ($type === 'pending')
                                <p class="text-xs text-amber-700 mt-1 font-semibold">
                                    <i class="bi bi-calendar-event mr-0.5"></i>
                                    {{ \Carbon\Carbon::parse($p->waktu_berangkat)->format('d M Y, H:i') }} WIB
                                </p>
                            @elseif($type === 'menunggu' && $p->waktu_serah_terima)
                                <p class="text-xs text-yellow-600 mt-1">
                                    Kunci: {{ \Carbon\Carbon::parse($p->waktu_serah_terima)->diffForHumans() }}
                                </p>
                            @elseif($type === 'berlangsung' && $p->waktu_mulai_perjalanan)
                                <p class="text-xs text-teal-600 mt-1">
                                    Mulai: {{ \Carbon\Carbon::parse($p->waktu_mulai_perjalanan)->diffForHumans() }}
                                </p>
                            @elseif($type === 'konfirmasi' && $p->waktu_kembali_aktual)
                                <p class="text-xs text-indigo-600 mt-1">
                                    Lapor kembali:
                                    {{ \Carbon\Carbon::parse($p->waktu_kembali_aktual)->diffForHumans() }}
                                </p>
                            @elseif($type === 'riwayat')
                                <x-status-badge :status="$p->status_permohonan" />
                            @endif
                        </div>
                    </div>
                    <div class="flex gap-2 mt-3">
                        @if ($showActions)
                            @if ($type === 'pending')
                                <button type="button"
                                    onclick="handleSerahTerima({{ $p->id }}, '{{ addslashes($p->nama_pic) }}')"
                                    class="flex-1 inline-flex items-center justify-center gap-1.5 text-xs font-bold text-white bg-amber-500 hover:bg-amber-600 py-2 px-3 rounded-lg shadow-sm transition">
                                    <i class="bi bi-key-fill"></i> Serah Terima
                                </button>
                            @elseif($type === 'konfirmasi')
                                <button type="button"
                                    onclick="handleKonfirmasiKembali({{ $p->id }}, '{{ addslashes($p->nama_pic) }}')"
                                    class="flex-1 inline-flex items-center justify-center gap-1.5 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 py-2 px-3 rounded-lg shadow-sm transition">
                                    <i class="bi bi-check2-circle"></i> Konfirmasi
                                </button>
                            @endif
                        @endif
                        <a href="{{ route('permohonan.show', $p->id) }}"
                            class="flex-1 inline-flex items-center justify-center gap-1 text-xs font-bold text-gray-600 bg-white border border-gray-300 hover:bg-gray-50 py-2 px-3 rounded-lg transition">
                            <i class="bi bi-eye"></i> Detail
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- ── DESKTOP TABLE ── --}}
        <div class="hidden md:block">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-gray-200">
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-12">No</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kode</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pemohon</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kendaraan</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pengemudi</th>

                        @if ($type === 'pending')
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Jadwal Berangkat</th>
                        @elseif($type === 'menunggu')
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kunci Diserahkan</th>
                        @elseif($type === 'berlangsung')
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Mulai Perjalanan</th>
                        @elseif($type === 'konfirmasi')
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Dilaporkan Kembali</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        @elseif($type === 'riwayat')
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Diperbarui</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        @endif

                        @if ($showActions)
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-36">Aksi</th>
                        @else
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-24">Detail</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($items as $i => $p)
                        @php $isUrgent = $type === 'pending' && \Carbon\Carbon::parse($p->waktu_berangkat)->diffInHours(now()) <= 2; @endphp
                        <tr class="hover:bg-blue-50/20 transition-colors {{ $isUrgent ? 'bg-red-50/20' : '' }}">
                            <td class="px-4 py-3.5 text-center text-xs text-gray-400 font-semibold">
                                {{ ($items->currentPage() - 1) * $items->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-4 py-3.5">
                                <span class="font-black text-blue-700 text-[11px] bg-blue-50 border border-blue-200 px-2 py-0.5 rounded-md">
                                    {{ $p->kode_permohonan ?? '—' }}
                                </span>
                                @if ($isUrgent)
                                    <span class="block mt-1 text-[10px] font-bold text-red-600 bg-red-50 border border-red-200 px-1.5 py-0.5 rounded w-fit">SEGERA</span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5">
                                <p class="font-semibold text-gray-800">{{ $p->nama_pic }}</p>
                                <p class="text-xs text-gray-500"><i class="bi bi-telephone mr-0.5"></i>{{ $p->kontak_pic }}</p>
                                <p class="text-xs text-gray-400"><i class="bi bi-geo-alt mr-0.5"></i>{{ $p->tujuan }}</p>
                            </td>
                            <td class="px-4 py-3.5">
                                @if ($p->kendaraan)
                                    <p class="font-medium text-gray-800">{{ $p->kendaraan->nama_kendaraan }}</p>
                                    <p class="text-xs text-gray-500 font-mono">{{ $p->kendaraan->plat_nomor }}</p>
                                @elseif($p->kendaraanVendor)
                                    <p class="font-medium text-gray-800">{{ $p->kendaraanVendor->nama_kendaraan }}</p>
                                    <p class="text-xs text-gray-500 font-mono">{{ $p->kendaraanVendor->plat_nomor }}</p>
                                    <span class="text-[10px] font-bold text-orange-600 bg-orange-50 border border-orange-200 px-1 rounded">VENDOR</span>
                                @else
                                    <span class="text-gray-400 italic text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5">
                                <p class="text-sm text-gray-700">{{ $p->pengemudi?->nama_pengemudi ?? 'Lepas Kunci' }}</p>
                                @if ($p->pengemudi?->kontak)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $p->pengemudi->kontak) }}" target="_blank" class="text-xs text-green-600 hover:underline flex items-center gap-0.5 mt-0.5">
                                        <i class="bi bi-whatsapp"></i> {{ $p->pengemudi->kontak }}
                                    </a>
                                @endif
                            </td>

                            @if ($type === 'pending')
                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    <p class="font-medium text-gray-700 {{ $isUrgent ? 'text-red-600' : '' }}">
                                        {{ \Carbon\Carbon::parse($p->waktu_berangkat)->format('d M Y') }}
                                    </p>
                                    <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($p->waktu_berangkat)->format('H:i') }} WIB</p>
                                    <p class="text-xs {{ $isUrgent ? 'text-red-500 font-bold' : 'text-gray-400' }}">
                                        {{ \Carbon\Carbon::parse($p->waktu_berangkat)->diffForHumans() }}
                                    </p>
                                </td>
                            @elseif($type === 'menunggu')
                                <td class="px-4 py-3.5">
                                    @if ($p->waktu_serah_terima)
                                        <p class="text-sm text-gray-700">{{ \Carbon\Carbon::parse($p->waktu_serah_terima)->format('d M Y, H:i') }}</p>
                                        <p class="text-xs text-yellow-600">{{ \Carbon\Carbon::parse($p->waktu_serah_terima)->diffForHumans() }}</p>
                                    @else
                                        <span class="text-gray-400 italic text-xs">—</span>
                                    @endif
                                </td>
                            @elseif($type === 'berlangsung')
                                <td class="px-4 py-3.5">
                                    @if ($p->waktu_mulai_perjalanan)
                                        <p class="text-sm text-gray-700">{{ \Carbon\Carbon::parse($p->waktu_mulai_perjalanan)->format('d M Y, H:i') }}</p>
                                        <p class="text-xs text-teal-600">{{ \Carbon\Carbon::parse($p->waktu_mulai_perjalanan)->diffForHumans() }}</p>
                                    @else
                                        <span class="text-gray-400 italic text-xs">—</span>
                                    @endif
                                </td>
                            @elseif($type === 'konfirmasi')
                                <td class="px-4 py-3.5">
                                    @if ($p->waktu_kembali_aktual)
                                        <p class="text-sm text-gray-700">{{ \Carbon\Carbon::parse($p->waktu_kembali_aktual)->format('d M Y, H:i') }}</p>
                                        <p class="text-xs text-indigo-600">{{ \Carbon\Carbon::parse($p->waktu_kembali_aktual)->diffForHumans() }}</p>
                                    @else
                                        <span class="text-gray-400 italic text-xs">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5">
                                    <x-status-badge :status="$p->status_permohonan" />
                                </td>
                            @elseif($type === 'riwayat')
                                <td class="px-4 py-3.5 text-xs text-gray-500">
                                    {{ $p->updated_at->format('d M Y, H:i') }}
                                </td>
                                <td class="px-4 py-3.5">
                                    <x-status-badge :status="$p->status_permohonan" />
                                </td>
                            @endif

                            {{-- Kolom Aksi / Detail --}}
                            <td class="px-4 py-3.5 text-center">
                                <div class="flex flex-col items-center gap-1.5">
                                    @if ($showActions && $type === 'pending')
                                        <button type="button"
                                            onclick="handleSerahTerima({{ $p->id }}, '{{ addslashes($p->nama_pic) }}')"
                                            class="px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold rounded-lg transition shadow-sm">
                                            <i class="bi bi-key-fill mr-1"></i> Serah Terima
                                        </button>
                                    @elseif($showActions && $type === 'konfirmasi')
                                        <button type="button"
                                            onclick="handleKonfirmasiKembali({{ $p->id }}, '{{ addslashes($p->nama_pic) }}')"
                                            class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg transition shadow-sm">
                                            <i class="bi bi-check2-circle mr-1"></i> Konfirmasi
                                        </button>
                                    @endif
                                    <a href="{{ route('permohonan.show', $p->id) }}"
                                        class="inline-flex items-center gap-1 text-xs text-gray-500 hover:text-gray-700 transition">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- ── PAGINATION ── --}}
        <div class="px-5 py-3 border-t border-gray-100 flex items-center justify-between gap-3 flex-wrap">
            <p class="text-xs text-gray-400">
                Menampilkan {{ $items->firstItem() }} – {{ $items->lastItem() }}
                dari {{ $items->total() }} data
            </p>

            @if ($items->lastPage() > 1)
                <div class="flex items-center gap-2">
                    @if ($items->onFirstPage())
                        <span class="px-3 py-2 text-xs font-bold text-gray-300 cursor-not-allowed">
                            <i class="bi bi-chevron-left"></i>
                        </span>
                    @else
                        <a href="{{ $paginated->previousPageUrl() }}" class="px-3 py-2 text-xs font-bold text-blue-600 hover:bg-blue-50 rounded-lg transition">
                            <i class="bi bi-chevron-left"></i>
                        </a>
                    @endif

                    <div class="hidden md:flex items-center gap-1">
                        @php
                            $current = $items->currentPage();
                            $last = $items->lastPage();
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
                            <a href="{{ $paginated->url(1) }}" class="w-8 h-8 flex items-center justify-center rounded-lg text-xs font-bold text-gray-500 hover:bg-gray-100">1</a>
                            @if ($start > 2)
                                <span class="w-8 h-8 flex items-center justify-center text-gray-400">…</span>
                            @endif
                        @endif

                        @for ($page = $start; $page <= $end; $page++)
                            @if ($page === $current)
                                <span class="w-8 h-8 flex items-center justify-center rounded-lg text-xs font-bold bg-blue-600 text-white shadow-sm">{{ $page }}</span>
                            @else
                                <a href="{{ $paginated->url($page) }}" class="w-8 h-8 flex items-center justify-center rounded-lg text-xs font-bold text-gray-500 hover:bg-gray-100">{{ $page }}</a>
                            @endif
                        @endfor

                        @if ($end < $last)
                            @if ($end < $last - 1)
                                <span class="w-8 h-8 flex items-center justify-center text-gray-400">…</span>
                            @endif
                            <a href="{{ $paginated->url($last) }}" class="w-8 h-8 flex items-center justify-center rounded-lg text-xs font-bold text-gray-500 hover:bg-gray-100">{{ $last }}</a>
                        @endif
                    </div>

                    @if ($items->hasMorePages())
                        <a href="{{ $paginated->nextPageUrl() }}" class="px-3 py-2 text-xs font-bold text-blue-600 hover:bg-blue-50 rounded-lg transition">
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    @else
                        <span class="px-3 py-2 text-xs font-bold text-gray-300 cursor-not-allowed">
                            <i class="bi bi-chevron-right"></i>
                        </span>
                    @endif
                </div>
            @endif

            <p class="text-xs text-gray-400" id="realtimeClock-{{ $tab }}"></p>
        </div>
    @endif
</div>

<script>
function handleSerahTerima(id, namaPic) {
    if (!window.customConfirm) {
        if (!confirm(`Serahkan kunci kepada ${namaPic}?`)) return;
        return fetchSubmit(id, 'serah-terima-kunci', 'Serah terima kunci berhasil dicatat');
    }
    
    window.customConfirm({
        title: 'Konfirmasi Serah Terima',
        message: `Apakah Anda sudah menyerahkan kunci kendaraannya?`,
        confirmText: 'Ya, Sudah Diserahkan',
        isDanger: false
    }, () => fetchSubmit(id, 'serah-terima-kunci', 'Serah terima kunci berhasil dicatat'));
}

function handleKonfirmasiKembali(id, namaPic) {
    if (!window.customConfirm) {
        if (!confirm(`Konfirmasi kendaraan dari ${namaPic} sudah kembali?`)) return;
        return fetchSubmit(id, 'konfirmasi-kembali', 'Kendaraan dikonfirmasi sudah kembali');
    }
    
    window.customConfirm({
        title: 'Konfirmasi Penerimaan Kendaraan',
        message: `Apakah kendaraan sudah kembali dalam kondisi baik?`,
        confirmText: 'Ya, Sudah Kembali',
        isDanger: false
    }, () => fetchSubmit(id, 'konfirmasi-kembali', 'Kendaraan dikonfirmasi sudah kembali'));
}

function fetchSubmit(id, action, successMessage) {
    fetch(`/permohonan/${id}/${action}`, {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (window.showCompactToast) {
                window.showCompactToast('Berhasil', data.message || successMessage);
            } else {
                alert(data.message || successMessage);
            }
            setTimeout(() => window.location.reload(), 1500);
        } else {
            if (window.showCompactToast) {
                window.showCompactToast('Gagal', data.message, 'error');
            } else {
                alert('Gagal: ' + data.message);
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (window.showCompactToast) {
            window.showCompactToast('Gagal', 'Terjadi kesalahan jaringan', 'error');
        } else {
            alert('Terjadi kesalahan jaringan');
        }
    });
}

function escapeHtml(str) {
    if (!str) return '';
    return str
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');
}

(function() {
    const el = document.getElementById('realtimeClock-{{ $tab }}');
    if (!el) return;

    function tick() {
        el.textContent = new Date().toLocaleString('id-ID', {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false,
        }).replace(/\./g, ':') + ' WIB';
    }
    setInterval(tick, 1000);
    tick();
})();
</script>
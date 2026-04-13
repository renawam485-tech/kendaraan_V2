<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <i class="bi bi-key-fill text-blue-600"></i> Manajemen Serah Terima Kendaraan
        </h2>
    </x-slot>

    <div class="py-6 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">

            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex items-center gap-3 shadow-sm">
                    <i class="bi bi-check-circle-fill text-emerald-500 text-lg flex-shrink-0"></i>
                    <p class="text-sm font-semibold">{{ session('success') }}</p>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl flex items-center gap-3 shadow-sm">
                    <i class="bi bi-exclamation-triangle-fill text-red-500 text-lg flex-shrink-0"></i>
                    <p class="text-sm font-semibold">{{ session('error') }}</p>
                </div>
            @endif

            {{-- STATS - Perbaikan variabel sesuai Controller --}}
            <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                @foreach([
                    [$pending->count(),            'Perlu Serah Terima',      'bi-key-fill',          'bg-amber-50',   'text-amber-600'],
                    [$menungguMulai->count(),       'Menunggu User Mulai',     'bi-person-walking',    'bg-yellow-50',  'text-yellow-600'],
                    [$berlangsung->count(),         'Dalam Perjalanan',        'bi-geo-alt-fill',      'bg-teal-50',    'text-teal-600'],
                    [$menungguKonfirmasi->count(),  'Menunggu Konfirmasi',     'bi-arrow-return-left', 'bg-indigo-50',  'text-indigo-600'],
                    [$riwayat->count(),             'Riwayat',                 'bi-archive',           'bg-slate-100',  'text-slate-600'],
                ] as [$count, $label, $icon, $bg, $tc])
                    <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg {{ $bg }} flex items-center justify-center flex-shrink-0">
                            <i class="bi {{ $icon }} {{ $tc }} text-lg"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-black text-gray-800">{{ $count }}</p>
                            <p class="text-xs text-gray-500 font-semibold leading-tight">{{ $label }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- TABS --}}
            <div x-data="{ tab: '{{ $menungguKonfirmasi->count() > 0 ? 'konfirmasi' : ($pending->count() > 0 ? 'pending' : 'berlangsung') }}' }"
                 class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">

                {{-- TAB HEADERS --}}
                <div class="border-b border-gray-200 flex overflow-x-auto bg-gray-50">
                    @foreach([
                        ['pending',     'Perlu Serah Terima',     $pending->count(),           'bg-amber-500'],
                        ['menunggu',    'Menunggu User Mulai',    $menungguMulai->count(),     'bg-yellow-500'],
                        ['berlangsung', 'Dalam Perjalanan',        $berlangsung->count(),       'bg-teal-500'],
                        ['konfirmasi',  'Konfirmasi Kembali',      $menungguKonfirmasi->count(), 'bg-indigo-500'],
                        ['riwayat',     'Riwayat',                 $riwayat->count(),           'bg-slate-400'],
                    ] as [$key, $label, $count, $badgeBg])
                        <button @click="tab = '{{ $key }}'"
                            :class="tab === '{{ $key }}'
                                ? 'border-b-2 border-blue-600 text-blue-700 bg-white font-bold'
                                : 'text-gray-500 hover:text-gray-700 hover:bg-gray-100'"
                            class="flex-shrink-0 flex items-center gap-2 px-5 py-3.5 text-sm transition-all focus:outline-none">
                            {{ $label }}
                            @if($count > 0)
                                <span class="{{ $badgeBg }} text-white text-[10px] font-black px-1.5 py-0.5 rounded-full">{{ $count }}</span>
                            @endif
                        </button>
                    @endforeach
                </div>

                {{-- TAB 1 — PERLU SERAH TERIMA --}}
                <div x-show="tab === 'pending'" class="overflow-x-auto">
                    @if($pending->isEmpty())
                        <div class="py-16 text-center text-gray-400">
                            <i class="bi bi-key text-5xl block mb-3 text-gray-300"></i>
                            <p class="font-medium text-gray-500">Tidak ada antrian serah terima kunci saat ini</p>
                        </div>
                    @else
                        <div class="p-4 bg-amber-50 border-b border-amber-100 flex items-start gap-2">
                            <i class="bi bi-info-circle-fill text-amber-500 flex-shrink-0 mt-0.5"></i>
                            <p class="text-sm text-amber-700">Permohonan berikut sudah <strong>disetujui Admin</strong>. Serahkan kunci fisik kepada pemohon dan klik tombol konfirmasi.</p>
                        </div>
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-200">
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kode</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pemohon</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kendaraan</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pengemudi</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Rencana Berangkat</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($pending as $p)
                                    @php
                                        $isUrgent = \Carbon\Carbon::parse($p->waktu_berangkat)->diffInHours(now(), false) >= 0
                                            || \Carbon\Carbon::parse($p->waktu_berangkat)->diffInHours(now()) <= 2;
                                    @endphp
                                    <tr class="hover:bg-amber-50/30 transition-colors {{ $isUrgent ? 'bg-red-50/20' : '' }}">
                                        <td class="px-4 py-3.5">
                                            <span class="font-black text-blue-700 tracking-wider text-[11px] bg-blue-50 border border-blue-200 px-2 py-0.5 rounded-md">{{ $p->kode_permohonan ?? '—' }}</span>
                                            @if($isUrgent)
                                                <span class="block mt-1 text-[10px] font-bold text-red-600 bg-red-50 border border-red-200 px-1.5 py-0.5 rounded w-fit">SEGERA</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3.5">
                                            <p class="font-semibold text-gray-800">{{ $p->nama_pic }}</p>
                                            <p class="text-xs text-gray-500"><i class="bi bi-telephone mr-0.5"></i>{{ $p->kontak_pic }}</p>
                                            <p class="text-xs text-gray-400"><i class="bi bi-geo-alt mr-0.5"></i>{{ $p->tujuan }}</p>
                                        </td>
                                        <td class="px-4 py-3.5">
                                            @if($p->kendaraan_id)
                                                <p class="font-medium text-gray-800">{{ $p->kendaraan->nama_kendaraan }}</p>
                                                <p class="text-xs font-mono text-gray-500">{{ $p->kendaraan->plat_nomor }}</p>
                                            @elseif($p->kendaraanVendor)
                                                <p class="font-medium text-gray-800">{{ $p->kendaraanVendor->nama_kendaraan }}</p>
                                                <p class="text-xs font-mono text-gray-500">{{ $p->kendaraanVendor->plat_nomor }}</p>
                                                <span class="text-[10px] font-bold text-orange-600 bg-orange-50 border border-orange-200 px-1 rounded">VENDOR</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3.5">
                                            <p class="text-sm text-gray-700">{{ $p->pengemudi->nama_pengemudi ?? '—' }}</p>
                                            @if($p->pengemudi?->kontak)
                                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $p->pengemudi->kontak) }}" target="_blank"
                                                   class="text-xs text-green-600 hover:underline"><i class="bi bi-whatsapp mr-0.5"></i>{{ $p->pengemudi->kontak }}</a>
                                            @else
                                                <span class="text-xs text-gray-400 italic">Lepas Kunci</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3.5 whitespace-nowrap">
                                            <p class="font-medium text-gray-700 {{ $isUrgent ? 'text-red-600' : '' }}">{{ \Carbon\Carbon::parse($p->waktu_berangkat)->format('d M Y') }}</p>
                                            <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($p->waktu_berangkat)->format('H:i') }} WIB</p>
                                            <p class="text-xs {{ $isUrgent ? 'text-red-500 font-bold' : 'text-gray-400' }}">{{ \Carbon\Carbon::parse($p->waktu_berangkat)->diffForHumans() }}</p>
                                        </td>
                                        <td class="px-4 py-3.5 text-center">
                                            <form action="{{ route('permohonan.serah_terima_kunci', $p->id) }}" method="POST"
                                                  onsubmit="return confirm('Konfirmasi serah terima kunci kepada {{ addslashes($p->nama_pic) }}?\n\nPastikan kunci fisik sudah diserahkan sebelum mengklik OK.')">
                                                @csrf @method('PUT')
                                                <button type="submit"
                                                    class="inline-flex items-center gap-1.5 text-xs font-bold text-white bg-amber-500 hover:bg-amber-600 px-3 py-2 rounded-lg shadow-sm transition whitespace-nowrap">
                                                    <i class="bi bi-key-fill"></i> Serah Terima Kunci
                                                </button>
                                            </form>
                                            <a href="{{ route('permohonan.show', $p->id) }}"
                                               class="mt-1.5 inline-flex items-center gap-1 text-xs text-gray-500 hover:text-gray-700 transition">
                                                <i class="bi bi-eye"></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>

                {{-- TAB 2 — MENUNGGU USER MULAI --}}
                <div x-show="tab === 'menunggu'" style="display:none" class="overflow-x-auto">
                    @if($menungguMulai->isEmpty())
                        <div class="py-16 text-center text-gray-400">
                            <i class="bi bi-person-walking text-5xl block mb-3 text-gray-300"></i>
                            <p class="font-medium text-gray-500">Tidak ada kendaraan yang menunggu keberangkatan</p>
                        </div>
                    @else
                        <table class="w-full text-sm">
                            <tbody class="divide-y divide-gray-100">
                                @foreach($menungguMulai as $p)
                                    <tr class="hover:bg-yellow-50/30 transition-colors">
                                        <td class="px-4 py-4">
                                            <span class="font-black text-blue-700 tracking-wider text-[11px] bg-blue-50 border border-blue-200 px-2 py-0.5 rounded-md">{{ $p->kode_permohonan ?? '—' }}</span>
                                            <p class="font-semibold text-gray-800 mt-1">{{ $p->nama_pic }}</p>
                                        </td>
                                        <td class="px-4 py-4">
                                            <p class="text-xs text-gray-500 italic">Kunci sudah diserahkan: {{ \Carbon\Carbon::parse($p->waktu_serah_terima)->diffForHumans() }}</p>
                                        </td>
                                        <td class="px-4 py-4 text-center text-gray-400 italic text-xs">Menunggu User Klik "Mulai Perjalanan"</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>

                {{-- TAB 3 — DALAM PERJALANAN --}}
                <div x-show="tab === 'berlangsung'" style="display:none" class="overflow-x-auto">
                    @if($berlangsung->isEmpty())
                        <div class="py-16 text-center text-gray-400">
                            <i class="bi bi-geo-alt text-5xl block mb-3 text-gray-300"></i>
                            <p class="font-medium text-gray-500">Tidak ada kendaraan yang sedang dalam perjalanan</p>
                        </div>
                    @else
                        <table class="w-full text-sm">
                            <tbody class="divide-y divide-gray-100">
                                @foreach($berlangsung as $p)
                                    <tr class="hover:bg-teal-50/10 transition">
                                        <td class="px-4 py-4">
                                            <p class="font-bold text-gray-800">{{ $p->nama_pic }}</p>
                                            <p class="text-xs text-teal-600 font-medium">Sedang di {{ $p->tujuan }}</p>
                                        </td>
                                        <td class="px-4 py-4 text-right text-xs text-gray-500">Dimulai: {{ \Carbon\Carbon::parse($p->waktu_mulai_perjalanan)->format('H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>

                {{-- TAB 4 — KONFIRMASI KEMBALI ⭐ --}}
                <div x-show="tab === 'konfirmasi'" style="display:none" class="overflow-x-auto">
                    @if($menungguKonfirmasi->isEmpty())
                        <div class="py-16 text-center text-gray-400">
                            <i class="bi bi-arrow-return-left text-5xl block mb-3 text-gray-300"></i>
                            <p class="font-medium text-gray-500">Tidak ada kendaraan yang menunggu konfirmasi kembali</p>
                        </div>
                    @else
                        <table class="w-full text-sm">
                            <tbody class="divide-y divide-gray-100">
                                @foreach($menungguKonfirmasi as $p)
                                    @php $sc = $p->status_permohonan->badgeClass(); @endphp
                                    <tr class="hover:bg-indigo-50/30 transition-colors">
                                        <td class="px-4 py-4">
                                            <p class="font-bold text-gray-800">{{ $p->nama_pic }}</p>
                                            <p class="text-xs text-gray-500">{{ $p->tujuan }}</p>
                                        </td>
                                        <td class="px-4 py-4">
                                            <span class="inline-block text-[10px] font-bold px-2 py-0.5 rounded-md border {{ $sc }}">{{ $p->status_permohonan->value }}</span>
                                        </td>
                                        <td class="px-4 py-4 text-center">
                                            <form action="{{ route('permohonan.konfirmasi_kembali', $p->id) }}" method="POST"
                                                  onsubmit="return confirm('Konfirmasi kendaraan sudah kembali dalam kondisi baik?')">
                                                @csrf @method('PUT')
                                                <button type="submit"
                                                    class="inline-flex items-center gap-1.5 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 px-3 py-2 rounded-lg shadow-sm transition whitespace-nowrap">
                                                    <i class="bi bi-check2-circle"></i> Konfirmasi Kembali
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>

                {{-- TAB 5 — RIWAYAT --}}
                <div x-show="tab === 'riwayat'" style="display:none" class="overflow-x-auto">
                    @if($riwayat->isEmpty())
                        <div class="py-16 text-center text-gray-400">
                            <i class="bi bi-archive text-5xl block mb-3 text-gray-300"></i>
                            <p class="font-medium text-gray-500">Belum ada riwayat perjalanan</p>
                        </div>
                    @else
                        <table class="w-full text-sm">
                            <tbody class="divide-y divide-gray-100">
                                @foreach($riwayat as $p)
                                    @php $sc = $p->status_permohonan->badgeClass(); @endphp
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-4 py-3.5 font-semibold text-gray-800">{{ $p->nama_pic }}</td>
                                        <td class="px-4 py-3.5 text-xs text-gray-500">{{ $p->updated_at->format('d/m/Y H:i') }}</td>
                                        <td class="px-4 py-3.5">
                                            <span class="inline-block text-[11px] font-bold px-2.5 py-1 rounded-md border {{ $sc }}">{{ $p->status_permohonan->value }}</span>
                                        </td>
                                        <td class="px-4 py-3.5 text-center">
                                            <a href="{{ route('permohonan.show', $p->id) }}"
                                               class="inline-flex items-center gap-1 text-xs font-bold text-gray-600 bg-white hover:bg-gray-50 border border-gray-300 px-3 py-1.5 rounded-lg transition">
                                                <i class="bi bi-eye"></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="px-5 py-3 border-t border-gray-100">
                            <p class="text-xs text-gray-400">Menampilkan 20 riwayat terakhir.</p>
                        </div>
                    @endif
                </div>

            </div>{{-- end tabs --}}
        </div>
    </div>
</x-app-layout>
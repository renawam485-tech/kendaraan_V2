@use('App\Enums\StatusPermohonan')
<x-app-layout>

    @php
        $status = $permohonan->status_permohonan;
        $userRole = Auth::user()->role;
        $isOwner = Auth::id() === $permohonan->user_id;

        // Resolusi satu action yang relevan untuk user+status saat ini
        $actionType = match (true) {
            $status === StatusPermohonan::MENUNGGU_MULAI_PERJALANAN && $isOwner => 'mulai',
            $status === StatusPermohonan::PERJALANAN_BERLANGSUNG && $isOwner => 'lapor_kembali',
            $status === StatusPermohonan::MENUNGGU_KONFIRMASI_KEMBALI && $userRole === 'spsi' => 'konfirmasi_kembali',
            $status === StatusPermohonan::MENUNGGU_PENYELESAIAN && $isOwner => 'selesaikan',
            $status === StatusPermohonan::MENUNGGU_PENGEMBALIAN_DANA && $isOwner => 'pengembalian',
            $status === StatusPermohonan::MENUNGGU_VERIFIKASI_KEMBALI && $userRole === 'keuangan' => 'verifikasi',
            default => null,
        };

        // Warna + ikon status header
        $statusMeta = match ($status) {
            StatusPermohonan::SELESAI => ['bg-emerald-50 border-emerald-200', 'bi-patch-check-fill text-emerald-500'],
            StatusPermohonan::DISETUJUI => ['bg-blue-50 border-blue-200', 'bi-check-circle-fill text-blue-500'],
            StatusPermohonan::DITOLAK => ['bg-red-50 border-red-200', 'bi-x-circle-fill text-red-500'],
            StatusPermohonan::PERJALANAN_BERLANGSUNG => ['bg-teal-50 border-teal-200', 'bi-geo-alt-fill text-teal-500'],
            StatusPermohonan::MENUNGGU_MULAI_PERJALANAN => [
                'bg-yellow-50 border-yellow-200',
                'bi-key-fill text-yellow-500',
            ],
            StatusPermohonan::MENUNGGU_PENGEMBALIAN_DANA => [
                'bg-orange-50 border-orange-200',
                'bi-exclamation-triangle-fill text-orange-500',
            ],
            StatusPermohonan::MENUNGGU_VERIFIKASI_KEMBALI => [
                'bg-pink-50 border-pink-200',
                'bi-hourglass-split text-pink-500',
            ],
            StatusPermohonan::MENUNGGU_PENYELESAIAN => [
                'bg-purple-50 border-purple-200',
                'bi-clipboard2-check text-purple-500',
            ],
            StatusPermohonan::MENUNGGU_KONFIRMASI_KEMBALI => [
                'bg-indigo-50 border-indigo-200',
                'bi-arrow-return-left text-indigo-500',
            ],
            default => ['bg-slate-50 border-slate-200', 'bi-hourglass-split text-slate-400'],
        };

        $hasAllocation = $permohonan->kendaraan_id || $permohonan->kendaraan_vendor_id || $permohonan->pengemudi_id;
        $hasTravelTime =
            $permohonan->waktu_serah_terima || $permohonan->waktu_mulai_perjalanan || $permohonan->waktu_kembali_aktual;
        $hasFinanceData = $permohonan->rab_disetujui || $permohonan->biaya_aktual;
    @endphp

    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <a href="{{ url()->previous() }}"
                    class="text-gray-400 hover:text-gray-600 transition p-1 rounded-lg hover:bg-gray-100">
                    <i class="bi bi-arrow-left text-lg"></i>
                </a>
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Pengajuan</h2>
                    <p class="text-sm text-gray-400 mt-0.5 truncate max-w-xs">{{ $permohonan->tujuan }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2 flex-shrink-0">
                @if ($status->canPrint())
                    <a href="{{ route('permohonan.cetak', $permohonan->id) }}" target="_blank"
                        class="hidden sm:inline-flex items-center gap-1.5 text-xs font-bold text-gray-700 bg-white hover:bg-gray-50 border border-gray-300 px-3 py-2 rounded-lg transition shadow-sm">
                        <i class="bi bi-printer"></i> Cetak SPJ
                    </a>
                @endif
                @if ($permohonan->kode_permohonan)
                    <span
                        class="font-black text-blue-700 bg-blue-50 border border-blue-200 px-3 py-1.5 rounded-lg text-sm tracking-widest">
                        {{ $permohonan->kode_permohonan }}
                    </span>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-6 bg-slate-50 min-h-screen">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">

            {{-- ── STATUS HEADER ── --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-11 h-11 rounded-xl border flex items-center justify-center flex-shrink-0 {{ $statusMeta[0] }}">
                            <i class="bi {{ $statusMeta[1] }} text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Status Permohonan</p>
                            <p class="font-black text-gray-900 text-base mt-0.5">{{ $status->value }}</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-x-5 gap-y-1 text-xs text-gray-400">
                        <span class="flex items-center gap-1">
                            <i class="bi bi-clock-history"></i>
                            Diajukan {{ $permohonan->created_at->diffForHumans() }}
                        </span>
                        <span class="hidden sm:inline-flex items-center gap-1">
                            <i class="bi bi-arrow-repeat"></i>
                            Update {{ $permohonan->updated_at->diffForHumans() }}
                        </span>
                        @if ($status->canPrint())
                            <a href="{{ route('permohonan.cetak', $permohonan->id) }}" target="_blank"
                                class="sm:hidden inline-flex items-center gap-1 text-gray-700 font-bold bg-white border border-gray-300 px-3 py-1.5 rounded-lg hover:bg-gray-50 transition">
                                <i class="bi bi-printer"></i> Cetak SPJ
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ── INFORMASI UTAMA  ── --}}
            <div
                class="grid grid-cols-1 {{ $permohonan->kendaraan_id ? 'md:grid-cols-4' : 'md:grid-cols-3' }} gap-6 mb-8">

                {{-- Pemohon --}}
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                        <i class="bi bi-person-fill text-blue-400"></i> Pemohon
                    </p>
                    <p class="font-bold text-gray-800">{{ $permohonan->nama_pic }}</p>
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $permohonan->kontak_pic) }}" target="_blank"
                        class="text-sm text-blue-600 hover:underline flex items-center gap-1 mt-1">
                        <i class="bi bi-whatsapp text-green-500"></i>{{ $permohonan->kontak_pic }}
                    </a>
                    @if ($permohonan->kategori_kegiatan)
                        <span
                            class="inline-block mt-2 text-xs font-bold px-2 py-0.5 rounded-md border
                            {{ $permohonan->kategori_kegiatan === 'Dinas SITH'
                                ? 'bg-green-50 text-green-700 border-green-200'
                                : 'bg-gray-50 text-gray-600 border-gray-200' }}">
                            {{ $permohonan->kategori_kegiatan }}
                        </span>
                    @endif
                    @if ($permohonan->user)
                        <p class="text-xs text-gray-400 mt-2 flex items-center gap-1">
                            <i class="bi bi-person-circle"></i>
                            Akun: {{ $permohonan->user->name }}
                        </p>
                    @endif
                </div>

                {{-- Rute --}}
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                        <i class="bi bi-signpost-split-fill text-blue-400"></i> Rute Perjalanan
                    </p>
                    <div class="space-y-2">
                        <div class="flex items-start gap-2">
                            <div
                                class="w-5 h-5 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="bi bi-circle-fill text-[6px] text-blue-500"></i>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 font-semibold uppercase">Dari</p>
                                <p class="text-sm font-semibold text-gray-800">{{ $permohonan->titik_jemput }}</p>
                            </div>
                        </div>
                        <div class="ml-2.5 w-px h-4 bg-gray-200"></div>
                        <div class="flex items-start gap-2">
                            <div
                                class="w-5 h-5 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="bi bi-geo-alt-fill text-[9px] text-red-500"></i>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 font-semibold uppercase">Tujuan</p>
                                <p class="text-sm font-semibold text-gray-800">{{ $permohonan->tujuan }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 pt-3 border-t border-gray-100 flex items-center gap-3 text-xs text-gray-500">
                        <span><i class="bi bi-people mr-0.5"></i>{{ $permohonan->jumlah_penumpang }} orang</span>
                        <span>·</span>
                        <span>{{ $permohonan->kendaraan_dibutuhkan }}</span>
                    </div>
                </div>

                {{-- Jadwal --}}
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                        <i class="bi bi-calendar-event-fill text-blue-400"></i> Jadwal
                    </p>
                    <div class="space-y-3">
                        <div>
                            <p class="text-[10px] text-gray-400 font-semibold uppercase">Berangkat</p>
                            <p class="font-bold text-gray-800 text-sm">
                                {{ \Carbon\Carbon::parse($permohonan->waktu_berangkat)->translatedFormat('d M Y') }}
                            </p>
                            <p class="text-xs text-blue-600 font-semibold">
                                {{ \Carbon\Carbon::parse($permohonan->waktu_berangkat)->format('H:i') }} WIB
                            </p>
                        </div>
                        <div class="w-full h-px bg-gray-100"></div>
                        <div>
                            <p class="text-[10px] text-gray-400 font-semibold uppercase">Kembali</p>
                            <p class="font-bold text-gray-800 text-sm">
                                {{ \Carbon\Carbon::parse($permohonan->waktu_kembali)->translatedFormat('d M Y') }}
                            </p>
                            <p class="text-xs text-gray-500 font-semibold">
                                {{ \Carbon\Carbon::parse($permohonan->waktu_kembali)->format('H:i') }} WIB
                            </p>
                        </div>
                    </div>
                    @php
                        $durasi = \Carbon\Carbon::parse($permohonan->waktu_berangkat)->diff(
                            \Carbon\Carbon::parse($permohonan->waktu_kembali),
                        );
                        $durasiStr = ($durasi->days > 0 ? $durasi->days . ' hari ' : '') . $durasi->h . ' jam';
                    @endphp
                    <div class="mt-3 pt-3 border-t border-gray-100">
                        <span class="text-xs text-gray-400 flex items-center gap-1">
                            <i class="bi bi-stopwatch"></i> Durasi: <strong
                                class="text-gray-600">{{ $durasiStr }}</strong>
                        </span>
                    </div>
                </div>

                {{-- 4. Alokasi Armada (Muncul setelah diproses SPSI) --}}
                @if ($hasAllocation || $permohonan->rekomendasi_admin)
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                        <p
                            class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                            <i class="bi bi-truck-front-fill text-blue-400"></i> Armada Dialokasikan
                        </p>
                        @if ($permohonan->kendaraan_id && $permohonan->kendaraan)
                            <p class="font-bold text-gray-800">{{ $permohonan->kendaraan->nama_kendaraan }}</p>
                            <p class="text-xs font-mono text-gray-500 mt-0.5">
                                {{ $permohonan->kendaraan->plat_nomor }}
                            </p>
                            <span
                                class="inline-block mt-1.5 text-[10px] font-bold text-blue-700 bg-blue-50 border border-blue-200 px-2 py-0.5 rounded">
                                Armada Kampus
                            </span>
                        @elseif($permohonan->kendaraanVendor)
                            <p class="font-bold text-gray-800">{{ $permohonan->kendaraanVendor->nama_kendaraan }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $permohonan->kendaraanVendor->nama_vendor }}</p>
                            @if ($permohonan->kendaraanVendor->plat_nomor)
                                <p class="text-xs font-mono text-gray-400">
                                    {{ $permohonan->kendaraanVendor->plat_nomor }}
                                </p>
                            @endif
                            <span
                                class="inline-block mt-1.5 text-[10px] font-bold text-orange-700 bg-orange-50 border border-orange-200 px-2 py-0.5 rounded">
                                Vendor Luar
                            </span>
                        @else
                            <p class="text-sm text-gray-400 italic">Menunggu alokasi</p>
                        @endif

                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Pengemudi</p>
                            @if ($permohonan->pengemudi)
                                <p class="font-semibold text-gray-800 text-sm">
                                    {{ $permohonan->pengemudi->nama_pengemudi }}</p>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $permohonan->pengemudi->kontak) }}"
                                    target="_blank"
                                    class="text-xs text-green-600 hover:underline flex items-center gap-1 mt-0.5">
                                    <i class="bi bi-whatsapp"></i>{{ $permohonan->pengemudi->kontak }}
                                </a>
                            @else
                                <p class="text-sm text-gray-500">Tanpa pengemudi (lepas kunci)</p>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            {{-- ── ANGGARAN, CATATAN & DOKUMEN ── --}}
            @if ($permohonan->anggaran_diajukan || $permohonan->catatan_pemohon || $permohonan->file_surat_penugasan)
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 bg-gray-50 flex items-center gap-2">
                        <i class="bi bi-card-list text-blue-600"></i>
                        <h4 class="font-bold text-gray-800 text-sm">Informasi Tambahan</h4>
                    </div>
                    <div class="p-5 grid grid-cols-1 sm:grid-cols-3 gap-5">
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Perkiraan Anggaran
                            </p>
                            @if ($permohonan->anggaran_diajukan)
                                <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">
                                    {{ $permohonan->anggaran_diajukan }}</p>
                            @else
                                <p class="text-sm text-gray-400 italic">Tidak diisi (Non-Dinas / ditanggung pribadi)
                                </p>
                            @endif
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Catatan Pemohon
                            </p>
                            @if ($permohonan->catatan_pemohon)
                                <div class="bg-amber-50 border border-amber-200 rounded-lg p-3">
                                    <p class="text-sm text-amber-800 leading-relaxed">
                                        {{ $permohonan->catatan_pemohon }}
                                    </p>
                                </div>
                            @else
                                <p class="text-sm text-gray-400 italic">Tidak ada catatan</p>
                            @endif
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Surat Penugasan
                            </p>
                            @if ($permohonan->file_surat_penugasan)
                                <a href="{{ asset('storage/' . $permohonan->file_surat_penugasan) }}" target="_blank"
                                    class="inline-flex items-center gap-2 text-xs font-bold text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200 px-3 py-2 rounded-lg transition">
                                    <i class="bi bi-file-earmark-pdf text-base"></i> Lihat Dokumen
                                </a>
                            @else
                                <p class="text-sm text-gray-400 italic">Tidak ada</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            {{-- ── KEUANGAN & LPJ (tampil jika ada data keuangan) ── --}}
            @if ($hasFinanceData)
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 bg-gray-50 flex items-center gap-2">
                        <i class="bi bi-cash-stack text-blue-600"></i>
                        <h4 class="font-bold text-gray-800 text-sm">Rekapitulasi Keuangan</h4>
                    </div>
                    <div class="p-5 grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <div>
                            <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">RAB Disetujui
                            </p>
                            <p class="font-black text-gray-800 text-lg">
                                Rp {{ number_format($permohonan->rab_disetujui ?? 0, 0, ',', '.') }}
                            </p>
                            @if ($permohonan->mekanisme_pembayaran)
                                <span
                                    class="text-[10px] font-bold text-gray-500 bg-gray-100 px-2 py-0.5 rounded mt-1 inline-block">
                                    {{ $permohonan->mekanisme_pembayaran }}
                                </span>
                            @endif
                        </div>
                        @if ($permohonan->biaya_aktual !== null)
                            @php $selisih = ($permohonan->rab_disetujui ?? 0) - $permohonan->biaya_aktual; @endphp
                            <div>
                                <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">Biaya
                                    Aktual
                                </p>
                                <p class="font-black text-gray-800 text-lg">
                                    Rp {{ number_format($permohonan->biaya_aktual, 0, ',', '.') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">Selisih
                                </p>
                                <p
                                    class="font-black text-lg {{ $selisih > 0 ? 'text-orange-600' : ($selisih < 0 ? 'text-red-600' : 'text-emerald-600') }}">
                                    Rp {{ number_format(abs($selisih), 0, ',', '.') }}
                                </p>
                                <p
                                    class="text-[10px] {{ $selisih > 0 ? 'text-orange-500' : ($selisih < 0 ? 'text-red-500' : 'text-emerald-500') }} font-semibold">
                                    {{ $selisih > 0 ? 'Harus dikembalikan' : ($selisih < 0 ? 'Kelebihan pakai' : 'Pas / Sesuai') }}
                                </p>
                            </div>
                        @endif
                        <div class="flex flex-col gap-2 justify-center">
                            @if ($permohonan->bukti_lpj)
                                <a href="{{ asset('storage/' . $permohonan->bukti_lpj) }}" target="_blank"
                                    class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200 px-3 py-2 rounded-lg transition">
                                    <i class="bi bi-receipt"></i> Bukti LPJ
                                </a>
                            @endif
                            @if ($permohonan->bukti_pengembalian)
                                <a href="{{ asset('storage/' . $permohonan->bukti_pengembalian) }}" target="_blank"
                                    class="inline-flex items-center gap-1.5 text-xs font-bold text-orange-700 bg-orange-50 hover:bg-orange-100 border border-orange-200 px-3 py-2 rounded-lg transition">
                                    <i class="bi bi-cash-coin"></i> Bukti Pengembalian
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            {{-- ── TIMELINE PERJALANAN (tampil jika ada data waktu aktual) ── --}}
            @if ($hasTravelTime)
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 bg-gray-50 flex items-center gap-2">
                        <i class="bi bi-clock-history text-blue-600"></i>
                        <h4 class="font-bold text-gray-800 text-sm">Timeline Perjalanan</h4>
                    </div>
                    <div class="p-5">
                        <ol class="relative border-l-2 border-gray-200 ml-3 space-y-0">
                            @foreach ([[$permohonan->waktu_serah_terima, 'Serah Terima Kunci oleh SPSI', 'bi-key-fill', 'bg-yellow-100 text-yellow-600'], [$permohonan->waktu_mulai_perjalanan, 'Perjalanan Dimulai', 'bi-play-fill', 'bg-teal-100 text-teal-600'], [$permohonan->waktu_kembali_aktual, 'Dilaporkan Sudah Kembali', 'bi-arrow-return-left', 'bg-indigo-100 text-indigo-600']] as [$waktu, $label, $icon, $style])
                                @if ($waktu)
                                    <li class="ml-6 pb-5 last:pb-0">
                                        <span
                                            class="absolute -left-[13px] w-6 h-6 rounded-full {{ $style }} flex items-center justify-center ring-4 ring-white">
                                            <i class="bi {{ $icon }} text-xs"></i>
                                        </span>
                                        <p class="text-sm font-semibold text-gray-700">{{ $label }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5">
                                            {{ \Carbon\Carbon::parse($waktu)->translatedFormat('l, d M Y — H:i') }} WIB
                                            <span
                                                class="ml-1">({{ \Carbon\Carbon::parse($waktu)->diffForHumans() }})</span>
                                        </p>
                                    </li>
                                @endif
                            @endforeach
                        </ol>
                    </div>
                </div>
            @endif

            {{-- ── ACTION BLOCK (satu blok per status+role) ── --}}
            @if ($actionType)
                <div
                    class="bg-white rounded-xl border shadow-sm overflow-hidden
                    @if ($actionType === 'mulai') border-yellow-300
                    @elseif($actionType === 'lapor_kembali')  border-teal-300
                    @elseif($actionType === 'konfirmasi_kembali') border-indigo-300
                    @elseif($actionType === 'selesaikan') border-purple-300
                    @elseif($actionType === 'pengembalian') border-orange-300
                    @elseif($actionType === 'verifikasi') border-blue-300 @endif">

                    {{-- Header action --}}
                    <div
                        class="px-5 py-4 flex items-center gap-3
                        @if ($actionType === 'mulai') bg-yellow-50
                        @elseif($actionType === 'lapor_kembali')   bg-teal-50
                        @elseif($actionType === 'konfirmasi_kembali') bg-indigo-50
                        @elseif($actionType === 'selesaikan')     bg-purple-50
                        @elseif($actionType === 'pengembalian')   bg-orange-50
                        @elseif($actionType === 'verifikasi')     bg-blue-50 @endif">
                        <i
                            class="bi text-xl
                            @if ($actionType === 'mulai') bi-play-circle-fill text-yellow-500
                            @elseif($actionType === 'lapor_kembali')   bi-arrow-return-left text-teal-500
                            @elseif($actionType === 'konfirmasi_kembali') bi-check2-circle text-indigo-500
                            @elseif($actionType === 'selesaikan')     bi-clipboard2-check text-purple-500
                            @elseif($actionType === 'pengembalian')   bi-cash-coin text-orange-500
                            @elseif($actionType === 'verifikasi')     bi-patch-check-fill text-blue-500 @endif"></i>
                        <div>
                            <h4 class="font-bold text-gray-800 text-sm">
                                @if ($actionType === 'mulai')
                                    Kunci Sudah Diterima — Mulai Perjalanan
                                @elseif($actionType === 'lapor_kembali')
                                    Perjalanan Aktif — Laporkan Kepulangan
                                @elseif($actionType === 'konfirmasi_kembali')
                                    Pemohon Melaporkan Sudah Kembali
                                @elseif($actionType === 'selesaikan')
                                    Kendaraan Kembali — Lengkapi Laporan
                                @elseif($actionType === 'pengembalian')
                                    Terdapat Sisa Dana yang Harus Dikembalikan
                                @elseif($actionType === 'verifikasi')
                                    Bukti Pengembalian Diunggah Pemohon
                                @endif
                            </h4>
                            <p class="text-xs text-gray-500 mt-0.5">
                                @if ($actionType === 'mulai')
                                    Pastikan kunci fisik sudah dipegang, lalu tekan tombol di bawah untuk memulai
                                    perjalanan
                                    secara resmi.
                                @elseif($actionType === 'lapor_kembali')
                                    Setelah tiba dan kendaraan sudah diserahkan kembali ke SPSI, tekan tombol
                                    konfirmasi.
                                @elseif($actionType === 'konfirmasi_kembali')
                                    Periksa kondisi fisik kendaraan, lalu konfirmasi penerimaan.
                                @elseif($actionType === 'selesaikan')
                                    Isi biaya aktual dan unggah bukti pengeluaran untuk menutup tiket ini.
                                @elseif($actionType === 'pengembalian')
                                    Unggah bukti transfer pengembalian sisa dana ke rekening instansi.
                                @elseif($actionType === 'verifikasi')
                                    Cocokkan nominal bukti dengan selisih RAB, lalu tutup tiket.
                                @endif
                            </p>
                        </div>
                    </div>

                    {{-- Body action --}}
                    <div class="p-5">

                        {{-- MULAI PERJALANAN --}}
                        @if ($actionType === 'mulai')
                            @if ($permohonan->waktu_serah_terima)
                                <p class="text-xs text-gray-500 mb-4 flex items-center gap-1.5">
                                    <i class="bi bi-clock text-gray-400"></i>
                                    Kunci diserahkan:
                                    <strong>{{ \Carbon\Carbon::parse($permohonan->waktu_serah_terima)->format('d M Y, H:i') }}</strong>
                                </p>
                            @endif
                            <form action="{{ route('permohonan.mulai_perjalanan', $permohonan->id) }}"
                                method="POST">
                                @csrf @method('PUT')
                                <button type="button"
                                    onclick="customConfirm({ title: 'Mulai Perjalanan', message: 'Konfirmasi: kunci sudah dipegang dan siap berangkat?', confirmText: 'Ya, Mulai Perjalanan' }, () => this.closest('form').submit())"
                                    class="inline-flex items-center gap-2 bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2.5 px-6 rounded-xl transition shadow-sm text-sm">
                                    <i class="bi bi-play-fill"></i> Mulai Perjalanan Sekarang
                                </button>
                            </form>

                            {{-- LAPOR KEMBALI --}}
                        @elseif($actionType === 'lapor_kembali')
                            @if ($permohonan->waktu_mulai_perjalanan)
                                <p class="text-xs text-gray-500 mb-4 flex items-center gap-1.5">
                                    <i class="bi bi-play-circle text-teal-400"></i>
                                    Dimulai:
                                    <strong>{{ \Carbon\Carbon::parse($permohonan->waktu_mulai_perjalanan)->format('d M Y, H:i') }}</strong>
                                    ({{ \Carbon\Carbon::parse($permohonan->waktu_mulai_perjalanan)->diffForHumans() }})
                                </p>
                            @endif
                            <form action="{{ route('permohonan.lapor_kembali', $permohonan->id) }}" method="POST">
                                @csrf @method('PUT')
                                <button type="button"
                                    onclick="customConfirm({ title: 'Lapor Kembali', message: 'Konfirmasi: perjalanan selesai dan kendaraan sudah diserahkan kembali ke SPSI?', confirmText: 'Ya, Sudah Kembali' }, () => this.closest('form').submit())"
                                    class="inline-flex items-center gap-2 bg-teal-600 hover:bg-teal-700 text-white font-bold py-2.5 px-6 rounded-xl transition shadow-sm text-sm">
                                    <i class="bi bi-arrow-return-left"></i> Konfirmasi Saya Sudah Kembali
                                </button>
                            </form>

                            {{-- KONFIRMASI KEMBALI (SPSI) --}}
                        @elseif($actionType === 'konfirmasi_kembali')
                            @if ($permohonan->waktu_kembali_aktual)
                                <p class="text-xs text-gray-500 mb-4 flex items-center gap-1.5">
                                    <i class="bi bi-clock text-indigo-400"></i>
                                    Dilaporkan:
                                    <strong>{{ \Carbon\Carbon::parse($permohonan->waktu_kembali_aktual)->format('d M Y, H:i') }}</strong>
                                    ({{ \Carbon\Carbon::parse($permohonan->waktu_kembali_aktual)->diffForHumans() }})
                                </p>
                            @endif
                            <form action="{{ route('permohonan.konfirmasi_kembali', $permohonan->id) }}"
                                method="POST">
                                @csrf @method('PUT')
                                <button type="button"
                                    onclick="customConfirm({ title: 'Konfirmasi Penerimaan', message: 'Konfirmasi kendaraan sudah kembali dan dalam kondisi baik?', confirmText: 'Ya, Konfirmasi' }, () => this.closest('form').submit())"
                                    class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded-xl transition shadow-sm text-sm">
                                    <i class="bi bi-check2-circle"></i> Konfirmasi Kendaraan Sudah Kembali
                                </button>
                            </form>

                            {{-- SELESAIKAN + LPJ --}}
                        @elseif($actionType === 'selesaikan')
                            <form action="{{ route('permohonan.selesai', $permohonan->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf @method('PUT')

                                @if ($permohonan->kategori_kegiatan !== 'Non SITH')
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                                Total Biaya Aktual (Rp) <span class="text-red-500">*</span>
                                            </label>
                                            <input type="number" name="biaya_aktual" required min="0"
                                                placeholder="cth: 150000"
                                                class="w-full border-gray-300 focus:border-purple-500 focus:ring-purple-500 rounded-lg shadow-sm text-sm transition">
                                            <p class="text-xs text-gray-400 mt-1">Tanpa titik pemisah ribuan</p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                                Bukti Pengeluaran (LPJ) <span class="text-red-500">*</span>
                                            </label>
                                            <input type="file" name="bukti_lpj" required
                                                accept=".jpg,.jpeg,.png,.pdf"
                                                class="w-full border border-gray-300 rounded-lg p-2 text-sm bg-gray-50 file:mr-3 file:border-0 file:bg-purple-50 file:text-purple-700 file:font-bold file:text-xs file:px-3 file:py-1 file:rounded transition">
                                            <p class="text-xs text-gray-400 mt-1">JPG, PNG, PDF — Maks. 2 MB</p>
                                        </div>
                                    </div>
                                @else
                                    <div
                                        class="bg-slate-50 border border-slate-200 rounded-lg p-4 mb-5 flex items-start gap-2">
                                        <i class="bi bi-info-circle text-blue-500 flex-shrink-0 mt-0.5"></i>
                                        <p class="text-sm text-gray-600">Kegiatan Non-Dinas — tidak perlu mengisi biaya
                                            atau mengunggah LPJ. Klik tombol di bawah untuk menutup tiket.</p>
                                    </div>
                                @endif

                                <button type="button"
                                    onclick="customConfirm({ title: 'Selesaikan Perjalanan', message: 'Yakin menutup tiket ini? Pastikan semua data sudah benar.', confirmText: 'Ya, Selesaikan', isDanger: true }, () => this.closest('form').submit())"
                                    class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white font-bold py-2.5 px-6 rounded-xl transition shadow-sm text-sm">
                                    <i class="bi bi-check2-square"></i> Selesaikan & Tutup Perjalanan
                                </button>
                            </form>

                            {{-- UPLOAD BUKTI PENGEMBALIAN --}}
                        @elseif($actionType === 'pengembalian')
                            @php $sisaDana = ($permohonan->rab_disetujui ?? 0) - ($permohonan->biaya_aktual ?? 0); @endphp
                            <div
                                class="grid grid-cols-3 gap-3 bg-orange-50 border border-orange-200 rounded-lg p-4 mb-5 text-center text-sm">
                                <div>
                                    <p class="text-xs text-gray-500">RAB Diberikan</p>
                                    <p class="font-black text-gray-800">Rp
                                        {{ number_format($permohonan->rab_disetujui, 0, ',', '.') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Biaya Aktual</p>
                                    <p class="font-black text-gray-800">Rp
                                        {{ number_format($permohonan->biaya_aktual, 0, ',', '.') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Wajib Dikembalikan</p>
                                    <p class="font-black text-red-600 text-base">Rp
                                        {{ number_format($sisaDana, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            <form action="{{ route('permohonan.submit_pengembalian', $permohonan->id) }}"
                                method="POST" enctype="multipart/form-data"
                                class="flex flex-col sm:flex-row items-start gap-3">
                                @csrf @method('PUT')
                                <div class="flex-1 w-full">
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                        Bukti Transfer / Tanda Terima <span class="text-red-500">*</span>
                                    </label>
                                    <input type="file" name="bukti_pengembalian" required accept=".jpg,.png,.pdf"
                                        class="w-full border border-gray-300 rounded-lg p-2 text-sm bg-white file:mr-3 file:border-0 file:bg-orange-50 file:text-orange-700 file:font-bold file:text-xs file:px-3 file:py-1 file:rounded">
                                </div>
                                <div class="sm:mt-7">
                                    <button type="submit"
                                        class="inline-flex items-center gap-2 bg-orange-600 hover:bg-orange-700 text-white font-bold py-2.5 px-5 rounded-xl transition shadow-sm text-sm whitespace-nowrap">
                                        <i class="bi bi-upload"></i> Kirim Bukti
                                    </button>
                                </div>
                            </form>

                            {{-- VERIFIKASI PENGEMBALIAN (Keuangan) --}}
                        @elseif($actionType === 'verifikasi')
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div>
                                    <p class="text-sm text-gray-600 mb-2">Bukti pengembalian dana sudah diunggah oleh
                                        pemohon.</p>
                                    <a href="{{ asset('storage/' . $permohonan->bukti_pengembalian) }}"
                                        target="_blank"
                                        class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200 px-3 py-2 rounded-lg transition">
                                        <i class="bi bi-eye text-base"></i> Lihat Bukti Transfer
                                    </a>
                                </div>
                                <form action="{{ route('permohonan.verifikasi_pengembalian', $permohonan->id) }}"
                                    method="POST">
                                    @csrf @method('PUT')
                                    <button type="button"
                                        onclick="customConfirm({ title: 'Verifikasi Pengembalian', message: 'Verifikasi dan tutup tiket ini? Pastikan bukti transfer sudah sesuai.', confirmText: 'Ya, Verifikasi' }, () => this.closest('form').submit())"
                                        class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-xl transition shadow-sm text-sm whitespace-nowrap">
                                        <i class="bi bi-check-all"></i> Verifikasi & Tutup Tiket
                                    </button>
                                </form>
                            </div>
                        @endif

                    </div>
                </div>
            @endif

            {{-- Info status akhir (no action needed) --}}
            @if (!$actionType && in_array($status, [StatusPermohonan::SELESAI, StatusPermohonan::DITOLAK]))
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 text-center">
                    @if ($status === StatusPermohonan::SELESAI)
                        <i class="bi bi-patch-check-fill text-3xl text-emerald-500 block mb-2"></i>
                        <p class="font-bold text-gray-700">Perjalanan Selesai</p>
                        <p class="text-sm text-gray-400 mt-1">Semua proses administrasi telah tuntas. Tiket ditutup.
                        </p>
                    @else
                        <i class="bi bi-x-circle-fill text-3xl text-red-400 block mb-2"></i>
                        <p class="font-bold text-gray-700">Permohonan Ditolak</p>
                        <p class="text-sm text-gray-400 mt-1">Anda dapat membuat pengajuan baru jika diperlukan.</p>
                        @if ($isOwner)
                            <a href="{{ route('permohonan.create') }}"
                                class="inline-flex items-center gap-2 mt-3 text-sm font-bold text-blue-600 hover:text-blue-800 transition">
                                <i class="bi bi-plus-circle"></i> Buat Pengajuan Baru
                            </a>
                        @endif
                    @endif
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
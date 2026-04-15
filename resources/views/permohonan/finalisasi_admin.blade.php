<x-app-layout>
    <div class="py-6 bg-slate-50 min-h-screen">
        <div class="w-full px-4 sm:px-6 lg:px-8">

            {{-- Tombol Kembali --}}
            <div class="mb-5">
                <a href="{{ route('admin.finalisasi') }}" class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-blue-600 transition bg-white border border-gray-200 px-3 py-2 rounded-lg shadow-sm">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>

            {{-- RANGKUMAN --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-5">
                <div class="px-4 sm:px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-purple-50 to-blue-50 flex items-center gap-2">
                    <i class="bi bi-clipboard2-data-fill text-purple-600"></i>
                    <h3 class="font-bold text-gray-800">Rangkuman Akhir Sebelum Penerbitan</h3>
                </div>
                <div class="divide-y divide-gray-100">
                    {{-- Kode Permohonan (baris pertama) --}}
                    <div class="px-4 sm:px-6 py-3.5">
                        <span class="text-sm text-gray-500 flex items-center gap-2 mb-1">
                            <i class="bi bi-upc-scan text-gray-400"></i>Kode Pengajuan
                        </span>
                        <p class="text-sm font-black text-blue-700 break-words">
                            @if ($permohonan->kode_permohonan)
                                {{ $permohonan->kode_permohonan }}
                            @else
                                <span class="text-gray-400 italic">—</span>
                            @endif
                        </p>
                    </div>

                    {{-- Pemohon --}}
                    <div class="px-4 sm:px-6 py-3.5">
                        <span class="text-sm text-gray-500 flex items-center gap-2 mb-1">
                            <i class="bi bi-person text-gray-400"></i>Pemohon
                        </span>
                        <p class="text-sm font-semibold text-gray-800 break-words">{{ $permohonan->nama_pic }}</p>
                    </div>

                    {{-- Tujuan --}}
                    <div class="px-4 sm:px-6 py-3.5">
                        <span class="text-sm text-gray-500 flex items-center gap-2 mb-1">
                            <i class="bi bi-geo-alt text-gray-400"></i>Tujuan
                        </span>
                        <p class="text-sm font-semibold text-gray-800 break-words">{{ $permohonan->tujuan }}</p>
                    </div>

                    {{-- Kategori Dana --}}
                    <div class="px-4 sm:px-6 py-3.5">
                        <span class="text-sm text-gray-500 flex items-center gap-2 mb-1">
                            <i class="bi bi-tag text-gray-400"></i>Kategori Kegiatan
                        </span>
                        <p class="text-sm font-semibold text-gray-800 break-words">{{ $permohonan->kategori_kegiatan ?? '—' }}</p>
                    </div>

                    {{-- Anggaran Diajukan --}}
                    <div class="px-4 sm:px-6 py-3.5">
                        <span class="text-sm text-gray-500 flex items-center gap-2 mb-1">
                            <i class="bi bi-wallet text-gray-400"></i>Anggaran Diajukan
                        </span>
                        <p class="text-sm font-semibold text-gray-800 break-words">
                            Rp {{ number_format((float) $permohonan->anggaran_diajukan, 0, ',', '.') }}
                        </p>
                    </div>

                    {{-- Kendaraan --}}
                    <div class="px-4 sm:px-6 py-3.5">
                        <span class="text-sm text-gray-500 flex items-center gap-2 mb-1">
                            <i class="bi bi-car-front text-gray-400"></i>Kendaraan
                        </span>
                        <div class="text-sm font-semibold text-gray-800">
                            @if ($permohonan->kendaraan_id && $permohonan->kendaraan)
                                <p class="break-words">{{ $permohonan->kendaraan->nama_kendaraan }}</p>
                                <p class="text-xs font-mono text-gray-500 mt-0.5">{{ $permohonan->kendaraan->plat_nomor }}</p>
                            @elseif($permohonan->kendaraanVendor)
                                <p class="break-words">{{ $permohonan->kendaraanVendor->nama_kendaraan }}</p>
                                @if($permohonan->kendaraanVendor->plat_nomor)
                                    <p class="text-xs font-mono text-gray-500 mt-0.5">{{ $permohonan->kendaraanVendor->plat_nomor }}</p>
                                @endif
                                <span class="text-[10px] font-bold text-orange-600 bg-orange-50 border border-orange-200 px-1 rounded inline-block mt-1">VENDOR</span>
                            @else
                                <p class="text-gray-400 italic">—</p>
                            @endif
                        </div>
                    </div>

                    {{-- Pengemudi --}}
                    <div class="px-4 sm:px-6 py-3.5">
                        <span class="text-sm text-gray-500 flex items-center gap-2 mb-1">
                            <i class="bi bi-person-badge text-gray-400"></i>Pengemudi
                        </span>
                        <p class="text-sm font-semibold text-gray-800 break-words">{{ $permohonan->pengemudi->nama_pengemudi ?? 'Tanpa Pengemudi' }}</p>
                    </div>

                    {{-- RAB Keuangan --}}
                    <div class="px-4 sm:px-6 py-3.5">
                        <span class="text-sm text-gray-500 flex items-center gap-2 mb-1">
                            <i class="bi bi-cash-coin text-gray-400"></i>RAB Keuangan
                        </span>
                        <p class="text-sm font-bold text-gray-800 break-words">
                            @if ($permohonan->kategori_kegiatan === 'Non SITH')
                                <span class="text-gray-400 italic">Rp 0 <br> Non - Dinas SITH</span>
                            @else
                                Rp {{ number_format($permohonan->rab_disetujui ?? 0, 0, ',', '.') }}
                            @endif
                        </p>
                    </div>

                    {{-- Mekanisme Bayar --}}
                    <div class="px-4 sm:px-6 py-3.5">
                        <span class="text-sm text-gray-500 flex items-center gap-2 mb-1">
                            <i class="bi bi-credit-card text-gray-400"></i>Mekanisme Bayar
                        </span>
                        <p class="text-sm font-semibold text-gray-800 break-words">
                            @if ($permohonan->mekanisme_pembayaran)
                                {{ $permohonan->mekanisme_pembayaran }}
                            @elseif($permohonan->kategori_kegiatan === 'Non SITH')
                                <span class="text-xs font-bold text-orange-700 bg-orange-50 border border-orange-200 px-2 py-0.5 rounded">Ditanggung Pemohon</span>
                            @else
                                <span class="text-gray-400 italic">—</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            {{-- ACTION --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-4 sm:px-6 py-4 border-b border-gray-100 bg-slate-50">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2 text-sm">
                        <i class="bi bi-send-check-fill text-purple-600"></i>
                        Konfirmasi Penerbitan
                    </h3>
                </div>
                <div class="p-4 sm:p-6">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-5 flex items-start gap-3">
                        <i class="bi bi-info-circle-fill text-blue-500 flex-shrink-0 mt-0.5"></i>
                        <p class="text-sm text-blue-700">Dengan menerbitkan dokumen ini, pengguna akan menerima
                            notifikasi bahwa permohonannya telah <strong>DISETUJUI</strong>.</p>
                    </div>
                    <form action="{{ route('permohonan.finalisasi_admin_submit', $permohonan->id) }}" method="POST">
                        @csrf @method('PUT')
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-6 rounded-xl shadow-sm transition text-sm">
                            <i class="bi bi-patch-check-fill text-lg"></i>
                            Terbitkan
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
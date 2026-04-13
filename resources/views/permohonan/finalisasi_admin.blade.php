<x-app-layout>
    <div class="py-6 bg-slate-50 min-h-screen">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex items-center gap-2 text-sm text-gray-500 mb-5">
                <a href="{{ route('admin.finalisasi') }}" class="hover:text-blue-600 transition flex items-center gap-1">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <span class="text-gray-300">/</span>
                <span class="text-gray-700 font-medium">Finalisasi Penerbitan</span>
                @if($permohonan->kode_permohonan)
                    <span class="text-gray-300">/</span>
                    <span class="font-black text-blue-700 bg-blue-50 border border-blue-200 px-2 py-0.5 rounded-md text-xs tracking-widest">{{ $permohonan->kode_permohonan }}</span>
                @endif
            </div>

            {{-- RANGKUMAN --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-5">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-purple-50 to-blue-50 flex items-center gap-2">
                    <i class="bi bi-clipboard2-data-fill text-purple-600"></i>
                    <h3 class="font-bold text-gray-800">Rangkuman Akhir Sebelum Penerbitan</h3>
                </div>
                <div class="divide-y divide-gray-100">
                    @foreach([
                        ['Pemohon',          $permohonan->nama_pic . ' — ' . $permohonan->tujuan, 'bi-person'],
                        ['Kategori Dana',    $permohonan->kategori_kegiatan ?? '—',                'bi-tag'],
                        ['Anggaran Diajukan','Rp ' . number_format($permohonan->anggaran_diajukan, 0, ',', '.'), 'bi-wallet'],
                    ] as [$lbl, $val, $icon])
                        <div class="px-6 py-3.5 flex items-center justify-between">
                            <span class="text-sm text-gray-500 flex items-center gap-2"><i class="bi {{ $icon }} text-gray-400"></i>{{ $lbl }}</span>
                            <span class="text-sm font-semibold text-gray-800">{{ $val }}</span>
                        </div>
                    @endforeach

                    {{-- Kendaraan: gunakan relasi, bukan kolom string lama --}}
                    <div class="px-6 py-3.5 flex items-center justify-between">
                        <span class="text-sm text-gray-500 flex items-center gap-2"><i class="bi bi-car-front text-gray-400"></i>Kendaraan</span>
                        <span class="text-sm font-semibold text-gray-800">
                            @if($permohonan->kendaraan_id && $permohonan->kendaraan)
                                {{ $permohonan->kendaraan->nama_kendaraan }} ({{ $permohonan->kendaraan->plat_nomor }})
                            @elseif($permohonan->kendaraanVendor)
                                {{ $permohonan->kendaraanVendor->nama_kendaraan }}
                                <span class="text-[10px] font-bold text-orange-600 bg-orange-50 border border-orange-200 px-1 rounded ml-1">VENDOR</span>
                            @else
                                <span class="text-gray-400 italic">—</span>
                            @endif
                        </span>
                    </div>

                    <div class="px-6 py-3.5 flex items-center justify-between">
                        <span class="text-sm text-gray-500 flex items-center gap-2"><i class="bi bi-person-badge text-gray-400"></i>Pengemudi</span>
                        <span class="text-sm font-semibold text-gray-800">{{ $permohonan->pengemudi->nama_pengemudi ?? 'Tanpa Pengemudi' }}</span>
                    </div>
                    <div class="px-6 py-3.5 flex items-center justify-between">
                        <span class="text-sm text-gray-500 flex items-center gap-2"><i class="bi bi-cash-coin text-gray-400"></i>RAB Keuangan</span>
                        <span class="text-sm font-bold text-gray-800">
                            @if($permohonan->kategori_kegiatan === 'Non SITH')
                                <span class="text-gray-400 italic">Rp 0 (Bypass)</span>
                            @else
                                Rp {{ number_format($permohonan->rab_disetujui ?? 0, 0, ',', '.') }}
                            @endif
                        </span>
                    </div>
                    <div class="px-6 py-3.5 flex items-center justify-between">
                        <span class="text-sm text-gray-500 flex items-center gap-2"><i class="bi bi-credit-card text-gray-400"></i>Mekanisme Bayar</span>
                        <span class="text-sm font-semibold text-gray-800">
                            @if($permohonan->mekanisme_pembayaran)
                                {{ $permohonan->mekanisme_pembayaran }}
                            @elseif($permohonan->kategori_kegiatan === 'Non SITH')
                                <span class="text-xs font-bold text-orange-700 bg-orange-50 border border-orange-200 px-2 py-0.5 rounded">Ditanggung Pemohon</span>
                            @else
                                <span class="text-gray-400 italic">—</span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            {{-- ACTION --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-slate-50">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2 text-sm">
                        <i class="bi bi-send-check-fill text-purple-600"></i>
                        Konfirmasi Penerbitan
                    </h3>
                </div>
                <div class="p-6">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-5 flex items-start gap-3">
                        <i class="bi bi-info-circle-fill text-blue-500 flex-shrink-0 mt-0.5"></i>
                        <p class="text-sm text-blue-700">Dengan menerbitkan dokumen ini, pengguna akan menerima notifikasi bahwa permohonannya telah <strong>DISETUJUI</strong> dan dapat mencetak Surat Perjalanan Jabatan (SPJ).</p>
                    </div>
                    <form action="{{ route('permohonan.finalisasi_admin_submit', $permohonan->id) }}" method="POST">
                        @csrf @method('PUT')
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-6 rounded-xl shadow-sm transition text-sm">
                            <i class="bi bi-patch-check-fill text-lg"></i>
                            Setujui & Terbitkan Surat ke Pengguna
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
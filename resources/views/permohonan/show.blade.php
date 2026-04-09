<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ url()->previous() }}" class="text-gray-400 hover:text-gray-600 transition">
                    <i class="bi bi-arrow-left text-lg"></i>
                </a>
                <h2 class="font-semibold text-xl text-gray-800">Detail Pengajuan</h2>
            </div>
            <div class="flex items-center gap-2">
                @if($permohonan->kode_permohonan)
                    <span class="font-black text-blue-700 bg-blue-50 border border-blue-200 px-3 py-1 rounded-lg text-sm tracking-widest">{{ $permohonan->kode_permohonan }}</span>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-6 bg-slate-50 min-h-screen">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">

            {{-- STATUS BAR --}}
            @php
                $statusConfig = match($permohonan->status_permohonan) {
                    'Selesai'                          => ['bg-emerald-50 border-emerald-200','text-emerald-700','bi-patch-check-fill text-emerald-500'],
                    'Disetujui'                        => ['bg-blue-50 border-blue-200','text-blue-700','bi-check-circle-fill text-blue-500'],
                    'Ditolak'                          => ['bg-red-50 border-red-200','text-red-700','bi-x-circle-fill text-red-500'],
                    'Menunggu Pengembalian Dana'        => ['bg-orange-50 border-orange-200','text-orange-700','bi-exclamation-triangle-fill text-orange-500'],
                    'Menunggu Verifikasi Pengembalian'  => ['bg-amber-50 border-amber-200','text-amber-700','bi-hourglass-split text-amber-500'],
                    default                            => ['bg-slate-50 border-slate-200','text-slate-700','bi-hourglass-split text-slate-400'],
                };
            @endphp
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm px-5 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl {{ explode(' ',$statusConfig[0])[0] }} border {{ explode(' ',$statusConfig[0])[1] }} flex items-center justify-center flex-shrink-0">
                        <i class="bi {{ explode(' ',$statusConfig[2])[0] }} {{ explode(' ',$statusConfig[2])[1] }} text-lg"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium">Status Permohonan</p>
                        <p class="font-black text-gray-900 text-sm">{{ $permohonan->status_permohonan }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 text-xs text-gray-400">
                    <span><i class="bi bi-clock mr-1"></i>Diajukan {{ $permohonan->created_at->diffForHumans() }}</span>
                    <span class="hidden sm:inline">·</span>
                    <span class="hidden sm:inline"><i class="bi bi-arrow-repeat mr-1"></i>Update {{ $permohonan->updated_at->diffForHumans() }}</span>
                    @if(in_array($permohonan->status_permohonan,['Disetujui','Menunggu Pengembalian Dana','Menunggu Verifikasi Pengembalian','Selesai']))
                        <a href="{{ route('permohonan.cetak', $permohonan->id) }}" target="_blank"
                           class="ml-2 inline-flex items-center gap-1 text-xs font-bold text-gray-700 bg-white hover:bg-gray-50 border border-gray-300 px-3 py-1.5 rounded-lg transition">
                            <i class="bi bi-printer"></i> Cetak SPJ
                        </a>
                    @endif
                </div>
            </div>

            {{-- BANNER: Pengembalian Dana --}}
            @if($permohonan->status_permohonan === 'Menunggu Pengembalian Dana')
                @php $sisaDana = $permohonan->rab_disetujui - $permohonan->biaya_aktual; @endphp
                <div class="bg-orange-50 border border-orange-200 rounded-xl p-5 shadow-sm">
                    <div class="flex items-start gap-3 mb-4">
                        <i class="bi bi-exclamation-triangle-fill text-orange-500 text-xl flex-shrink-0 mt-0.5"></i>
                        <div>
                            <h3 class="font-bold text-orange-900 text-base">Sisa Dana Wajib Dikembalikan</h3>
                            <p class="text-sm text-orange-700 mt-1">Terdapat sisa anggaran yang belum dikembalikan ke instansi.</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-3 bg-white rounded-lg p-4 border border-orange-200 mb-4 text-center">
                        <div>
                            <p class="text-xs text-gray-500">RAB Diberikan</p>
                            <p class="font-black text-gray-800 text-sm">Rp {{ number_format($permohonan->rab_disetujui,0,',','.') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Biaya Aktual</p>
                            <p class="font-black text-gray-800 text-sm">Rp {{ number_format($permohonan->biaya_aktual,0,',','.') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Harus Dikembalikan</p>
                            <p class="font-black text-red-600 text-base">Rp {{ number_format($sisaDana,0,',','.') }}</p>
                        </div>
                    </div>
                    @if(Auth::user()->role === 'pengguna' && Auth::id() === $permohonan->user_id)
                        <form action="{{ route('permohonan.submit_pengembalian', $permohonan->id) }}" method="POST" enctype="multipart/form-data"
                              class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                            @csrf @method('PUT')
                            <div class="flex-1 w-full">
                                <label class="text-xs font-bold text-orange-800 block mb-1">Bukti Transfer (JPG/PNG/PDF)</label>
                                <input type="file" name="bukti_pengembalian" required accept=".jpg,.png,.pdf"
                                    class="w-full bg-white border border-orange-300 rounded-lg p-2 text-sm text-gray-700 file:mr-3 file:border-0 file:bg-orange-50 file:text-orange-700 file:font-bold file:text-xs file:px-3 file:py-1 file:rounded">
                            </div>
                            <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white font-bold py-2.5 px-5 rounded-lg transition text-sm whitespace-nowrap">
                                <i class="bi bi-upload mr-1"></i> Kirim Bukti
                            </button>
                        </form>
                    @endif
                </div>
            @endif

            {{-- BANNER: Verifikasi Pengembalian (Keuangan) --}}
            @if($permohonan->status_permohonan === 'Menunggu Verifikasi Pengembalian' && Auth::user()->role === 'keuangan')
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-5 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h3 class="font-bold text-blue-900 flex items-center gap-2"><i class="bi bi-file-earmark-check text-blue-500"></i> Bukti Transfer Telah Diunggah</h3>
                        <a href="{{ asset('storage/' . $permohonan->bukti_pengembalian) }}" target="_blank"
                           class="inline-flex items-center gap-1.5 mt-2 text-xs font-bold text-blue-700 bg-white hover:bg-blue-50 border border-blue-200 px-3 py-1.5 rounded-lg transition">
                            <i class="bi bi-eye"></i> Lihat Bukti Transfer
                        </a>
                    </div>
                    <form action="{{ route('permohonan.verifikasi_pengembalian', $permohonan->id) }}" method="POST">
                        @csrf @method('PUT')
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-5 rounded-lg transition text-sm whitespace-nowrap">
                            <i class="bi bi-check-all mr-1"></i> Verifikasi & Tutup Tiket
                        </button>
                    </form>
                </div>
            @endif

            {{-- DETAIL CARDS --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

                {{-- KARTU 1: Info Kegiatan --}}
                <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="px-5 py-3.5 border-b border-gray-100 bg-slate-50 flex items-center gap-2">
                        <i class="bi bi-info-circle text-blue-600"></i>
                        <h4 class="font-bold text-gray-800 text-sm">Informasi Kegiatan</h4>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Nama PIC</p>
                                <p class="font-semibold text-gray-800">{{ $permohonan->nama_pic }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">{{ $permohonan->kontak_pic }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Kategori</p>
                                @if($permohonan->kategori_kegiatan)
                                    <span class="inline-block text-xs font-bold px-2.5 py-1 rounded-md border {{ $permohonan->kategori_kegiatan === 'Dinas SITH' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-gray-50 text-gray-600 border-gray-200' }}">{{ $permohonan->kategori_kegiatan }}</span>
                                @else
                                    <p class="text-gray-400 italic text-sm">Belum ditentukan</p>
                                @endif
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Rute Perjalanan</p>
                                <p class="text-sm text-gray-700"><i class="bi bi-geo-alt text-gray-400 mr-1"></i>{{ $permohonan->titik_jemput }}</p>
                                <p class="text-sm text-gray-500"><i class="bi bi-arrow-down text-gray-300 mr-1"></i>{{ $permohonan->tujuan }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Jadwal</p>
                                <p class="text-sm text-gray-700 font-medium">{{ \Carbon\Carbon::parse($permohonan->waktu_berangkat)->format('d M Y, H:i') }}</p>
                                <p class="text-xs text-gray-500">s/d {{ \Carbon\Carbon::parse($permohonan->waktu_kembali)->format('d M Y, H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Kebutuhan</p>
                                <p class="text-sm text-gray-700">{{ $permohonan->kendaraan_dibutuhkan }}</p>
                                <p class="text-xs text-gray-500"><i class="bi bi-people mr-0.5"></i>{{ $permohonan->jumlah_penumpang }} orang</p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Anggaran Diajukan</p>
                                <p class="text-sm font-bold text-gray-800">Rp {{ number_format($permohonan->anggaran_diajukan,0,',','.') }}</p>
                            </div>
                            @if($permohonan->catatan_pemohon)
                                <div class="sm:col-span-2">
                                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Catatan Pemohon</p>
                                    <div class="bg-slate-50 border border-slate-200 rounded-lg p-3 text-sm text-gray-700">{{ $permohonan->catatan_pemohon }}</div>
                                </div>
                            @endif
                            <div>
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Surat Penugasan</p>
                                @if($permohonan->file_surat_penugasan)
                                    <a href="{{ asset('storage/'.$permohonan->file_surat_penugasan) }}" target="_blank"
                                       class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200 px-3 py-1.5 rounded-lg transition">
                                        <i class="bi bi-file-earmark-pdf"></i> Lihat Dokumen
                                    </a>
                                @else
                                    <span class="text-gray-400 text-sm italic">Tidak ada</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- KARTU 2: Armada + Keuangan --}}
                <div class="space-y-4">
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-5 py-3.5 border-b border-gray-100 bg-slate-50 flex items-center gap-2">
                            <i class="bi bi-car-front text-blue-600"></i>
                            <h4 class="font-bold text-gray-800 text-sm">Armada</h4>
                        </div>
                        <div class="p-5 space-y-3">
                            <div>
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Kendaraan</p>
                                @if($permohonan->kendaraan_id)
                                    <p class="font-semibold text-gray-800 text-sm">{{ $permohonan->kendaraan->nama_kendaraan }}</p>
                                    <p class="text-xs text-gray-500">{{ $permohonan->kendaraan->plat_nomor }}</p>
                                @elseif($permohonan->kendaraanVendor)
                                    <p class="font-semibold text-gray-800 text-sm">{{ $permohonan->kendaraanVendor->nama_kendaraan }}</p>
                                    <span class="text-[10px] font-bold text-orange-600 bg-orange-50 border border-orange-200 px-1.5 py-0.5 rounded">VENDOR LUAR</span>
                                @else
                                    <p class="text-gray-400 italic text-sm">Menunggu alokasi</p>
                                @endif
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Pengemudi</p>
                                <p class="text-sm text-gray-700">{{ $permohonan->pengemudi->nama_pengemudi ?? 'Tanpa Pengemudi' }}</p>
                            </div>
                            @if($permohonan->rekomendasi_admin)
                                <div>
                                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Instruksi Admin</p>
                                    <p class="text-xs text-gray-600 bg-slate-50 border border-slate-200 rounded-lg p-2">{{ $permohonan->rekomendasi_admin }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-5 py-3.5 border-b border-gray-100 bg-slate-50 flex items-center gap-2">
                            <i class="bi bi-cash-stack text-blue-600"></i>
                            <h4 class="font-bold text-gray-800 text-sm">Keuangan & LPJ</h4>
                        </div>
                        <div class="p-5 space-y-3">
                            @if($permohonan->rab_disetujui)
                                <div>
                                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">RAB Disetujui</p>
                                    <p class="text-xl font-black text-gray-900">Rp {{ number_format($permohonan->rab_disetujui,0,',','.') }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $permohonan->mekanisme_pembayaran }}</p>
                                </div>
                                @if($permohonan->biaya_aktual)
                                    <div class="border-t border-gray-100 pt-3">
                                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Biaya Aktual</p>
                                        <p class="font-bold text-gray-800">Rp {{ number_format($permohonan->biaya_aktual,0,',','.') }}</p>
                                        @php $selisih = $permohonan->rab_disetujui - $permohonan->biaya_aktual; @endphp
                                        <p class="text-xs mt-1 {{ $selisih > 0 ? 'text-orange-600 font-bold' : 'text-emerald-600' }}">
                                            {{ $selisih > 0 ? '⚠ Sisa: Rp '.number_format($selisih,0,',','.') : '✓ Lunas / Sesuai' }}
                                        </p>
                                    </div>
                                @endif
                                @if($permohonan->bukti_lpj)
                                    <a href="{{ asset('storage/'.$permohonan->bukti_lpj) }}" target="_blank"
                                       class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200 px-3 py-1.5 rounded-lg transition">
                                        <i class="bi bi-file-earmark-text"></i> Lihat Bukti Nota
                                    </a>
                                @endif
                            @else
                                <p class="text-sm text-gray-400 italic">{{ $permohonan->kategori_kegiatan === 'Non SITH' ? 'Bypass Keuangan (Non-Dinas)' : 'Belum diproses' }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- FORM LPJ --}}
            @if($permohonan->status_permohonan === 'Disetujui' && Auth::user()->role === 'pengguna')
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="px-5 py-3.5 border-b border-gray-100 bg-slate-50 flex items-center gap-2">
                        <i class="bi bi-clipboard2-check text-blue-600"></i>
                        <h4 class="font-bold text-gray-800 text-sm">Selesaikan Perjalanan & Submit LPJ</h4>
                    </div>
                    <div class="p-5">
                        <form action="{{ route('permohonan.selesai', $permohonan->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf @method('PUT')
                            @if($permohonan->kategori_kegiatan !== 'Non SITH')
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-1.5">Total Biaya Aktual (Rp) <span class="text-red-500">*</span></label>
                                        <input type="number" name="biaya_aktual" required min="0"
                                            placeholder="Contoh: 150000"
                                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm transition">
                                        <p class="text-xs text-gray-400 mt-1">Masukkan angka tanpa titik/koma</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-1.5">Bukti Pengeluaran (Nota/Struk) <span class="text-red-500">*</span></label>
                                        <input type="file" name="bukti_lpj" required accept=".jpg,.png,.pdf"
                                            class="w-full border border-gray-300 rounded-lg p-2 text-sm bg-gray-50 file:mr-3 file:border-0 file:bg-blue-50 file:text-blue-700 file:font-bold file:text-xs file:px-3 file:py-1 file:rounded">
                                        <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG, atau PDF (maks. 2MB)</p>
                                    </div>
                                </div>
                            @else
                                <div class="bg-slate-50 border border-slate-200 rounded-lg p-4 mb-5">
                                    <p class="text-sm text-gray-600 flex items-start gap-2">
                                        <i class="bi bi-info-circle text-blue-500 mt-0.5 flex-shrink-0"></i>
                                        Kegiatan Non-Dinas tidak memerlukan laporan pengeluaran anggaran instansi. Silakan langsung tutup tiket ini.
                                    </p>
                                </div>
                            @endif
                            <button type="submit"
                                onclick="return confirm('Yakin ingin menutup tiket perjalanan ini? Pastikan data sudah benar.')"
                                class="inline-flex items-center gap-2 bg-gray-800 hover:bg-black text-white font-bold py-2.5 px-6 rounded-lg transition text-sm">
                                <i class="bi bi-check2-square"></i> Tutup & Selesaikan Perjalanan
                            </button>
                        </form>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
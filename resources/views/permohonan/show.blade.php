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
                @if ($permohonan->kode_permohonan)
                    <span
                        class="font-black text-blue-700 bg-blue-50 border border-blue-200 px-3 py-1 rounded-lg text-sm tracking-widest">{{ $permohonan->kode_permohonan }}</span>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-6 bg-slate-50 min-h-screen">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">

            {{-- STATUS BAR --}}
            @php
                $status = $permohonan->status_permohonan;
                $statusBg = $status->badgeClass();
                $statusIcon = 'bi-info-circle';
            @endphp
            <div
                class="bg-white rounded-xl border border-gray-200 shadow-sm px-5 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-xl border flex items-center justify-center flex-shrink-0 {{ $statusBg }}">
                        <i class="bi {{ $statusIcon }} text-lg"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium">Status Permohonan</p>
                        <p class="font-black text-gray-900 text-sm">{{ $status->value }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 text-xs text-gray-400 flex-wrap">
                    <span><i class="bi bi-clock mr-1"></i>Diajukan {{ $permohonan->created_at->diffForHumans() }}</span>
                    <span class="hidden sm:inline">·</span>
                    <span class="hidden sm:inline"><i class="bi bi-arrow-repeat mr-1"></i>Update
                        {{ $permohonan->updated_at->diffForHumans() }}</span>
                    @if ($status->canPrint())
                        <a href="{{ route('permohonan.cetak', $permohonan->id) }}" target="_blank"
                            class="ml-2 inline-flex items-center gap-1 text-xs font-bold text-gray-700 bg-white hover:bg-gray-50 border border-gray-300 px-3 py-1.5 rounded-lg transition">
                            <i class="bi bi-printer"></i> Cetak SPJ
                        </a>
                    @endif
                </div>
            </div>

            {{-- BANNER: Disetujui (Updated Namespace) --}}
            @if ($status === \App\Enums\StatusPermohonan::DISETUJUI)
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-5 shadow-sm flex items-start gap-3">
                    <i class="bi bi-key-fill text-blue-400 text-xl flex-shrink-0 mt-0.5"></i>
                    <div>
                        <h3 class="font-bold text-blue-900">Permohonan Disetujui!</h3>
                        <p class="text-sm text-blue-700 mt-1">Tim SPSI sedang mempersiapkan kendaraan. Anda akan
                            mendapat notifikasi saat kunci siap diserahkan.</p>
                    </div>
                </div>
            @endif

            {{-- BANNER: Menunggu Mulai Perjalanan (Updated Namespace) --}}
            @if ($status === \App\Enums\StatusPermohonan::MENUNGGU_MULAI_PERJALANAN)
                @if (Auth::user()->role === 'pengguna' && Auth::id() === $permohonan->user_id)
                    <div class="bg-yellow-50 border border-yellow-300 rounded-xl p-5 shadow-sm">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex items-start gap-3">
                                <i class="bi bi-key-fill text-yellow-500 text-xl flex-shrink-0 mt-0.5"></i>
                                <div>
                                    <h3 class="font-bold text-yellow-900">Kunci Kendaraan Sudah Diserahkan!</h3>
                                    <p class="text-sm text-yellow-800 mt-1">Pastikan Anda sudah memegang kunci fisik,
                                        lalu tekan tombol di bawah untuk memulai perjalanan secara resmi.</p>
                                    @if ($permohonan->waktu_serah_terima)
                                        <p class="text-xs text-yellow-700 mt-1.5">
                                            <i class="bi bi-clock mr-1"></i>
                                            Diserahkan:
                                            <strong>{{ \Carbon\Carbon::parse($permohonan->waktu_serah_terima)->format('d M Y, H:i') }}</strong>
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-3 mt-6">
                                @if ($permohonan->status_permohonan === \App\Enums\StatusPermohonan::MENUNGGU_MULAI_PERJALANAN)
                                    <form action="{{ route('permohonan.mulai_perjalanan', $permohonan->id) }}"
                                        method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit"
                                            class="bg-blue-600 text-white px-4 py-2 rounded-lg font-bold">
                                            Mulai Perjalanan
                                        </button>
                                    </form>
                                @endif

                                @if ($permohonan->status_permohonan->canPrint())
                                    <a href="{{ route('permohonan.cetak', $permohonan->id) }}" target="_blank"
                                        class="inline-flex items-center gap-2 bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg font-bold">
                                        <i class="bi bi-printer"></i> Cetak SPJ
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 shadow-sm flex items-start gap-3">
                        <i class="bi bi-info-circle-fill text-yellow-500 flex-shrink-0 mt-0.5"></i>
                        <p class="text-sm text-yellow-700">Kunci diserahkan
                            {{ $permohonan->waktu_serah_terima ? \Carbon\Carbon::parse($permohonan->waktu_serah_terima)->diffForHumans() : '—' }}.
                            Menunggu pengguna mengklik <strong>"Mulai Perjalanan"</strong>.</p>
                    </div>
                @endif
            @endif

            {{-- BANNER: Perjalanan Berlangsung (Updated Namespace) --}}
            @if ($status === \App\Enums\StatusPermohonan::PERJALANAN_BERLANGSUNG)
                @if (Auth::user()->role === 'pengguna' && Auth::id() === $permohonan->user_id)
                    <div class="bg-teal-50 border border-teal-200 rounded-xl p-5 shadow-sm">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex items-start gap-3">
                                <i class="bi bi-geo-alt-fill text-teal-500 text-xl flex-shrink-0 mt-0.5"></i>
                                <div>
                                    <h3 class="font-bold text-teal-900">Perjalanan Sedang Berlangsung</h3>
                                    <p class="text-sm text-teal-700 mt-1">Setelah perjalanan selesai dan kendaraan sudah
                                        dikembalikan ke SPSI, tekan tombol di bawah untuk memberi tahu tim.</p>
                                    @if ($permohonan->waktu_mulai_perjalanan)
                                        <p class="text-xs text-teal-600 mt-1.5">
                                            <i class="bi bi-play-circle mr-1"></i>
                                            Dimulai:
                                            <strong>{{ \Carbon\Carbon::parse($permohonan->waktu_mulai_perjalanan)->format('d M Y, H:i') }}</strong>
                                            ({{ \Carbon\Carbon::parse($permohonan->waktu_mulai_perjalanan)->diffForHumans() }})
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <form action="{{ route('permohonan.lapor_kembali', $permohonan->id) }}" method="POST">
                                @csrf @method('PUT')
                                <button type="submit"
                                    onclick="return confirm('Konfirmasi: Anda sudah kembali dan kendaraan sudah diserahkan ke SPSI?')"
                                    class="whitespace-nowrap inline-flex items-center gap-2 bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 px-6 rounded-xl transition text-sm shadow-md">
                                    <i class="bi bi-arrow-return-left text-lg"></i> Saya Sudah Kembali
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="bg-teal-50 border border-teal-200 rounded-xl p-4 shadow-sm flex items-start gap-3">
                        <i class="bi bi-geo-alt-fill text-teal-500 flex-shrink-0 mt-0.5"></i>
                        <div>
                            <p class="text-sm font-bold text-teal-800">Perjalanan Sedang Berlangsung</p>
                            <p class="text-xs text-teal-700 mt-0.5">
                                Dimulai
                                {{ $permohonan->waktu_mulai_perjalanan ? \Carbon\Carbon::parse($permohonan->waktu_mulai_perjalanan)->diffForHumans() : '—' }}.
                                Rencana kembali:
                                <strong>{{ \Carbon\Carbon::parse($permohonan->waktu_kembali)->format('d M Y, H:i') }}</strong>.
                            </p>
                        </div>
                    </div>
                @endif
            @endif

            {{-- BANNER: Menunggu Konfirmasi Kembali (Updated Namespace) --}}
            @if ($status === \App\Enums\StatusPermohonan::MENUNGGU_KONFIRMASI_KEMBALI)
                @if (Auth::user()->role === 'pengguna' && Auth::id() === $permohonan->user_id)
                    <div class="bg-indigo-50 border border-indigo-200 rounded-xl p-5 shadow-sm flex items-start gap-3">
                        <i class="bi bi-hourglass-split text-indigo-400 text-xl flex-shrink-0 mt-0.5 animate-spin"
                            style="animation-duration:3s"></i>
                        <div>
                            <h3 class="font-bold text-indigo-900">Menunggu Konfirmasi SPSI</h3>
                            <p class="text-sm text-indigo-700 mt-1">Laporan kembali Anda sudah diterima. Tim SPSI sedang
                                memeriksa kondisi kendaraan.</p>
                            @if ($permohonan->waktu_kembali_aktual)
                                <p class="text-xs text-indigo-600 mt-1.5">
                                    <i class="bi bi-clock mr-1"></i>
                                    Dilaporkan kembali:
                                    <strong>{{ \Carbon\Carbon::parse($permohonan->waktu_kembali_aktual)->format('d M Y, H:i') }}</strong>
                                </p>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="bg-indigo-50 border border-indigo-300 rounded-xl p-5 shadow-sm">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex items-start gap-3">
                                <i class="bi bi-arrow-return-left text-indigo-500 text-xl flex-shrink-0 mt-0.5"></i>
                                <div>
                                    <h3 class="font-bold text-indigo-900">Pemohon Melaporkan Sudah Kembali</h3>
                                    <p class="text-sm text-indigo-700 mt-1">
                                        <strong>{{ $permohonan->nama_pic }}</strong> melaporkan kendaraan sudah
                                        dikembalikan.
                                        Periksa kondisi fisik kendaraan, lalu klik tombol konfirmasi.
                                    </p>
                                    @if ($permohonan->waktu_kembali_aktual)
                                        <p class="text-xs text-indigo-600 mt-1.5">
                                            <i class="bi bi-clock mr-1"></i>
                                            Dilaporkan:
                                            <strong>{{ \Carbon\Carbon::parse($permohonan->waktu_kembali_aktual)->format('d M Y, H:i') }}</strong>
                                            ({{ \Carbon\Carbon::parse($permohonan->waktu_kembali_aktual)->diffForHumans() }})
                                        </p>
                                    @endif
                                </div>
                            </div>
                            @if (Auth::user()->role === 'spsi')
                                <form action="{{ route('permohonan.konfirmasi_kembali', $permohonan->id) }}"
                                    method="POST">
                                    @csrf @method('PUT')
                                    <button type="submit"
                                        onclick="return confirm('Konfirmasi kendaraan sudah kembali dan dalam kondisi baik?')"
                                        class="whitespace-nowrap inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-xl transition text-sm shadow-md">
                                        <i class="bi bi-check2-circle text-lg"></i> Konfirmasi Kendaraan Kembali
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endif
            @endif

            {{-- BANNER: Menunggu Penyelesaian (Updated Namespace) --}}
            @if ($status === \App\Enums\StatusPermohonan::MENUNGGU_PENYELESAIAN)
                <div class="bg-purple-50 border border-purple-200 rounded-xl p-4 shadow-sm flex items-start gap-3">
                    <i class="bi bi-check-circle-fill text-purple-500 text-xl flex-shrink-0 mt-0.5"></i>
                    <div>
                        <p class="text-sm font-bold text-purple-900">Kendaraan Sudah Dikonfirmasi Kembali oleh SPSI</p>
                        <p class="text-sm text-purple-700 mt-0.5">Silakan lengkapi laporan perjalanan di bawah untuk
                            menutup tiket ini.</p>
                    </div>
                </div>
            @endif

            {{-- BANNER: Pengembalian Dana (Updated Namespace) --}}
            @if ($status === \App\Enums\StatusPermohonan::MENUNGGU_PENGEMBALIAN_DANA)
                @php $sisaDana = $permohonan->rab_disetujui - $permohonan->biaya_aktual; @endphp
                <div class="bg-orange-50 border border-orange-200 rounded-xl p-5 shadow-sm">
                    <div class="flex items-start gap-3 mb-4">
                        <i class="bi bi-exclamation-triangle-fill text-orange-500 text-xl flex-shrink-0 mt-0.5"></i>
                        <div>
                            <h3 class="font-bold text-orange-900 text-base">Sisa Dana Wajib Dikembalikan</h3>
                            <p class="text-sm text-orange-700 mt-1">Terdapat sisa anggaran yang belum dikembalikan ke
                                instansi.</p>
                        </div>
                    </div>
                    <div
                        class="grid grid-cols-3 gap-3 bg-white rounded-lg p-4 border border-orange-200 mb-4 text-center">
                        <div>
                            <p class="text-xs text-gray-500">RAB Diberikan</p>
                            <p class="font-black text-gray-800 text-sm">Rp
                                {{ number_format($permohonan->rab_disetujui, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Biaya Aktual</p>
                            <p class="font-black text-gray-800 text-sm">Rp
                                {{ number_format($permohonan->biaya_aktual, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Harus Dikembalikan</p>
                            <p class="font-black text-red-600 text-base">Rp
                                {{ number_format($sisaDana, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    @if (Auth::user()->role === 'pengguna' && Auth::id() === $permohonan->user_id)
                        <form action="{{ route('permohonan.submit_pengembalian', $permohonan->id) }}" method="POST"
                            enctype="multipart/form-data"
                            class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                            @csrf @method('PUT')
                            <div class="flex-1 w-full">
                                <label class="text-xs font-bold text-orange-800 block mb-1">Bukti Transfer
                                    (JPG/PNG/PDF)</label>
                                <input type="file" name="bukti_pengembalian" required accept=".jpg,.png,.pdf"
                                    class="w-full bg-white border border-orange-300 rounded-lg p-2 text-sm text-gray-700 file:mr-3 file:border-0 file:bg-orange-50 file:text-orange-700 file:font-bold file:text-xs file:px-3 file:py-1 file:rounded">
                            </div>
                            <button type="submit"
                                class="bg-orange-600 hover:bg-orange-700 text-white font-bold py-2.5 px-5 rounded-lg transition text-sm whitespace-nowrap">
                                <i class="bi bi-upload mr-1"></i> Kirim Bukti
                            </button>
                        </form>
                    @endif
                </div>
            @endif

            {{-- BANNER: Verifikasi Pengembalian (Keuangan) (Updated Namespace) --}}
            @if ($status === \App\Enums\StatusPermohonan::MENUNGGU_VERIFIKASI_KEMBALI && Auth::user()->role === 'keuangan')
                <div
                    class="bg-blue-50 border border-blue-200 rounded-xl p-5 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h3 class="font-bold text-blue-900 flex items-center gap-2"><i
                                class="bi bi-file-earmark-check text-blue-500"></i> Bukti Transfer Telah Diunggah</h3>
                        <a href="{{ asset('storage/' . $permohonan->bukti_pengembalian) }}" target="_blank"
                            class="inline-flex items-center gap-1.5 mt-2 text-xs font-bold text-blue-700 bg-white hover:bg-blue-50 border border-blue-200 px-3 py-1.5 rounded-lg transition">
                            <i class="bi bi-eye"></i> Lihat Bukti Transfer
                        </a>
                    </div>
                    <form action="{{ route('permohonan.verifikasi_pengembalian', $permohonan->id) }}" method="POST">
                        @csrf @method('PUT')
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-5 rounded-lg transition text-sm whitespace-nowrap">
                            <i class="bi bi-check-all mr-1"></i> Verifikasi & Tutup Tiket
                        </button>
                    </form>
                </div>
            @endif

            {{-- DETAIL CARDS --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

                {{-- KARTU: Info Kegiatan --}}
                <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="px-5 py-3.5 border-b border-gray-100 bg-slate-50 flex items-center gap-2">
                        <i class="bi bi-info-circle text-blue-600"></i>
                        <h4 class="font-bold text-gray-800 text-sm">Informasi Kegiatan</h4>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Nama PIC
                                </p>
                                <p class="font-semibold text-gray-800">{{ $permohonan->nama_pic }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">{{ $permohonan->kontak_pic }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Kategori
                                </p>
                                @if ($permohonan->kategori_kegiatan)
                                    <span
                                        class="inline-block text-xs font-bold px-2.5 py-1 rounded-md border {{ $permohonan->kategori_kegiatan === 'Dinas SITH' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-gray-50 text-gray-600 border-gray-200' }}">{{ $permohonan->kategori_kegiatan }}</span>
                                @else
                                    <p class="text-gray-400 italic text-sm">Belum ditentukan</p>
                                @endif
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Rute
                                    Perjalanan</p>
                                <p class="text-sm text-gray-700"><i
                                        class="bi bi-geo-alt text-gray-400 mr-1"></i>{{ $permohonan->titik_jemput }}
                                </p>
                                <p class="text-sm text-gray-500"><i
                                        class="bi bi-arrow-down text-gray-300 mr-1"></i>{{ $permohonan->tujuan }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Jadwal</p>
                                <p class="text-sm font-medium text-gray-700">
                                    {{ \Carbon\Carbon::parse($permohonan->waktu_berangkat)->format('d M Y, H:i') }}</p>
                                <p class="text-xs text-gray-500">s/d
                                    {{ \Carbon\Carbon::parse($permohonan->waktu_kembali)->format('d M Y, H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Kebutuhan
                                </p>
                                <p class="text-sm text-gray-700">{{ $permohonan->kendaraan_dibutuhkan }}</p>
                                <p class="text-xs text-gray-500"><i
                                        class="bi bi-people mr-0.5"></i>{{ $permohonan->jumlah_penumpang }} orang</p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Anggaran
                                    Diajukan</p>
                                <p class="text-sm font-bold text-gray-800">Rp
                                    {{ number_format($permohonan->anggaran_diajukan, 0, ',', '.') }}</p>
                            </div>
                            @if ($permohonan->catatan_pemohon)
                                <div class="sm:col-span-2">
                                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">
                                        Catatan Pemohon</p>
                                    <div
                                        class="bg-slate-50 border border-slate-200 rounded-lg p-3 text-sm text-gray-700">
                                        {{ $permohonan->catatan_pemohon }}</div>
                                </div>
                            @endif
                            <div>
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Surat
                                    Penugasan</p>
                                @if ($permohonan->file_surat_penugasan)
                                    <a href="{{ asset('storage/' . $permohonan->file_surat_penugasan) }}"
                                        target="_blank"
                                        class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200 px-3 py-1.5 rounded-lg transition">
                                        <i class="bi bi-file-earmark-pdf"></i> Lihat Dokumen
                                    </a>
                                @else
                                    <span class="text-gray-400 text-sm italic">Tidak ada</span>
                                @endif
                            </div>
                        </div>

                        {{-- Timeline --}}
                        @php
                            $hasTimeline =
                                $permohonan->waktu_serah_terima ||
                                $permohonan->waktu_mulai_perjalanan ||
                                $permohonan->waktu_kembali_aktual;
                        @endphp
                        @if ($hasTimeline)
                            <div class="mt-5 pt-4 border-t border-gray-100">
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Timeline
                                    Perjalanan</p>
                                <ol class="relative border-l-2 border-gray-200 ml-3 space-y-0">
                                    @foreach ([[$permohonan->waktu_serah_terima, 'Serah Terima Kunci', 'bi-key-fill', 'bg-yellow-100 text-yellow-600'], [$permohonan->waktu_mulai_perjalanan, 'Perjalanan Dimulai', 'bi-play-fill', 'bg-teal-100 text-teal-600'], [$permohonan->waktu_kembali_aktual, 'Dilaporkan Kembali', 'bi-arrow-return-left', 'bg-indigo-100 text-indigo-600']] as [$waktu, $label, $icon, $style])
                                        @if ($waktu)
                                            <li class="ml-6 pb-4 last:pb-0">
                                                <span
                                                    class="absolute -left-[13px] w-6 h-6 rounded-full {{ $style }} flex items-center justify-center ring-4 ring-white">
                                                    <i class="bi {{ $icon }} text-xs"></i>
                                                </span>
                                                <p class="text-sm font-semibold text-gray-700">{{ $label }}</p>
                                                <p class="text-xs text-gray-400">
                                                    {{ \Carbon\Carbon::parse($waktu)->format('d M Y, H:i') }} ·
                                                    {{ \Carbon\Carbon::parse($waktu)->diffForHumans() }}</p>
                                            </li>
                                        @endif
                                    @endforeach
                                </ol>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- KARTU: Armada + Keuangan --}}
                <div class="space-y-4">
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-5 py-3.5 border-b border-gray-100 bg-slate-50 flex items-center gap-2">
                            <i class="bi bi-car-front text-blue-600"></i>
                            <h4 class="font-bold text-gray-800 text-sm">Armada</h4>
                        </div>
                        <div class="p-5 space-y-3">
                            <div>
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Kendaraan
                                </p>
                                @if ($permohonan->kendaraan_id)
                                    <p class="font-semibold text-gray-800 text-sm">
                                        {{ $permohonan->kendaraan->nama_kendaraan }}</p>
                                    <p class="text-xs text-gray-500">{{ $permohonan->kendaraan->plat_nomor }}</p>
                                @elseif($permohonan->kendaraanVendor)
                                    <p class="font-semibold text-gray-800 text-sm">
                                        {{ $permohonan->kendaraanVendor->nama_kendaraan }}</p>
                                    <span
                                        class="text-[10px] font-bold text-orange-600 bg-orange-50 border border-orange-200 px-1.5 py-0.5 rounded">VENDOR
                                        LUAR</span>
                                @else
                                    <p class="text-gray-400 italic text-sm">Menunggu alokasi</p>
                                @endif
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Pengemudi
                                </p>
                                <p class="text-sm text-gray-700">
                                    {{ $permohonan->pengemudi->nama_pengemudi ?? 'Tanpa Pengemudi' }}</p>
                            </div>
                            @if ($permohonan->rekomendasi_admin)
                                <div>
                                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">
                                        Instruksi Admin</p>
                                    <p
                                        class="text-xs text-gray-600 bg-slate-50 border border-slate-200 rounded-lg p-2">
                                        {{ $permohonan->rekomendasi_admin }}</p>
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
                            @if ($permohonan->rab_disetujui)
                                <div>
                                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">RAB
                                        Disetujui</p>
                                    <p class="text-xl font-black text-gray-900">Rp
                                        {{ number_format($permohonan->rab_disetujui, 0, ',', '.') }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $permohonan->mekanisme_pembayaran }}
                                    </p>
                                </div>
                                @if ($permohonan->biaya_aktual)
                                    <div class="border-t border-gray-100 pt-3">
                                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">
                                            Biaya Aktual</p>
                                        <p class="font-bold text-gray-800">Rp
                                            {{ number_format($permohonan->biaya_aktual, 0, ',', '.') }}</p>
                                        @php $selisih = $permohonan->rab_disetujui - $permohonan->biaya_aktual; @endphp
                                        <p
                                            class="text-xs mt-1 {{ $selisih > 0 ? 'text-orange-600 font-bold' : 'text-emerald-600' }}">
                                            {{ $selisih > 0 ? '⚠ Sisa: Rp ' . number_format($selisih, 0, ',', '.') : '✓ Lunas / Sesuai' }}
                                        </p>
                                    </div>
                                @endif
                                @if ($permohonan->bukti_lpj)
                                    <a href="{{ asset('storage/' . $permohonan->bukti_lpj) }}" target="_blank"
                                        class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200 px-3 py-1.5 rounded-lg transition">
                                        <i class="bi bi-file-earmark-text"></i> Lihat Bukti Nota
                                    </a>
                                @endif
                            @else
                                <p class="text-sm text-gray-400 italic">
                                    {{ $permohonan->kategori_kegiatan === 'Non SITH' ? 'Bypass Keuangan (Non-Dinas)' : 'Belum diproses' }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- FORM LPJ (Updated Namespace) --}}
            @if (
                $status === \App\Enums\StatusPermohonan::MENUNGGU_PENYELESAIAN &&
                    Auth::user()->role === 'pengguna' &&
                    Auth::id() === $permohonan->user_id)
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="px-5 py-3.5 border-b border-gray-100 bg-purple-50 flex items-center gap-2">
                        <i class="bi bi-clipboard2-check text-purple-600"></i>
                        <h4 class="font-bold text-gray-800 text-sm">Selesaikan Perjalanan & Submit LPJ</h4>
                    </div>
                    <div class="p-5">
                        <form action="{{ route('permohonan.selesai', $permohonan->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf @method('PUT')
                            @if ($permohonan->kategori_kegiatan !== 'Non SITH')
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-1.5">Total Biaya Aktual
                                            (Rp) <span class="text-red-500">*</span></label>
                                        <input type="number" name="biaya_aktual" required min="0"
                                            placeholder="Contoh: 150000"
                                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm transition">
                                        <p class="text-xs text-gray-400 mt-1">Masukkan angka tanpa titik/koma</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-1.5">Bukti Pengeluaran
                                            (Nota/Struk) <span class="text-red-500">*</span></label>
                                        <input type="file" name="bukti_lpj" required accept=".jpg,.png,.pdf"
                                            class="w-full border border-gray-300 rounded-lg p-2 text-sm bg-gray-50 file:mr-3 file:border-0 file:bg-blue-50 file:text-blue-700 file:font-bold file:text-xs file:px-3 file:py-1 file:rounded">
                                        <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG, atau PDF (maks. 2MB)
                                        </p>
                                    </div>
                                </div>
                            @else
                                <div class="bg-slate-50 border border-slate-200 rounded-lg p-4 mb-5">
                                    <p class="text-sm text-gray-600 flex items-start gap-2">
                                        <i class="bi bi-info-circle text-blue-500 mt-0.5 flex-shrink-0"></i>
                                        Kegiatan Non-Dinas tidak memerlukan laporan pengeluaran. Silakan langsung tutup
                                        tiket ini.
                                    </p>
                                </div>
                            @endif
                            <button type="submit"
                                onclick="return confirm('Yakin ingin menutup tiket ini? Pastikan semua data sudah benar.')"
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

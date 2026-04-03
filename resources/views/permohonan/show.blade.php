<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Pengajuan Kendaraan') }}
            </h2>
            <span class="text-sm text-gray-500">ID Tiket: #{{ str_pad($permohonan->id, 4, '0', STR_PAD_LEFT) }}</span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-4">
                <a href="{{ url()->previous() == url()->current() ? route('dashboard') : url()->previous() }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700">
                    <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Daftar
                </a>
            </div>

            @php
                $statusColor = 'bg-gray-100 text-gray-800 border-gray-200';
                if ($permohonan->status_permohonan === 'Disetujui' || $permohonan->status_permohonan === 'Selesai') {
                    $statusColor = 'bg-green-100 text-green-800 border-green-200';
                } elseif ($permohonan->status_permohonan === 'Ditolak') {
                    $statusColor = 'bg-red-100 text-red-800 border-red-200';
                } elseif (str_contains($permohonan->status_permohonan, 'Menunggu')) {
                    $statusColor = 'bg-yellow-100 text-yellow-800 border-yellow-200';
                }
            @endphp
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 border-l-4 {{ str_replace('bg-', 'border-', explode(' ', $statusColor)[0]) }}">
                <div class="p-4 sm:p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Status Saat Ini</h3>
                        <p class="text-sm text-gray-500">Pembaruan terakhir: {{ $permohonan->updated_at->diffForHumans() }}</p>
                    </div>
                    <div class="px-4 py-2 rounded-full border font-bold text-sm text-center {{ $statusColor }} shadow-sm">
                        {{ $permohonan->status_permohonan }}
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 font-bold text-gray-700 flex items-center gap-2">
                        <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Informasi Pemohon & Kegiatan
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-3 gap-2 border-b border-gray-50 pb-3">
                            <div class="col-span-1 text-sm font-semibold text-gray-500">Nama PIC</div>
                            <div class="col-span-2 text-sm text-gray-900">{{ $permohonan->nama_pic }}</div>
                        </div>
                        <div class="grid grid-cols-3 gap-2 border-b border-gray-50 pb-3">
                            <div class="col-span-1 text-sm font-semibold text-gray-500">Kontak</div>
                            <div class="col-span-2 text-sm text-gray-900">{{ $permohonan->kontak_pic }}</div>
                        </div>
                        <div class="grid grid-cols-3 gap-2 border-b border-gray-50 pb-3">
                            <div class="col-span-1 text-sm font-semibold text-gray-500">Kategori</div>
                            <div class="col-span-2 text-sm text-gray-900">{{ $permohonan->kategori_kegiatan ?? 'Belum ditentukan Admin' }}</div>
                        </div>
                        <div class="grid grid-cols-3 gap-2 border-b border-gray-50 pb-3">
                            <div class="col-span-1 text-sm font-semibold text-gray-500">Tujuan</div>
                            <div class="col-span-2 text-sm text-gray-900">{{ $permohonan->tujuan }}</div>
                        </div>
                        <div class="grid grid-cols-3 gap-2 border-b border-gray-50 pb-3">
                            <div class="col-span-1 text-sm font-semibold text-gray-500">Penumpang</div>
                            <div class="col-span-2 text-sm text-gray-900">{{ $permohonan->jumlah_penumpang }} Orang</div>
                        </div>
                        <div class="grid grid-cols-3 gap-2 border-b border-gray-50 pb-3">
                            <div class="col-span-1 text-sm font-semibold text-gray-500">Waktu Jalan</div>
                            <div class="col-span-2 text-sm text-gray-900">
                                <span class="text-green-600 font-medium">Berangkat:</span> <br> {{ \Carbon\Carbon::parse($permohonan->waktu_berangkat)->translatedFormat('l, d F Y H:i') }} <br>
                                <span class="text-red-600 font-medium mt-1 inline-block">Kembali:</span> <br> {{ \Carbon\Carbon::parse($permohonan->waktu_kembali)->translatedFormat('l, d F Y H:i') }}
                            </div>
                        </div>

                        <div class="pt-2">
                            <div class="text-sm font-semibold text-gray-500 mb-2">Dokumen Lampiran / Surat Tugas</div>
                            @if($permohonan->file_surat_penugasan)
                                <a href="{{ asset('storage/' . $permohonan->file_surat_penugasan) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-indigo-50 border border-indigo-200 rounded-md font-semibold text-xs text-indigo-700 uppercase tracking-widest hover:bg-indigo-100 transition shadow-sm w-full justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    Lihat / Unduh Dokumen
                                </a>
                            @else
                                <span class="text-sm text-red-500 italic">Tidak ada dokumen terlampir</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 font-bold text-gray-700 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                            Alokasi Armada (SPSI)
                        </div>
                        <div class="p-6">
                            @if($permohonan->kendaraan_id)
                                <div class="space-y-3">
                                    <div class="flex justify-between border-b pb-2">
                                        <span class="text-sm text-gray-500">Kendaraan</span>
                                        <span class="text-sm font-bold text-gray-900">{{ $permohonan->kendaraan->nama_kendaraan ?? '-' }} ({{ $permohonan->kendaraan->plat_nomor ?? '-' }})</span>
                                    </div>
                                    <div class="flex justify-between border-b pb-2">
                                        <span class="text-sm text-gray-500">Pengemudi</span>
                                        <span class="text-sm font-bold text-gray-900">{{ $permohonan->pengemudi->nama_pengemudi ?? 'Tanpa Pengemudi' }}</span>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <p class="text-sm text-gray-500 italic mb-2">Menunggu alokasi dari bagian armada.</p>
                                    <p class="text-xs text-gray-400">Permintaan awal: {{ $permohonan->kendaraan_dibutuhkan }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 font-bold text-gray-700 flex items-center gap-2">
                            <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Rincian Anggaran (Keuangan)
                        </div>
                        <div class="p-6">
                            <div class="space-y-3">
                                <div class="flex justify-between border-b pb-2">
                                    <span class="text-sm text-gray-500">Anggaran Diajukan Pemohon</span>
                                    <span class="text-sm font-semibold text-gray-900">Rp {{ number_format((float) preg_replace('/[^0-9.]/', '', $permohonan->anggaran_diajukan ?? 0), 0, ',', '.') }}</span>
                                </div>
                                
                                @if($permohonan->estimasi_biaya_operasional)
                                    <div class="flex justify-between border-b pb-2">
                                        <span class="text-sm text-gray-500">Estimasi Kebutuhan (SPSI)</span>
                                        <span class="text-sm font-semibold text-orange-600">Rp {{ number_format((float) preg_replace('/[^0-9.]/', '', $permohonan->estimasi_biaya_operasional), 0, ',', '.') }}</span>
                                    </div>
                                @endif

                                @if($permohonan->rab_disetujui)
                                    <div class="flex justify-between border-b pb-2">
                                        <span class="text-sm font-bold text-gray-800">RAB Disetujui (Keuangan)</span>
                                        <span class="text-sm font-black text-green-600">Rp {{ number_format((float) preg_replace('/[^0-9.]/', '', $permohonan->rab_disetujui), 0, ',', '.') }}</span>
                                    </div>
                                    <div class="mt-2 text-xs text-gray-500">
                                        <span class="font-semibold">Mekanisme:</span> {{ $permohonan->mekanisme_pembayaran }}
                                    </div>
                                @else
                                    <div class="mt-4 text-center">
                                        <p class="text-sm text-gray-500 italic">Anggaran belum disetujui / dalam proses penilaian.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div> <div class="mt-8 flex flex-col sm:flex-row justify-between items-center gap-4 bg-gray-50 p-4 sm:p-6 rounded-lg border border-gray-200 shadow-sm">
                <a href="{{ url()->previous() == url()->current() ? route('dashboard') : url()->previous() }}" class="w-full sm:w-auto text-center bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 sm:py-2 px-6 rounded-md transition shadow-sm">
                    &larr; Kembali
                </a>

                <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                    
                    @if(in_array($permohonan->status_permohonan, ['Disetujui', 'Selesai']))
                        <a href="{{ route('permohonan.cetak', $permohonan->id) }}" target="_blank" class="w-full sm:w-auto text-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 sm:py-2 px-6 rounded-md transition shadow-sm flex justify-center items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            Cetak Bukti Izin
                        </a>
                    @endif

                    @if($permohonan->status_permohonan === 'Disetujui' && Auth::user()->role === 'pengguna')
                        <form action="{{ route('permohonan.selesai', $permohonan->id) }}" method="POST" class="w-full sm:w-auto m-0" onsubmit="return confirm('Apakah Anda yakin perjalanan telah selesai?');">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white font-bold py-3 sm:py-2 px-6 rounded-md transition shadow-sm flex justify-center items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Selesaikan Perjalanan
                            </button>
                        </form>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
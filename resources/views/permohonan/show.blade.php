<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Pengajuan Kendaraan</h2>
            <span class="text-sm font-bold text-gray-500 border border-gray-300 px-3 py-1 rounded">ID: #{{ str_pad($permohonan->id, 4, '0', STR_PAD_LEFT) }}</span>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- STATUS HEADER --}}
            @php
                $statusClass = match($permohonan->status_permohonan) {
                    'Selesai'                          => 'border-green-500 text-green-700 bg-green-50',
                    'Disetujui'                        => 'border-blue-500 text-blue-700 bg-blue-50',
                    'Ditolak'                          => 'border-red-500 text-red-700 bg-red-50',
                    'Menunggu Pengembalian Dana'       => 'border-orange-500 text-orange-700 bg-orange-50',
                    'Menunggu Verifikasi Pengembalian' => 'border-yellow-500 text-yellow-700 bg-yellow-50',
                    default                            => 'border-gray-800 text-gray-800 bg-gray-50',
                };
            @endphp
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 border border-gray-200">
                <div class="p-6 flex flex-col md:flex-row justify-between items-center gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Status Dokumen</h3>
                        <p class="text-xs text-gray-500 mt-1">Terakhir update: {{ $permohonan->updated_at->diffForHumans() }}</p>
                    </div>
                    <div class="px-6 py-2 rounded-full border-2 font-bold text-sm text-center uppercase tracking-widest {{ $statusClass }}">
                        {{ $permohonan->status_permohonan }}
                    </div>
                </div>
            </div>

            {{-- BANNER: Menunggu Pengembalian Dana (Hanya muncul jika ada sisa dana & bukan reimburse) --}}
            @if($permohonan->status_permohonan === 'Menunggu Pengembalian Dana')
                <div class="bg-orange-50 border-l-4 border-orange-500 p-6 mb-6 rounded-r-lg shadow-sm">
                    <h3 class="text-lg font-bold text-orange-800 mb-2">⚠️ Sisa Dana Wajib Dikembalikan</h3>
                    
                    @php
                        $sisaDana = $permohonan->rab_disetujui - $permohonan->biaya_aktual;
                    @endphp
                    
                    <ul class="text-sm text-orange-800 mb-4 list-disc list-inside bg-white bg-opacity-70 p-4 rounded border border-orange-200">
                        <li>Total RAB Diberikan: <strong>Rp {{ number_format($permohonan->rab_disetujui, 0, ',', '.') }}</strong></li>
                        <li>Total Biaya Aktual: <strong>Rp {{ number_format($permohonan->biaya_aktual, 0, ',', '.') }}</strong></li>
                        <li class="mt-2 pt-2 border-t border-orange-300">Total Sisa yang Harus Dikembalikan: <strong class="text-red-600 text-base">Rp {{ number_format($sisaDana, 0, ',', '.') }}</strong></li>
                    </ul>

                    @if(Auth::user()->role === 'pengguna' && Auth::id() === $permohonan->user_id)
                    <form action="{{ route('permohonan.submit_pengembalian', $permohonan->id) }}" method="POST" enctype="multipart/form-data" class="flex flex-col md:flex-row items-center gap-4 mt-4">
                        @csrf @method('PUT')
                        <div class="w-full md:w-auto">
                            <input type="file" name="bukti_pengembalian" required accept=".jpg,.png,.pdf" class="bg-white border border-orange-300 rounded p-1.5 w-full text-sm">
                        </div>
                        <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-6 rounded transition w-full md:w-auto">
                            Kirim Bukti Transfer Pengembalian
                        </button>
                    </form>
                    @endif
                </div>
            @endif

            {{-- BANNER: Menunggu Verifikasi Pengembalian (untuk keuangan) --}}
            @if($permohonan->status_permohonan === 'Menunggu Verifikasi Pengembalian' && Auth::user()->role === 'keuangan')
                <div class="bg-blue-50 border border-blue-200 p-6 mb-6 rounded-lg shadow-sm flex flex-col md:flex-row justify-between items-center gap-4">
                    <div>
                        <h3 class="text-blue-800 font-bold mb-1">Bukti Transfer Pengembalian telah Diunggah</h3>
                        <a href="{{ asset('storage/' . $permohonan->bukti_pengembalian) }}" target="_blank" class="text-sm text-blue-600 hover:text-blue-800 hover:underline font-bold bg-white px-3 py-1 rounded border border-blue-200 inline-block mt-1">
                            📄 Lihat Bukti Transfer
                        </a>
                    </div>
                    <form action="{{ route('permohonan.verifikasi_pengembalian', $permohonan->id) }}" method="POST">
                        @csrf @method('PUT')
                        <button type="submit" class="bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 transition w-full md:w-auto">
                            Verifikasi & Tutup Tiket Selesai
                        </button>
                    </form>
                </div>
            @endif

            {{-- DETAIL GRID --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- KOTAK 1: Info Kegiatan --}}
                <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                    <h4 class="font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Informasi Kegiatan</h4>
                    <ul class="space-y-3 text-sm text-gray-700">
                        <li><span class="font-semibold w-1/3 inline-block">Nama PIC:</span> {{ $permohonan->nama_pic }}</li>
                        <li><span class="font-semibold w-1/3 inline-block">Kategori:</span> {{ $permohonan->kategori_kegiatan ?? 'Belum ditentukan' }}</li>
                        <li><span class="font-semibold w-1/3 inline-block">Tujuan:</span> {{ $permohonan->tujuan }}</li>
                        <li><span class="font-semibold w-1/3 inline-block top-0">Jadwal:</span>
                            {{ \Carbon\Carbon::parse($permohonan->waktu_berangkat)->format('d M y H:i') }}
                            - {{ \Carbon\Carbon::parse($permohonan->waktu_kembali)->format('d M y H:i') }}
                        </li>
                    </ul>
                </div>

                {{-- KOTAK 2: Armada & Keuangan --}}
                <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm space-y-6">
                    <div>
                        <h4 class="font-bold text-gray-800 mb-2 border-b border-gray-100 pb-2">Armada Assigned</h4>
                        <p class="text-sm text-gray-700 font-bold">
                            {{ $permohonan->kendaraan_id ? $permohonan->kendaraan->nama_kendaraan : ($permohonan->kendaraanVendor->nama_kendaraan ?? 'Menunggu Alokasi / Belum Ada') }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">Supir: {{ $permohonan->pengemudi->nama_pengemudi ?? 'Tanpa Supir (Atau Lepas Kunci)' }}</p>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800 mb-2 border-b border-gray-100 pb-2">RAB Keuangan & LPJ</h4>
                        @if($permohonan->rab_disetujui)
                            <p class="text-lg font-black text-gray-900">Rp {{ number_format($permohonan->rab_disetujui, 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-500 font-medium">Metode: {{ $permohonan->mekanisme_pembayaran }}</p>
                            
                            {{-- TAMBAHAN: Tombol Lihat Bukti LPJ untuk Keuangan & Admin --}}
                            @if($permohonan->bukti_lpj)
                                <div class="mt-4 pt-3 border-t border-gray-100">
                                    <p class="text-xs font-semibold text-gray-500 mb-2">Bukti LPJ (Nota/Struk Aktual):</p>
                                    <p class="text-sm text-gray-700 mb-2">Biaya Aktual: <strong>Rp {{ number_format($permohonan->biaya_aktual, 0, ',', '.') }}</strong></p>
                                    <a href="{{ asset('storage/' . $permohonan->bukti_lpj) }}" target="_blank" class="inline-block bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold py-1.5 px-3 rounded text-sm transition border border-blue-200">
                                        📄 Lihat Bukti Nota
                                    </a>
                                </div>
                            @endif

                        @else
                            <p class="text-sm text-gray-500 italic">{{ $permohonan->kategori_kegiatan === 'Non SITH' ? 'Bypass Keuangan (Non-Dinas)' : 'Belum diproses' }}</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- FORM LPJ: hanya untuk pengguna saat status masih Disetujui --}}
            @if($permohonan->status_permohonan === 'Disetujui' && Auth::user()->role === 'pengguna')
                <div class="mt-8 bg-white border border-gray-200 p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Selesaikan Perjalanan & Form LPJ</h3>
                    <form action="{{ route('permohonan.selesai', $permohonan->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        @if($permohonan->kategori_kegiatan !== 'Non SITH')
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Total Biaya Aktual Habis (Rp) <span class="text-red-500">*</span></label>
                                    <input type="number" name="biaya_aktual" required min="0" class="w-full border-gray-300 rounded focus:border-green-500">
                                    <p class="text-xs text-gray-500 mt-1">Masukkan angka tanpa titik. Contoh: 150000</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Unggah Struk / Nota (Bukti) <span class="text-red-500">*</span></label>
                                    <input type="file" name="bukti_lpj" required accept=".jpg,.png,.pdf" class="w-full border-gray-300 rounded p-1.5 text-sm bg-gray-50">
                                    <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, atau PDF.</p>
                                </div>
                            </div>
                        @else
                            <p class="text-sm text-gray-600 mb-4 bg-gray-100 p-3 rounded">Karena kegiatan Non-Dinas, Anda tidak perlu mengisi laporan pengeluaran keuangan kampus. Silakan langsung tutup tiket ini.</p>
                        @endif
                        <button type="submit" onclick="return confirm('Yakin ingin menutup tiket perjalanan ini?')" class="bg-gray-800 hover:bg-black text-white font-bold py-2.5 px-6 rounded transition mt-2">
                            Tutup & Selesaikan Perjalanan
                        </button>
                    </form>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
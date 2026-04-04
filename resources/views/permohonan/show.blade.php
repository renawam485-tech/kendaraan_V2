<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Pengajuan Kendaraan</h2>
            <span class="text-sm font-bold text-gray-500 border border-gray-300 px-3 py-1 rounded">ID: #{{ str_pad($permohonan->id, 4, '0', STR_PAD_LEFT) }}</span>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 border border-gray-200">
                <div class="p-6 flex flex-col md:flex-row justify-between items-center gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Status Dokumen</h3>
                        <p class="text-xs text-gray-500 mt-1">Terakhir update: {{ $permohonan->updated_at->diffForHumans() }}</p>
                    </div>
                    <div class="px-6 py-2 rounded-full border-2 font-bold text-sm text-center uppercase tracking-widest
                        {{ $permohonan->status_permohonan === 'Selesai' ? 'border-green-500 text-green-700 bg-green-50' : 'border-gray-800 text-gray-800 bg-gray-50' }}">
                        {{ $permohonan->status_permohonan }}
                    </div>
                </div>
            </div>

            @if($permohonan->status_permohonan === 'Menunggu Pengembalian Dana')
                <div class="bg-red-50 border-l-4 border-red-500 p-6 mb-6 rounded-r-lg shadow-sm">
                    <h3 class="text-red-800 font-bold text-lg mb-2">⚠️ Sisa Dana Wajib Dikembalikan</h3>
                    <p class="text-sm text-red-700 mb-4">Terdapat sisa anggaran Dinas SITH sebesar <strong class="text-lg">Rp {{ number_format($permohonan->rab_disetujui - $permohonan->biaya_aktual, 0, ',', '.') }}</strong>. Mohon segera transfer sisa dana tersebut ke rekening SITH dan unggah bukti transfer Anda di bawah ini agar tiket dapat ditutup (Selesai).</p>
                    
                    @if(Auth::user()->role === 'pengguna')
                    <form action="{{ route('permohonan.submit_pengembalian', $permohonan->id) }}" method="POST" enctype="multipart/form-data" class="flex flex-col md:flex-row items-center gap-4">
                        @csrf @method('PUT')
                        <input type="file" name="bukti_pengembalian" required class="bg-white border border-red-300 rounded p-1 w-full md:w-auto text-sm">
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded transition w-full md:w-auto">Unggah Bukti Transfer</button>
                    </form>
                    @endif
                </div>
            @endif

            @if($permohonan->status_permohonan === 'Menunggu Verifikasi Pengembalian' && Auth::user()->role === 'keuangan')
                <div class="bg-blue-50 border border-blue-200 p-6 mb-6 rounded-lg shadow-sm flex justify-between items-center">
                    <div>
                        <h3 class="text-blue-800 font-bold mb-1">Bukti Transfer Pengembalian telah Diunggah</h3>
                        <a href="{{ asset('storage/' . $permohonan->bukti_pengembalian) }}" target="_blank" class="text-sm text-blue-600 hover:underline font-bold">Lihat Bukti Transfer</a>
                    </div>
                    <form action="{{ route('permohonan.verifikasi_pengembalian', $permohonan->id) }}" method="POST">
                        @csrf @method('PUT')
                        <button type="submit" class="bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 transition">Verifikasi & Tutup Tiket Selesai</button>
                    </form>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                    <h4 class="font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Informasi Kegiatan</h4>
                    <ul class="space-y-3 text-sm text-gray-700">
                        <li><span class="font-semibold w-1/3 inline-block">Nama PIC:</span> {{ $permohonan->nama_pic }}</li>
                        <li><span class="font-semibold w-1/3 inline-block">Kategori:</span> {{ $permohonan->kategori_kegiatan }}</li>
                        <li><span class="font-semibold w-1/3 inline-block">Tujuan:</span> {{ $permohonan->tujuan }}</li>
                        <li><span class="font-semibold w-1/3 inline-block top-0">Jadwal:</span> {{ \Carbon\Carbon::parse($permohonan->waktu_berangkat)->format('d M y H:i') }} - {{ \Carbon\Carbon::parse($permohonan->waktu_kembali)->format('d M y H:i') }}</li>
                    </ul>
                </div>

                <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm space-y-6">
                    <div>
                        <h4 class="font-bold text-gray-800 mb-2 border-b border-gray-100 pb-2">Armada Assigned</h4>
                        <p class="text-sm text-gray-700 font-bold">
                            {{ $permohonan->kendaraan_id ? $permohonan->kendaraan->nama_kendaraan : ($permohonan->kendaraan_vendor ?? 'Menunggu Alokasi') }}
                        </p>
                        <p class="text-xs text-gray-500">Supir: {{ $permohonan->pengemudi->nama_pengemudi ?? 'Tanpa Supir' }}</p>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800 mb-2 border-b border-gray-100 pb-2">RAB Keuangan</h4>
                        @if($permohonan->rab_disetujui)
                            <p class="text-lg font-black text-gray-900">Rp {{ number_format($permohonan->rab_disetujui, 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-500 font-medium">Metode: {{ $permohonan->mekanisme_pembayaran }}</p>
                        @else
                            <p class="text-sm text-gray-500 italic">{{ $permohonan->kategori_kegiatan === 'Non SITH' ? 'Bypass Keuangan (Non-Dinas)' : 'Belum diproses' }}</p>
                        @endif
                    </div>
                </div>
            </div>

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
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Unggah Struk / Nota (Bukti) <span class="text-red-500">*</span></label>
                                    <input type="file" name="bukti_lpj" required accept=".jpg,.png,.pdf" class="w-full border-gray-300 rounded p-1 text-sm bg-gray-50">
                                </div>
                            </div>
                        @else
                            <p class="text-sm text-gray-600 mb-4">Karena kegiatan Non-Dinas, Anda tidak perlu mengisi laporan pengeluaran keuangan kampus.</p>
                        @endif
                        <button type="submit" onclick="return confirm('Yakin ingin menutup tiket perjalanan ini?')" class="bg-gray-800 hover:bg-black text-white font-bold py-2.5 px-6 rounded transition">Tutup & Selesaikan Perjalanan</button>
                    </form>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
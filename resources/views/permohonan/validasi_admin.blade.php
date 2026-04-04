<x-app-layout>
    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b border-gray-100 pb-4">Validasi Permohonan Kendaraan</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama PIC</p>
                            <p class="font-bold text-gray-900 text-lg">{{ $permohonan->nama_pic }} <span class="text-sm font-normal text-gray-600">({{ $permohonan->kontak_pic }})</span></p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Kebutuhan Kendaraan</p>
                            <p class="font-bold text-gray-900 text-lg">{{ $permohonan->kendaraan_dibutuhkan }} <span class="text-sm font-normal text-gray-600">({{ $permohonan->jumlah_penumpang }} Orang)</span></p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Rute Perjalanan</p>
                            <p class="font-medium text-gray-800">{{ $permohonan->titik_jemput }} <span class="text-gray-400 mx-1">➔</span> {{ $permohonan->tujuan }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Jadwal Perjalanan</p>
                            <p class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($permohonan->waktu_berangkat)->format('d M Y, H:i') }} <span class="text-gray-400 mx-1">-</span> {{ \Carbon\Carbon::parse($permohonan->waktu_kembali)->format('d M Y, H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Anggaran Diminta</p>
                            <p class="font-bold text-gray-900">Rp {{ number_format($permohonan->anggaran_diajukan, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Surat Penugasan</p>
                            @if($permohonan->file_surat_penugasan)
                                <a href="{{ asset('storage/' . $permohonan->file_surat_penugasan) }}" target="_blank" class="text-green-600 font-bold hover:text-green-700 hover:underline flex items-center gap-1 mt-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    Lihat Dokumen
                                </a>
                            @else
                                <span class="text-gray-400 italic">Tidak ada dokumen</span>
                            @endif
                        </div>
                        
                        <div class="col-span-1 md:col-span-2 mt-2">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Catatan Khusus dari Pemohon</p>
                            <div class="bg-gray-50 border border-gray-200 p-4 rounded-md">
                                @if($permohonan->catatan_pemohon)
                                    <p class="font-medium text-gray-800">{{ $permohonan->catatan_pemohon }}</p>
                                @else
                                    <p class="text-gray-500 italic">Status: Default (Pemohon tidak meninggalkan catatan khusus)</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('permohonan.validasi_admin_proses', $permohonan->id) }}" method="POST" class="border-t border-gray-100 pt-6">
                        @csrf
                        @method('PUT')

                        <div class="mb-6">
                            <label class="block font-bold text-gray-800 mb-2">Kategori Kegiatan (Filter Dana) <span class="text-red-500">*</span></label>
                            <select name="kategori_kegiatan" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 font-medium text-gray-800 transition">
                                <option value="">-- Tentukan Status Kegiatan --</option>
                                <option value="Dinas SITH">Dinas SITH (Ditanggung Instansi)</option>
                                <option value="Non SITH">Non-Dinas / Mandiri (Anggaran otomatis di-nol-kan)</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-2">Pilih <strong>Non-Dinas</strong> jika ini adalah acara pribadi. Sistem akan mem-bypass Keuangan.</p>
                        </div>

                        <div class="mb-8">
                            <label class="block font-bold text-gray-800 mb-2">Instruksi Admin (Untuk SPSI)</label>
                            <textarea name="rekomendasi_admin" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 transition" placeholder="Tambahkan instruksi untuk penyiapan armada..."></textarea>
                        </div>

                        <div class="flex items-center gap-4">
                            <button type="submit" name="status_permohonan" value="Menunggu Proses SPSI" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 px-6 rounded-md shadow-sm transition">
                                Setujui & Teruskan (SPSI)
                            </button>
                            <button type="submit" name="status_permohonan" value="Ditolak" class="bg-white border border-red-300 text-red-600 hover:bg-red-50 font-bold py-2.5 px-6 rounded-md transition" onclick="return confirm('Yakin ingin MENOLAK permohonan ini?')">
                                Tolak Pengajuan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
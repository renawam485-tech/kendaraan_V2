<x-app-layout>
    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-8 border-b border-gray-200">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Validasi Permohonan Kendaraan</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 bg-gray-50 p-4 rounded-md">
                        <div>
                            <p class="text-sm text-gray-500">Nama PIC:</p>
                            <p class="font-semibold">{{ $permohonan->nama_pic }} ({{ $permohonan->kontak_pic }})</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Kebutuhan Kendaraan:</p>
                            <p class="font-semibold text-purple-700">{{ $permohonan->kendaraan_dibutuhkan }} ({{ $permohonan->jumlah_penumpang }} Penumpang)</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Rute:</p>
                            <p class="font-semibold">{{ $permohonan->titik_jemput }} ➔ {{ $permohonan->tujuan }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Jadwal:</p>
                            <p class="font-semibold">{{ \Carbon\Carbon::parse($permohonan->waktu_berangkat)->format('d M Y H:i') }} - {{ \Carbon\Carbon::parse($permohonan->waktu_kembali)->format('d M Y H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Anggaran Diminta:</p>
                            <p class="font-bold text-orange-600">Rp {{ number_format($permohonan->anggaran_diajukan, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Surat Penugasan:</p>
                            @if($permohonan->file_surat_penugasan)
                                <a href="{{ asset('storage/' . $permohonan->file_surat_penugasan) }}" target="_blank" class="text-blue-600 font-bold hover:underline">Lihat Dokumen</a>
                            @else
                                <span class="text-red-500">Tidak ada dokumen</span>
                            @endif
                        </div>
                        <div class="col-span-2 border-t pt-2">
                            <p class="text-sm text-gray-500">Catatan Khusus dari Pemohon:</p>
                            <p class="font-semibold italic bg-yellow-50 p-2 rounded">{{ $permohonan->catatan_pemohon ?? 'Tidak ada catatan.' }}</p>
                        </div>
                    </div>

                    <form action="{{ route('permohonan.validasi_admin_proses', $permohonan->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-6 p-5 border border-blue-200 bg-blue-50 rounded-lg">
                            <label class="block font-bold text-blue-900 mb-2">Kategori Kegiatan (Filter Dana) <span class="text-red-500">*</span></label>
                            <select name="kategori_kegiatan" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 font-semibold text-gray-800">
                                <option value="">-- Tentukan Status Kegiatan --</option>
                                <option value="Dinas SITH">Dinas SITH (Ditanggung SITH)</option>
                                <option value="Non SITH">Non-Dinas / Mandiri (Anggaran otomatis di-nol-kan)</option>
                            </select>
                            <p class="text-xs text-blue-700 mt-2">Pilih <strong>Non-Dinas / Mandiri</strong> jika ini adalah acara himpunan/pribadi. Sistem akan me-reset anggaran menjadi Rp 0 dan mem-bypass bagian Keuangan.</p>
                        </div>

                        <div class="mb-6">
                            <label class="block font-medium text-gray-700 mb-2">Rekomendasi / Catatan Admin</label>
                            <textarea name="rekomendasi_admin" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Misal: Sediakan bensin penuh, tujuan jauh..."></textarea>
                        </div>

                        <div class="flex items-center gap-4">
                            <button type="submit" name="status_permohonan" value="Menunggu Proses SPSI" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow">
                                Setujui & Teruskan ke SPSI
                            </button>
                            <button type="submit" name="status_permohonan" value="Ditolak" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded shadow" onclick="return confirm('Yakin ingin menolak permohonan ini?')">
                                Tolak Permohonan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
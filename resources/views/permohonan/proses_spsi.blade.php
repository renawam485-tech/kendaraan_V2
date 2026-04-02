<x-app-layout>
    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-8 border-b border-gray-200">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Alokasi Armada & Estimasi Biaya (SPSI)</h2>
                    
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                        <p class="text-sm font-bold">Kebutuhan Pemohon:</p>
                        <p class="text-sm">{{ $permohonan->kendaraan_dibutuhkan }} ({{ $permohonan->jumlah_penumpang }} Penumpang)</p>
                        <p class="text-sm mt-2 font-bold">Rekomendasi / Catatan Admin ({{ $permohonan->kategori_kegiatan }}):</p>
                        <p class="text-sm italic">{{ $permohonan->rekomendasi_admin ?? 'Tidak ada catatan khusus.' }}</p>
                        <p class="text-sm mt-2 font-bold">Waktu Penggunaan:</p>
                        <p class="text-sm text-red-600">{{ \Carbon\Carbon::parse($permohonan->waktu_berangkat)->format('d M Y H:i') }} - {{ \Carbon\Carbon::parse($permohonan->waktu_kembali)->format('d M Y H:i') }}</p>
                    </div>

                    <form action="{{ route('permohonan.proses_spsi_submit', $permohonan->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block font-medium text-gray-700 mb-2">Pilih Kendaraan <span class="text-red-500">*</span></label>
                                <select name="kendaraan_id" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                                    <option value="">-- Pilih Armada --</option>
                                    @foreach($kendaraans as $k)
                                        <option value="{{ $k->id }}">{{ $k->nama_kendaraan }} ({{ $k->plat_nomor }}) - Kap: {{ $k->kapasitas_penumpang }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block font-medium text-gray-700 mb-2">Pilih Pengemudi</label>
                                <select name="pengemudi_id" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                                    <option value="">-- Tanpa Pengemudi / Lepas Kunci --</option>
                                    @foreach($pengemudis as $p)
                                        <option value="{{ $p->id }}">{{ $p->nama_pengemudi }} ({{ $p->kontak }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block font-medium text-gray-700 mb-2">Estimasi Biaya Operasional (Rp) <span class="text-red-500">*</span></label>
                            <p class="text-xs text-gray-500 mb-2">BBM, Tol, Parkir, dll. Ketik angka saja tanpa titik/koma.</p>
                            <input type="number" name="estimasi_biaya_operasional" required min="0" placeholder="Contoh: 150000"
                                class="w-full md:w-1/2 border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                        </div>

                        <div class="flex items-center gap-4">
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded shadow">
                                Simpan & Teruskan ke Keuangan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
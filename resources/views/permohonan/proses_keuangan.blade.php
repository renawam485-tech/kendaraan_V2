<x-app-layout>
    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-8 border-b border-gray-200">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Persetujuan RAB & Pembayaran (Keuangan)</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 bg-blue-50 p-6 rounded-md border border-blue-200">
                        <div>
                            <p class="text-sm text-gray-500">Pemohon / Kategori:</p>
                            <p class="font-bold">{{ $permohonan->nama_pic }} / <span class="text-purple-600">{{ $permohonan->kategori_kegiatan }}</span></p>
                            <p class="text-xs text-gray-600 mt-1">Sumber dana dari pengguna: {{ $permohonan->anggaran_diajukan }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Armada Dialokasikan:</p>
                            <p class="font-bold">{{ $permohonan->kendaraan->nama_kendaraan ?? '-' }} ({{ $permohonan->kendaraan->plat_nomor ?? '-' }})</p>
                            <p class="text-xs text-gray-600 mt-1">Supir: {{ $permohonan->pengemudi->nama_pengemudi ?? 'Tanpa Supir' }}</p>
                        </div>
                        <div class="col-span-2 border-t border-blue-200 pt-4 mt-2">
                            <p class="text-sm text-gray-500">Estimasi Biaya dari SPSI:</p>
                            <p class="text-2xl font-black text-red-600">Rp {{ number_format($permohonan->estimasi_biaya_operasional, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <form action="{{ route('permohonan.proses_keuangan_submit', $permohonan->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div>
                                <label class="block font-medium text-gray-700 mb-2">RAB Disetujui (Rp) <span class="text-red-500">*</span></label>
                                <input type="number" name="rab_disetujui" required min="0" 
                                    value="{{ $permohonan->estimasi_biaya_operasional }}" 
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500">
                                <p class="text-xs text-gray-500 mt-1">Bisa disesuaikan jika berbeda dengan estimasi SPSI.</p>
                            </div>

                            <div>
                                <label class="block font-medium text-gray-700 mb-2">Mekanisme Pembayaran <span class="text-red-500">*</span></label>
                                <select name="mekanisme_pembayaran" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500">
                                    <option value="">-- Pilih Mekanisme --</option>
                                    <option value="Transfer ke Pengemudi / Kasbon">Transfer ke Pengemudi / Kasbon</option>
                                    <option value="Reimburse oleh Pemohon">Reimburse oleh Pemohon</option>
                                    <option value="Biaya Ditanggung Pemohon (Non SITH)">Biaya Ditanggung Pemohon (Non SITH)</option>
                                    <option value="Lainnya">Lainnya...</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 px-6 rounded shadow">
                                Setujui Anggaran & Kembalikan ke Admin
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
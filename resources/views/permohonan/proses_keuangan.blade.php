<x-app-layout>
    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 border border-gray-200">
                <div class="p-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b border-gray-100 pb-4">Persetujuan RAB & Pembayaran (Keuangan)</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8 bg-gray-50 p-6 rounded-md border border-gray-200">
                        <div>
                            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Pemohon / Kategori</p>
                            <p class="font-bold text-gray-900 text-lg">{{ $permohonan->nama_pic }}</p>
                            <p class="text-sm font-bold text-green-700">{{ $permohonan->kategori_kegiatan }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Armada Dialokasikan SPSI</p>
                            <p class="font-bold text-gray-900 text-lg">
                                {{ $permohonan->kendaraan_id ? $permohonan->kendaraan->nama_kendaraan : $permohonan->kendaraan_vendor }} 
                                {{ $permohonan->kendaraan_id ? '('.$permohonan->kendaraan->plat_nomor.')' : '(Vendor)' }}
                            </p>
                            <p class="text-sm text-gray-600">Supir: {{ $permohonan->pengemudi->nama_pengemudi ?? 'Lepas Kunci' }}</p>
                        </div>
                        <div class="col-span-1 md:col-span-2 border-t border-gray-200 pt-4 mt-2">
                            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Estimasi RAB dari SPSI</p>
                            <p class="text-2xl font-black text-gray-900">Rp {{ number_format($permohonan->estimasi_biaya_operasional, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <form action="{{ route('permohonan.proses_keuangan_submit', $permohonan->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div>
                                <label class="block font-bold text-gray-800 mb-2">RAB Disetujui (Rp) <span class="text-red-500">*</span></label>
                                <input type="number" name="rab_disetujui" required min="0" 
                                    value="{{ $permohonan->estimasi_biaya_operasional }}" 
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 transition">
                            </div>

                            <div>
                                <label class="block font-bold text-gray-800 mb-2">Mekanisme Pembayaran <span class="text-red-500">*</span></label>
                                <select name="mekanisme_pembayaran" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 transition">
                                    <option value="">-- Pilih Mekanisme --</option>
                                    <option value="Cash (Tunai)">Cash (Uang Tunai)</option>
                                    <option value="Cashless (Transfer/E-Toll)">Cashless (Transfer Bank / E-Toll)</option>
                                    <option value="Reimburse">Reimburse (Ditalangi Pemohon)</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 border-t border-gray-100 pt-6">
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 px-6 rounded-md shadow-sm transition">
                                Setujui Anggaran & Teruskan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
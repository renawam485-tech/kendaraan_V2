<x-app-layout>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container .select2-selection--single { height: 42px; border: 1px solid #d1d5db; border-radius: 0.375rem; display: flex; align-items: center; }
        .select2-container--default .select2-selection--single:focus { border-color: #22c55e; box-shadow: 0 0 0 1px #22c55e; }
        .select2-container--default .select2-selection--single .select2-selection__arrow { height: 40px; right: 10px; }
    </style>

    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 border border-gray-200">
                <div class="p-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b border-gray-100 pb-4">Alokasi Armada & Supir (SPSI)</h2>
                    
                    <div class="flex flex-col md:flex-row gap-4 mb-8">
                        <div class="flex-1 border {{ $permohonan->kategori_kegiatan === 'Non SITH' ? 'border-gray-300 bg-gray-50 text-gray-700' : 'border-green-200 bg-green-50 text-green-800' }} rounded-lg p-4">
                            <p class="text-xs uppercase tracking-wider font-bold opacity-70 mb-1">Status Kegiatan</p>
                            <p class="text-lg font-black">{{ $permohonan->kategori_kegiatan === 'Non SITH' ? 'NON-DINAS / MANDIRI' : 'DINAS SITH' }}</p>
                        </div>
                        <div class="flex-[2] border border-gray-200 bg-white rounded-lg p-4 shadow-sm">
                            <p class="text-xs uppercase tracking-wider font-bold text-gray-500 mb-1">Catatan/Instruksi Admin</p>
                            @if($permohonan->rekomendasi_admin)
                                <p class="font-medium text-gray-800">{{ $permohonan->rekomendasi_admin }}</p>
                            @else
                                <p class="text-gray-400 italic">Tidak ada instruksi tambahan dari Admin.</p>
                            @endif
                        </div>
                    </div>

                    <div class="mb-6 grid grid-cols-2 gap-4 text-sm bg-gray-50 p-4 rounded border border-gray-100">
                        <div><span class="text-gray-500 block">Kebutuhan Awal:</span> <strong class="text-gray-800">{{ $permohonan->kendaraan_dibutuhkan }} ({{ $permohonan->jumlah_penumpang }} Org)</strong></div>
                        <div><span class="text-gray-500 block">Waktu:</span> <strong class="text-gray-800">{{ \Carbon\Carbon::parse($permohonan->waktu_berangkat)->format('d M H:i') }} - {{ \Carbon\Carbon::parse($permohonan->waktu_kembali)->format('d M H:i') }}</strong></div>
                    </div>

                    <form action="{{ route('permohonan.proses_spsi_submit', $permohonan->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div class="md:col-span-2">
                                <label class="block font-bold text-gray-700 mb-2">Pilih Sumber Armada <span class="text-red-500">*</span></label>
                                <select id="sumber_armada" name="sumber_armada" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500" onchange="toggleArmada()">
                                    <option value="">-- Tentukan Sumber Kendaraan --</option>
                                    <option value="Kampus">Mobil Kampus (Aset Internal)</option>
                                    <option value="Vendor">Sewa Vendor Luar</option>
                                </select>
                            </div>

                            <div id="div_kampus" class="md:col-span-2 hidden">
                                <label class="block font-bold text-gray-700 mb-2">Alokasikan Mobil Kampus <span class="text-red-500">*</span></label>
                                <select id="kendaraan_id" name="kendaraan_id" class="select2 w-full">
                                    <option value="">-- Pilih Mobil Master Data --</option>
                                    @foreach($kendaraans as $k)
                                        <option value="{{ $k->id }}">{{ $k->nama_kendaraan }} ({{ $k->plat_nomor }}) - Kap: {{ $k->kapasitas_penumpang }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div id="div_vendor" class="md:col-span-2 hidden">
    <label class="block font-bold text-gray-700 mb-2">Tentukan Mobil Vendor Luar <span class="text-red-500">*</span></label>
    <select id="kendaraan_vendor_id" name="kendaraan_vendor_id" class="select2 w-full">
        <option value="">-- Cari & Pilih Mobil Vendor --</option>
        @foreach($kendaraanVendors as $v)
            <option value="{{ $v->id }}">
                {{ $v->nama_vendor }} - {{ $v->nama_kendaraan }} (Kap: {{ $v->kapasitas_penumpang }})
            </option>
        @endforeach
    </select>
</div> 

                            <div>
                                <label class="block font-bold text-gray-700 mb-2">Pilih Pengemudi</label>
                                <select name="pengemudi_id" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                                    <option value="">-- Tanpa Pengemudi / Lepas Kunci --</option>
                                    @foreach($pengemudis as $p)
                                        <option value="{{ $p->id }}">{{ $p->nama_pengemudi }} ({{ $p->kontak }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        @if($permohonan->kategori_kegiatan === 'Non SITH')
                            <input type="hidden" name="estimasi_biaya_operasional" value="0">
                            <div class="mb-6 p-4 bg-gray-50 border border-gray-200 rounded-md">
                                <p class="text-sm text-gray-600 font-medium flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Kolom anggaran disembunyikan otomatis karena ini kegiatan Non-Dinas (Biaya pribadi pemohon).
                                </p>
                            </div>
                        @else
                            <div class="mb-8">
                                <label class="block font-bold text-gray-700 mb-2">Estimasi Biaya Operasional (Rp) <span class="text-red-500">*</span></label>
                                <input type="number" name="estimasi_biaya_operasional" required min="0" placeholder="Ketik angka tanpa titik..."
                                    class="w-full md:w-1/2 border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                            </div>
                        @endif

                        <div class="flex items-center gap-4 pt-4 border-t border-gray-100">
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 px-6 rounded-md shadow-sm transition">
                                Simpan Alokasi Armada
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({ width: '100%' });
        });

        function toggleArmada() {
            const sumber = document.getElementById('sumber_armada').value;
            const divKampus = document.getElementById('div_kampus');
            const divVendor = document.getElementById('div_vendor');
            const selKampus = document.getElementById('kendaraan_id');
            const selVendor = document.getElementById('kendaraan_vendor_id');

            if(sumber === 'Kampus') {
                divKampus.classList.remove('hidden'); divVendor.classList.add('hidden');
                selKampus.required = true; selVendor.required = false; selVendor.value = '';
            } else if(sumber === 'Vendor') {
                divVendor.classList.remove('hidden'); divKampus.classList.add('hidden');
                selVendor.required = true; selKampus.required = false; selKampus.value = '';
            } else {
                divKampus.classList.add('hidden'); divVendor.classList.add('hidden');
                selKampus.required = false; selVendor.required = false;
            }
        }
    </script>
</x-app-layout>
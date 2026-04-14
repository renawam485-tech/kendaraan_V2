<x-app-layout>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container {
            width: 100% !important;
        }

        .select2-container--default .select2-selection--single {
            height: 40px;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            background: #fff;
            transition: all .15s;
        }

        .select2-container--default.select2-container--open .select2-selection--single,
        .select2-container--default .select2-selection--single:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, .2);
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 38px;
            right: 8px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #374151;
            font-size: .875rem;
            padding-left: 12px;
        }

        .select2-dropdown {
            border: 1px solid #3b82f6;
            border-radius: .5rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, .1);
        }

        .select2-results__option--highlighted {
            background: #3b82f6 !important;
        }
    </style>

    <div class="py-6 bg-slate-50 min-h-screen">
        <div class="w-full px-4 sm:px-6 lg:px-8">

            <div class="flex items-center gap-2 text-sm text-gray-500 mb-5">
                <a href="{{ route('spsi.alokasi') }}" class="hover:text-blue-600 transition flex items-center gap-1">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <span class="text-gray-300">/</span>
                <span class="text-gray-700 font-medium">Alokasi Armada</span>
                @if ($permohonan->kode_permohonan)
                    <span class="text-gray-300">/</span>
                    <span
                        class="font-black text-blue-700 bg-blue-50 border border-blue-200 px-2 py-0.5 rounded-md text-xs tracking-widest">{{ $permohonan->kode_permohonan }}</span>
                @endif
            </div>

            {{-- INFO KEGIATAN --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-5">
                <div class="px-6 py-4 border-b border-gray-100 bg-slate-50 flex items-center gap-2">
                    <i class="bi bi-info-circle text-blue-600"></i>
                    <h3 class="font-bold text-gray-800 text-sm">Informasi Kegiatan</h3>
                </div>
                <div class="p-5 grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <div
                        class="{{ $permohonan->kategori_kegiatan === 'Non SITH' ? 'bg-gray-50 border-gray-200' : 'bg-green-50 border-green-200' }} rounded-xl border p-4">
                        <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-1">Jenis Kegiatan</p>
                        <p class="font-black text-gray-800 text-sm">
                            {{ $permohonan->kategori_kegiatan === 'Non SITH' ? 'NON-DINAS' : 'DINAS SITH' }}</p>
                    </div>
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-4">
                        <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-1">Kebutuhan Awal</p>
                        <p class="font-semibold text-gray-800 text-sm">{{ $permohonan->kendaraan_dibutuhkan }}</p>
                        <p class="text-xs text-gray-500"><i
                                class="bi bi-people mr-0.5"></i>{{ $permohonan->jumlah_penumpang }} orang</p>
                    </div>
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-4">
                        <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-1">Jadwal</p>
                        <p class="font-semibold text-gray-800 text-sm">
                            {{ \Carbon\Carbon::parse($permohonan->waktu_berangkat)->format('d M, H:i') }}</p>
                        <p class="text-xs text-gray-500">s/d
                            {{ \Carbon\Carbon::parse($permohonan->waktu_kembali)->format('d M, H:i') }}</p>
                    </div>
                </div>
                @if ($permohonan->rekomendasi_admin)
                    <div class="px-5 pb-5">
                        <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 flex items-start gap-2">
                            <i class="bi bi-chat-quote-fill text-amber-500 flex-shrink-0 mt-0.5"></i>
                            <p class="text-sm text-amber-800"><strong>Instruksi Admin:</strong>
                                {{ $permohonan->rekomendasi_admin }}</p>
                        </div>
                    </div>
                @endif
            </div>

            {{-- FORM ALOKASI --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-slate-50 flex items-center gap-2">
                    <i class="bi bi-signpost-split-fill text-blue-600"></i>
                    <h3 class="font-bold text-gray-800 text-sm">Form Alokasi Armada</h3>
                </div>
                <div class="p-6">
                    <form action="{{ route('permohonan.proses_spsi_submit', $permohonan->id) }}" method="POST"
                        class="space-y-6">
                        @csrf @method('PUT')

                        @if ($errors->any())
                            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                <p class="text-sm font-bold text-red-700 mb-1 flex items-center gap-1"><i
                                        class="bi bi-exclamation-triangle"></i> Perbaiki kesalahan:</p>
                                <ul class="text-sm text-red-600 list-disc list-inside">
                                    @foreach ($errors->all() as $e)
                                        <li>{{ $e }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- PILIH SUMBER --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-2">Sumber Armada <span
                                    class="text-red-500">*</span></label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach ([['Kampus', 'Mobil Kampus (Aset Internal)', 'bi-building', 'text-blue-600', 'bg-blue-50 border-blue-200'], ['Vendor', 'Sewa Vendor Luar', 'bi-shop', 'text-orange-600', 'bg-orange-50 border-orange-200']] as [$val, $lbl, $icon, $tc, $bc])
                                    <label
                                        class="flex items-center gap-3 p-4 rounded-xl border-2 cursor-pointer transition hover:shadow-md {{ $bc }} has-[:checked]:ring-2 has-[:checked]:ring-blue-400">
                                        <input type="radio" name="sumber_armada" value="{{ $val }}" required
                                            class="text-blue-600 focus:ring-blue-500" onchange="toggleArmada()">
                                        <div class="flex items-center gap-2">
                                            <i class="bi {{ $icon }} {{ $tc }} text-lg"></i>
                                            <div>
                                                <p class="font-bold text-gray-800 text-sm">{{ $lbl }}</p>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- PILIH KENDARAAN KAMPUS --}}
                        <div id="div_kampus" class="hidden">
                            <label class="block text-sm font-bold text-gray-800 mb-1.5">Pilih Kendaraan Kampus <span
                                    class="text-red-500">*</span></label>
                            <select id="kendaraan_id" name="kendaraan_id" class="select2-kend w-full" disabled>
                                <option value="">-- Pilih Kendaraan --</option>
                                @foreach ($kendaraans as $k)
                                    <option value="{{ $k->id }}">{{ $k->nama_kendaraan }}
                                        ({{ $k->plat_nomor }}) — Kapasitas: {{ $k->kapasitas_penumpang }} orang
                                    </option>
                                @endforeach
                            </select>
                            @if ($kendaraans->isEmpty())
                                <p class="text-xs text-orange-600 mt-1.5"><i
                                        class="bi bi-exclamation-triangle mr-1"></i>Tidak ada kendaraan tersedia saat
                                    ini.</p>
                            @endif
                        </div>

                        {{-- PILIH KENDARAAN VENDOR --}}
                        <div id="div_vendor" class="hidden">
                            <label class="block text-sm font-bold text-gray-800 mb-1.5">Pilih Kendaraan Vendor <span
                                    class="text-red-500">*</span></label>
                            <select id="kendaraan_vendor_id" name="kendaraan_vendor_id" class="select2-vendor w-full"
                                disabled>
                                <option value="">-- Cari & Pilih Mobil Vendor --</option>
                                @foreach ($kendaraanVendors as $v)
                                    <option value="{{ $v->id }}">{{ $v->nama_vendor }} —
                                        {{ $v->nama_kendaraan }} ({{ $v->kapasitas_penumpang }} org)</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- PENGEMUDI --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-1.5">Pengemudi <span
                                    class="text-gray-400 font-normal">(opsional)</span></label>
                            <select name="pengemudi_id"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm transition">
                                <option value="">— Tanpa Pengemudi / Lepas Kunci —</option>
                                @foreach ($pengemudis as $p)
                                    <option value="{{ $p->id }}">{{ $p->nama_pengemudi }}
                                        ({{ $p->kontak }})</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- ESTIMASI BIAYA --}}
                        @if ($permohonan->kategori_kegiatan === 'Non SITH')
                            <input type="hidden" name="estimasi_biaya_operasional" value="0">
                            <div class="bg-slate-50 border border-slate-200 rounded-lg p-4 flex items-center gap-2">
                                <i class="bi bi-info-circle text-blue-500 flex-shrink-0"></i>
                                <p class="text-sm text-gray-600">Kolom anggaran tidak diperlukan karena ini adalah
                                    kegiatan Non-Dinas.</p>
                            </div>
                        @else
                            <div>
                                <label class="block text-sm font-bold text-gray-800 mb-1.5">Estimasi Biaya Operasional
                                    (Rp) <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <span
                                        class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm font-medium">Rp</span>
                                    <input type="number" name="estimasi_biaya_operasional" required min="0"
                                        placeholder="0"
                                        class="w-full pl-10 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm transition">
                                </div>
                                <p class="text-xs text-gray-400 mt-1">Masukkan angka tanpa titik/koma</p>
                            </div>
                        @endif

                        <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
                            <button type="submit"
                                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-lg shadow-sm transition text-sm">
                                <i class="bi bi-floppy2-fill"></i> Simpan Alokasi Armada
                            </button>
                            <a href="{{ route('spsi.alokasi') }}"
                                class="text-sm text-gray-500 hover:text-gray-700 transition">Batal</a>
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
            $('.select2-kend, .select2-vendor').select2({
                width: '100%'
            });
        });

        function toggleArmada() {
            const val = document.querySelector('input[name="sumber_armada"]:checked')?.value;
            const kampusDiv = document.getElementById('div_kampus');
            const vendorDiv = document.getElementById('div_vendor');
            const kendaraanSel = document.getElementById('kendaraan_id');
            const vendorSel = document.getElementById('kendaraan_vendor_id');

            if (val === 'Kampus') {
                kampusDiv.classList.remove('hidden');
                vendorDiv.classList.add('hidden');
                kendaraanSel.disabled = false;
                kendaraanSel.required = true;
                vendorSel.disabled = true;
                vendorSel.required = false;
                vendorSel.value = '';
                // Reset Select2 vendor
                $('#kendaraan_vendor_id').val(null).trigger('change');
            } else if (val === 'Vendor') {
                vendorDiv.classList.remove('hidden');
                kampusDiv.classList.add('hidden');
                vendorSel.disabled = false;
                vendorSel.required = true;
                kendaraanSel.disabled = true;
                kendaraanSel.required = false;
                kendaraanSel.value = '';
                // Reset Select2 kampus
                $('#kendaraan_id').val(null).trigger('change');
            }
        }
    </script>
</x-app-layout>

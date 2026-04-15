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

        .select2-results__options {
            max-height: 250px !important;
            overflow-y: auto !important;
        }

        .select2-results__option--highlighted {
            background: #3b82f6 !important;
        }
    </style>

    <div class="py-6 bg-slate-50 min-h-screen">
        <div class="w-full px-4 sm:px-6 lg:px-8">

            {{-- Tombol Kembali --}}
            <div class="mb-5">
                <a href="{{ route('spsi.alokasi') }}"
                    class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-blue-600 transition bg-white border border-gray-200 px-3 py-2 rounded-lg shadow-sm">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>

            {{-- DATA PEMOHON --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-5">
                <div
                    class="px-4 sm:px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50 flex items-center justify-between flex-wrap gap-2">
                    <div class="flex items-center gap-2">
                        <i class="bi bi-person-lines-fill text-blue-600"></i>
                        <h3 class="font-bold text-gray-800">Data Permohonan</h3>
                    </div>
                    <div class="flex items-center gap-3 flex-wrap">
                        <span class="text-xs text-gray-400" id="diajukan-time"
                            data-created="{{ $permohonan->created_at->toISOString() }}">
                            <i class="bi bi-clock-history"></i> <span class="font-semibold"
                                id="relative-time">{{ $permohonan->created_at->diffForHumans() }}</span>
                        </span>
                    </div>
                </div>
                <div class="divide-y divide-gray-100">
                    <div class="px-4 sm:px-6 py-3.5">
                        <span class="text-sm text-gray-500 flex items-center gap-2 mb-1">
                            <i class="bi bi-upc-scan text-gray-400"></i>Kode Pengajuan
                        </span>
                        <p class="text-sm font-black text-blue-700 break-words">
                            {{ $permohonan->kode_permohonan ?? '—' }}
                        </p>
                    </div>

                    {{-- Pemohon --}}
                    <div class="px-4 sm:px-6 py-3.5">
                        <span class="text-sm text-gray-500 flex items-center gap-2 mb-1">
                            <i class="bi bi-person text-gray-400"></i>Nama PIC
                        </span>
                        <p class="text-sm font-semibold text-gray-800 break-words">{{ $permohonan->nama_pic }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $permohonan->kontak_pic }}</p>
                    </div>

                    {{-- Kebutuhan Kendaraan --}}
                    <div class="px-4 sm:px-6 py-3.5">
                        <span class="text-sm text-gray-500 flex items-center gap-2 mb-1">
                            <i class="bi bi-truck text-gray-400"></i>Kebutuhan Kendaraan
                        </span>
                        <p class="text-sm font-semibold text-gray-800 break-words">
                            {{ $permohonan->kendaraan_dibutuhkan }}</p>
                        <p class="text-xs text-gray-500 mt-0.5"><i
                                class="bi bi-people mr-0.5"></i>{{ $permohonan->jumlah_penumpang }} orang</p>
                    </div>

                    {{-- Rute --}}
                    <div class="px-4 sm:px-6 py-3.5">
                        <span class="text-sm text-gray-500 flex items-center gap-2 mb-1">
                            <i class="bi bi-signpost-split text-gray-400"></i>Rute Perjalanan
                        </span>
                        <p class="text-sm text-gray-700 break-words">Dari: {{ $permohonan->titik_jemput }}</p>
                        <p class="text-sm text-gray-700 break-words">Tujuan: {{ $permohonan->tujuan }}</p>
                    </div>

                    {{-- Jadwal --}}
                    <div class="px-4 sm:px-6 py-3.5">
                        <span class="text-sm text-gray-500 flex items-center gap-2 mb-1">
                            <i class="bi bi-calendar-event text-gray-400"></i>Jadwal Perjalanan
                        </span>
                        <p class="text-sm font-medium text-gray-700">
                            {{ \Carbon\Carbon::parse($permohonan->waktu_berangkat)->translatedFormat('l, d M Y H:i') }}
                            WIB</p>
                        <p class="text-xs text-gray-500">s/d
                            {{ \Carbon\Carbon::parse($permohonan->waktu_kembali)->translatedFormat('l, d M Y H:i') }}
                            WIB</p>
                    </div>

                    {{-- KATEGORI KEGIATAN (Pengganti Anggaran Diajukan) --}}
                    <div class="px-4 sm:px-6 py-3.5">
                        <span class="text-sm text-gray-500 flex items-center gap-2 mb-1">
                            <i class="bi bi-tag text-gray-400"></i>Kategori Kegiatan
                        </span>
                        <div>
                            @if ($permohonan->kategori_kegiatan === 'Dinas SITH')
                                <span
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-bold bg-green-50 text-green-700 border border-green-200">
                                    <i class="bi bi-building-check"></i> Dinas SITH
                                </span>
                                <p class="text-xs text-gray-500 mt-1">Biaya ditanggung instansi</p>
                            @elseif($permohonan->kategori_kegiatan === 'Non SITH')
                                <span
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-bold bg-gray-50 text-gray-600 border border-gray-200">
                                    <i class="bi bi-person-check"></i> Non SITH
                                </span>
                                <p class="text-xs text-gray-500 mt-1">Biaya ditanggung pribadi pemohon</p>
                            @else
                                <p class="text-sm text-gray-400 italic">Belum ditentukan</p>
                            @endif
                        </div>
                    </div>

                    {{-- Surat Penugasan --}}
                    <div class="px-4 sm:px-6 py-3.5">
                        <span class="text-sm text-gray-500 flex items-center gap-2 mb-1">
                            <i class="bi bi-file-earmark-pdf text-gray-400"></i>Surat Penugasan
                        </span>
                        @if ($permohonan->file_surat_penugasan)
                            <a href="{{ asset('storage/' . $permohonan->file_surat_penugasan) }}" target="_blank"
                                class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200 px-3 py-1.5 rounded-lg transition">
                                <i class="bi bi-file-earmark-pdf"></i> Lihat Dokumen
                            </a>
                        @else
                            <p class="text-sm text-gray-400 italic">Tidak ada</p>
                        @endif
                    </div>

                    {{-- Catatan Pemohon --}}
                    @if ($permohonan->catatan_pemohon)
                        <div class="px-4 sm:px-6 py-3.5">
                            <span class="text-sm text-gray-500 flex items-center gap-2 mb-1">
                                <i class="bi bi-chat-quote text-gray-400"></i>Catatan dari Pemohon
                            </span>
                            <div
                                class="bg-amber-50 border border-amber-200 rounded-lg p-3 text-sm text-amber-800 break-words">
                                {{ $permohonan->catatan_pemohon }}
                            </div>
                        </div>
                    @endif

                    {{-- Instruksi Admin --}}
                    @if ($permohonan->rekomendasi_admin)
                        <div class="px-4 sm:px-6 py-3.5">
                            <span class="text-sm text-gray-500 flex items-center gap-2 mb-1">
                                <i class="bi bi-chat-quote-fill text-amber-500"></i>Instruksi Admin
                            </span>
                            <div
                                class="bg-amber-50 border border-amber-200 rounded-lg p-3 text-sm text-amber-800 break-words">
                                {{ $permohonan->rekomendasi_admin }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- FORM ALOKASI --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-4 sm:px-6 py-4 border-b border-gray-100 bg-slate-50 flex items-center gap-2">
                    <i class="bi bi-signpost-split-fill text-blue-600"></i>
                    <h3 class="font-bold text-gray-800 text-sm">Form Alokasi Armada</h3>
                </div>
                <div class="p-4 sm:p-6">
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
                                            id="sumber_{{ strtolower($val) }}"
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
                            <label for="kendaraan_id" class="block text-sm font-bold text-gray-800 mb-1.5">Pilih
                                Kendaraan Kampus <span class="text-red-500">*</span></label>
                            <select id="kendaraan_id" name="kendaraan_id" class="select2-kend w-full" disabled>
                                <option value="">-- Pilih Kendaraan --</option>
                                @foreach ($kendaraans as $k)
                                    <option value="{{ $k->id }}">{{ $k->nama_kendaraan }}
                                        ({{ $k->plat_nomor }})
                                        — {{ $k->kapasitas_penumpang }} orang</option>
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
                            <label for="kendaraan_vendor_id"
                                class="block text-sm font-bold text-gray-800 mb-1.5">Pilih Kendaraan Vendor <span
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
                            <label for="pengemudi_id" class="block text-sm font-bold text-gray-800 mb-1.5">Pengemudi
                                <span class="text-gray-400 font-normal">(opsional)</span></label>
                            <select id="pengemudi_id" name="pengemudi_id" class="select2-pengemudi w-full">
                                <option value="">-- Tanpa Pengemudi --</option>
                                @foreach ($pengemudis as $p)
                                    <option value="{{ $p->id }}">{{ $p->nama_pengemudi }}
                                        ({{ $p->kontak }})
                                        — {{ $p->status_pengemudi }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- ESTIMASI BIAYA --}}
                        @if ($permohonan->kategori_kegiatan === 'Non SITH')
                            <input type="hidden" name="estimasi_biaya_operasional" value="0">
                        @else
                            <div>
                                <label for="estimasi_biaya"
                                    class="block text-sm font-bold text-gray-800 mb-1.5">Estimasi Biaya Operasional
                                    (Rp) <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <span
                                        class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm font-medium">Rp</span>
                                    <input type="number" id="estimasi_biaya" name="estimasi_biaya_operasional"
                                        required min="0" placeholder="0"
                                        class="w-full pl-10 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm transition">
                                </div>
                                <p class="text-xs text-gray-400 mt-1">Masukkan angka tanpa titik/koma</p>
                            </div>
                        @endif

                        <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
                            <button type="submit"
                                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-lg shadow-sm transition text-sm">
                                <i class="bi bi-floppy2-fill"></i> Simpan
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
            $('.select2-kend').select2({
                width: '100%',
                placeholder: '-- Pilih Kendaraan --',
                allowClear: true,
                dropdownParent: $('body')
            });

            $('.select2-vendor').select2({
                width: '100%',
                placeholder: '-- Cari & Pilih Mobil Vendor --',
                allowClear: true,
                dropdownParent: $('body')
            });

            $('.select2-pengemudi').select2({
                width: '100%',
                placeholder: '-- Cari & Pilih Pengemudi (Lepas Kunci) --',
                allowClear: true,
                dropdownParent: $('body')
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
                $('#kendaraan_vendor_id').val(null).trigger('change');
            } else if (val === 'Vendor') {
                vendorDiv.classList.remove('hidden');
                kampusDiv.classList.add('hidden');
                vendorSel.disabled = false;
                vendorSel.required = true;
                kendaraanSel.disabled = true;
                kendaraanSel.required = false;
                kendaraanSel.value = '';
                $('#kendaraan_id').val(null).trigger('change');
            }
        }

        function updateRelativeTime() {
            const element = document.getElementById('relative-time');
            const container = document.getElementById('diajukan-time');
            if (element && container) {
                const createdDate = new Date(container.dataset.created);
                const now = new Date();
                const diffInSeconds = Math.floor((now - createdDate) / 1000);
                const diffInMinutes = Math.floor(diffInSeconds / 60);
                const diffInHours = Math.floor(diffInMinutes / 60);
                const diffInDays = Math.floor(diffInHours / 24);
                const diffInMonths = Math.floor(diffInDays / 30);
                const diffInYears = Math.floor(diffInDays / 365);

                let relativeText = '';
                if (diffInSeconds < 60) {
                    relativeText = 'baru saja';
                } else if (diffInMinutes < 60) {
                    relativeText = diffInMinutes + ' menit yang lalu';
                } else if (diffInHours < 24) {
                    relativeText = diffInHours + ' jam yang lalu';
                } else if (diffInDays < 30) {
                    relativeText = diffInDays + ' hari yang lalu';
                } else if (diffInMonths < 12) {
                    relativeText = diffInMonths + ' bulan yang lalu';
                } else {
                    relativeText = diffInYears + ' tahun yang lalu';
                }
                element.textContent = relativeText;
            }
        }

        setInterval(updateRelativeTime, 60000);
        updateRelativeTime();
    </script>
</x-app-layout>

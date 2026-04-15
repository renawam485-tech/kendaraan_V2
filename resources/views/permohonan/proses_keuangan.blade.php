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

            {{-- Tombol Kembali --}}
            <div class="mb-5">
                <a href="{{ route('keuangan.rab') }}"
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
                    {{-- Kode Permohonan --}}
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

                    {{-- Tujuan --}}
                    <div class="px-4 sm:px-6 py-3.5">
                        <span class="text-sm text-gray-500 flex items-center gap-2 mb-1">
                            <i class="bi bi-geo-alt text-gray-400"></i>Tujuan
                        </span>
                        <p class="text-sm font-semibold text-gray-800 break-words">{{ $permohonan->tujuan }}</p>
                    </div>

                    {{-- Kategori Kegiatan --}}
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

                    {{-- Armada Dialokasikan --}}
                    <div class="px-4 sm:px-6 py-3.5">
                        <span class="text-sm text-gray-500 flex items-center gap-2 mb-1">
                            <i class="bi bi-car-front text-gray-400"></i>Armada Dialokasikan
                        </span>
                        <div>
                            @if ($permohonan->kendaraan_id && $permohonan->kendaraan)
                                <p class="font-semibold text-gray-800 text-sm">
                                    {{ $permohonan->kendaraan->nama_kendaraan }}</p>
                                <p class="text-xs text-gray-500">{{ $permohonan->kendaraan->plat_nomor }}</p>
                            @elseif($permohonan->kendaraanVendor)
                                <p class="font-semibold text-gray-800 text-sm">
                                    {{ $permohonan->kendaraanVendor->nama_kendaraan }}</p>
                                <p class="text-xs text-gray-500">{{ $permohonan->kendaraanVendor->nama_vendor }}</p>
                                <span
                                    class="text-[10px] font-bold text-orange-600 bg-orange-50 border border-orange-100 px-1 rounded inline-block mt-1">VENDOR</span>
                            @else
                                <p class="text-gray-400 italic text-sm">—</p>
                            @endif
                        </div>
                    </div>

                    {{-- Pengemudi --}}
                    <div class="px-4 sm:px-6 py-3.5">
                        <span class="text-sm text-gray-500 flex items-center gap-2 mb-1">
                            <i class="bi bi-person-badge text-gray-400"></i>Pengemudi
                        </span>
                        <p class="font-medium text-gray-700 text-sm">
                            {{ $permohonan->pengemudi->nama_pengemudi ?? 'Tanpa Pengemudi' }}</p>
                    </div>

                    {{-- Jadwal --}}
                    <div class="px-4 sm:px-6 py-3.5">
                        <span class="text-sm text-gray-500 flex items-center gap-2 mb-1">
                            <i class="bi bi-calendar-event text-gray-400"></i>Jadwal Perjalanan
                        </span>
                        <p class="text-sm font-medium text-gray-700">
                            {{ \Carbon\Carbon::parse($permohonan->waktu_berangkat)->translatedFormat('l, d M Y H:i') }}
                            WIB
                        </p>
                        <p class="text-xs text-gray-500">s/d
                            {{ \Carbon\Carbon::parse($permohonan->waktu_kembali)->translatedFormat('l, d M Y H:i') }}
                            WIB</p>
                    </div>

                    {{-- Estimasi Biaya  --}}
                    <div class="px-4 sm:px-6 py-3.5 bg-blue-50">
                        <span class="text-sm text-gray-500 flex items-center gap-2 mb-1">
                            <i class="bi bi-calculator text-blue-600"></i>Estimasi Biaya Operasional
                        </span>
                        <p class="text-2xl font-black text-gray-900">Rp
                            {{ number_format($permohonan->estimasi_biaya_operasional, 0, ',', '.') }}</p>
                        <p class="text-xs text-blue-600 mt-0.5">Gunakan nilai ini sebagai acuan RAB atau sesuaikan di
                            bawah</p>
                    </div>
                </div>
            </div>

            {{-- FORM RAB --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-4 sm:px-6 py-4 border-b border-gray-100 bg-slate-50 flex items-center gap-2">
                    <i class="bi bi-pen-fill text-blue-600"></i>
                    <h3 class="font-bold text-gray-800 text-sm">Form Persetujuan RAB</h3>
                </div>
                <div class="p-4 sm:p-6">
                    <form action="{{ route('permohonan.proses_keuangan_submit', $permohonan->id) }}" method="POST"
                        class="space-y-5">
                        @csrf @method('PUT')

                        @if ($errors->any())
                            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                <p class="text-sm font-bold text-red-700 mb-1"><i
                                        class="bi bi-exclamation-triangle mr-1"></i>Perbaiki kesalahan:</p>
                                <ul class="text-sm text-red-600 list-disc list-inside">
                                    @foreach ($errors->all() as $e)
                                        <li>{{ $e }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-bold text-gray-800 mb-1.5">RAB Disetujui <span
                                        class="text-red-500">*</span></label>
                                <div class="relative">
                                    <span
                                        class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm font-medium">Rp</span>
                                    <input type="number" name="rab_disetujui" required min="0" step="1"
                                        value="{{ old('rab_disetujui', $permohonan->estimasi_biaya_operasional) }}"
                                        class="w-full pl-10 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm transition">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-800 mb-1.5">Mekanisme Pembayaran <span
                                        class="text-red-500">*</span></label>
                                <select id="mekanisme_pembayaran" name="mekanisme_pembayaran" required
                                    class="select2-mekanisme w-full">
                                    <option value="">— Pilih Mekanisme —</option>
                                    @foreach (['Cash (Tunai)' => 'Cash (Uang Tunai)', 'Cashless (Transfer/E-Toll)' => 'Cashless (Transfer / E-Toll)', 'Reimburse' => 'Reimburse (Ditalangi Pemohon)'] as $v => $l)
                                        <option value="{{ $v }}"
                                            {{ old('mekanisme_pembayaran') === $v ? 'selected' : '' }}>
                                            {{ $l }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row items-center gap-3 pt-2 border-t border-gray-100">
                            <button type="submit"
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-lg shadow-sm transition text-sm">
                                Setujui
                            </button>
                            <a href="{{ route('keuangan.rab') }}"
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
            $('#mekanisme_pembayaran').select2({
                width: '100%',
                placeholder: '-- Pilih Mekanisme --',
                allowClear: true,
                minimumResultsForSearch: Infinity
            });
        });

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

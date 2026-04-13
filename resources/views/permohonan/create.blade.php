<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-gray-600 transition">
                <i class="bi bi-arrow-left text-lg"></i>
            </a>
            <div>
                <h2 class="font-semibold text-xl text-gray-800">Buat Pengajuan Kendaraan</h2>
                <p class="text-sm text-gray-400 mt-0.5">Lengkapi semua data dengan benar dan jelas</p>
            </div>
        </div>
    </x-slot>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <style>
        .select2-container { width: 100% !important; }
        .select2-container--default .select2-selection--single { height: 40px; border: 1px solid #d1d5db; border-radius: 0.5rem; display: flex; align-items: center; background: #fff; transition: all .15s; }
        .select2-container--default.select2-container--open .select2-selection--single,
        .select2-container--default .select2-selection--single:focus { border-color: #3b82f6; box-shadow: 0 0 0 2px rgba(59,130,246,.15); }
        .select2-container--default .select2-selection--single .select2-selection__arrow { height: 38px; right: 8px; }
        .select2-container--default .select2-selection--single .select2-selection__rendered { color: #374151; font-size: .875rem; padding-left: 12px; }
        .select2-dropdown { border: 1px solid #3b82f6; border-radius: .5rem; box-shadow: 0 6px 20px rgba(0,0,0,.1); }
        .select2-results__option--highlighted { background: #3b82f6 !important; }
        .flatpickr-day.selected { background: #3b82f6 !important; border-color: #3b82f6 !important; }
    </style>

    <div class="py-8 bg-slate-50 min-h-screen">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 flex gap-3 items-start shadow-sm">
                    <i class="bi bi-exclamation-triangle-fill text-red-500 text-lg flex-shrink-0 mt-0.5"></i>
                    <div>
                        <p class="font-bold text-red-700 text-sm mb-1.5">Mohon perbaiki kesalahan berikut:</p>
                        <ul class="text-sm text-red-600 space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li class="flex items-start gap-1.5">
                                    <span class="text-red-400 mt-0.5 flex-shrink-0">•</span>{{ $error }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

                {{-- ── FORM UTAMA ── --}}
                <div class="lg:col-span-2 space-y-4">
                    <form action="{{ route('permohonan.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- SEKSI 1: Pemohon --}}
                        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                            <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-3 bg-gray-50">
                                <span class="w-7 h-7 bg-blue-600 text-white text-xs font-black rounded-lg flex items-center justify-center flex-shrink-0">1</span>
                                <div>
                                    <h3 class="font-bold text-gray-800 text-sm">Informasi Pemohon</h3>
                                    <p class="text-xs text-gray-400">Person in charge yang bertanggung jawab atas perjalanan</p>
                                </div>
                            </div>
                            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                        Nama PIC <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="nama_pic"
                                        value="{{ old('nama_pic', Auth::user()->name) }}"
                                        maxlength="100" required
                                        class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm text-sm transition">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                        Nomor Kontak (WhatsApp) <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="kontak_pic" name="kontak_pic"
                                        value="{{ old('kontak_pic', '+62') }}" required
                                        class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm text-sm transition">
                                    <p class="text-xs text-gray-400 mt-1">Format: +62xxxxxxxxxx</p>
                                </div>
                            </div>
                        </div>

                        {{-- SEKSI 2: Rute & Jadwal --}}
                        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                            <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-3 bg-gray-50">
                                <span class="w-7 h-7 bg-blue-600 text-white text-xs font-black rounded-lg flex items-center justify-center flex-shrink-0">2</span>
                                <div>
                                    <h3 class="font-bold text-gray-800 text-sm">Rute & Jadwal Perjalanan</h3>
                                    <p class="text-xs text-gray-400">Titik berangkat, tujuan, dan estimasi waktu perjalanan</p>
                                </div>
                            </div>
                            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                        Titik Jemput / Keberangkatan <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="titik_jemput"
                                        value="{{ old('titik_jemput') }}"
                                        maxlength="150" required
                                        placeholder="cth: Gedung Rektorat lt.1"
                                        class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm text-sm transition">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                        Tujuan / Lokasi Kegiatan <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="tujuan"
                                        value="{{ old('tujuan') }}"
                                        maxlength="150" required
                                        placeholder="cth: Kementerian Pendidikan, Jakarta"
                                        class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm text-sm transition">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                        Waktu Keberangkatan <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="waktu_berangkat" name="waktu_berangkat"
                                        value="{{ old('waktu_berangkat') }}"
                                        placeholder="Pilih tanggal & jam" required readonly
                                        class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm text-sm bg-white cursor-pointer transition">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                        Waktu Kembali <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="waktu_kembali" name="waktu_kembali"
                                        value="{{ old('waktu_kembali') }}"
                                        placeholder="Pilih tanggal & jam" required readonly
                                        class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm text-sm bg-white transition">
                                </div>
                            </div>
                        </div>

                        {{-- SEKSI 3: Kebutuhan Armada --}}
                        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                            <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-3 bg-gray-50">
                                <span class="w-7 h-7 bg-blue-600 text-white text-xs font-black rounded-lg flex items-center justify-center flex-shrink-0">3</span>
                                <div>
                                    <h3 class="font-bold text-gray-800 text-sm">Kebutuhan Armada</h3>
                                    <p class="text-xs text-gray-400">Tipe kendaraan dan jumlah penumpang yang ikut</p>
                                </div>
                            </div>
                            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="sm:col-span-2">
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                        Tipe Kendaraan yang Dibutuhkan <span class="text-red-500">*</span>
                                    </label>
                                    <select name="kendaraan_dibutuhkan" id="kendaraan_dibutuhkan" required
                                        class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm text-sm">
                                        <option value="">— Pilih tipe kendaraan —</option>
                                        @foreach($kombinasiMobil as $mobil)
                                            <option value="{{ $mobil->nama_kendaraan }}"
                                                {{ old('kendaraan_dibutuhkan') === $mobil->nama_kendaraan ? 'selected' : '' }}>
                                                {{ $mobil->nama_kendaraan }} — kapasitas {{ $mobil->kapasitas_penumpang }} orang
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                        Jumlah Rombongan <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" name="jumlah_penumpang"
                                        value="{{ old('jumlah_penumpang') }}"
                                        min="1" max="60" required
                                        placeholder="Maks. 60 orang"
                                        oninput="if(this.value > 60) this.value = 60"
                                        class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm text-sm transition">
                                    <p class="text-xs text-gray-400 mt-1">Termasuk pengemudi jika membawa supir sendiri</p>
                                </div>
                            </div>
                        </div>

                        {{-- SEKSI 4: Anggaran & Catatan --}}
                        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                            <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-3 bg-gray-50">
                                <span class="w-7 h-7 bg-blue-600 text-white text-xs font-black rounded-lg flex items-center justify-center flex-shrink-0">4</span>
                                <div>
                                    <h3 class="font-bold text-gray-800 text-sm">Anggaran & Catatan</h3>
                                    <p class="text-xs text-gray-400">Perkiraan biaya dan instruksi tambahan untuk tim logistik</p>
                                </div>
                            </div>
                            <div class="p-5 space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                        Perkiraan Anggaran yang Dibutuhkan
                                        <span class="text-gray-400 font-normal">(opsional)</span>
                                    </label>
                                    <textarea name="anggaran_diajukan" rows="3" maxlength="500"
                                        placeholder="cth: BBM Rp 200.000, Tol Rp 75.000, Parkir Rp 25.000 → Estimasi total Rp 300.000"
                                        class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm text-sm transition resize-none">{{ old('anggaran_diajukan') }}</textarea>
                                    <p class="text-xs text-gray-400 mt-1">
                                        Kosongkan jika kegiatan Non-Dinas atau biaya ditanggung pribadi. Rincian membantu proses RAB lebih cepat.
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                        Catatan Tambahan
                                        <span class="text-gray-400 font-normal">(opsional)</span>
                                    </label>
                                    <textarea id="catatan_pemohon" name="catatan_pemohon" rows="3" maxlength="500"
                                        placeholder="Instruksi khusus: preferensi rute, kebutuhan khusus penumpang, dll."
                                        class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm text-sm transition resize-none">{{ old('catatan_pemohon') }}</textarea>
                                    <div class="flex justify-end mt-1">
                                        <span class="text-xs text-gray-400"><span id="char_count">0</span>/500 karakter</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- SEKSI 5: Dokumen --}}
                        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                            <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-3 bg-gray-50">
                                <span class="w-7 h-7 bg-blue-600 text-white text-xs font-black rounded-lg flex items-center justify-center flex-shrink-0">5</span>
                                <div>
                                    <h3 class="font-bold text-gray-800 text-sm">Dokumen Pendukung</h3>
                                    <p class="text-xs text-gray-400">Surat penugasan wajib dilampirkan untuk kelengkapan berkas</p>
                                </div>
                            </div>
                            <div class="p-5">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Surat Tugas / Surat Penugasan <span class="text-red-500">*</span>
                                </label>
                                <div id="drop_zone"
                                    class="border-2 border-dashed border-gray-200 rounded-xl p-7 text-center hover:border-blue-400 hover:bg-blue-50/30 transition cursor-pointer"
                                    onclick="document.getElementById('file_surat').click()">
                                    <i class="bi bi-cloud-arrow-up text-4xl text-gray-300 block mb-2"></i>
                                    <p class="text-sm font-semibold text-gray-500">Klik untuk pilih file</p>
                                    <p class="text-xs text-gray-400 mt-1">PDF, JPG, PNG — Maks. 2 MB</p>
                                    <div id="file_info" class="hidden mt-3 inline-flex items-center gap-2 bg-blue-50 border border-blue-200 text-blue-700 text-xs font-bold px-3 py-1.5 rounded-lg">
                                        <i class="bi bi-file-earmark-check"></i>
                                        <span id="file_name"></span>
                                    </div>
                                </div>
                                <input type="file" id="file_surat" name="file_surat"
                                    accept=".pdf,.jpg,.jpeg,.png" required class="hidden">
                            </div>
                        </div>

                        {{-- TOMBOL SUBMIT --}}
                        <div class="flex items-center justify-end gap-3 pt-1">
                            <a href="{{ route('dashboard') }}"
                                class="text-sm text-gray-500 hover:text-gray-700 font-medium transition">
                                Batalkan
                            </a>
                            <button type="submit"
                                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-bold py-2.5 px-8 rounded-xl transition shadow-sm text-sm">
                                <i class="bi bi-send-fill"></i> Ajukan Perjalanan
                            </button>
                        </div>

                    </form>
                </div>

                {{-- ── SIDEBAR ── --}}
                <div class="lg:col-span-1 space-y-4 lg:sticky lg:top-20">

                    {{-- Jadwal Armada Aktif --}}
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                            <i class="bi bi-calendar-check text-blue-600"></i>
                            <h4 class="font-bold text-gray-800 text-sm">Jadwal Armada Aktif</h4>
                        </div>
                        @if($jadwalBooking->count() > 0)
                            <div class="p-3 max-h-80 overflow-y-auto space-y-2">
                                <p class="text-xs text-gray-400 px-1 pb-1">
                                    Armada berikut sedang atau akan digunakan. Admin akan mempertimbangkan jadwal Anda.
                                </p>
                                @foreach($jadwalBooking as $jadwal)
                                    <div class="bg-slate-50 border border-gray-200 rounded-lg p-3">
                                        <div class="flex items-start justify-between gap-1">
                                            <p class="font-bold text-gray-800 text-xs leading-tight">
                                                {{ $jadwal->kendaraan->nama_kendaraan ?? '—' }}
                                            </p>
                                            <span class="font-mono text-[10px] text-gray-400 flex-shrink-0">
                                                {{ $jadwal->kendaraan->plat_nomor ?? '—' }}
                                            </span>
                                        </div>
                                        <p class="text-[11px] text-gray-500 mt-1 flex items-center gap-1">
                                            <i class="bi bi-geo-alt text-gray-400 flex-shrink-0"></i>
                                            {{ Str::limit($jadwal->tujuan, 32) }}
                                        </p>
                                        <div class="grid grid-cols-2 gap-1 mt-2 pt-2 border-t border-gray-100">
                                            <div>
                                                <p class="text-[10px] text-gray-400">Berangkat</p>
                                                <p class="text-[11px] font-semibold text-gray-700">
                                                    {{ \Carbon\Carbon::parse($jadwal->waktu_berangkat)->format('d/m H:i') }}
                                                </p>
                                            </div>
                                            <div>
                                                <p class="text-[10px] text-gray-400">Kembali</p>
                                                <p class="text-[11px] font-semibold text-gray-700">
                                                    {{ \Carbon\Carbon::parse($jadwal->waktu_kembali)->format('d/m H:i') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="p-8 text-center">
                                <i class="bi bi-check-circle text-3xl text-emerald-400 block mb-2"></i>
                                <p class="text-sm font-bold text-gray-600">Semua Armada Tersedia</p>
                                <p class="text-xs text-gray-400 mt-1">Tidak ada jadwal aktif saat ini.</p>
                            </div>
                        @endif
                    </div>

                    {{-- Tips Pengajuan --}}
                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                        <p class="text-xs font-bold text-amber-700 flex items-center gap-1.5 mb-2.5">
                            <i class="bi bi-lightbulb-fill"></i> Tips Pengajuan
                        </p>
                        <ul class="text-xs text-amber-800 space-y-2">
                            <li class="flex items-start gap-1.5">
                                <span class="text-amber-500 mt-0.5 flex-shrink-0">•</span>
                                Ajukan minimal <strong>H-2</strong> dari jadwal keberangkatan
                            </li>
                            <li class="flex items-start gap-1.5">
                                <span class="text-amber-500 mt-0.5 flex-shrink-0">•</span>
                                Surat penugasan harus sudah ditandatangani pejabat berwenang
                            </li>
                            <li class="flex items-start gap-1.5">
                                <span class="text-amber-500 mt-0.5 flex-shrink-0">•</span>
                                Isi anggaran dengan rincian agar lebih mudah diproses Tim Keuangan
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Alert Modal --}}
    <div id="customAlertModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-gray-900 bg-opacity-60 px-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-sm w-full p-6 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                <i class="bi bi-exclamation-triangle-fill text-red-500 text-xl"></i>
            </div>
            <h3 class="text-base font-bold text-gray-900 mb-2">Jadwal Tidak Valid</h3>
            <p id="customAlertMessage" class="text-sm text-gray-600 mb-5"></p>
            <button onclick="closeCustomAlert()"
                class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2.5 rounded-lg text-sm transition">
                Mengerti
            </button>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>

    <script>
        function showCustomAlert(msg) {
            document.getElementById('customAlertMessage').innerText = msg;
            document.getElementById('customAlertModal').classList.remove('hidden');
        }
        function closeCustomAlert() {
            document.getElementById('customAlertModal').classList.add('hidden');
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Select2
            if (typeof jQuery !== 'undefined') {
                $('#kendaraan_dibutuhkan').select2({
                    placeholder: 'Ketik atau pilih tipe kendaraan...',
                    allowClear: true,
                });
            }

            // Char counter catatan
            const catatan = document.getElementById('catatan_pemohon');
            const charCount = document.getElementById('char_count');
            if (catatan) {
                catatan.addEventListener('input', () => charCount.textContent = catatan.value.length);
            }

            // File upload display
            document.getElementById('file_surat').addEventListener('change', function () {
                const fileInfo = document.getElementById('file_info');
                const fileName = document.getElementById('file_name');
                if (this.files[0]) {
                    fileName.textContent = this.files[0].name;
                    fileInfo.classList.remove('hidden');
                } else {
                    fileInfo.classList.add('hidden');
                }
            });

            // Format kontak +62
            document.getElementById('kontak_pic').addEventListener('input', function () {
                let v = this.value;
                if (!v.startsWith('+62')) v = '+62' + v.replace(/[^0-9]/g, '');
                else v = '+62' + v.substring(3).replace(/[^0-9]/g, '');
                this.value = v;
            });

            // Flatpickr waktu kembali (disabled sampai berangkat diisi)
            const inputKembali = document.getElementById('waktu_kembali');
            inputKembali.disabled = true;
            inputKembali.classList.add('bg-gray-100', 'cursor-not-allowed', 'opacity-60');

            const fpKembali = flatpickr('#waktu_kembali', {
                enableTime: true, time_24hr: true,
                dateFormat: 'Y-m-d H:i', locale: 'id', disableMobile: true,
            });

            flatpickr('#waktu_berangkat', {
                enableTime: true, time_24hr: true,
                dateFormat: 'Y-m-d H:i', minDate: new Date(),
                locale: 'id', disableMobile: true,
                onChange: function (selectedDates, dateStr) {
                    if (selectedDates.length > 0) {
                        inputKembali.disabled = false;
                        inputKembali.classList.remove('bg-gray-100', 'cursor-not-allowed', 'opacity-60');
                        fpKembali.set('minDate', dateStr);
                        const tK = document.getElementById('waktu_kembali').value;
                        if (tK && tK <= dateStr) {
                            showCustomAlert('Waktu kembali harus lebih lambat dari waktu keberangkatan!');
                            fpKembali.clear();
                        }
                    } else {
                        inputKembali.disabled = true;
                        inputKembali.classList.add('bg-gray-100', 'cursor-not-allowed', 'opacity-60');
                        fpKembali.clear();
                    }
                },
            });

            document.getElementById('waktu_kembali').addEventListener('change', function () {
                const tB = document.getElementById('waktu_berangkat').value;
                if (tB && this.value <= tB) {
                    showCustomAlert('Waktu kembali harus lebih lambat dari waktu keberangkatan!');
                    fpKembali.clear();
                }
            });
        });
    </script>
</x-app-layout>
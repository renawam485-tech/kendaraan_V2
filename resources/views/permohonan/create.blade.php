<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Pengajuan Kendaraan Baru') }}
        </h2>
    </x-slot>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    
    <style>
        /* Styling Custom Select2 agar identik dengan Input Tailwind */
        .select2-container { width: 100% !important; }
        .select2-container--default .select2-selection--single { 
            height: 42px; border: 1px solid #d1d5db; border-radius: 0.375rem; 
            display: flex; align-items: center; padding-left: 4px; transition: all 0.2s; background-color: #fff;
        }
        .select2-container--default .select2-selection--single:focus, 
        .select2-container--default.select2-container--open .select2-selection--single { 
            border-color: #22c55e; box-shadow: 0 0 0 1px #22c55e; outline: none;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow { height: 40px; right: 10px; }
        .select2-container--default .select2-selection--single .select2-selection__rendered { color: #374151; font-size: 0.875rem; font-weight: 500; }
        .select2-dropdown { border: 1px solid #22c55e; border-radius: 0.375rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); border-top: none; }
        .select2-search__field { border-radius: 0.25rem !important; border: 1px solid #d1d5db !important; }
        .select2-search__field:focus { border-color: #22c55e !important; outline: none !important; }
        .select2-container--default .select2-results__option--highlighted[aria-selected] { background-color: #22c55e; color: white; }
        
        /* Styling Custom Flatpickr Kalender */
        .flatpickr-calendar { font-family: inherit; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); border: 1px solid #e5e7eb; }
        .flatpickr-day.selected { background: #22c55e !important; border-color: #22c55e !important; }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-6">
                
                <div class="lg:w-2/3 bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                    <div class="p-8 text-gray-900">
                        
                        <div class="mb-8 border-b border-gray-100 pb-4">
                            <h3 class="text-xl font-bold text-gray-800">Formulir Permohonan Perjalanan</h3>
                            <p class="text-sm text-gray-500 mt-1">Lengkapi data perjalanan dan pilih armada yang sesuai dengan kebutuhan Anda.</p>
                        </div>

                        @if ($errors->any())
                            <div class="mb-6 bg-red-50 border border-red-200 p-4 rounded-md">
                                <span class="text-red-700 font-bold text-sm flex items-center gap-2">
                                    <svg class="w-4 h-4" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Mohon perbaiki kesalahan berikut:
                                </span>
                                <ul class="mt-2 text-sm text-red-600 list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('permohonan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                            @csrf

                            <div class="bg-white p-5 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition duration-200">
                                <h4 class="text-sm font-bold text-gray-800 mb-4 uppercase tracking-wider">Informasi Pemohon</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="nama_pic" class="block text-sm font-semibold text-gray-700 mb-1">Nama PIC <span class="text-red-500">*</span></label>
                                        <input type="text" id="nama_pic" name="nama_pic" value="{{ old('nama_pic', Auth::user()->name) }}" maxlength="100" class="w-full border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-md shadow-sm transition" required>
                                    </div>
                                    <div>
                                        <label for="kontak_pic" class="block text-sm font-semibold text-gray-700 mb-1">Nomor Kontak <span class="text-red-500">*</span></label>
                                        <input type="text" id="kontak_pic" name="kontak_pic" value="{{ old('kontak_pic', '+62') }}" class="w-full border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-md shadow-sm font-medium text-gray-800 bg-gray-50 transition" required>
                                    </div>
                                    <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-gray-100 pt-4 mt-2">
                                        <div>
                                            <label for="titik_jemput" class="block text-sm font-semibold text-gray-700 mb-1">Titik Jemput <span class="text-red-500">*</span></label>
                                            <input type="text" id="titik_jemput" name="titik_jemput" value="{{ old('titik_jemput') }}" maxlength="150" class="w-full border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-md shadow-sm transition" required placeholder="Contoh: Gedung Rektorat">
                                        </div>
                                        <div>
                                            <label for="tujuan" class="block text-sm font-semibold text-gray-700 mb-1">Tujuan / Lokasi <span class="text-red-500">*</span></label>
                                            <input type="text" id="tujuan" name="tujuan" value="{{ old('tujuan') }}" maxlength="150" class="w-full border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-md shadow-sm transition" required placeholder="Contoh: Kemenag Jakarta">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white p-5 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition duration-200">
                                <h4 class="text-sm font-bold text-gray-800 mb-4 uppercase tracking-wider">Jadwal Perjalanan</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 relative">
                                    <div>
                                        <label for="waktu_berangkat" class="block text-sm font-semibold text-gray-700 mb-1">Waktu Keberangkatan <span class="text-red-500">*</span></label>
                                        <input type="text" id="waktu_berangkat" name="waktu_berangkat" value="{{ old('waktu_berangkat') }}" class="w-full border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-md shadow-sm bg-white cursor-pointer transition" placeholder="Pilih Tanggal & Jam" required readonly>
                                    </div>
                                    <div>
                                        <label for="waktu_kembali" class="block text-sm font-semibold text-gray-700 mb-1">Waktu Kembali <span class="text-red-500">*</span></label>
                                        <input type="text" id="waktu_kembali" name="waktu_kembali" value="{{ old('waktu_kembali') }}" class="w-full border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-md shadow-sm bg-white cursor-pointer transition" placeholder="Pilih Tanggal & Jam" required readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white p-5 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition duration-200">
                                <h4 class="text-sm font-bold text-gray-800 mb-4 uppercase tracking-wider">Kebutuhan Armada & Anggaran</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Kendaraan yang Dibutuhkan <span class="text-red-500">*</span></label>
                            <select name="kendaraan_dibutuhkan" id="kendaraan_dibutuhkan" class="w-full border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-md shadow-sm transition" required>
                                <option value="">-- Pilih Tipe Kendaraan --</option>
                                @foreach($kombinasiMobil as $mobil)
                                    <option value="{{ $mobil->nama_kendaraan }}">
                                        {{ $mobil->nama_kendaraan }} (Kapasitas: {{ $mobil->kapasitas_penumpang }} Orang)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                                    <div>
                                        <label for="jumlah_penumpang" class="block text-sm font-semibold text-gray-700 mb-1">Total Rombongan <span class="text-red-500">*</span></label>
                                        <input type="number" id="jumlah_penumpang" name="jumlah_penumpang" value="{{ old('jumlah_penumpang') }}" min="1" max="60" oninput="if(this.value > 60) this.value = 60;" class="w-full border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-md shadow-sm transition" required placeholder="Maks. 60 Orang">
                                    </div>
                                    <div class="relative">
                                        <label for="anggaran_diajukan" class="block text-sm font-semibold text-gray-700 mb-1">Estimasi Anggaran (Rp)</label>
                                        <input type="number" id="anggaran_diajukan" name="anggaran_diajukan" value="{{ old('anggaran_diajukan') }}" min="0" max="500000000" step="1000" class="w-full border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-md shadow-sm transition bg-gray-50" placeholder="Kosongkan jika Non-Dinas">
                                        <p class="text-[11px] text-gray-500 font-medium mt-1">Biarkan kosong jika ini merupakan kegiatan Mandiri / Non-Dinas.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white p-5 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition duration-200 space-y-6">
                                <div>
                                    <label for="catatan_pemohon" class="block text-sm font-semibold text-gray-700 mb-1">Catatan Tambahan (Opsional)</label>
                                    <textarea id="catatan_pemohon" name="catatan_pemohon" rows="3" maxlength="500" class="w-full border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-md shadow-sm transition" placeholder="Instruksi khusus untuk armada atau supir...">{{ old('catatan_pemohon') }}</textarea>
                                    <div class="text-right text-xs text-gray-400 mt-1"><span id="char_count">0</span> / 500 karakter</div>
                                </div>

                                <div class="pt-4 border-t border-gray-100">
                                    <label for="file_surat" class="block text-sm font-semibold text-gray-700 mb-2">Unggah Surat Tugas (PDF/JPG) <span class="text-red-500">*</span></label>
                                    <input type="file" id="file_surat" name="file_surat" accept=".pdf,.jpg,.jpeg,.png" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 transition" required>
                                </div>
                            </div>

                            <div class="flex items-center justify-end gap-4 pt-2">
                                <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700 font-medium text-sm transition">Batalkan</a>
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 px-8 rounded-md transition shadow-md">
                                    Ajukan Perjalanan
                                </button>
                            </div>
                        </form>
                    </div>
                </div> 

                <div class="lg:w-1/3">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 sticky top-20">
                        <div class="bg-gray-50 px-5 py-4 border-b border-gray-200 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-500" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <h3 class="font-bold text-gray-800 text-sm">Info Pemakaian Armada Kampus</h3>
                        </div>
                        <div class="p-5 max-h-[600px] overflow-y-auto bg-white">
                            @if($jadwalBooking->count() > 0)
                                <p class="text-xs text-gray-500 mb-4 text-justify">Daftar armada internal yang sedang bertugas. Jika armada penuh di jadwal Anda, admin akan mengupayakan opsi vendor luar.</p>
                                
                                <div class="space-y-4">
                                    @foreach($jadwalBooking as $jadwal)
                                        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition text-sm">
                                            <div class="font-bold text-gray-800 mb-1 line-clamp-1">{{ $jadwal->kendaraan->nama_kendaraan ?? 'Kendaraan' }} ({{ $jadwal->kendaraan->plat_nomor ?? '-' }})</div>
                                            <div class="text-xs text-gray-600 font-medium mb-3">Tujuan: {{ $jadwal->tujuan }}</div>
                                            <div class="grid grid-cols-2 gap-2 text-[11px] text-gray-500 border-t border-gray-100 pt-3">
                                                <div><span class="block font-bold text-gray-700">Mulai:</span> {{ \Carbon\Carbon::parse($jadwal->waktu_berangkat)->format('d/m/y H:i') }}</div>
                                                <div><span class="block font-bold text-gray-700">Selesai:</span> {{ \Carbon\Carbon::parse($jadwal->waktu_kembali)->format('d/m/y H:i') }}</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-10">
                                    <div class="mx-auto w-16 h-16 bg-green-50 rounded-full flex items-center justify-center mb-3">
                                        <svg class="w-8 h-8 text-green-500" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                    <p class="text-sm font-bold text-gray-800">Seluruh Armada Tersedia</p>
                                    <p class="text-xs text-gray-500 mt-1">Belum ada jadwal yang aktif.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div id="customAlertModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-gray-900 bg-opacity-60 px-4 transition-opacity duration-300">
        <div class="bg-white rounded-xl shadow-2xl max-w-sm w-full p-6 text-center transform scale-95 transition-transform duration-300" id="customAlertContent">
            <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-red-100 mb-4 shadow-inner">
                <svg class="h-8 w-8 text-red-600" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">Peringatan Jadwal</h3>
            <p id="customAlertMessage" class="text-sm text-gray-600 mb-6 px-2"></p>
            <button type="button" onclick="closeCustomAlert()" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2.5 bg-red-600 text-base font-bold text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:text-sm transition-colors">
                Saya Mengerti
            </button>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>

    <script>
        // --- LOGIKA CUSTOM ALERT MODAL ---
        function showCustomAlert(message) {
            const modal = document.getElementById('customAlertModal');
            const content = document.getElementById('customAlertContent');
            document.getElementById('customAlertMessage').innerText = message;
            
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('bg-opacity-0');
                content.classList.remove('scale-95');
                content.classList.add('scale-100');
            }, 10);
        }

        function closeCustomAlert() {
            const modal = document.getElementById('customAlertModal');
            const content = document.getElementById('customAlertContent');
            content.classList.remove('scale-100');
            content.classList.add('scale-95');
            setTimeout(() => { modal.classList.add('hidden'); }, 300);
        }

        document.addEventListener('DOMContentLoaded', function() {
            
            // 1. SELECT2 UNTUK PENCARIAN KENDARAAN (Sangat Responsif & Bersih)
            if (typeof jQuery !== 'undefined') {
                $('.select2-mobil').select2({
                    placeholder: "Ketik merk/model mobil di sini...",
                    allowClear: true
                });
            }

            // 2. COUNTER CATATAN
            const textarea = document.getElementById('catatan_pemohon');
            const countDisplay = document.getElementById('char_count');
            if (textarea) {
                textarea.addEventListener('input', function() {
                    countDisplay.textContent = this.value.length;
                });
            }

            // 3. KEAMANAN NOMOR HP (+62)
            document.getElementById('kontak_pic').addEventListener('input', function() {
                let val = this.value;
                if (!val.startsWith('+62')) { val = '+62' + val.replace(/[^0-9]/g, ''); } 
                else { val = '+62' + val.substring(3).replace(/[^0-9]/g, ''); }
                this.value = val;
            });

            // 4. FLATPICKR: KALENDER CUSTOM (Format Y-m-d H:i, Tanpa T)
            // 1. Kunci dan beri efek abu-abu pada waktu kembali saat halaman pertama dimuat
            const inputKembali = document.getElementById('waktu_kembali');
            inputKembali.disabled = true;
            inputKembali.classList.add('bg-gray-100', 'cursor-not-allowed', 'opacity-60');

            const fpKembali = flatpickr("#waktu_kembali", {
                enableTime: true,
                time_24hr: true,
                dateFormat: "Y-m-d H:i",
                locale: "id",
                disableMobile: "true"
            });

            const fpBerangkat = flatpickr("#waktu_berangkat", {
                enableTime: true,
                time_24hr: true,
                dateFormat: "Y-m-d H:i",
                minDate: new Date(), 
                locale: "id",
                disableMobile: "true",
                onChange: function(selectedDates, dateStr, instance) {
                    if(selectedDates.length > 0) {
                        // BUKA KUNCI waktu kembali jika waktu berangkat sudah diisi
                        inputKembali.disabled = false;
                        inputKembali.classList.remove('bg-gray-100', 'cursor-not-allowed', 'opacity-60');
                        
                        fpKembali.set('minDate', dateStr);
                        
                        let tKembali = document.getElementById('waktu_kembali').value;
                        if(tKembali && tKembali <= dateStr) {
                            showCustomAlert('Waktu kembali yang Anda pilih tidak valid. Harus lebih lambat dari waktu keberangkatan!');
                            fpKembali.clear(); 
                        }
                    } else {
                        // KUNCI KEMBALI jika waktu berangkat dihapus
                        inputKembali.disabled = true;
                        inputKembali.classList.add('bg-gray-100', 'cursor-not-allowed', 'opacity-60');
                        fpKembali.clear();
                    }
                }
            });

            // Pengecekan Tambahan
            document.getElementById('waktu_kembali').addEventListener('change', function() {
                let tBerangkat = document.getElementById('waktu_berangkat').value;
                if(tBerangkat && this.value <= tBerangkat) {
                    showCustomAlert('Waktu kembali harus lebih lambat dari waktu keberangkatan!');
                    fpKembali.clear();
                }
            });
        });
    </script>
</x-app-layout>
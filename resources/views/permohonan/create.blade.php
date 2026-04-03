<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Pengajuan Kendaraan Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex flex-col lg:flex-row gap-6">
                
                <div class="lg:w-2/3 bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                    <div class="p-6 text-gray-900">
                        
                        <div class="mb-6 border-b pb-4">
                            <h3 class="text-lg font-bold text-gray-800">Formulir Permohonan Perjalanan</h3>
                            <p class="text-sm text-gray-500">Isi data di bawah ini dengan batas karakter yang sesuai.</p>
                        </div>

                        @if ($errors->any())
                            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-md">
                                <span class="text-red-700 font-bold text-sm">Mohon perbaiki kesalahan berikut:</span>
                                <ul class="mt-2 text-sm text-red-600 list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('permohonan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                            @csrf

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50 p-4 rounded-lg border border-gray-100">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Nama PIC <span class="text-red-500">*</span></label>
                                    <input type="text" name="nama_pic" value="{{ old('nama_pic', Auth::user()->name) }}" maxlength="100" class="w-full border-gray-300 focus:border-purple-500 focus:ring-purple-500 rounded-md shadow-sm" required placeholder="Maks. 100 karakter">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Nomor Kontak (Otomatis +62) <span class="text-red-500">*</span></label>
                                    <input type="text" id="kontak_pic" name="kontak_pic" value="{{ old('kontak_pic', '+62') }}" class="w-full border-gray-300 focus:border-purple-500 focus:ring-purple-500 rounded-md shadow-sm font-semibold text-purple-700 bg-purple-50" required placeholder="+6281234567890">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Titik Jemput <span class="text-red-500">*</span></label>
                                    <input type="text" name="titik_jemput" value="{{ old('titik_jemput') }}" maxlength="150" class="w-full border-gray-300 focus:border-purple-500 focus:ring-purple-500 rounded-md shadow-sm" required placeholder="Contoh: Lobi Utama">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Tujuan / Lokasi <span class="text-red-500">*</span></label>
                                    <input type="text" name="tujuan" value="{{ old('tujuan') }}" maxlength="150" class="w-full border-gray-300 focus:border-purple-500 focus:ring-purple-500 rounded-md shadow-sm" required placeholder="Contoh: Kantor Pemkot">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-blue-50 p-4 rounded-lg border border-blue-100">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Waktu Keberangkatan <span class="text-red-500">*</span></label>
                                    <input type="datetime-local" id="waktu_berangkat" name="waktu_berangkat" value="{{ old('waktu_berangkat') }}" class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Waktu Kembali <span class="text-red-500">*</span></label>
                                    <input type="datetime-local" id="waktu_kembali" name="waktu_kembali" value="{{ old('waktu_kembali') }}" class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm" required>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Pilihan Kendaraan <span class="text-red-500">*</span></label>
                                    <select name="kendaraan_dibutuhkan" class="w-full border-gray-300 focus:border-purple-500 focus:ring-purple-500 rounded-md shadow-sm text-sm" required>
                                        <option value="">-- Pilih Armada --</option>
                                        <optgroup label="ASET KAMPUS">
                                            <option value="Mobil Kampus - MPV / Avanza" {{ old('kendaraan_dibutuhkan') == 'Mobil Kampus - MPV / Avanza' ? 'selected' : '' }}>Mobil Kampus - MPV / Avanza</option>
                                            <option value="Mobil Kampus - Minibus / Hiace" {{ old('kendaraan_dibutuhkan') == 'Mobil Kampus - Minibus / Hiace' ? 'selected' : '' }}>Mobil Kampus - Minibus / Hiace</option>
                                            <option value="Mobil Kampus - Bus Kampus" {{ old('kendaraan_dibutuhkan') == 'Mobil Kampus - Bus Kampus' ? 'selected' : '' }}>Mobil Kampus - Bus Kampus</option>
                                        </optgroup>
                                        <optgroup label="SEWA / VENDOR LUAR">
                                            <option value="Sewa Vendor - MPV / Innova" {{ old('kendaraan_dibutuhkan') == 'Sewa Vendor - MPV / Innova' ? 'selected' : '' }}>Sewa Vendor - MPV / Innova</option>
                                            <option value="Sewa Vendor - Minibus / Elf" {{ old('kendaraan_dibutuhkan') == 'Sewa Vendor - Minibus / Elf' ? 'selected' : '' }}>Sewa Vendor - Minibus / Elf</option>
                                            <option value="Sewa Vendor - Bus Besar" {{ old('kendaraan_dibutuhkan') == 'Sewa Vendor - Bus Besar' ? 'selected' : '' }}>Sewa Vendor - Bus Besar</option>
                                        </optgroup>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Rombongan <span class="text-red-500">*</span></label>
                                    <input type="number" name="jumlah_penumpang" value="{{ old('jumlah_penumpang') }}" min="1" max="60" class="w-full border-gray-300 focus:border-purple-500 focus:ring-purple-500 rounded-md shadow-sm" required placeholder="Maks. 60">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Anggaran (Rp) <span class="text-red-500">*</span></label>
                                    <input type="number" name="anggaran_diajukan" value="{{ old('anggaran_diajukan', 0) }}" min="0" max="500000000" step="1000" class="w-full border-gray-300 focus:border-purple-500 focus:ring-purple-500 rounded-md shadow-sm" required>
                                </div>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-lg border border-dashed border-gray-300">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Unggah Surat Tugas (PDF/JPG) <span class="text-red-500">*</span></label>
                                <input type="file" name="file_surat" accept=".pdf,.jpg,.jpeg,.png" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100" required>
                            </div>

                            <div class="flex items-center justify-end gap-4 pt-4 border-t">
                                <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700 font-medium text-sm transition">Batalkan</a>
                                <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-8 rounded-md transition shadow-md">
                                    Ajukan Sekarang
                                </button>
                            </div>
                        </form>
                    </div>
                </div> <div class="lg:w-1/3">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-orange-200 sticky top-20">
                        <div class="bg-orange-50 px-4 py-3 border-b border-orange-200 flex items-center gap-2">
                            <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <h3 class="font-bold text-orange-800 text-sm">Info Pemakaian Mobil Kampus</h3>
                        </div>
                        <div class="p-4 bg-orange-50/30 max-h-[500px] overflow-y-auto">
                            @if($jadwalBooking->count() > 0)
                                <p class="text-xs text-gray-600 mb-4 text-justify">Berikut adalah daftar mobil kampus yang <strong>sudah dibooking/dipakai</strong>. Mohon jangan mengajukan di tanggal dan jam yang berbenturan.</p>
                                
                                <div class="space-y-3">
                                    @foreach($jadwalBooking as $jadwal)
                                        <div class="bg-white p-3 rounded border border-orange-100 shadow-sm text-sm">
                                            <div class="font-bold text-gray-800 mb-1 line-clamp-1">{{ $jadwal->kendaraan_dibutuhkan }}</div>
                                            <div class="text-xs text-orange-600 font-semibold mb-2">Tujuan: {{ $jadwal->tujuan }}</div>
                                            <div class="grid grid-cols-2 gap-2 text-[11px] text-gray-500 border-t pt-2">
                                                <div><span class="block font-bold text-gray-700">Mulai:</span> {{ \Carbon\Carbon::parse($jadwal->waktu_berangkat)->format('d/m/Y H:i') }}</div>
                                                <div><span class="block font-bold text-gray-700">Selesai:</span> {{ \Carbon\Carbon::parse($jadwal->waktu_kembali)->format('d/m/Y H:i') }}</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <svg class="w-12 h-12 text-green-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <p class="text-sm font-bold text-green-700">Semua Mobil Kampus Tersedia!</p>
                                    <p class="text-xs text-gray-500 mt-1">Belum ada jadwal penyewaan yang aktif saat ini.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div> </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            // 1. KUNCI FORMAT NOMOR HP (+62)
            const phoneInput = document.getElementById('kontak_pic');
            phoneInput.addEventListener('input', function() {
                let val = this.value;
                // Jika pengguna menghapus +62, paksa kembali menjadi +62
                if (!val.startsWith('+62')) {
                    val = '+62' + val.replace(/[^0-9]/g, ''); 
                } else {
                    // Hapus karakter selain angka setelah +62
                    let numbers = val.substring(3).replace(/[^0-9]/g, '');
                    val = '+62' + numbers;
                }
                this.value = val;
            });

            // 2. KUNCI WAKTU SAMPAI KE LEVEL MENIT
            const berangkatInput = document.getElementById('waktu_berangkat');
            const kembaliInput = document.getElementById('waktu_kembali');

            // Dapatkan waktu lokal SAAT INI (Format: YYYY-MM-DDThh:mm)
            const now = new Date();
            const tzOffset = now.getTimezoneOffset() * 60000;
            const localISOTime = (new Date(now.getTime() - tzOffset)).toISOString().slice(0, 16);
            
            // Kunci input agar tidak bisa memilih jam/menit sebelum detik ini
            berangkatInput.setAttribute('min', localISOTime);
            kembaliInput.setAttribute('min', localISOTime);

            // 3. CEGAH WAKTU KEMBALI MUNDUR KE BELAKANG
            berangkatInput.addEventListener('change', function() {
                if (this.value) {
                    // Waktu kembali minimal adalah waktu berangkat
                    kembaliInput.min = this.value;
                    
                    // Jika user mencoba mengakali / input salah, reset & beri peringatan
                    if (kembaliInput.value && kembaliInput.value <= this.value) {
                        kembaliInput.value = '';
                        alert('Jam kembali tidak valid! Harus setelah jam keberangkatan.');
                    }
                }
            });

            kembaliInput.addEventListener('change', function() {
                if (berangkatInput.value && this.value <= berangkatInput.value) {
                    this.value = '';
                    alert('Jam kembali tidak valid! Harus setelah jam keberangkatan.');
                }
            });

        });
    </script>
</x-app-layout>
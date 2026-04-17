<x-app-layout>
    <div class="py-6 bg-slate-50 min-h-screen">
        <div class="w-full px-4 sm:px-6 lg:px-8">

            {{-- Tombol Kembali --}}
            <div class="mb-5">
                <a href="{{ route('admin.validasi') }}" class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-blue-600 transition bg-white border border-gray-200 px-3 py-2 rounded-lg shadow-sm">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>

            {{-- DATA PEMOHON  --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-5">
                <div class="px-4 sm:px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50 flex items-center justify-between flex-wrap gap-2">
                    <div class="flex items-center gap-2">
                        <i class="bi bi-person-lines-fill text-blue-600"></i>
                        <h3 class="font-bold text-gray-800">Data Permohonan</h3>
                    </div>
                    <div class="flex items-center gap-3 flex-wrap">
                        <span class="text-xs text-gray-400" id="diajukan-time" data-created="{{ $permohonan->created_at->toISOString() }}">
                            <i class="bi bi-clock-history"></i> <span class="font-semibold" id="relative-time">{{ $permohonan->created_at->diffForHumans() }}</span>
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

                    <div class="px-4 sm:px-6 py-3.5">
                        <span class="text-sm text-gray-500 flex items-center gap-2 mb-1">
                            <i class="bi bi-person text-gray-400"></i>Nama PIC
                        </span>
                        <p class="text-sm font-semibold text-gray-800 break-words">{{ $permohonan->nama_pic }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $permohonan->kontak_pic }}</p>
                    </div>

                    <div class="px-4 sm:px-6 py-3.5">
                        <span class="text-sm text-gray-500 flex items-center gap-2 mb-1">
                            <i class="bi bi-truck text-gray-400"></i>Kebutuhan Kendaraan
                        </span>
                        <p class="text-sm font-semibold text-gray-800 break-words">{{ $permohonan->kendaraan_dibutuhkan }}</p>
                        <p class="text-xs text-gray-500 mt-0.5"><i class="bi bi-people mr-0.5"></i>{{ $permohonan->jumlah_penumpang }} orang</p>
                    </div>

                    <div class="px-4 sm:px-6 py-3.5">
                        <span class="text-sm text-gray-500 flex items-center gap-2 mb-1">
                            <i class="bi bi-signpost-split text-gray-400"></i>Rute Perjalanan
                        </span>
                        <p class="text-sm text-gray-700 break-words">Dari: {{ $permohonan->titik_jemput }}</p>
                        <p class="text-sm text-gray-700 break-words">Tujuan: {{ $permohonan->tujuan }}</p>
                    </div>

                    <div class="px-4 sm:px-6 py-3.5">
                        <span class="text-sm text-gray-500 flex items-center gap-2 mb-1">
                            <i class="bi bi-calendar-event text-gray-400"></i>Jadwal Perjalanan
                        </span>
                        <p class="text-sm font-medium text-gray-700">{{ \Carbon\Carbon::parse($permohonan->waktu_berangkat)->translatedFormat('l, d M Y H:i') }} WIB</p>
                        <p class="text-xs text-gray-500">s/d {{ \Carbon\Carbon::parse($permohonan->waktu_kembali)->translatedFormat('l, d M Y H:i') }} WIB</p>
                    </div>

                    <div class="px-4 sm:px-6 py-3.5">
                        <span class="text-sm text-gray-500 flex items-center gap-2 mb-1">
                            <i class="bi bi-wallet text-gray-400"></i>Anggaran Diajukan
                        </span>
                        <p class="text-sm font-semibold text-gray-800 break-words">{{ $permohonan->anggaran_diajukan ?: '—' }}</p>
                    </div>

                    <div class="px-4 sm:px-6 py-3.5">
                        <span class="text-sm text-gray-500 flex items-center gap-2 mb-1">
                            <i class="bi bi-file-earmark-pdf text-gray-400"></i>Surat Penugasan
                        </span>
                        @if($permohonan->file_surat_penugasan)
                            <a href="{{ asset('storage/'.$permohonan->file_surat_penugasan) }}" target="_blank"
                               class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200 px-3 py-1.5 rounded-lg transition">
                                <i class="bi bi-file-earmark-pdf"></i> Lihat Dokumen
                            </a>
                        @else
                            <p class="text-sm text-gray-400 italic">Tidak ada</p>
                        @endif
                    </div>

                    @if($permohonan->catatan_pemohon)
                        <div class="px-4 sm:px-6 py-3.5">
                            <span class="text-sm text-gray-500 flex items-center gap-2 mb-1">
                                <i class="bi bi-chat-quote text-gray-400"></i>Catatan dari Pemohon
                            </span>
                            <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 text-sm text-amber-800 break-words">
                                {{ $permohonan->catatan_pemohon }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- FORM VALIDASI --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-4 sm:px-6 py-4 border-b border-gray-100 bg-slate-50 flex items-center gap-2">
                    <i class="bi bi-pencil-square text-blue-600"></i>
                    <h3 class="font-bold text-gray-800">Keputusan Admin</h3>
                </div>
                <div class="p-4 sm:p-6">
                    <form action="{{ route('permohonan.validasi_admin_proses', $permohonan->id) }}" method="POST" class="space-y-5" id="validasiForm">
                        @csrf @method('PUT')

                        @if($errors->any())
                            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                <p class="text-sm font-bold text-red-700 mb-1 flex items-center gap-1"><i class="bi bi-exclamation-triangle"></i> Perbaiki kesalahan:</p>
                                <ul class="text-sm text-red-600 list-disc list-inside space-y-0.5">
                                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- KATEGORI KEGIATAN (hanya tampil saat disetujui) --}}
                        <div id="bagian_setuju">
                            <label class="block text-sm font-bold text-gray-800 mb-2">Kategori Kegiatan <span class="text-red-500">*</span></label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach([
                                    ['Dinas SITH','Ditanggung Instansi','bi-building-check','text-green-600','bg-green-50 border-green-300'],
                                    ['Non SITH','Biaya Pribadi Pemohon','bi-person-check','text-gray-600','bg-gray-50 border-gray-300'],
                                ] as [$val,$desc,$icon,$tc,$bc])
                                    <label class="flex items-start gap-3 p-4 rounded-xl border-2 cursor-pointer transition hover:shadow-md {{ $bc }} has-[:checked]:ring-2 has-[:checked]:ring-blue-400">
                                        <input type="radio" name="kategori_kegiatan" value="{{ $val }}"
                                            {{ old('kategori_kegiatan') === $val ? 'checked' : '' }}
                                            class="mt-0.5 text-blue-600 focus:ring-blue-500">
                                        <div>
                                            <p class="font-bold text-gray-800 text-sm flex items-center gap-1.5"><i class="bi {{ $icon }} {{ $tc }}"></i> {{ $val }}</p>
                                            <p class="text-xs text-gray-500 mt-0.5">{{ $desc }}</p>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            <p class="text-xs text-gray-500 mt-2"><i class="bi bi-info-circle mr-1"></i>Memilih <strong>Non SITH</strong> akan otomatis mem-bypass proses Keuangan dan menol-kan anggaran.</p>
                        </div>

                        {{-- INSTRUKSI SPSI --}}
                        <div id="bagian_instruksi">
                            <label for="rekomendasi_admin" class="block text-sm font-bold text-gray-800 mb-1.5">Instruksi untuk SPSI <span class="text-gray-400 font-normal">(opsional)</span></label>
                            <textarea id="rekomendasi_admin" name="rekomendasi_admin" rows="3"
                                placeholder="Contoh: Gunakan HiAce, prioritaskan pengemudi senior..."
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm transition">{{ old('rekomendasi_admin') }}</textarea>
                        </div>

                        {{-- ALASAN PENOLAKAN (hanya tampil saat menolak) --}}
                        <div id="bagian_tolak" class="hidden">
                            <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                                <label for="alasan_penolakan" class="block text-sm font-bold text-red-700 mb-2 flex items-center gap-2">
                                    <i class="bi bi-x-circle-fill"></i> Alasan Penolakan <span class="text-red-500">*</span>
                                </label>
                                <textarea id="alasan_penolakan" name="alasan_penolakan" rows="4"
                                    placeholder="Tuliskan alasan penolakan yang jelas agar pemohon dapat memahami dan memperbaiki pengajuannya..."
                                    maxlength="1000"
                                    class="w-full border-red-300 focus:border-red-500 focus:ring-red-500 rounded-lg shadow-sm text-sm transition bg-white resize-none">{{ old('alasan_penolakan') }}</textarea>
                                <div class="flex justify-between mt-1">
                                    <p class="text-xs text-red-600">Alasan ini akan ditampilkan kepada pemohon melalui notifikasi dan halaman detail.</p>
                                    <span class="text-xs text-red-400"><span id="char_alasan">0</span>/1000</span>
                                </div>
                            </div>
                        </div>

                        {{-- HIDDEN field status_permohonan --}}
                        <input type="hidden" name="status_permohonan" id="hidden_status" value="{{ old('status_permohonan', '') }}">

                        {{-- TOMBOL AKSI --}}
                        {{-- TOMBOL AKSI --}}
<div id="tombolAksiNormal" class="flex flex-col sm:flex-row items-center gap-3 pt-2">
    <button type="button" id="btnSetujui"
        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-lg shadow-sm transition text-sm">
        <i class="bi bi-check2-circle"></i> Setujui
    </button>
    <button type="button" id="btnTolak"
        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-white hover:bg-red-50 text-red-600 border border-red-300 font-bold py-2.5 px-6 rounded-lg transition text-sm">
        <i class="bi bi-x-circle"></i> Tolak
    </button>
</div>

{{-- TOMBOL SUBMIT KHUSUS UNTUK TOLAK (muncul saat alasan ditampilkan) --}}
<div id="tombolSubmitTolak" class="hidden flex flex-col sm:flex-row items-center gap-3 pt-2 mt-4 border-t border-gray-100">
    <button type="button" id="btnBatalTolak"
        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-white hover:bg-gray-50 text-gray-600 border border-gray-300 font-bold py-2.5 px-6 rounded-lg transition text-sm">
        <i class="bi bi-arrow-left"></i> Batal
    </button>
    <button type="button" id="btnKirimTolak"
        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-red-600 hover:bg-red-700 text-white font-bold py-2.5 px-6 rounded-lg shadow-sm transition text-sm">
        <i class="bi bi-send"></i> Kirim Penolakan
    </button>
</div>

                    </form>
                </div>
            </div>
        </div>
    </div>

<script>
    // ── Toggle tampilan bagian form sesuai keputusan ──
    const bagianSetuju   = document.getElementById('bagian_setuju');
    const bagianInstruksi = document.getElementById('bagian_instruksi');
    const bagianTolak    = document.getElementById('bagian_tolak');
    const tombolAksiNormal = document.getElementById('tombolAksiNormal');
    const tombolSubmitTolak = document.getElementById('tombolSubmitTolak');
    const hiddenStatus   = document.getElementById('hidden_status');
    const alasanField    = document.getElementById('alasan_penolakan');
    const charAlasan     = document.getElementById('char_alasan');
    const btnSetujui     = document.getElementById('btnSetujui');
    const btnTolak       = document.getElementById('btnTolak');
    const btnBatalTolak  = document.getElementById('btnBatalTolak');
    const btnKirimTolak  = document.getElementById('btnKirimTolak');
    const form           = document.getElementById('validasiForm');

    // Char counter
    if (alasanField) {
        alasanField.addEventListener('input', () => {
            charAlasan.textContent = alasanField.value.length;
            // Hapus error styling jika user mulai mengetik
            alasanField.classList.remove('border-red-500', 'ring-2', 'ring-red-300');
        });
        charAlasan.textContent = alasanField.value.length;
    }

    const oldStatus = '{{ old("status_permohonan", "") }}';
    if (oldStatus === 'DITOLAK') {
        bagianSetuju.classList.add('hidden');
        bagianInstruksi.classList.add('hidden');
        bagianTolak.classList.remove('hidden');
        tombolAksiNormal.classList.add('hidden'); 
        tombolSubmitTolak.classList.remove('hidden');
    }

    btnSetujui.addEventListener('click', function () {
        customConfirm({
            title: 'Setujui Permohonan',
            message: 'Yakin menyetujui permohonan ini dan meneruskannya ke SPSI?',
            confirmText: 'Ya, Setujui'
        }, () => {
            hiddenStatus.value = 'Menunggu Proses SPSI';
            form.submit();
        });
    });

    // Tombol TOLAK (pertama kali klik)
    btnTolak.addEventListener('click', function () {
        // Sembunyikan bagian setuju dan instruksi
        bagianSetuju.classList.add('hidden');
        bagianInstruksi.classList.add('hidden');
        
        // Tampilkan bagian alasan penolakan
        bagianTolak.classList.remove('hidden');
        
        // SEMBUNYIKAN tombol aksi normal (termasuk tombol Setujui)
        tombolAksiNormal.classList.add('hidden');
        
        // Tampilkan tombol submit tolak
        tombolSubmitTolak.classList.remove('hidden');
        
        // Focus ke alasan
        alasanField.focus();
    });

    // Tombol BATAL (kembali ke mode normal)
    btnBatalTolak.addEventListener('click', function () {
        // Reset tampilan ke semula
        bagianSetuju.classList.remove('hidden');
        bagianInstruksi.classList.remove('hidden');
        bagianTolak.classList.add('hidden');
        
        // MUNCULKAN KEMBALI tombol aksi normal (termasuk tombol Setujui)
        tombolAksiNormal.classList.remove('hidden');
        
        // Sembunyikan tombol submit tolak
        tombolSubmitTolak.classList.add('hidden');
        
        // Reset form
        hiddenStatus.value = '';
        alasanField.value = '';
        charAlasan.textContent = '0';
        
        // Hapus error styling
        alasanField.classList.remove('border-red-500', 'ring-2', 'ring-red-300');
    });

    // Tombol KIRIM PENOLAKAN
    btnKirimTolak.addEventListener('click', function () {
        // Validasi alasan harus diisi
        if (!alasanField.value.trim()) {
            alasanField.focus();
            alasanField.classList.add('border-red-500', 'ring-2', 'ring-red-300');
            // Tampilkan alert atau toast
            alert('Harap isi alasan penolakan terlebih dahulu!');
            return;
        }
        
        // Konfirmasi penolakan
        customConfirm({
            title: 'Tolak Pengajuan',
            message: 'Yakin ingin MENOLAK permohonan ini? Alasan akan dikirim ke pemohon.',
            confirmText: 'Ya, Tolak',
            isDanger: true
        }, () => {
            hiddenStatus.value = 'DITOLAK';
            form.submit();
        });
    });

    // Optional: Validasi sebelum submit form (untuk jaga-jaga)
    form.addEventListener('submit', function (e) {
        if (hiddenStatus.value === 'DITOLAK') {
            if (!alasanField.value.trim()) {
                e.preventDefault();
                alasanField.focus();
                alasanField.classList.add('border-red-500', 'ring-2', 'ring-red-300');
                alert('Harap isi alasan penolakan terlebih dahulu!');
                return false;
            }
        }
    });

    // Relative time
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

            let relativeText = '';
            if (diffInSeconds < 60) {
                relativeText = 'baru saja';
            } else if (diffInMinutes < 60) {
                relativeText = diffInMinutes + ' menit yang lalu';
            } else if (diffInHours < 24) {
                relativeText = diffInHours + ' jam yang lalu';
            } else {
                relativeText = diffInDays + ' hari yang lalu';
            }
            element.textContent = relativeText;
        }
    }

    setInterval(updateRelativeTime, 60000);
    updateRelativeTime();
</script>
</x-app-layout>
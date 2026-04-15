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
                        {{-- Waktu diajukan REAL TIME --}}
                        <span class="text-xs text-gray-400" id="diajukan-time" data-created="{{ $permohonan->created_at->toISOString() }}">
                            <i class="bi bi-clock-history"></i> <span class="font-semibold" id="relative-time">{{ $permohonan->created_at->diffForHumans() }}</span>
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

                    {{-- Kebutuhan Kendaraan --}}
                    <div class="px-4 sm:px-6 py-3.5">
                        <span class="text-sm text-gray-500 flex items-center gap-2 mb-1">
                            <i class="bi bi-truck text-gray-400"></i>Kebutuhan Kendaraan
                        </span>
                        <p class="text-sm font-semibold text-gray-800 break-words">{{ $permohonan->kendaraan_dibutuhkan }}</p>
                        <p class="text-xs text-gray-500 mt-0.5"><i class="bi bi-people mr-0.5"></i>{{ $permohonan->jumlah_penumpang }} orang</p>
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
                        <p class="text-sm font-medium text-gray-700">{{ \Carbon\Carbon::parse($permohonan->waktu_berangkat)->translatedFormat('l, d M Y H:i') }} WIB</p>
                        <p class="text-xs text-gray-500">s/d {{ \Carbon\Carbon::parse($permohonan->waktu_kembali)->translatedFormat('l, d M Y H:i') }} WIB</p>
                    </div>

                    {{-- Anggaran Diajukan --}}
                    <div class="px-4 sm:px-6 py-3.5">
                        <span class="text-sm text-gray-500 flex items-center gap-2 mb-1">
                            <i class="bi bi-wallet text-gray-400"></i>Anggaran Diajukan
                        </span>
                        <p class="text-sm font-semibold text-gray-800 break-words">{{ $permohonan->anggaran_diajukan ?: '—' }}</p>
                    </div>

                    {{-- Surat Penugasan --}}
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

                    {{-- Catatan Pemohon --}}
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
                    <form action="{{ route('permohonan.validasi_admin_proses', $permohonan->id) }}" method="POST" class="space-y-5">
                        @csrf @method('PUT')

                        @if($errors->any())
                            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                <p class="text-sm font-bold text-red-700 mb-1 flex items-center gap-1"><i class="bi bi-exclamation-triangle"></i> Perbaiki kesalahan:</p>
                                <ul class="text-sm text-red-600 list-disc list-inside space-y-0.5">
                                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                                </ul>
                            </div>
                        @endif

                        <div>
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

                        <div>
                            <label for="rekomendasi_admin" class="block text-sm font-bold text-gray-800 mb-1.5">Instruksi untuk SPSI <span class="text-gray-400 font-normal">(opsional)</span></label>
                            <textarea id="rekomendasi_admin" name="rekomendasi_admin" rows="3"
                                placeholder="Contoh: Gunakan HiAce, prioritaskan pengemudi senior..."
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm transition">{{ old('rekomendasi_admin') }}</textarea>
                        </div>

                        <div class="flex flex-col sm:flex-row items-center gap-3 pt-2">
                            <button type="submit" name="status_permohonan" value="Menunggu Proses SPSI"
                                onclick="customConfirm({ title: 'Setujui Permohonan', message: 'Yakin menyetujui permohonan ini dan meneruskannya ke SPSI?', confirmText: 'Ya, Setujui' }, () => this.closest('form').submit())"
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-lg shadow-sm transition text-sm">
                                <i class="bi bi-check2-circle"></i> Setujui
                            </button>
                            <button type="submit" name="status_permohonan" value="Ditolak"
                                onclick="customConfirm({ title: 'Tolak Pengajuan', message: 'Yakin ingin MENOLAK permohonan ini?', confirmText: 'Ya, Tolak', isDanger: true }, () => this.closest('form').submit())"
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-white hover:bg-red-50 text-red-600 border border-red-300 font-bold py-2.5 px-6 rounded-lg transition text-sm">
                                <i class="bi bi-x-circle"></i> Tolak Pengajuan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
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
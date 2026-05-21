<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 flex items-center gap-2">
                    @if (Auth::user()->role === 'pengguna')
                        Riwayat Pengajuan
                    @else
                        @php
                            $judul = match (Auth::user()->role) {
                                'super_admin' => 'Laporan',
                                'kepala_admin' => 'Laporan',
                                'spsi' => 'Laporan',
                                'keuangan' => 'Laporan',
                                default => 'Laporan',
                            };
                        @endphp
                        {{ $judul }}
                    @endif
                </h2>
                @if (Auth::user()->role !== 'pengguna')
                    <p class="text-sm text-gray-500 mt-1">Ringkasan dan statistik data permohonan</p>
                @endif
            </div>

            {{-- TOMBOL AKSI --}}
            <div class="flex gap-2 w-full sm:w-auto flex-wrap">
                @if (Auth::user()->role !== 'pengguna')
                    {{-- TOMBOL EXPORT --}}
                    <button type="button" onclick="document.getElementById('modalExcel').classList.remove('hidden')"
                        class="flex-1 sm:flex-none justify-center bg-white border border-green-600 text-green-700 hover:bg-green-50 font-bold py-2 px-4 rounded-lg text-sm shadow-sm flex items-center gap-2 transition">
                        <i class="bi bi-file-earmark-spreadsheet text-lg"></i> Excel
                    </button>
                    <a href="{{ route('laporan.export.pdf', request()->query()) }}" target="_blank"
                        class="flex-1 sm:flex-none justify-center bg-white border border-red-600 text-red-700 hover:bg-red-50 font-bold py-2 px-4 rounded-lg text-sm shadow-sm flex items-center gap-2 transition">
                        <i class="bi bi-file-earmark-pdf text-lg"></i> PDF
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="w-full px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- ============================================================ --}}
            {{-- ROUTER KE PARTIAL BERDASARKAN ROLE                           --}}
            {{-- ============================================================ --}}
            @if (Auth::user()->role === 'pengguna')
                @include('laporan.partials.pengguna')
            @elseif (Auth::user()->role === 'spsi')
                @include('laporan.partials.spsi')
            @elseif (Auth::user()->role === 'keuangan')
                @include('laporan.partials.keuangan')
            @elseif (in_array(Auth::user()->role, ['kepala_admin', 'super_admin']))
                @include('laporan.partials.admin')
            @endif

            @if (Auth::user()->role !== 'pengguna')
                <p class="text-xs text-gray-400 text-center font-mono mt-4">Data diakses pada:
                    {{ now()->translatedFormat('d F Y, H:i') }} WIB</p>
            @endif

        </div>
    </div>
    {{-- MODAL EXPORT EXCEL --}}
    <div id="modalExcel"
        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden">

            {{-- Header Modal --}}
            <div class="bg-gradient-to-r from-green-600 to-emerald-500 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="bg-white/20 rounded-lg p-2">
                        <i class="bi bi-file-earmark-spreadsheet text-white text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-white text-base">Export Excel</h3>
                        <p class="text-green-100 text-xs">Pilih rentang tanggal data yang ingin diexport</p>
                    </div>
                </div>
                <button onclick="tutupModal()"
                    class="text-white/70 hover:text-white transition text-2xl leading-none">&times;</button>
            </div>

            {{-- Body Modal --}}
            <form id="formExcel" method="GET" action="{{ route('laporan.export.excel') }}">

                {{-- Teruskan filter aktif selain tanggal --}}
                @foreach (request()->except(['dari', 'sampai', 'page']) as $key => $val)
                    @if ($val)
                        <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                    @endif
                @endforeach

                <div class="px-6 py-5 space-y-4">

                    {{-- Info --}}
                    <div
                        class="bg-green-50 border border-green-100 rounded-lg px-4 py-3 text-xs text-green-700 flex items-start gap-2">
                        <i class="bi bi-info-circle-fill mt-0.5 flex-shrink-0"></i>
                        <span>Kosongkan kedua tanggal untuk export <strong>semua data</strong> tanpa filter
                            tanggal.</span>
                    </div>

                    {{-- Dari Tanggal --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1.5 uppercase tracking-wide">
                            <i class="bi bi-calendar-event text-green-600 mr-1"></i> Dari Tanggal
                        </label>
                        <input type="date" name="dari" id="excelDari" value="{{ request('dari') }}"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-gray-50 transition">
                    </div>

                    {{-- Sampai Tanggal --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1.5 uppercase tracking-wide">
                            <i class="bi bi-calendar-check text-green-600 mr-1"></i> Sampai Tanggal
                        </label>
                        <input type="date" name="sampai" id="excelSampai" value="{{ request('sampai') }}"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-gray-50 transition">
                        <p id="errTanggal" class="text-red-500 text-xs mt-1.5 hidden flex items-center gap-1">
                            <i class="bi bi-exclamation-circle"></i> Tanggal akhir tidak boleh sebelum tanggal awal.
                        </p>
                    </div>

                    {{-- Shortcut Rentang --}}
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Shortcut Rentang</p>
                        <div class="flex flex-wrap gap-2">
                            <button type="button" onclick="setRange(7, this)"
                                class="shortcut-btn text-xs px-3 py-1.5 rounded-full border border-gray-200 text-gray-600 hover:bg-green-50 hover:border-green-400 hover:text-green-700 transition font-medium">
                                7 Hari Terakhir
                            </button>
                            <button type="button" onclick="setRange(30, this)"
                                class="shortcut-btn text-xs px-3 py-1.5 rounded-full border border-gray-200 text-gray-600 hover:bg-green-50 hover:border-green-400 hover:text-green-700 transition font-medium">
                                30 Hari Terakhir
                            </button>
                            <button type="button" onclick="setThisMonth(this)"
                                class="shortcut-btn text-xs px-3 py-1.5 rounded-full border border-gray-200 text-gray-600 hover:bg-green-50 hover:border-green-400 hover:text-green-700 transition font-medium">
                                Bulan Ini
                            </button>
                            <button type="button" onclick="setLastMonth(this)"
                                class="shortcut-btn text-xs px-3 py-1.5 rounded-full border border-gray-200 text-gray-600 hover:bg-green-50 hover:border-green-400 hover:text-green-700 transition font-medium">
                                Bulan Lalu
                            </button>
                            <button type="button" onclick="setThisYear(this)"
                                class="shortcut-btn text-xs px-3 py-1.5 rounded-full border border-gray-200 text-gray-600 hover:bg-green-50 hover:border-green-400 hover:text-green-700 transition font-medium">
                                Tahun Ini
                            </button>
                        </div>
                    </div>

                    {{-- Preview label rentang terpilih --}}
                    <div id="previewRentang"
                        class="hidden bg-gray-50 border border-gray-100 rounded-lg px-4 py-2.5 text-xs text-gray-600 flex items-center gap-2">
                        <i class="bi bi-calendar-range text-green-600"></i>
                        <span id="previewTeks"></span>
                    </div>

                </div>

                {{-- Footer Modal --}}
                <div class="px-6 pb-6 flex gap-3">
                    <button type="button" onclick="tutupModal()"
                        class="flex-1 border border-gray-200 text-gray-600 hover:bg-gray-50 font-bold py-2.5 rounded-lg text-sm transition">
                        Batal
                    </button>
                    <button type="button" onclick="submitExcel()"
                        class="flex-1 bg-green-600 hover:bg-green-700 active:bg-green-800 text-white font-bold py-2.5 rounded-lg text-sm transition flex items-center justify-center gap-2 shadow-sm">
                        <i class="bi bi-download"></i> Download Excel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById('modalExcel');
        const inputDari = document.getElementById('excelDari');
        const inputSampai = document.getElementById('excelSampai');
        const errEl = document.getElementById('errTanggal');
        const previewBox = document.getElementById('previewRentang');
        const previewTeks = document.getElementById('previewTeks');

        modal.addEventListener('click', function(e) {
            if (e.target === this) tutupModal();
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') tutupModal();
        });

        function tutupModal() {
            modal.classList.add('hidden');
            errEl.classList.add('hidden');
        }

        function pad(n) {
            return String(n).padStart(2, '0');
        }

        function toDateStr(d) {
            return d.getFullYear() + '-' + pad(d.getMonth() + 1) + '-' + pad(d.getDate());
        }

        function toDisplayStr(dateStr) {
            if (!dateStr) return '-';
            const [y, m, d] = dateStr.split('-');
            const bln = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            return d + ' ' + bln[parseInt(m) - 1] + ' ' + y;
        }

        function updatePreview() {
            const dari = inputDari.value;
            const sampai = inputSampai.value;
            if (dari || sampai) {
                previewBox.classList.remove('hidden');
                previewTeks.textContent = toDisplayStr(dari) + '  →  ' + toDisplayStr(sampai);
            } else {
                previewBox.classList.add('hidden');
            }
        }

        function highlightShortcut(el) {
            document.querySelectorAll('.shortcut-btn').forEach(b => {
                b.classList.remove('bg-green-100', 'border-green-400', 'text-green-700', 'font-bold');
            });
            if (el) {
                el.classList.add('bg-green-100', 'border-green-400', 'text-green-700', 'font-bold');
            }
        }

        // 7 hari terakhir: dari 14 sampai 21 (mundur 6 hari dari hari ini)
        function setRange(days, btn) {
            const end = new Date();
            const start = new Date();
            start.setDate(end.getDate() - (days - 1));
            inputDari.value = toDateStr(start); // lebih dulu (lebih lama)
            inputSampai.value = toDateStr(end); // lebih akhir (hari ini)
            errEl.classList.add('hidden');
            updatePreview();
            highlightShortcut(btn);
        }

        // Bulan ini: 1 Mei - 31 Mei
        function setThisMonth(btn) {
            const now = new Date();
            const start = new Date(now.getFullYear(), now.getMonth(), 1);
            const end = new Date(now.getFullYear(), now.getMonth() + 1, 0);
            inputDari.value = toDateStr(start);
            inputSampai.value = toDateStr(end);
            errEl.classList.add('hidden');
            updatePreview();
            highlightShortcut(btn);
        }

        // Bulan lalu: 1 Apr - 30 Apr
        function setLastMonth(btn) {
            const now = new Date();
            const start = new Date(now.getFullYear(), now.getMonth() - 1, 1);
            const end = new Date(now.getFullYear(), now.getMonth(), 0);
            inputDari.value = toDateStr(start);
            inputSampai.value = toDateStr(end);
            errEl.classList.add('hidden');
            updatePreview();
            highlightShortcut(btn);
        }

        // Tahun ini: 1 Jan - 31 Des
        function setThisYear(btn) {
            const y = new Date().getFullYear();
            inputDari.value = y + '-01-01';
            inputSampai.value = y + '-12-31';
            errEl.classList.add('hidden');
            updatePreview();
            highlightShortcut(btn);
        }

        inputDari.addEventListener('change', () => {
            updatePreview();
            highlightShortcut(null);
        });
        inputSampai.addEventListener('change', () => {
            updatePreview();
            highlightShortcut(null);
        });

        function submitExcel() {
            const dari = inputDari.value;
            const sampai = inputSampai.value;

            if (dari && sampai && sampai < dari) {
                errEl.classList.remove('hidden');
                inputSampai.focus();
                return;
            }

            errEl.classList.add('hidden');
            tutupModal(); // tutup modal sebelum download
            document.getElementById('formExcel').submit();
        }
    </script>
</x-app-layout>

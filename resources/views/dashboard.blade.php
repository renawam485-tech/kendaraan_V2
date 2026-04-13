<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Utama') }}
        </h2>
    </x-slot>

    <div class="py-6 md:py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- BANNER SELAMAT DATANG (Ada border kiri biru) --}}
            <div
                class="bg-white rounded-xl shadow-sm mb-8 border border-gray-100 border-l-4 border-l-blue-600 p-6 flex items-center gap-5">
                <div class="hidden sm:flex p-4 bg-blue-50 rounded-full text-blue-600">
                    <i class="bi bi-person-workspace text-3xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-black text-gray-800">Selamat Datang, {{ Auth::user()->name }}!</h3>
                    <p class="mt-1 text-sm text-gray-500">Berikut adalah ringkasan informasi dan tugas operasional Anda
                        saat ini.</p>
                </div>
            </div>

            {{-- STATISTIK KARTU (TANPA BORDER WARNA) --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
                @if (Auth::user()->role === 'kepala_admin')
                    <div
                        class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4 hover:shadow transition">
                        <div class="p-4 bg-gray-50 rounded-lg text-gray-500"><i class="bi bi-collection text-2xl"></i>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider block">Total
                                Pengajuan</span>
                            <span class="text-3xl font-black text-gray-800">{{ $stats['total_semua'] }}</span>
                        </div>
                    </div>
                    <div
                        class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4 hover:shadow transition">
                        <div class="p-4 bg-blue-50 rounded-lg text-blue-600"><i class="bi bi-check-circle text-2xl"></i>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider block">Menunggu
                                Validasi</span>
                            <span class="text-3xl font-black text-gray-800">{{ $stats['menunggu_validasi'] }}</span>
                        </div>
                    </div>
                    <div
                        class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4 hover:shadow transition">
                        <div class="p-4 bg-blue-50 rounded-lg text-blue-600"><i
                                class="bi bi-file-earmark-check text-2xl"></i></div>
                        <div>
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider block">Menunggu
                                Finalisasi</span>
                            <span class="text-3xl font-black text-gray-800">{{ $stats['menunggu_finalisasi'] }}</span>
                        </div>
                    </div>
                @elseif(Auth::user()->role === 'spsi')
                    <div
                        class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4 hover:shadow transition">
                        <div class="p-4 bg-blue-50 rounded-lg text-blue-600"><i class="bi bi-truck-front text-2xl"></i>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider block">Butuh Alokasi
                                Armada</span>
                            <span class="text-3xl font-black text-gray-800">{{ $stats['menunggu_alokasi'] }}</span>
                        </div>
                    </div>
                    <div
                        class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4 hover:shadow transition">
                        <div class="p-4 bg-gray-50 rounded-lg text-green-600"><i class="bi bi-car-front text-2xl"></i>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider block">Mobil
                                Tersedia</span>
                            <span class="text-3xl font-black text-gray-800">{{ $stats['mobil_tersedia'] }}</span>
                        </div>
                    </div>
                    <div
                        class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4 hover:shadow transition">
                        <div class="p-4 bg-gray-50 rounded-lg text-green-600"><i
                                class="bi bi-person-vcard text-2xl"></i></div>
                        <div>
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider block">Supir
                                Tersedia</span>
                            <span class="text-3xl font-black text-gray-800">{{ $stats['supir_tersedia'] }}</span>
                        </div>
                    </div>
                @elseif(Auth::user()->role === 'keuangan')
                    <div
                        class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4 hover:shadow transition">
                        <div class="p-4 bg-blue-50 rounded-lg text-blue-600"><i class="bi bi-cash-coin text-2xl"></i>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider block">Persetujuan
                                RAB</span>
                            <span class="text-3xl font-black text-gray-800">{{ $stats['menunggu_rab'] }}</span>
                        </div>
                    </div>
                    <div
                        class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4 hover:shadow transition">
                        <div class="p-4 bg-gray-50 rounded-lg text-orange-500"><i
                                class="bi bi-arrow-return-left text-2xl"></i></div>
                        <div>
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider block">Verifikasi
                                Refund</span>
                            <span class="text-3xl font-black text-gray-800">{{ $stats['menunggu_verifikasi'] }}</span>
                        </div>
                    </div>
                    <div
                        class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4 hover:shadow transition">
                        <div class="p-4 bg-gray-50 rounded-lg text-green-600"><i class="bi bi-wallet2 text-2xl"></i>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider block">RAB
                                Disetujui</span>
                            <span class="text-2xl font-black text-gray-800">Rp
                                {{ number_format($stats['rab_disetujui'], 0, ',', '.') }}</span>
                        </div>
                    </div>
                @endif
            </div>

            {{-- TABEL TUGAS TERKINI DENGAN SMART LINKING --}}
            <div class="bg-white shadow-sm sm:rounded-xl overflow-hidden border border-gray-100">
                <div class="p-5 md:p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4">
                        <h3 class="text-lg font-bold flex items-center gap-2">
                            <i class="bi bi-list-task text-blue-600"></i> Sekilas Tugas Menunggu
                        </h3>
                    </div>

                    {{-- DESKTOP VIEW --}}
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-600">
                            <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-y border-gray-100">
                                <tr>
                                    <th class="px-6 py-4">Pemohon</th>
                                    <th class="px-6 py-4">Tujuan & Waktu Acara</th>
                                    <th class="px-6 py-4">Waktu Masuk</th>
                                    <th class="px-6 py-4 text-center">Aksi (Proses)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tugasTerbaru as $tugas)
                                    @php
                                        $role = Auth::user()->role;
                                        // $status sekarang adalah objek Enum StatusPermohonan
                                        $status = $tugas->status_permohonan;
                                        $detailUrl = route('dashboard');

                                        // PERBAIKAN: Gunakan perbandingan dengan Class Enum langsung atau ->value
                                        if ($role === 'kepala_admin') {
                                            $detailUrl =
                                                $status === \App\Enums\StatusPermohonan::MENUNGGU_VALIDASI_ADMIN
                                                    ? route('permohonan.validasi_admin', $tugas->id)
                                                    : route('permohonan.finalisasi_admin', $tugas->id);
                                        } elseif ($role === 'spsi') {
                                            $detailUrl = route('permohonan.proses_spsi', $tugas->id);
                                        } elseif ($role === 'keuangan') {
                                            $detailUrl =
                                                $status === \App\Enums\StatusPermohonan::MENUNGGU_PROSES_KEUANGAN
                                                    ? route('permohonan.proses_keuangan', $tugas->id)
                                                    : route('permohonan.show', $tugas->id);
                                        }

                                        // PERBAIKAN: Tambahkan ->value agar str_contains menerima string, bukan objek
                                        $badgeClass =
                                            str_contains($status->value, 'Validasi') ||
                                            str_contains($status->value, 'RAB')
                                                ? 'bg-blue-50 text-blue-700 border-blue-200'
                                                : 'bg-orange-50 text-orange-700 border-orange-200';
                                    @endphp
                                    <tr class="bg-white border-b border-gray-50 hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 font-bold text-gray-800">
                                            <i class="bi bi-person text-gray-400 mr-2"></i> {{ $tugas->nama_pic }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2 text-gray-700">
                                                <i class="bi bi-geo-alt text-gray-400"></i> {{ $tugas->tujuan }}
                                            </div>
                                            <div class="text-xs text-gray-500 mt-1 flex items-center gap-2">
                                                <i class="bi bi-calendar-event text-gray-400"></i>
                                                {{ \Carbon\Carbon::parse($tugas->waktu_berangkat)->format('d M Y H:i') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-xs font-medium block mb-1">
                                                <i class="bi bi-clock-history text-gray-400 mr-1"></i>
                                                {{ $tugas->updated_at->diffForHumans() }}
                                            </span>
                                            {{-- PERBAIKAN: Tampilkan nilai string dengan ->value --}}
                                            <span
                                                class="px-2 py-0.5 border text-[10px] font-bold rounded {{ $badgeClass }}">
                                                {{ $status->value }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <a href="{{ $detailUrl }}"
                                                class="inline-flex items-center gap-1 bg-white border border-gray-300 text-gray-700 hover:text-blue-700 hover:border-blue-400 hover:bg-blue-50 py-1.5 px-3 rounded text-xs font-bold transition shadow-sm">
                                                Proses <i class="bi bi-arrow-right"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                            <i class="bi bi-inboxes text-4xl block mb-3 text-gray-300"></i>
                                            Tidak ada tugas yang menunggu. Bagus!
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- TOMBOL AKSI CEPAT DENGAN SMART ROUTING --}}
                    @if ($tugasTerbaru->count() > 0)
                        <div class="mt-6">
                            <a href="{{ $ruteTugas }}"
                                class="flex justify-center items-center gap-2 w-full md:w-auto md:inline-flex bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-lg shadow-sm transition">
                                <i class="bi bi-lightning-charge-fill"></i> Mulai Kerjakan Tugas Terbanyak
                            </a>
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
</x-app-layout>

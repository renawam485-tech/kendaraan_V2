<x-app-layout>
    <div class="py-6 md:py-10">
        <div class="w-full px-4 sm:px-6 lg:px-8">

            <!-- HEADER -->
            <div class="relative overflow-hidden rounded-2xl mb-8 p-6 text-white shadow-lg bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600">
                <div class="absolute w-[300px] h-[300px] bg-white/10 blur-3xl rounded-full -top-20 -right-20"></div>
                <div class="relative flex items-center gap-5">
                    <div class="hidden sm:flex p-4 bg-white/20 rounded-xl">
                        <i class="bi bi-speedometer2 text-3xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold">
                            Selamat Datang, {{ Auth::user()->name }}
                        </h3>
                        <p class="text-sm text-white/80 mt-1">
                            Dashboard Sistem Peminjaman Kendaraan
                        </p>
                    </div>
                </div>
            </div>

            <!-- STATS -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

                @if (Auth::user()->role === 'kepala_admin')
                    <div class="card">
                        <div class="icon bg-gradient-to-br from-blue-500 to-indigo-500">
                            <i class="bi bi-collection"></i>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider block">Total Pengajuan</span>
                            <h2>{{ $stats['total_semua'] }}</h2>
                        </div>
                    </div>
                    <div class="card">
                        <div class="icon bg-gradient-to-br from-blue-500 to-cyan-500">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider block">Menunggu Validasi</span>
                            <h2>{{ $stats['menunggu_validasi'] }}</h2>
                        </div>
                    </div>
                    <div class="card">
                        <div class="icon bg-gradient-to-br from-indigo-500 to-purple-500">
                            <i class="bi bi-file-earmark-check"></i>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider block">Menunggu Finalisasi</span>
                            <h2>{{ $stats['menunggu_finalisasi'] }}</h2>
                        </div>
                    </div>

                @elseif(Auth::user()->role === 'spsi')
                    <div class="card">
                        <div class="icon bg-gradient-to-br from-green-500 to-emerald-500">
                            <i class="bi bi-truck"></i>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider block">Butuh Alokasi Armada</span>
                            <h2>{{ $stats['menunggu_alokasi'] }}</h2>
                        </div>
                    </div>
                    <div class="card">
                        <div class="icon bg-gradient-to-br from-green-500 to-teal-500">
                            <i class="bi bi-car-front"></i>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider block">Mobil Tersedia</span>
                            <h2>{{ $stats['mobil_tersedia'] }}</h2>
                        </div>
                    </div>
                    <div class="card">
                        <div class="icon bg-gradient-to-br from-teal-500 to-green-500">
                            <i class="bi bi-person-vcard"></i>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider block">Supir Tersedia</span>
                            <h2>{{ $stats['supir_tersedia'] }}</h2>
                        </div>
                    </div>

                @elseif(Auth::user()->role === 'keuangan')
                    <div class="card">
                        <div class="icon bg-gradient-to-br from-yellow-500 to-orange-500">
                            <i class="bi bi-cash-coin"></i>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider block">Persetujuan RAB</span>
                            <h2>{{ $stats['menunggu_rab'] }}</h2>
                        </div>
                    </div>
                    <div class="card">
                        <div class="icon bg-gradient-to-br from-orange-500 to-red-500">
                            <i class="bi bi-arrow-repeat"></i>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider block">Verifikasi Refund</span>
                            <h2>{{ $stats['menunggu_verifikasi'] }}</h2>
                        </div>
                    </div>
                    <div class="card">
                        <div class="icon bg-gradient-to-br from-green-500 to-emerald-500">
                            <i class="bi bi-wallet2"></i>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider block">RAB Disetujui</span>
                            <h2>Rp {{ number_format($stats['rab_disetujui'], 0, ',', '.') }}</h2>
                        </div>
                    </div>
                @endif

            </div>

            <!-- TABLE -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

                <div class="p-6 border-b flex justify-between items-center">
                    <h3 class="font-bold flex items-center gap-2">
                        <i class="bi bi-list-task text-blue-600"></i>
                        Tugas Menunggu
                    </h3>
                </div>

                {{-- DESKTOP VIEW --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-600">
                        <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-y border-gray-100">
                            <tr>
                                <th class="px-6 py-4 text-center w-12">No</th>
                                <th class="px-6 py-4">Pemohon</th>
                                <th class="px-6 py-4">Tujuan & Waktu Acara</th>
                                <th class="px-6 py-4">Waktu Masuk</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tugasTerbaru as $index => $tugas)
                                @php
                                    $role = Auth::user()->role;
                                    $status = $tugas->status_permohonan;
                                    $detailUrl = route('dashboard');

                                    if ($role === 'kepala_admin') {
                                        $detailUrl = $status === \App\Enums\StatusPermohonan::MENUNGGU_VALIDASI_ADMIN
                                            ? route('permohonan.validasi_admin', $tugas->id)
                                            : route('permohonan.finalisasi_admin', $tugas->id);
                                    } elseif ($role === 'spsi') {
                                        $detailUrl = route('permohonan.proses_spsi', $tugas->id);
                                    } elseif ($role === 'keuangan') {
                                        $detailUrl = $status === \App\Enums\StatusPermohonan::MENUNGGU_PROSES_KEUANGAN
                                            ? route('permohonan.proses_keuangan', $tugas->id)
                                            : route('permohonan.show', $tugas->id);
                                    }

                                    $badgeClass = str_contains($status->value, 'Validasi') || str_contains($status->value, 'RAB')
                                        ? 'bg-blue-50 text-blue-700 border-blue-200'
                                        : 'bg-orange-50 text-orange-700 border-orange-200';
                                @endphp
                                <tr class="bg-white border-b border-gray-50 hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 text-center text-xs text-gray-400 font-semibold">
                                        {{ $index + 1 }}
                                    </td>
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
                                            <span class="relative-time-updated" data-updated="{{ $tugas->updated_at->toISOString() }}">
                                                {{ $tugas->updated_at->diffForHumans() }}
                                            </span>
                                        </span>
                                        <span class="px-2 py-0.5 border text-[10px] font-bold rounded {{ $badgeClass }}">
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
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                        <i class="bi bi-inboxes text-4xl block mb-3 text-gray-300"></i>
                                        Tidak ada tugas yang menunggu. Bagus!
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- MOBILE CARDS --}}
                <div class="block md:hidden space-y-3 p-4">
                    @forelse($tugasTerbaru as $index => $tugas)
                        @php
                            $role = Auth::user()->role;
                            $status = $tugas->status_permohonan;
                            $detailUrl = route('dashboard');

                            if ($role === 'kepala_admin') {
                                $detailUrl = $status === \App\Enums\StatusPermohonan::MENUNGGU_VALIDASI_ADMIN
                                    ? route('permohonan.validasi_admin', $tugas->id)
                                    : route('permohonan.finalisasi_admin', $tugas->id);
                            } elseif ($role === 'spsi') {
                                $detailUrl = route('permohonan.proses_spsi', $tugas->id);
                            } elseif ($role === 'keuangan') {
                                $detailUrl = $status === \App\Enums\StatusPermohonan::MENUNGGU_PROSES_KEUANGAN
                                    ? route('permohonan.proses_keuangan', $tugas->id)
                                    : route('permohonan.show', $tugas->id);
                            }

                            $badgeClass = str_contains($status->value, 'Validasi') || str_contains($status->value, 'RAB')
                                ? 'bg-blue-50 text-blue-700 border-blue-200'
                                : 'bg-orange-50 text-orange-700 border-orange-200';
                        @endphp
                        <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm hover:shadow-md transition">
                            <div class="flex items-start justify-between gap-2">
                                <div class="flex items-start gap-2 min-w-0">
                                    <span class="flex-shrink-0 w-6 h-6 bg-slate-100 text-slate-500 text-xs font-bold rounded-md flex items-center justify-center mt-0.5">
                                        {{ $index + 1 }}
                                    </span>
                                    <div class="min-w-0">
                                        @if ($tugas->kode_permohonan)
                                            <span class="text-[10px] font-black text-blue-700 tracking-widest bg-blue-50 border border-blue-200 px-1.5 py-0.5 rounded block w-fit mb-1">
                                                {{ $tugas->kode_permohonan }}
                                            </span>
                                        @endif
                                        <p class="font-semibold text-sm text-gray-800 truncate">{{ $tugas->nama_pic }}</p>
                                        <p class="text-xs text-gray-500 mt-0.5">
                                            <i class="bi bi-geo-alt mr-0.5"></i>{{ $tugas->tujuan }}
                                        </p>
                                        <p class="text-xs text-gray-400 mt-0.5">
                                            <i class="bi bi-calendar2-event mr-0.5"></i>
                                            {{ \Carbon\Carbon::parse($tugas->waktu_berangkat)->format('d M Y, H:i') }}
                                        </p>
                                        <p class="text-xs text-gray-400 mt-0.5">
                                            <i class="bi bi-clock-history mr-0.5"></i>
                                            <span class="relative-time-updated" data-updated="{{ $tugas->updated_at->toISOString() }}">
                                                {{ $tugas->updated_at->diffForHumans() }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex gap-2 mt-3 pl-8">
                                <a href="{{ $detailUrl }}"
                                    class="flex-1 text-center py-1.5 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition flex items-center justify-center gap-1">
                                    Proses
                                </a>
                                <a href="{{ route('permohonan.show', $tugas->id) }}"
                                    class="flex-1 text-center py-1.5 text-xs font-bold text-gray-700 bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-lg transition flex items-center justify-center gap-1">
                                    Detail
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white rounded-xl border border-gray-200 p-8 text-center text-gray-400">
                            <i class="bi bi-inbox text-5xl block mb-3 text-gray-300"></i>
                            <p class="font-medium text-gray-500">Tidak ada tugas yang menunggu</p>
                        </div>
                    @endforelse
                </div>

                {{-- FOOTER --}}
                <div class="px-5 py-3 border-t border-gray-100 flex items-center justify-between">
                    <p class="text-xs text-gray-400">
                        Menampilkan {{ $tugasTerbaru->count() }} dari {{ $tugasTerbaru->count() }} data
                    </p>
                    <p class="text-xs text-gray-400" id="realtimeClockFooter">
                        {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
                    </p>
                </div>

            </div>

        </div>
    </div>

    <style>
        .card {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 20px;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid #eee;
            transition: 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .card .icon {
            padding: 12px;
            border-radius: 12px;
            color: white;
            font-size: 20px;
        }

        .card span {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
        }

        .card h2 {
            font-size: 24px;
            font-weight: bold;
            color: #111;
        }

        .btn-primary {
            background: linear-gradient(to right, #2563eb, #4f46e5);
            color: white;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: bold;
            transition: 0.2s;
        }

        .btn-primary:hover {
            opacity: 0.85;
        }
    </style>

    <script>
        function updateRelativeTimes() {
            document.querySelectorAll('.relative-time-updated').forEach(element => {
                const updatedDate = new Date(element.dataset.updated);
                const now = new Date();
                const diffInSeconds = Math.floor((now - updatedDate) / 1000);
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
            });

            const clockEl = document.getElementById('realtimeClockFooter');
            if (clockEl) {
                const now = new Date();
                const formatted = now.toLocaleString('id-ID', {
                    day: '2-digit',
                    month: 'long',
                    year: 'numeric'
                });
                clockEl.textContent = formatted;
            }
        }

        setInterval(updateRelativeTimes, 60000);
        updateRelativeTimes();
    </script>

</x-app-layout>
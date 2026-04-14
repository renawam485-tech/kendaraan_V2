<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <i class="bi bi-key-fill text-blue-600"></i> Manajemen Serah Terima Kendaraan
        </h2>
    </x-slot>

    <div class="py-6 bg-slate-50 min-h-screen">
        <div class="w-full px-4 sm:px-6 lg:px-8 space-y-5">

            {{-- ── STATS ── --}}
            <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                @foreach ([[$pending->total(), 'Perlu Serah Terima', 'bi-key-fill', 'bg-amber-50', 'text-amber-600'], [$menungguMulai->total(), 'Menunggu User Mulai', 'bi-person-walking', 'bg-yellow-50', 'text-yellow-600'], [$berlangsung->total(), 'Dalam Perjalanan', 'bi-geo-alt-fill', 'bg-teal-50', 'text-teal-600'], [$menungguKonfirmasi->total(), 'Menunggu Konfirmasi', 'bi-arrow-return-left', 'bg-indigo-50', 'text-indigo-600'], [$riwayat->total(), 'Riwayat', 'bi-archive', 'bg-slate-100', 'text-slate-600']] as [$count, $label, $icon, $bg, $tc])
                    <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-lg {{ $bg }} flex items-center justify-center flex-shrink-0">
                                <i class="bi {{ $icon }} {{ $tc }} text-lg"></i>
                            </div>
                            <div>
                                <p class="text-2xl font-black text-gray-800">{{ $count }}</p>
                                <p class="text-xs text-gray-500 font-semibold leading-tight">{{ $label }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- ── TABS ── --}}
            @php
                $activeTab = $tab ?? 'pending';

                $tabConfig = [
                    'pending' => ['Perlu Serah Terima', $pending->total(), 'bg-amber-500'],
                    'menunggu' => ['Menunggu User Mulai', $menungguMulai->total(), 'bg-yellow-500'],
                    'berlangsung' => ['Dalam Perjalanan', $berlangsung->total(), 'bg-teal-500'],
                    'konfirmasi' => ['Konfirmasi Kembali', $menungguKonfirmasi->total(), 'bg-indigo-500'],
                    'riwayat' => ['Riwayat', $riwayat->total(), 'bg-slate-400'],
                ];

                $tabAlerts = [
                    'pending' => [
                        'bg-amber-50 border-amber-100',
                        'text-amber-500',
                        'bi-info-circle-fill',
                        'text-amber-700',
                        'Permohonan sudah <strong>disetujui Admin</strong>. Serahkan kunci fisik kepada pemohon dan klik tombol konfirmasi.',
                    ],
                    'menunggu' => [
                        'bg-yellow-50 border-yellow-100',
                        'text-yellow-500',
                        'bi-info-circle-fill',
                        'text-yellow-700',
                        'Kunci sudah diserahkan. Menunggu pemohon klik <strong>"Mulai Perjalanan"</strong>.',
                    ],
                    'berlangsung' => [
                        'bg-teal-50 border-teal-100',
                        'text-teal-500',
                        'bi-geo-alt-fill',
                        'text-teal-700',
                        'Kendaraan sedang dalam perjalanan aktif.',
                    ],
                    'konfirmasi' => [
                        'bg-indigo-50 border-indigo-100',
                        'text-indigo-500',
                        'bi-arrow-return-left',
                        'text-indigo-700',
                        'Pemohon melaporkan sudah kembali. Periksa kondisi kendaraan lalu konfirmasi.',
                    ],
                    'riwayat' => [
                        'bg-slate-50 border-slate-200',
                        'text-slate-500',
                        'bi-archive',
                        'text-gray-600',
                        'Riwayat perjalanan yang sudah selesai atau ditutup.',
                    ],
                ];

                $tabTableConfig = [
                    'pending' => [
                        'items' => $pending,
                        'type' => 'pending',
                        'showActions' => true,
                        'actionRoute' => 'permohonan.serah_terima_kunci',
                        'actionText' => 'Serah Terima',
                        'actionColor' => 'bg-amber-500 hover:bg-amber-600',
                    ],
                    'menunggu' => ['items' => $menungguMulai, 'type' => 'menunggu', 'showActions' => false],
                    'berlangsung' => ['items' => $berlangsung, 'type' => 'berlangsung', 'showActions' => false],
                    'konfirmasi' => [
                        'items' => $menungguKonfirmasi,
                        'type' => 'konfirmasi',
                        'showActions' => true,
                        'actionRoute' => 'permohonan.konfirmasi_kembali',
                        'actionText' => 'Konfirmasi',
                        'actionColor' => 'bg-indigo-600 hover:bg-indigo-700',
                    ],
                    'riwayat' => ['items' => $riwayat, 'type' => 'riwayat', 'showActions' => false],
                ];
            @endphp

            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">

                {{-- Tab Headers --}}
                <div class="border-b border-gray-200 flex overflow-x-auto bg-gray-50">
                    @foreach ($tabConfig as $key => [$label, $count, $badgeBg])
                        <a href="{{ route('spsi.serah_terima', ['tab' => $key]) }}"
                            class="flex-shrink-0 flex items-center gap-2 px-5 py-3.5 text-sm transition
                                {{ $activeTab === $key
                                    ? 'border-b-2 border-blue-600 text-blue-700 bg-white font-bold'
                                    : 'text-gray-500 hover:text-gray-700 hover:bg-gray-100' }}">
                            {{ $label }}
                            @if ($count > 0)
                                <span
                                    class="{{ $badgeBg }} text-white text-[10px] font-black px-1.5 py-0.5 rounded-full">{{ $count }}</span>
                            @endif
                        </a>
                    @endforeach
                </div>

                {{-- Tab Alert Banner --}}
                @php [$alertBg, $alertIconColor, $alertIcon, $alertTextColor, $alertText] = $tabAlerts[$activeTab]; @endphp
                <div class="p-4 {{ $alertBg }} border-b flex items-start gap-2">
                    <i class="bi {{ $alertIcon }} {{ $alertIconColor }} flex-shrink-0 mt-0.5"></i>
                    <p class="text-sm {{ $alertTextColor }}">{!! $alertText !!}</p>
                </div>

                {{-- Search Bar --}}
                <div
                    class="px-5 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2 text-sm">
                        <i class="bi bi-table text-blue-600"></i>
                        {{ $tabConfig[$activeTab][0] }}
                        <span class="ml-1 text-xs font-semibold text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full">
                            {{ $tabTableConfig[$activeTab]['items']->total() }} data
                        </span>
                    </h3>
                    <form method="GET" action="{{ route('spsi.serah_terima') }}" class="relative w-full sm:w-72">
                        <input type="hidden" name="tab" value="{{ $activeTab }}">
                        <i
                            class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>
                        <input type="text" name="search" value="{{ request('search', '') }}" autocomplete="off"
                            placeholder="Cari kode, nama, tujuan..."
                            class="w-full pl-9 pr-9 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition bg-gray-50 focus:bg-white">
                        @if (request('search'))
                            <a href="{{ route('spsi.serah_terima', ['tab' => $activeTab]) }}"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-red-500 transition">
                                <i class="bi bi-x-circle-fill"></i>
                            </a>
                        @endif
                    </form>
                </div>

                {{-- Table via partial --}}
                @include(
                    'permohonan.partials.tabel-serah-terima',
                    array_merge($tabTableConfig[$activeTab], [
                        'search' => request('search', ''),
                        'tab' => $activeTab,
                        'actionRoute' => $tabTableConfig[$activeTab]['actionRoute'] ?? null,
                        'actionText' => $tabTableConfig[$activeTab]['actionText'] ?? null,
                        'actionColor' => $tabTableConfig[$activeTab]['actionColor'] ?? '',
                    ]))

            </div>{{-- end card --}}
        </div>
    </div>
    <script>
        function handleSerahTerima(id, namaPic) {
            customConfirm({
                title: 'Konfirmasi Serah Terima',
                message: `Apakah Anda sudah menyerahkan kunci kendaraan kepada <strong>${escapeHtml(namaPic)}</strong>?`,
                confirmText: 'Ya, Sudah Diserahkan',
                isDanger: false
            }, () => {
                fetch(`/permohonan/${id}/serah-terima-kunci`, {
                        method: 'PUT', // ← PUT sesuai route
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.showCompactToast('Berhasil', data.message);
                            setTimeout(() => window.location.reload(), 1500);
                        } else {
                            window.showCompactToast('Gagal', data.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        window.showCompactToast('Gagal', 'Terjadi kesalahan jaringan', 'error');
                    });
            });
        }

        function handleKonfirmasiKembali(id, namaPic) {
            customConfirm({
                title: 'Konfirmasi Penerimaan Kendaraan',
                message: `Apakah kendaraan sudah kembali dalam kondisi baik dari <strong>${escapeHtml(namaPic)}</strong>?`,
                confirmText: 'Ya, Sudah Kembali',
                isDanger: false
            }, () => {
                fetch(`/permohonan/${id}/konfirmasi-kembali`, {
                        method: 'PUT', // ← PUT sesuai route
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.showCompactToast('Berhasil', data.message);
                            setTimeout(() => window.location.reload(), 1500);
                        } else {
                            window.showCompactToast('Gagal', data.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        window.showCompactToast('Gagal', 'Terjadi kesalahan jaringan', 'error');
                    });
            });
        }

        // Helper function untuk mencegah XSS
        function escapeHtml(str) {
            if (!str) return '';
            return str
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
        }
    </script>
</x-app-layout>

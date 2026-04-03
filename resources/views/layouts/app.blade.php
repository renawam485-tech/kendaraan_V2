<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')
        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">{{ $header }}</div>
            </header>
        @endisset
        <main>{{ $slot }}</main>
    </div>

    @auth
        <div x-data="{
            open: false,
            view: 'list',
            activeTitle: '',
            activeMessage: '',
            activeTime: '',
            activeUrl: '',
            unreadCount: {{ auth()->user()->unreadNotifications->count() }},
        
            bacaSemua() {
                fetch('{{ route('notif.baca_semua') }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
                    .then(() => {
                        window.dispatchEvent(new CustomEvent('clear-badge'));
                        document.querySelectorAll('.unread-item').forEach(el => {
                            el.classList.remove('bg-purple-50', 'unread-item');
                            el.classList.add('opacity-60', 'bg-white', 'read-item');
                            let dot = el.querySelector('.unread-dot');
                            if (dot) dot.remove();
                        });
                    });
            },
            hapusTerbaca() {
                fetch('{{ route('notif.hapus_terbaca') }}', { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
                    .then(() => document.querySelectorAll('.read-item').forEach(el => el.remove()));
            },
            openDetail(el) {
                this.activeTitle = el.dataset.title;
                this.activeMessage = el.dataset.message;
                this.activeTime = el.dataset.time;
                this.activeUrl = el.dataset.url;
                this.view = 'detail';
        
                if (el.classList.contains('unread-item')) {
                    el.classList.remove('bg-purple-50', 'unread-item');
                    el.classList.add('opacity-60', 'bg-white', 'read-item');
                    let dot = el.querySelector('.unread-dot');
                    if (dot) dot.remove();
        
                    window.dispatchEvent(new CustomEvent('decrease-badge'));
        
                    fetch(`/notifikasi/${el.dataset.id}/baca`, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                    });
                }
            }
        }" @open-notif-panel.window="open = true; view = 'list'"
            @increase-badge.window="unreadCount++" @decrease-badge.window="if(unreadCount > 0) unreadCount--"
            @clear-badge.window="unreadCount = 0">

            <div x-show="open" style="display: none;" class="fixed inset-0 bg-black bg-opacity-40 z-40 transition-opacity"
                @click="open = false"></div>

            <div class="fixed top-0 right-0 h-full w-full sm:w-96 bg-white shadow-2xl z-50 transform transition-transform duration-300 flex flex-col"
                :class="open ? 'translate-x-0' : 'translate-x-full'">

                <div x-show="view === 'list'" class="p-4 border-b flex justify-between items-center bg-gray-50">
                    <h3 class="font-bold text-lg text-gray-800 flex items-center gap-2">Notifikasi
                        <template x-if="unreadCount > 0"><span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full"
                                x-text="unreadCount"></span></template>
                    </h3>
                    <button @click="open = false"
                        class="text-gray-400 hover:text-red-500 text-3xl font-light focus:outline-none">&times;</button>
                </div>

                <div x-show="view === 'detail'" style="display: none;"
                    class="p-4 border-b flex items-center bg-white shadow-sm z-10">
                    <button @click="view = 'list'"
                        class="text-blue-600 font-bold text-sm flex items-center hover:text-blue-800 transition focus:outline-none py-1">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg> Kembali
                    </button>
                </div>

                <div x-show="view === 'list'" class="flex-1 overflow-y-auto flex flex-col bg-white">
                    <div class="flex border-b text-xs flex-shrink-0 bg-white sticky top-0 z-10 shadow-sm">
                        <button @click="bacaSemua()"
                            class="flex-1 py-4 px-2 text-blue-600 hover:bg-blue-50 font-bold border-r transition focus:outline-none">✓
                            Baca Semua</button>
                        <button @click="hapusTerbaca()"
                            class="flex-1 py-4 px-2 text-red-600 hover:bg-red-50 font-bold transition focus:outline-none">🗑
                            Hapus Terbaca</button>
                    </div>
                    @forelse(auth()->user()->notifications as $notif)
                        @php
                            $detailUrl = route('dashboard');
                            if (isset($notif->data['permohonan_id'])) {
                                $id = $notif->data['permohonan_id'];
                                $role = auth()->user()->role;
                                $status = $notif->data['status'] ?? '';
                                if ($role === 'pengguna') {
                                    $detailUrl = route('permohonan.show', $id);
                                } elseif ($role === 'kepala_admin') {
                                    if ($status === 'Menunggu Validasi Admin') {
                                        $detailUrl = route('permohonan.validasi_admin', $id);
                                    } elseif ($status === 'Menunggu Finalisasi') {
                                        $detailUrl = route('permohonan.finalisasi_admin', $id);
                                    }
                                } elseif ($role === 'spsi' && $status === 'Menunggu Proses SPSI') {
                                    $detailUrl = route('permohonan.proses_spsi', $id);
                                } elseif ($role === 'keuangan' && $status === 'Menunggu Proses Keuangan') {
                                    $detailUrl = route('permohonan.proses_keuangan', $id);
                                }
                            }
                        @endphp

                        <a href="#" @click.prevent="openDetail($el)" data-id="{{ $notif->id }}"
                            data-title="{{ $notif->data['status'] ?? 'Info Sistem' }}"
                            data-message="{{ $notif->data['pesan'] }}"
                            data-time="{{ $notif->created_at->diffForHumans() }}" data-url="{{ $detailUrl }}"
                            class="block p-4 border-b hover:bg-gray-100 transition {{ $notif->read_at ? 'opacity-60 bg-white read-item' : 'bg-purple-50 unread-item' }}">

                            <div class="font-bold text-sm {{ $notif->read_at ? 'text-gray-600' : 'text-purple-700' }}">
                                {{ $notif->data['status'] ?? 'Info Sistem' }}</div>
                            <div class="text-sm text-gray-800 mt-1 leading-snug line-clamp-2">{{ $notif->data['pesan'] }}
                            </div>
                            <div class="text-[11px] text-gray-500 mt-2 flex justify-between items-center">
                                <span>{{ $notif->created_at->diffForHumans() }}</span>
                                @if (!$notif->read_at)
                                    <span class="w-2.5 h-2.5 bg-red-500 rounded-full unread-dot shadow-sm"></span>
                                @endif
                            </div>
                        </a>
                    @empty
                        <div class="p-8 flex flex-col items-center justify-center text-gray-400 mt-10">
                            <svg class="w-16 h-16 mb-4 text-gray-200" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            <p class="text-sm font-medium">Tidak ada notifikasi.</p>
                        </div>
                    @endforelse
                </div>

                <div x-show="view === 'detail'" style="display: none;" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0"
                    class="flex-1 bg-gray-50 overflow-y-auto p-4 sm:p-6">
                    <h4 class="font-black text-xl sm:text-2xl text-gray-800" x-text="activeTitle"></h4>
                    <p class="text-xs text-gray-400 mt-2 mb-6 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg> <span x-text="activeTime"></span>
                    </p>

                    <div class="bg-white border border-gray-200 p-4 sm:p-5 rounded-lg shadow-sm text-gray-700 text-sm leading-relaxed mb-8"
                        x-text="activeMessage"></div>

                    <a :href="activeUrl"
                        class="block w-full text-center bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 sm:py-4 px-4 rounded-lg shadow-md transition focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                        Tindak Lanjuti / Lihat Detail
                    </a>
                </div>
            </div>
        </div>
    @endauth

    <div id="toast-container" class="fixed bottom-5 right-5 sm:bottom-10 sm:right-10 z-[60] flex flex-col gap-3"></div>
    <audio id="notif-sound" src="{{ asset('sounds/notif.mp3') }}" preload="auto"></audio>

    <script type="module">
        // Fungsi memunculkan Toast ala Windows Compact
        window.showCompactToast = function(title, message) {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className =
                'bg-white border border-gray-200 shadow-2xl rounded-lg p-3 sm:p-4 w-[90vw] sm:w-80 max-w-sm transform transition-all duration-500 translate-x-[120%] flex items-start gap-3 relative';
            toast.innerHTML = `
                <div class="flex-shrink-0 mt-1 text-purple-600 bg-purple-100 p-1.5 rounded-full">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div class="flex-1 pr-4">
                    <p class="font-bold text-sm sm:text-base text-gray-800">${title}</p>
                    <p class="text-xs sm:text-sm text-gray-600 mt-1 leading-tight line-clamp-2">${message}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700 focus:outline-none">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            `;
            container.appendChild(toast);
            const audio = document.getElementById('notif-sound');
            if (audio) {
                audio.currentTime = 0;
                audio.play().catch(() => {});
            }
            setTimeout(() => toast.classList.remove('translate-x-[120%]'), 50);
            setTimeout(() => {
                toast.classList.add('translate-x-[120%]');
                setTimeout(() => toast.remove(), 500);
            }, 7000);
        };

        document.addEventListener('DOMContentLoaded', function() {
            const userId = {{ auth()->check() ? auth()->id() : 'null' }};

            if (userId !== null && window.Echo) {
                window.Echo.private('App.Models.User.' + userId)
                    .notification((notification) => {
                        const title = notification.status || (notification.data && notification.data.status) ||
                            'Notifikasi Baru';
                        const message = notification.pesan || (notification.data && notification.data.pesan) ||
                            'Silakan cek pembaruan terbaru.';

                        // 1. Tampilkan Pop-up Notifikasi & Suara
                        window.showCompactToast(title, message);

                        // 2. Tambah angka di Ikon Lonceng secara Real-Time
                        window.dispatchEvent(new CustomEvent('increase-badge'));

                        // 3. FITUR BARU: AUTO-UPDATE KONTEN DASHBOARD (Silent Reload)
                        // Daftarkan semua URL halaman tugas yang ingin auto-update di sini
                        const autoUpdatePaths = [
                            '/dashboard',
                            '/admin/validasi', '/admin/finalisasi', '/admin/riwayat',
                            '/spsi/alokasi', '/spsi/monitoring',
                            '/keuangan/rab', '/keuangan/monitoring'
                        ];

                        // Cek apakah halaman saat ini ada di dalam daftar di atas
                        if (autoUpdatePaths.includes(window.location.pathname)) {

                            // Minta data terbaru dari server di latar belakang
                            fetch(window.location.href)
                                .then(response => response.text())
                                .then(html => {
                                    // Ambil HTML halaman baru
                                    const parser = new DOMParser();
                                    const doc = parser.parseFromString(html, 'text/html');

                                    // Ekstrak hanya bagian <main> (Tabel/Kartu) dari HTML baru
                                    const newMainContent = doc.querySelector('main');

                                    // Timpa (Replace) tabel lama dengan tabel baru
                                    if (newMainContent) {
                                        document.querySelector('main').innerHTML = newMainContent.innerHTML;
                                    }
                                })
                                .catch(error => console.error('Gagal memperbarui halaman secara real-time:',
                                    error));
                        }
                    });
            }
        });
    </script>
</body>

</html>

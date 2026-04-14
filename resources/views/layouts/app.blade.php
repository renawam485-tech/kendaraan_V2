<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Drivora') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        [x-cloak] {
            display: none !important;
        }

        .notif-scroll {
            overscroll-behavior-y: contain;
        }

        .sidebar-transition {
            transition: width 0.3s ease, transform 0.3s ease;
        }

        .content-transition {
            transition: margin-left 0.3s ease;
        }
    </style>

    <script>
        (function() {
            const isMobile = window.innerWidth < 768;
            const collapsed = !isMobile && localStorage.getItem('sidebarCollapsed') === 'true';
            document.documentElement.classList.add(collapsed ? 'sidebar-mini' : 'sidebar-full');
        })();
    </script>
</head>

<body class="font-sans antialiased text-gray-800 bg-gray-50" x-data="{
    sidebarOpen: false,
    sidebarCollapsed: false,
    isMobile: window.innerWidth < 768,

    toggleSidebar() {
        this.sidebarCollapsed = !this.sidebarCollapsed;
        localStorage.setItem('sidebarCollapsed', this.sidebarCollapsed);
    },
    init() {
        this.checkScreen();
        window.addEventListener('resize', () => this.checkScreen());
    },
    checkScreen() {
        this.isMobile = window.innerWidth < 768;
        if (this.isMobile) {
            this.sidebarCollapsed = false;
        } else {
            this.sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
        }
    }
}">

    @php
        $isPengguna = auth()->check() ? auth()->user()->role === 'pengguna' : true;
    @endphp

    @include('layouts.navigation', ['render' => 'topbar'])

    <div class="flex pt-16 min-h-screen">
        @include('layouts.navigation', ['render' => 'sidebar'])

        <main class="flex-1 w-full pb-10 content-transition"
            :class="{
                'md:ml-64': !sidebarCollapsed && !{{ $isPengguna ? 'true' : 'false' }},
                'md:ml-20': sidebarCollapsed && !{{ $isPengguna ? 'true' : 'false' }},
                'ml-0': {{ $isPengguna ? 'true' : 'false' }}
            }">

            @isset($header)
                <header class="bg-white shadow-sm border-b border-gray-200">
                    <div class="w-full py-5 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <div class="py-6">
                {{ $slot }}
            </div>
        </main>
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
            touchStartY: 0,
            pullDistance: 0,
            isPulling: false,
            isRefreshing: false,
        
            bacaSemua() {
                fetch('{{ route('notif.baca_semua') }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
                    .then(() => {
                        window.dispatchEvent(new CustomEvent('clear-badge'));
                        document.querySelectorAll('.unread-item').forEach(el => {
                            el.classList.remove('bg-blue-50', 'unread-item');
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
                    el.classList.remove('bg-blue-50', 'unread-item');
                    el.classList.add('opacity-60', 'bg-white', 'read-item');
                    let dot = el.querySelector('.unread-dot');
                    if (dot) dot.remove();
                    window.dispatchEvent(new CustomEvent('decrease-badge'));
                    fetch(`/notifikasi/${el.dataset.id}/baca`, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
                }
            },
            handleTouchStart(e) {
                if (this.$refs.notifList.scrollTop === 0) {
                    this.touchStartY = e.touches[0].clientY;
                    this.isPulling = true;
                }
            },
            handleTouchMove(e) {
                if (!this.isPulling) return;
                let currentY = e.touches[0].clientY;
                if (currentY > this.touchStartY && this.$refs.notifList.scrollTop === 0) {
                    this.pullDistance = currentY - this.touchStartY;
                    if (e.cancelable) e.preventDefault();
                } else { this.pullDistance = 0; }
            },
            handleTouchEnd() {
                if (!this.isPulling) return;
                if (this.pullDistance > 65) {
                    this.isRefreshing = true;
                    setTimeout(() => window.location.reload(), 400);
                } else { this.pullDistance = 0; }
                this.isPulling = false;
            }
        }" @open-notif-panel.window="open = true; view = 'list'"
            @increase-badge.window="unreadCount++" @decrease-badge.window="if(unreadCount > 0) unreadCount--"
            @clear-badge.window="unreadCount = 0" x-cloak>
            <div x-show="open" x-transition.opacity class="fixed inset-0 bg-black bg-opacity-50 z-[60]"
                @click="open = false"></div>
            <div class="fixed top-0 right-0 h-full w-full sm:w-96 bg-white shadow-2xl z-[70] transform transition-transform duration-300 flex flex-col"
                :class="open ? 'translate-x-0' : 'translate-x-full'">
                <div x-show="view === 'list'"
                    class="p-4 border-b border-gray-200 flex justify-between items-center bg-white shadow-sm z-20 relative">
                    <h3 class="font-bold text-lg text-gray-800 flex items-center gap-2">Notifikasi
                        <template x-if="unreadCount > 0"><span
                                class="bg-red-500 text-white text-[10px] font-black px-2 py-0.5 rounded-full"
                                x-text="unreadCount"></span></template>
                    </h3>
                    <button @click="open = false"
                        class="text-gray-400 hover:text-red-500 text-3xl leading-none focus:outline-none">&times;</button>
                </div>
                <div x-show="view === 'detail'"
                    class="p-4 border-b border-gray-200 flex items-center bg-white shadow-sm z-20">
                    <button @click="view = 'list'"
                        class="text-blue-600 font-bold text-sm flex items-center hover:text-blue-800 transition focus:outline-none py-1">
                        <i class="bi bi-arrow-left mr-2"></i> Kembali
                    </button>
                </div>
                <div x-show="view === 'list'" x-ref="notifList"
                    class="flex-1 overflow-y-auto flex flex-col bg-gray-50 notif-scroll" @touchstart="handleTouchStart"
                    @touchmove="handleTouchMove" @touchend="handleTouchEnd">
                    <div x-show="pullDistance > 0"
                        class="flex justify-center items-center bg-gray-100 overflow-hidden text-gray-400 transition-all"
                        :style="`height: ${Math.min(pullDistance, 80)}px`">
                        <template x-if="!isRefreshing && pullDistance <= 65"><span
                                class="text-xs font-bold flex items-center"><i
                                    class="bi bi-arrow-down-circle mr-2 text-lg"></i> Tarik untuk muat
                                ulang</span></template>
                        <template x-if="!isRefreshing && pullDistance > 65"><span
                                class="text-xs font-bold text-blue-600 flex items-center"><i
                                    class="bi bi-arrow-up-circle mr-2 text-lg"></i> Lepas untuk muat ulang</span></template>
                        <template x-if="isRefreshing"><span class="text-xs font-bold text-blue-600 flex items-center"><i
                                    class="bi bi-arrow-repeat animate-spin mr-2 text-lg"></i>
                                Memperbarui...</span></template>
                    </div>
                    <div class="flex border-b border-gray-200 text-xs flex-shrink-0 bg-white sticky top-0 z-10 shadow-sm">
                        <button @click="bacaSemua()"
                            class="flex-1 flex justify-center items-center py-3 px-2 text-blue-600 hover:bg-blue-50 font-bold border-r border-gray-200 transition focus:outline-none"><i
                                class="bi bi-check2-all text-lg mr-1"></i> Baca Semua</button>
                        <button @click="hapusTerbaca()"
                            class="flex-1 flex justify-center items-center py-3 px-2 text-red-600 hover:bg-red-50 font-bold transition focus:outline-none"><i
                                class="bi bi-trash text-base mr-1"></i> Hapus Terbaca</button>
                    </div>

                    <div id="notif-list-container" class="bg-white flex-1">
                        @forelse(auth()->user()->notifications as $notif)
                            <a href="#" @click.prevent="openDetail($el)"
                                class="block p-4 border-b border-gray-100 hover:bg-gray-50 transition {{ $notif->read_at ? 'opacity-70 bg-white read-item' : 'bg-blue-50/40 unread-item' }}"
                                data-id="{{ $notif->id }}" data-title="{{ $notif->data['status'] ?? 'Info Sistem' }}"
                                data-message="{{ $notif->data['pesan'] }}"
                                data-time="{{ $notif->created_at->diffForHumans() }}">
                                <div class="font-bold text-sm {{ $notif->read_at ? 'text-gray-700' : 'text-blue-700' }}">
                                    {{ $notif->data['status'] ?? 'Info Sistem' }}</div>
                                <div class="text-xs text-gray-600 mt-1 leading-snug line-clamp-2">
                                    {{ $notif->data['pesan'] }}</div>
                                <div class="text-[10px] text-gray-400 mt-2 flex justify-between items-center">
                                    <span>{{ $notif->created_at->diffForHumans() }}</span>
                                    @if (!$notif->read_at)
                                        <span class="w-2 h-2 bg-red-500 rounded-full unread-dot shadow-sm"></span>
                                    @endif
                                </div>
                            </a>
                        @empty
                            <div class="p-8 flex flex-col items-center justify-center text-gray-400 mt-10">
                                <i class="bi bi-bell-slash text-5xl mb-3 opacity-30"></i>
                                <p class="text-sm font-medium">Belum ada notifikasi.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
                <div x-show="view === 'detail'" class="flex-1 bg-white overflow-y-auto p-6">
                    <h4 class="font-black text-xl text-gray-800" x-text="activeTitle"></h4>
                    <p class="text-[11px] font-bold text-blue-500 mt-1 mb-6" x-text="activeTime"></p>
                    <div class="bg-gray-50 border border-gray-200 p-4 rounded-lg text-gray-700 text-sm leading-relaxed mb-8"
                        x-text="activeMessage"></div>
                </div>
            </div>
        </div>
    @endauth
    {{-- GLOBAL CONFIRM DIALOG --}}
    <div x-data="{
        open: false,
        title: 'Konfirmasi',
        message: '',
        confirmText: 'Ya, Lanjutkan',
        isDanger: false,
        _cb: null,
    }"
        @open-confirm.window="
        title       = $event.detail.title       ?? 'Konfirmasi';
        message     = $event.detail.message     ?? 'Apakah Anda yakin?';
        confirmText = $event.detail.confirmText ?? 'Ya, Lanjutkan';
        isDanger    = $event.detail.isDanger    ?? false;
        _cb         = $event.detail.callback    ?? null;
        open        = true;
    "
        x-show="open" x-cloak class="fixed inset-0 z-[90] flex items-center justify-center px-4" style="display:none;">

        <div class="absolute inset-0 bg-gray-900/60" @click="open = false"></div>

        <div class="relative bg-white rounded-xl shadow-2xl max-w-sm w-full p-6 text-center"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">

            <div class="mx-auto flex items-center justify-center w-12 h-12 rounded-full mb-4"
                :class="isDanger ? 'bg-red-100' : 'bg-blue-100'">
                <i class="text-xl"
                    :class="isDanger ? 'bi bi-exclamation-triangle-fill text-red-500' :
                        'bi bi-question-circle-fill text-blue-500'"></i>
            </div>

            <h3 class="text-base font-bold text-gray-900 mb-2" x-text="title"></h3>
            <p class="text-sm text-gray-600 mb-6 leading-relaxed" x-text="message"></p>

            <div class="flex gap-3">
                <button @click="open = false"
                    class="flex-1 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 font-bold py-2.5 rounded-lg text-sm transition">
                    Batal
                </button>
                <button @click="if (_cb) _cb(); open = false;"
                    class="flex-1 font-bold py-2.5 rounded-lg text-sm text-white transition"
                    :class="isDanger ? 'bg-red-600 hover:bg-red-700' : 'bg-blue-600 hover:bg-blue-700'"
                    x-text="confirmText">
                </button>
            </div>
        </div>
    </div>

    <div id="toast-container" class="fixed bottom-5 right-5 z-[80] flex flex-col gap-3"></div>
    <audio id="notif-sound" src="{{ asset('sounds/notif.mp3') }}" preload="auto"></audio>

    <script type="module">
        window.showCompactToast = function(title, message, type = 'info') {
            const isError = type === 'error' || title === 'Gagal';
            const iconClass = isError ? 'bi-exclamation-circle-fill' : 'bi-check-circle-fill';
            const iconBg = isError ? 'bg-red-100' : 'bg-emerald-100';
            const iconColor = isError ? 'text-red-600' : 'text-emerald-600';

            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className =
                'bg-white border border-gray-200 shadow-2xl rounded-lg p-3 w-[90vw] sm:w-80 max-w-sm transform transition-all duration-500 translate-x-[120%] flex items-start gap-3 relative';
            toast.innerHTML = `
        <div class='flex-shrink-0 mt-1 ${iconColor} ${iconBg} p-2 rounded-full leading-none'>
            <i class='bi ${iconClass} text-lg'></i>
        </div>
        <div class='flex-1 pr-4'>
            <p class='font-bold text-sm text-gray-800'>${title}</p>
            <p class='text-xs text-gray-600 mt-1 leading-tight line-clamp-2'>${message}</p>
        </div>
        <button onclick="this.closest('div[class*=translate]').remove()"
            class='absolute top-2 right-2 text-gray-300 hover:text-gray-500 text-lg leading-none'>&times;</button>
    `;
            container.appendChild(toast);

            const audio = document.getElementById('notif-sound');
            if (audio && !isError) {
                audio.currentTime = 0;
                audio.play().catch(() => {});
            }

            setTimeout(() => toast.classList.remove('translate-x-[120%]'), 50);
            setTimeout(() => {
                toast.classList.add('translate-x-[120%]');
                setTimeout(() => toast.remove(), 500);
            }, 5000);
        };

        window.customConfirm = function(opts, callback) {
            const detail = typeof opts === 'string' ?
                {
                    message: opts,
                    callback
                } :
                {
                    ...opts,
                    callback
                };
            window.dispatchEvent(new CustomEvent('open-confirm', {
                detail
            }));
        };

        document.addEventListener('DOMContentLoaded', function() {
            const userId = {{ auth()->check() ? auth()->id() : 'null' }};
            if (userId !== null && window.Echo) {
                window.Echo.private('App.Models.User.' + userId).notification((notification) => {

                    const title = notification.status || (notification.data && notification.data.status) ||
                        'Notifikasi Baru';
                    const message = notification.pesan || (notification.data && notification.data.pesan) ||
                        'Silakan cek pembaruan terbaru.';
                    window.showCompactToast(title, message);

                    // Trigger Alpine.js untuk menambah angka merah di Lonceng
                    window.dispatchEvent(new CustomEvent('increase-badge'));

                    const autoUpdatePaths = ['/dashboard', '/admin/validasi', '/admin/finalisasi',
                        '/admin/riwayat', '/spsi/alokasi', '/spsi/monitoring', '/keuangan/rab',
                        '/keuangan/monitoring'
                    ];

                    // Ambil konten HTML baru tanpa me-refresh browser
                    if (autoUpdatePaths.includes(window.location.pathname) || document.getElementById(
                            'notif-list-container')) {
                        fetch(window.location.href).then(response => response.text()).then(html => {
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(html, 'text/html');

                            // Update Tabel Dashboard secara halus
                            const newMainContent = doc.querySelector('main');
                            if (newMainContent && document.querySelector('main')) {
                                document.querySelector('main').innerHTML = newMainContent.innerHTML;
                            }

                            // Update Panel Daftar Notifikasi secara halus (agar notif baru langsung terlihat)
                            const newNotifList = doc.getElementById('notif-list-container');
                            if (newNotifList && document.getElementById('notif-list-container')) {
                                document.getElementById('notif-list-container').innerHTML =
                                    newNotifList.innerHTML;
                            }

                            // Update Sidebar secara halus (memperbarui angka/badge notifikasi realtime)
                            const newSidebarNav = doc.querySelector('#sidebar-main nav');
                            const currentSidebarNav = document.querySelector('#sidebar-main nav');

                            if (newSidebarNav && currentSidebarNav) {
                                currentSidebarNav.innerHTML = newSidebarNav.innerHTML;
                            }
                        });
                    }
                });
            }
        });
    </script>
</body>
@if (session('success') || session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                window.showCompactToast('Berhasil', @json(session('success')));
            @endif
            @if (session('error'))
                window.showCompactToast('Gagal', @json(session('error')));
            @endif
        });
    </script>
@endif

</html>

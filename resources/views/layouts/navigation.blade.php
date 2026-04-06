<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 shadow-sm sticky top-0 z-30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            {{-- LOGO & MENU DESKTOP --}}
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-purple-600" />
                    </a>
                </div>

                <div class="hidden space-x-1 sm:-my-px sm:ms-8 sm:flex items-center">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        Dashboard
                    </x-nav-link>

                    @auth
                        @php $role = Auth::user()->role; @endphp

                        {{-- SUPER ADMIN --}}
                        @if($role === 'super_admin')
                            <x-dropdown align="left" width="52">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-3 py-2 border-b-2 {{ request()->routeIs('superadmin.*') ? 'border-purple-400 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 transition focus:outline-none">
                                        Master Data
                                        <svg class="ms-1 fill-current h-4 w-4" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <div class="px-3 py-2 text-xs font-bold text-gray-400 uppercase tracking-wider">Armada</div>
                                    <x-dropdown-link :href="route('superadmin.kendaraan.index')">🚗 Kendaraan</x-dropdown-link>
                                    <x-dropdown-link :href="route('superadmin.pengemudi.index')">👤 Pengemudi</x-dropdown-link>
                                    <div class="border-t border-gray-100 my-1"></div>
                                    <div class="px-3 py-2 text-xs font-bold text-gray-400 uppercase tracking-wider">Sistem</div>
                                    <x-dropdown-link :href="route('superadmin.users.index')">👥 Manajemen Pengguna</x-dropdown-link>
                                </x-slot>
                            </x-dropdown>

                        {{-- KEPALA ADMIN --}}
                        @elseif($role === 'kepala_admin')
                            <x-dropdown align="left" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-3 py-2 border-b-2 {{ request()->routeIs('admin.*') ? 'border-purple-400 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 transition focus:outline-none">
                                        Tugas Administrasi
                                        <svg class="ms-1 fill-current h-4 w-4" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link :href="route('admin.validasi')">✅ Validasi Masuk</x-dropdown-link>
                                    <x-dropdown-link :href="route('admin.finalisasi')">📝 Finalisasi</x-dropdown-link>
                                    <div class="border-t border-gray-100 my-1"></div>
                                    <x-dropdown-link :href="route('admin.riwayat')">🗂️ Arsip Riwayat</x-dropdown-link>
                                </x-slot>
                            </x-dropdown>

                        {{-- SPSI --}}
                        @elseif($role === 'spsi')
                            <x-dropdown align="left" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-3 py-2 border-b-2 {{ request()->routeIs('spsi.*') ? 'border-purple-400 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 transition focus:outline-none">
                                        Kelola Armada
                                        <svg class="ms-1 fill-current h-4 w-4" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link :href="route('spsi.alokasi')">🚐 Penugasan Armada</x-dropdown-link>
                                    <x-dropdown-link :href="route('spsi.monitoring')">👁️ Pantauan & Riwayat</x-dropdown-link>
                                </x-slot>
                            </x-dropdown>

                        {{-- KEUANGAN --}}
                        @elseif($role === 'keuangan')
                            <x-dropdown align="left" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-3 py-2 border-b-2 {{ request()->routeIs('keuangan.*') ? 'border-purple-400 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 transition focus:outline-none">
                                        Kelola Anggaran
                                        <svg class="ms-1 fill-current h-4 w-4" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link :href="route('keuangan.rab')">💰 Persetujuan RAB</x-dropdown-link>
                                    <x-dropdown-link :href="route('keuangan.monitoring')">📈 Pantauan Anggaran</x-dropdown-link>
                                </x-slot>
                            </x-dropdown>

                        {{-- PENGGUNA --}}
                        @elseif($role === 'pengguna')
                            <a href="{{ route('permohonan.create') }}"
                               class="inline-flex items-center px-3 py-2 border-b-2 {{ request()->routeIs('permohonan.create') ? 'border-purple-400 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 transition">
                                + Buat Pengajuan
                            </a>
                        @endif

                        {{-- LAPORAN (semua role) --}}
                        <x-nav-link :href="route('laporan.index')" :active="request()->routeIs('laporan.*')">
                            📊 Laporan
                        </x-nav-link>

                        {{-- BANTUAN (semua role) --}}
                        <x-nav-link :href="route('bantuan.index')" :active="request()->routeIs('bantuan.*')">
                            ❓ Bantuan
                        </x-nav-link>
                    @endauth
                </div>
            </div>

            {{-- KANAN: Notif + User --}}
            <div class="flex items-center gap-2 sm:gap-3">
                @auth
                    {{-- NOTIFIKASI --}}
                    <div x-data="{ hoverOpen: false, unreadCount: {{ auth()->user()->unreadNotifications->count() }} }"
                         @increase-badge.window="unreadCount++"
                         @decrease-badge.window="if(unreadCount > 0) unreadCount--"
                         @clear-badge.window="unreadCount = 0"
                         @mouseenter="hoverOpen = true" @mouseleave="hoverOpen = false"
                         class="relative pt-1">
                        <button @click="$dispatch('open-notif-panel')" class="p-2 text-gray-500 hover:text-purple-600 hover:bg-purple-50 rounded-full transition relative focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            <template x-if="unreadCount > 0">
                                <span x-text="unreadCount" class="absolute top-0 right-0 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[10px] text-white font-bold border-2 border-white"></span>
                            </template>
                        </button>
                        {{-- Hover Preview Notif --}}
                        <div x-show="hoverOpen" x-transition style="display: none;" class="hidden sm:block absolute top-12 right-0 w-80 bg-white shadow-xl rounded-lg border border-gray-100 overflow-hidden z-50">
                            <div class="px-4 py-2 bg-purple-50 text-xs font-bold text-purple-700 border-b">Notifikasi Terbaru</div>
                            <div class="max-h-64 overflow-y-auto">
                                @forelse(auth()->user()->notifications->take(5) as $notif)
                                    <a href="{{ route('permohonan.show', $notif->data['permohonan_id'] ?? 0) }}"
                                       class="block px-4 py-3 border-b hover:bg-gray-50 transition {{ $notif->read_at ? 'bg-white' : 'bg-purple-50' }}">
                                        <p class="font-semibold text-gray-800 text-xs line-clamp-1">{{ $notif->data['status'] ?? 'Info Sistem' }}</p>
                                        <p class="text-gray-600 text-[11px] mt-1 leading-tight">{{ $notif->data['pesan'] }}</p>
                                    </a>
                                @empty
                                    <div class="px-4 py-4 text-xs text-center text-gray-500">Belum ada notifikasi</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- USER DROPDOWN --}}
                    <div class="hidden sm:flex sm:items-center">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center gap-2 px-3 py-2 border border-transparent text-sm font-medium rounded-md text-gray-600 bg-white hover:bg-gray-50 hover:text-gray-800 transition focus:outline-none">
                                    <div class="w-7 h-7 rounded-full bg-purple-100 text-purple-700 flex items-center justify-center text-xs font-black">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                    <div class="text-left hidden md:block">
                                        <div class="font-semibold text-sm leading-tight">{{ Auth::user()->name }}</div>
                                        <div class="text-xs text-gray-400 leading-tight">{{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }}</div>
                                    </div>
                                    <svg class="fill-current h-4 w-4 text-gray-400" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('profile.edit')">👤 Profil Saya</x-dropdown-link>
                                <x-dropdown-link :href="route('laporan.index')">📊 Laporan</x-dropdown-link>
                                <x-dropdown-link :href="route('bantuan.index')">❓ Pusat Bantuan</x-dropdown-link>
                                <div class="border-t border-gray-100 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                        🚪 Keluar
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @endauth

                {{-- HAMBURGER --}}
                <div class="-me-2 flex items-center sm:hidden">
                    <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 transition focus:outline-none">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- MOBILE MENU --}}
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-gray-100 bg-gray-50">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Dashboard</x-responsive-nav-link>

            @auth
                @php $role = Auth::user()->role; @endphp

                @if($role === 'super_admin')
                    <div class="px-4 py-2 text-xs font-bold text-gray-400 uppercase">Master Data</div>
                    <x-responsive-nav-link :href="route('superadmin.kendaraan.index')">🚗 Kendaraan</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('superadmin.pengemudi.index')">👤 Pengemudi</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('superadmin.users.index')">👥 Manajemen Pengguna</x-responsive-nav-link>

                @elseif($role === 'kepala_admin')
                    <div class="px-4 py-2 text-xs font-bold text-gray-400 uppercase">Administrasi</div>
                    <x-responsive-nav-link :href="route('admin.validasi')">✅ Validasi Masuk</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.finalisasi')">📝 Finalisasi</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.riwayat')">🗂️ Arsip Riwayat</x-responsive-nav-link>

                @elseif($role === 'spsi')
                    <div class="px-4 py-2 text-xs font-bold text-gray-400 uppercase">Armada</div>
                    <x-responsive-nav-link :href="route('spsi.alokasi')">🚐 Penugasan Armada</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('spsi.monitoring')">👁️ Pantauan & Riwayat</x-responsive-nav-link>

                @elseif($role === 'keuangan')
                    <div class="px-4 py-2 text-xs font-bold text-gray-400 uppercase">Keuangan</div>
                    <x-responsive-nav-link :href="route('keuangan.rab')">💰 Persetujuan RAB</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('keuangan.monitoring')">📈 Pantauan Anggaran</x-responsive-nav-link>

                @elseif($role === 'pengguna')
                    <x-responsive-nav-link :href="route('permohonan.create')">+ Buat Pengajuan</x-responsive-nav-link>
                @endif

                <div class="px-4 py-2 text-xs font-bold text-gray-400 uppercase">Umum</div>
                <x-responsive-nav-link :href="route('laporan.index')">📊 Laporan</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('bantuan.index')">❓ Pusat Bantuan</x-responsive-nav-link>
            @endauth
        </div>

        @auth
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4 flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-purple-100 text-purple-700 flex items-center justify-center text-sm font-black flex-shrink-0">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div>
                    <div class="font-semibold text-sm text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-gray-500">{{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }}</div>
                </div>
            </div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">👤 Profil Saya</x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">🚪 Keluar</x-responsive-nav-link>
                </form>
            </div>
        </div>
        @endauth
    </div>
</nav>
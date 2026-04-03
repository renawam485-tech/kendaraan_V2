<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 shadow-sm sticky top-0 z-30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-purple-600" />
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @auth
                        @if(Auth::user()->role === 'kepala_admin')
                            <div class="hidden sm:flex sm:items-center sm:ms-2">
                                <x-dropdown align="right" width="48">
                                    <x-slot name="trigger">
                                        <button class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('admin.*') ? 'border-purple-400 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 transition duration-150 ease-in-out focus:outline-none">
                                            <div>Tugas Administrasi</div>
                                            <div class="ms-1">
                                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                            </div>
                                        </button>
                                    </x-slot>
                                    <x-slot name="content">
                                        <x-dropdown-link :href="route('admin.validasi')">Validasi Masuk</x-dropdown-link>
                                        <x-dropdown-link :href="route('admin.finalisasi')">Finalisasi</x-dropdown-link>
                                        <div class="border-t border-gray-100"></div>
                                        <x-dropdown-link :href="route('admin.riwayat')">Arsip Riwayat</x-dropdown-link>
                                    </x-slot>
                                </x-dropdown>
                            </div>

                        @elseif(Auth::user()->role === 'spsi')
                            <div class="hidden sm:flex sm:items-center sm:ms-2">
                                <x-dropdown align="right" width="48">
                                    <x-slot name="trigger">
                                        <button class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('spsi.*') ? 'border-purple-400 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 transition duration-150 ease-in-out focus:outline-none">
                                            <div>Kelola Armada</div>
                                            <div class="ms-1">
                                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                            </div>
                                        </button>
                                    </x-slot>
                                    <x-slot name="content">
                                        <x-dropdown-link :href="route('spsi.alokasi')">Penugasan Armada</x-dropdown-link>
                                        <x-dropdown-link :href="route('spsi.monitoring')">Pantauan & Riwayat</x-dropdown-link>
                                    </x-slot>
                                </x-dropdown>
                            </div>

                        @elseif(Auth::user()->role === 'keuangan')
                            <div class="hidden sm:flex sm:items-center sm:ms-2">
                                <x-dropdown align="right" width="48">
                                    <x-slot name="trigger">
                                        <button class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('keuangan.*') ? 'border-purple-400 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 transition duration-150 ease-in-out focus:outline-none">
                                            <div>Kelola Anggaran</div>
                                            <div class="ms-1">
                                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                            </div>
                                        </button>
                                    </x-slot>
                                    <x-slot name="content">
                                        <x-dropdown-link :href="route('keuangan.rab')">Persetujuan RAB</x-dropdown-link>
                                        <x-dropdown-link :href="route('keuangan.monitoring')">Pantauan Anggaran</x-dropdown-link>
                                    </x-slot>
                                </x-dropdown>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>

            <div class="flex items-center gap-2 sm:gap-4">
                
                @auth
                <div x-data="{ hoverOpen: false, unreadCount: {{ auth()->user()->unreadNotifications->count() }} }" 
                     @increase-badge.window="unreadCount++"
                     @decrease-badge.window="if(unreadCount > 0) unreadCount--"
                     @clear-badge.window="unreadCount = 0"
                     @mouseenter="hoverOpen = true" @mouseleave="hoverOpen = false"
                     class="relative pt-1">

                    <button @click="$dispatch('open-notif-panel')" class="p-2 text-gray-500 hover:text-purple-600 hover:bg-purple-50 rounded-full transition relative focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <template x-if="unreadCount > 0">
                            <span x-text="unreadCount" class="absolute top-0 right-0 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[10px] text-white font-bold border-2 border-white"></span>
                        </template>
                    </button>

                    <div x-show="hoverOpen" x-transition style="display: none;" class="hidden sm:block absolute top-12 right-0 w-80 bg-white shadow-xl rounded-lg border border-gray-100 overflow-hidden z-50">
                        <div class="px-4 py-2 bg-purple-50 text-xs font-bold text-purple-700 border-b">Notifikasi Terbaru</div>
                        <div class="max-h-64 overflow-y-auto">
                            @forelse(auth()->user()->notifications->take(5) as $notif)
                                @php
                                    $targetUrl = route('dashboard');
                                    if(isset($notif->data['permohonan_id'])) {
                                        // Semua notifikasi sekarang diarahkan dengan damai ke halaman Detail
                                        $targetUrl = route('permohonan.show', $notif->data['permohonan_id']);
                                    }
                                @endphp
                                <a href="{{ $targetUrl }}" class="block px-4 py-3 border-b hover:bg-gray-50 transition {{ $notif->read_at ? 'bg-white' : 'bg-purple-50' }}">
                                    <p class="font-semibold text-gray-800 text-xs line-clamp-1">{{ $notif->data['status'] ?? 'Info Sistem' }}</p>
                                    <p class="text-gray-600 text-[11px] mt-1 leading-tight">{{ $notif->data['pesan'] }}</p>
                                </a>
                            @empty
                                <div class="px-4 py-4 text-xs text-center text-gray-500">Belum ada notifikasi</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="hidden sm:flex sm:items-center">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 transition focus:outline-none">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ms-1"><svg class="fill-current h-4 w-4" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg></div>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">{{ __('Profile') }}</x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Log Out') }}</x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
                @endauth

                <div class="-me-2 flex items-center sm:hidden">
                    <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 transition focus:outline-none">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-gray-100 bg-gray-50">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            @auth
                @if(Auth::user()->role === 'kepala_admin')
                    <div class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Administrasi</div>
                    <x-responsive-nav-link :href="route('admin.validasi')" :active="request()->routeIs('admin.validasi')">Validasi Masuk</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.finalisasi')" :active="request()->routeIs('admin.finalisasi')">Finalisasi</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.riwayat')" :active="request()->routeIs('admin.riwayat')">Arsip Riwayat</x-responsive-nav-link>
                @elseif(Auth::user()->role === 'spsi')
                    <div class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Armada</div>
                    <x-responsive-nav-link :href="route('spsi.alokasi')" :active="request()->routeIs('spsi.alokasi')">Penugasan Armada</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('spsi.monitoring')" :active="request()->routeIs('spsi.monitoring')">Pantauan & Riwayat</x-responsive-nav-link>
                @elseif(Auth::user()->role === 'keuangan')
                    <div class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Keuangan</div>
                    <x-responsive-nav-link :href="route('keuangan.rab')" :active="request()->routeIs('keuangan.rab')">Persetujuan RAB</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('keuangan.monitoring')" :active="request()->routeIs('keuangan.monitoring')">Pantauan Anggaran</x-responsive-nav-link>
                @endif
            @endauth
        </div>

        @auth
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">{{ __('Profile') }}</x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Log Out') }}</x-responsive-nav-link>
                </form>
            </div>
        </div>
        @endauth
    </div>
</nav>
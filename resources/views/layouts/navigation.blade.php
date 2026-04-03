<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 shadow-sm sticky top-0 z-30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            <div class="flex">
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @auth
                        @if (Auth::user()->role === 'kepala_admin')
                            <x-nav-link :href="route('admin.tugas')" :active="request()->routeIs('admin.tugas')">
                                Validasi Permohonan
                            </x-nav-link>
                        @elseif(Auth::user()->role === 'spsi')
                            <x-nav-link :href="route('spsi.tugas')" :active="request()->routeIs('spsi.tugas')">
                                Alokasi Armada
                            </x-nav-link>
                        @elseif(Auth::user()->role === 'keuangan')
                            <x-nav-link :href="route('keuangan.tugas')" :active="request()->routeIs('keuangan.tugas')">
                                Persetujuan RAB
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <div class="flex items-center gap-2 sm:gap-4">

                @auth
                    <div x-data="{ hoverOpen: false, unreadCount: {{ auth()->user()->unreadNotifications->count() }} }" @increase-badge.window="unreadCount++"
                        @decrease-badge.window="if(unreadCount > 0) unreadCount--" @clear-badge.window="unreadCount = 0"
                        @mouseenter="hoverOpen = true" @mouseleave="hoverOpen = false"
                        class="relative flex items-center cursor-pointer pt-1">

                        <button @click="$dispatch('open-notif-panel')"
                            class="relative p-2 text-gray-500 hover:text-purple-600 hover:bg-purple-50 rounded-full focus:outline-none transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>

                            <template x-if="unreadCount > 0">
                                <span x-text="unreadCount"
                                    class="absolute top-0 right-0 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[10px] text-white font-bold border-2 border-white"></span>
                            </template>
                        </button>

                        <div x-show="hoverOpen" x-transition style="display: none;"
                            class="hidden sm:block absolute top-12 right-0 w-72 bg-white shadow-xl rounded-lg border border-gray-100 overflow-hidden z-50">
                            <div class="px-4 py-2 bg-purple-50 text-xs font-bold text-purple-700 border-b">3 Notifikasi
                                Terbaru</div>
                            @forelse(auth()->user()->notifications->take(3) as $notif)
                                <div class="px-4 py-3 border-b text-sm {{ $notif->read_at ? 'bg-white' : 'bg-gray-50' }}">
                                    <p class="font-semibold text-gray-800 text-xs">{{ $notif->data['status'] ?? 'Info' }}
                                    </p>
                                    <p class="text-gray-600 text-xs truncate">{{ $notif->data['pesan'] }}</p>
                                </div>
                            @empty
                                <div class="px-4 py-3 text-xs text-gray-500">Belum ada notifikasi</div>
                            @endforelse
                        </div>
                    </div>
                @endauth

                <div class="hidden sm:flex sm:items-center">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 hover:bg-gray-50 focus:outline-none transition">
                                <div>{{ Auth::user()->name ?? 'User' }}</div>
                                <div class="ms-1"><svg class="fill-current h-4 w-4" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg></div>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">{{ __('Profile') }}</x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Log Out') }}</x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>

                <div class="-me-2 flex items-center sm:hidden">
                    <button @click="open = ! open"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

            </div>
        </div>
    </div>

    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden border-t border-gray-200">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            @auth
                @if (Auth::user()->role === 'kepala_admin')
                    <x-responsive-nav-link :href="route('admin.tugas')" :active="request()->routeIs('admin.tugas')">
                        Validasi Permohonan
                    </x-responsive-nav-link>
                @elseif(Auth::user()->role === 'spsi')
                    <x-responsive-nav-link :href="route('spsi.tugas')" :active="request()->routeIs('spsi.tugas')">
                        Alokasi Armada
                    </x-responsive-nav-link>
                @elseif(Auth::user()->role === 'keuangan')
                    <x-responsive-nav-link :href="route('keuangan.tugas')" :active="request()->routeIs('keuangan.tugas')">
                        Persetujuan RAB
                    </x-responsive-nav-link>
                @endif
            @endauth
        </div>
</nav>

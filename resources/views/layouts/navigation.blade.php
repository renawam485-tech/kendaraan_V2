<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}"><x-application-logo class="block h-9 w-auto" /></a>
                </div>
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">{{ __('Dashboard') }}</x-nav-link>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-4">
                @auth
                <div x-data="{ hoverOpen: false, unreadCount: {{ auth()->user()->unreadNotifications->count() }} }" 
                     @increase-badge.window="unreadCount++"
                     @decrease-badge.window="if(unreadCount > 0) unreadCount--"
                     @clear-badge.window="unreadCount = 0"
                     @mouseenter="hoverOpen = true" @mouseleave="hoverOpen = false"
                     class="relative mt-1 mr-4 flex items-center cursor-pointer">

                    <button @click="$dispatch('open-notif-panel')"
                        class="relative text-gray-500 hover:text-purple-600 focus:outline-none transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        
                        <template x-if="unreadCount > 0">
                            <span x-text="unreadCount" class="absolute -top-1 -right-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[10px] text-white font-bold"></span>
                        </template>
                    </button>

                    <div x-show="hoverOpen" x-transition style="display: none;"
                        class="absolute top-8 right-0 w-72 bg-white shadow-lg rounded-md border border-gray-100 overflow-hidden z-50">
                        <div class="px-4 py-2 bg-purple-50 text-xs font-bold text-purple-700 border-b">3 Notifikasi Terbaru</div>
                        @forelse(auth()->user()->notifications->take(3) as $notif)
                            <div class="px-4 py-3 border-b text-sm {{ $notif->read_at ? 'bg-white' : 'bg-gray-50' }}">
                                <p class="font-semibold text-gray-800 text-xs">{{ $notif->data['status'] ?? 'Info' }}</p>
                                <p class="text-gray-600 text-xs truncate">{{ $notif->data['pesan'] }}</p>
                            </div>
                        @empty
                            <div class="px-4 py-3 text-xs text-gray-500">Belum ada notifikasi</div>
                        @endforelse
                    </div>
                </div>
                @endauth

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1"><svg class="fill-current h-4 w-4" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg></div>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">{{ __('Profile') }}</x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">@csrf<x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Log Out') }}</x-dropdown-link></form>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>
</nav>
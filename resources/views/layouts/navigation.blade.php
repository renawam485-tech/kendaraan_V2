@php
    $render = $render ?? 'topbar';
    $user = Auth::user();
    $role = $user->role ?? 'guest';
    $isPengguna = $role === 'pengguna';
@endphp

@if ($render === 'sidebar')
    {{-- ========================================== --}}
    {{-- RENDER 1: SIDEBAR (PUTIH & AKSEN BIRU)     --}}
    {{-- ========================================== --}}

    <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-40 bg-black bg-opacity-50 md:hidden"
        @click="sidebarOpen = false" x-cloak></div>

    <aside id="sidebar-main"
        :class="{
            'translate-x-0': sidebarOpen,
            '-translate-x-full': !sidebarOpen,
            'w-64': !sidebarCollapsed,
            'w-20': sidebarCollapsed
        }"
        class="fixed left-0 top-16 z-40 h-[calc(100vh-64px)] bg-white border-r border-gray-200 text-gray-700 flex flex-col sidebar-transition shadow-lg md:translate-x-0 {{ $isPengguna ? 'md:hidden' : '' }}"
        x-cloak>

        <nav class="flex-1 overflow-y-auto py-4 scrollbar-hide">
            @php
                $cVal = 0;
                $cFin = 0;
                $cAlo = 0;
                $cRab = 0;
                $cVer = 0;
                if ($role === 'kepala_admin') {
                    $cVal = \App\Models\Permohonan::where('status_permohonan', 'Menunggu Validasi Admin')->count();
                    $cFin = \App\Models\Permohonan::where('status_permohonan', 'Menunggu Finalisasi')->count();
                } elseif ($role === 'spsi') {
                    $cAlo = \App\Models\Permohonan::where('status_permohonan', 'Menunggu Proses SPSI')->count();
                    $cSerah = \App\Models\Permohonan::where('status_permohonan', 'Disetujui')->count();
                    $cKonfirmasi = \App\Models\Permohonan::where(
                        'status_permohonan',
                        'Menunggu Konfirmasi Kembali',
                    )->count();
                } elseif ($role === 'keuangan') {
                    $cRab = \App\Models\Permohonan::where('status_permohonan', 'Menunggu Proses Keuangan')->count();
                    $cVer = \App\Models\Permohonan::where(
                        'status_permohonan',
                        'Menunggu Verifikasi Pengembalian',
                    )->count();
                }
            @endphp

            <a href="{{ Auth::user()->role === 'super_admin' ? route('superadmin.dashboard') : route('dashboard') }}"
                title="Dashboard"
                class="group flex items-center px-6 py-3 transition {{ request()->routeIs('dashboard') || request()->routeIs('superadmin.dashboard') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-bold' : 'border-l-4 border-transparent hover:bg-gray-50 hover:text-blue-600 text-gray-600' }}">
                <i class="bi bi-grid-1x2-fill text-lg w-8 text-center"></i>
                <span x-show="!sidebarCollapsed" class="ml-3 whitespace-nowrap">Dashboard</span>
            </a>

            @if ($role === 'pengguna')
                <div x-show="!sidebarCollapsed"
                    class="px-6 mt-6 mb-2 text-[10px] font-black text-gray-400 uppercase tracking-widest">Aksi</div>
                <hr x-show="sidebarCollapsed" class="mx-4 my-4 border-gray-200">
                <a href="{{ route('permohonan.create') }}" title="Buat Pengajuan Baru"
                    class="flex items-center px-6 py-3 transition {{ request()->routeIs('permohonan.create') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-bold' : 'border-l-4 border-transparent hover:bg-gray-50 hover:text-blue-600 text-gray-600' }}">
                    <i class="bi bi-plus-square text-lg w-8 text-center"></i><span x-show="!sidebarCollapsed"
                        class="ml-3 whitespace-nowrap text-sm">Buat Pengajuan</span>
                </a>
            @endif

            @if ($role === 'super_admin')
                <div x-show="!sidebarCollapsed"
                    class="px-6 mt-6 mb-2 text-[10px] font-black text-gray-400 uppercase tracking-widest">Master Data
                </div>
                <hr x-show="sidebarCollapsed" class="mx-4 my-4 border-gray-200">
                <a href="{{ route('superadmin.kendaraan.index') }}" title="Mobil Internal"
                    class="flex items-center px-6 py-3 transition {{ request()->routeIs('superadmin.kendaraan.*') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-bold' : 'border-l-4 border-transparent hover:bg-gray-50 hover:text-blue-600 text-gray-600' }}">
                    <i class="bi bi-car-front text-lg w-8 text-center"></i><span x-show="!sidebarCollapsed"
                        class="ml-3 whitespace-nowrap text-sm">Mobil Internal</span>
                </a>
                <a href="{{ route('superadmin.kendaraan_vendor.index') }}" title="Mobil Vendor"
                    class="flex items-center px-6 py-3 transition {{ request()->routeIs('superadmin.kendaraan_vendor.*') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-bold' : 'border-l-4 border-transparent hover:bg-gray-50 hover:text-blue-600 text-gray-600' }}">
                    <i class="bi bi-buildings text-lg w-8 text-center"></i><span x-show="!sidebarCollapsed"
                        class="ml-3 whitespace-nowrap text-sm">Mobil Vendor</span>
                </a>
                <a href="{{ route('superadmin.pengemudi.index') }}" title="Data Pengemudi"
                    class="flex items-center px-6 py-3 transition {{ request()->routeIs('superadmin.pengemudi.*') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-bold' : 'border-l-4 border-transparent hover:bg-gray-50 hover:text-blue-600 text-gray-600' }}">
                    <i class="bi bi-person-vcard text-lg w-8 text-center"></i><span x-show="!sidebarCollapsed"
                        class="ml-3 whitespace-nowrap text-sm">Pengemudi</span>
                </a>
                <a href="{{ route('superadmin.users.index') }}" title="Data Pengguna"
                    class="flex items-center px-6 py-3 transition {{ request()->routeIs('superadmin.users.*') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-bold' : 'border-l-4 border-transparent hover:bg-gray-50 hover:text-blue-600 text-gray-600' }}">
                    <i class="bi bi-people text-lg w-8 text-center"></i><span x-show="!sidebarCollapsed"
                        class="ml-3 whitespace-nowrap text-sm">Data Pengguna</span>
                </a>
            @elseif($role === 'kepala_admin')
                <div x-show="!sidebarCollapsed"
                    class="px-6 mt-6 mb-2 text-[10px] font-black text-gray-400 uppercase tracking-widest">Tugas Utama
                </div>
                <hr x-show="sidebarCollapsed" class="mx-4 my-4 border-gray-200">
                <a href="{{ route('admin.validasi') }}" title="Validasi Masuk"
                    class="relative flex items-center px-6 py-3 transition {{ request()->routeIs('admin.validasi') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-bold' : 'border-l-4 border-transparent hover:bg-gray-50 hover:text-blue-600 text-gray-600' }}">
                    <i class="bi bi-check-circle text-lg w-8 text-center"></i>
                    <span x-show="!sidebarCollapsed" class="ml-3 whitespace-nowrap flex-1 text-sm">Validasi Masuk</span>
                    @if ($cVal > 0)
                        <span class="bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full"
                            :class="sidebarCollapsed ? 'absolute left-10 top-2' : ''">{{ $cVal }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.finalisasi') }}" title="Finalisasi Surat"
                    class="relative flex items-center px-6 py-3 transition {{ request()->routeIs('admin.finalisasi') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-bold' : 'border-l-4 border-transparent hover:bg-gray-50 hover:text-blue-600 text-gray-600' }}">
                    <i class="bi bi-file-earmark-check text-lg w-8 text-center"></i>
                    <span x-show="!sidebarCollapsed" class="ml-3 whitespace-nowrap flex-1 text-sm">Finalisasi
                        Surat</span>
                    @if ($cFin > 0)
                        <span class="bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full"
                            :class="sidebarCollapsed ? 'absolute left-10 top-2' : ''">{{ $cFin }}</span>
                    @endif
                </a>
            @elseif($role === 'spsi')
                <div x-show="!sidebarCollapsed"
                    class="px-6 mt-6 mb-2 text-[10px] font-black text-gray-400 uppercase tracking-widest">Operasional
                </div>
                <hr x-show="sidebarCollapsed" class="mx-4 my-4 border-gray-200">

                <a href="{{ route('spsi.alokasi') }}" title="Penugasan Armada"
                    class="relative flex items-center px-6 py-3 transition {{ request()->routeIs('spsi.alokasi') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-bold' : 'border-l-4 border-transparent hover:bg-gray-50 hover:text-blue-600 text-gray-600' }}">
                    <i class="bi bi-truck-front text-lg w-8 text-center"></i>
                    <span x-show="!sidebarCollapsed" class="ml-3 whitespace-nowrap flex-1 text-sm">Penugasan
                        Armada</span>
                    @if ($cAlo > 0)
                        <span class="bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full"
                            :class="sidebarCollapsed ? 'absolute left-10 top-2' : ''">{{ $cAlo }}</span>
                    @endif
                </a>

                <a href="{{ route('spsi.serah_terima') }}" title="Serah Terima Kunci"
                    class="relative flex items-center px-6 py-3 transition {{ request()->routeIs('spsi.serah_terima') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-bold' : 'border-l-4 border-transparent hover:bg-gray-50 hover:text-blue-600 text-gray-600' }}">
                    <i class="bi bi-key-fill text-lg w-8 text-center"></i>
                    <span x-show="!sidebarCollapsed" class="ml-3 whitespace-nowrap flex-1 text-sm">Serah Terima
                        Kunci</span>
                    @if ($cSerah + $cKonfirmasi > 0)
                        <span class="bg-amber-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full"
                            :class="sidebarCollapsed ? 'absolute left-10 top-2' : ''">{{ $cSerah + $cKonfirmasi }}</span>
                    @endif
                </a>

                <a href="{{ route('spsi.monitoring') }}" title="Pantauan Armada"
                    class="flex items-center px-6 py-3 transition {{ request()->routeIs('spsi.monitoring') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-bold' : 'border-l-4 border-transparent hover:bg-gray-50 hover:text-blue-600 text-gray-600' }}">
                    <i class="bi bi-binoculars text-lg w-8 text-center"></i>
                    <span x-show="!sidebarCollapsed" class="ml-3 whitespace-nowrap text-sm">Pantauan & Riwayat</span>
                </a>
            @elseif($role === 'keuangan')
                <div x-show="!sidebarCollapsed"
                    class="px-6 mt-6 mb-2 text-[10px] font-black text-gray-400 uppercase tracking-widest">Keuangan
                </div>
                <hr x-show="sidebarCollapsed" class="mx-4 my-4 border-gray-200">
                <a href="{{ route('keuangan.rab') }}" title="Persetujuan RAB"
                    class="relative flex items-center px-6 py-3 transition {{ request()->routeIs('keuangan.rab') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-bold' : 'border-l-4 border-transparent hover:bg-gray-50 hover:text-blue-600 text-gray-600' }}">
                    <i class="bi bi-cash-coin text-lg w-8 text-center"></i>
                    <span x-show="!sidebarCollapsed" class="ml-3 whitespace-nowrap flex-1 text-sm">Persetujuan
                        RAB</span>
                    @if ($cRab > 0)
                        <span class="bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full"
                            :class="sidebarCollapsed ? 'absolute left-10 top-2' : ''">{{ $cRab }}</span>
                    @endif
                </a>
                <a href="{{ route('keuangan.monitoring') }}" title="Verifikasi Refund"
                    class="relative flex items-center px-6 py-3 transition {{ request()->routeIs('keuangan.monitoring') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-bold' : 'border-l-4 border-transparent hover:bg-gray-50 hover:text-blue-600 text-gray-600' }}">
                    <i class="bi bi-arrow-return-left text-lg w-8 text-center"></i>
                    <span x-show="!sidebarCollapsed" class="ml-3 whitespace-nowrap flex-1 text-sm">Verifikasi
                        Refund</span>
                    @if ($cVer > 0)
                        <span class="bg-orange-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full"
                            :class="sidebarCollapsed ? 'absolute left-10 top-2' : ''">{{ $cVer }}</span>
                    @endif
                </a>
            @endif

            <div x-show="!sidebarCollapsed"
                class="px-6 mt-6 mb-2 text-[10px] font-black text-gray-400 uppercase tracking-widest">Akses Umum</div>
            <hr x-show="sidebarCollapsed" class="mx-4 my-4 border-gray-200">
            <a href="{{ route('laporan.index') }}"
                title="{{ $role === 'pengguna' ? 'Riwayat Pengajuan' : 'Laporan Filter' }}"
                class="flex items-center px-6 py-3 transition {{ request()->routeIs('laporan.*') ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-600 font-bold' : 'border-l-4 border-transparent hover:bg-gray-50 hover:text-blue-600 text-gray-600' }}">
                <i class="bi bi-file-earmark-bar-graph text-lg w-8 text-center"></i>
                <span x-show="!sidebarCollapsed" class="ml-3 whitespace-nowrap text-sm">
                    {{ $role === 'pengguna' ? 'Riwayat' : 'Laporan Filter' }}
                </span>
            </a>
        </nav>

        {{-- FOOTER SIDEBAR --}}
        <div class="border-t border-gray-100 py-3 mt-auto">
            <a href="{{ route('bantuan.index') }}" title="Pusat Bantuan"
                class="flex items-center px-6 py-3 text-gray-500 hover:bg-gray-50 hover:text-blue-600 transition">
                <i class="bi bi-question-circle text-xl w-8 text-center"></i>
                <span x-show="!sidebarCollapsed" class="ml-3 font-semibold text-sm">Pusat Bantuan</span>
            </a>
        </div>
    </aside>
@elseif($render === 'topbar')
    {{-- ========================================== --}}
    {{-- RENDER 2: TOP NAVBAR (PUTIH & FULL WIDTH)  --}}
    {{-- ========================================== --}}
    <header
        class="bg-white border-b border-gray-200 shadow-sm fixed top-0 left-0 w-full z-50 h-16 flex items-center justify-between px-4 sm:px-6 lg:px-8">

        <div class="flex items-center gap-3 sm:gap-4">
            <button @click="if(window.innerWidth >= 768) { toggleSidebar() } else { sidebarOpen = !sidebarOpen }"
                class="text-gray-500 hover:text-blue-600 focus:outline-none transition {{ $isPengguna ? 'md:hidden' : '' }}">
                <i class="bi bi-list text-2xl sm:text-3xl"></i>
            </button>

            <a href="{{ route('dashboard') }}"
                class="text-xl font-black text-blue-700 tracking-widest flex items-center gap-2 {{ $isPengguna ? 'ml-0' : 'ml-2' }}">
                DRIVORA
            </a>

            @if ($isPengguna)
                <nav class="hidden md:flex items-center gap-6 ml-8 text-sm font-bold text-gray-500">
                    <a href="{{ route('dashboard') }}"
                        class="hover:text-blue-600 transition {{ request()->routeIs('dashboard') ? 'text-blue-600' : '' }}">Dashboard</a>
                    <a href="{{ route('permohonan.create') }}"
                        class="hover:text-blue-600 transition {{ request()->routeIs('permohonan.create') ? 'text-blue-600' : '' }}">+
                        Buat Pengajuan</a>
                    <a href="{{ route('laporan.index') }}"
                        class="hover:text-blue-600 transition {{ request()->routeIs('laporan.*') ? 'text-blue-600' : '' }}">Riwayat</a>
                    <a href="{{ route('bantuan.index') }}"
                        class="hover:text-blue-600 transition {{ request()->routeIs('bantuan.*') ? 'text-blue-600' : '' }}">Pusat
                        Bantuan</a>
                </nav>
            @endif
        </div>

        <div class="flex items-center gap-3 sm:gap-5">

            {{-- KEMBALIKAN LOGIKA ALPINE.JS UNTUK NOTIFIKASI REAL-TIME --}}
            <div x-data="{ unreadCount: {{ auth()->check() ? auth()->user()->unreadNotifications->count() : 0 }} }" @increase-badge.window="unreadCount++"
                @decrease-badge.window="if(unreadCount > 0) unreadCount--" @clear-badge.window="unreadCount = 0">

                <button @click="$dispatch('open-notif-panel')"
                    class="p-2 text-gray-500 hover:text-blue-700 bg-gray-50 hover:bg-blue-50 rounded-full transition relative border border-gray-200 focus:outline-none">
                    <i class="bi bi-bell text-lg"></i>
                    <template x-if="unreadCount > 0">
                        <span x-text="unreadCount"
                            class="absolute -top-1 -right-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[10px] text-white font-bold shadow-sm"></span>
                    </template>
                </button>
            </div>

            <div class="border-l pl-3 sm:pl-5 border-gray-200">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-gray-50 hover:bg-blue-50 text-gray-700 hover:text-blue-700 border border-gray-200 transition">
                            <span class="font-bold text-sm hidden sm:inline">{{ explode(' ', $user->name)[0] }}</span>
                            <i class="bi bi-person-circle text-lg"></i>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <div class="px-4 py-2 border-b border-gray-100 block sm:hidden">
                            <p class="text-sm font-bold text-gray-800">{{ $user->name }}</p>
                            <p class="text-xs text-gray-500">{{ ucfirst(str_replace('_', ' ', $role)) }}</p>
                        </div>
                        <x-dropdown-link :href="route('profile.edit')" class="hover:text-blue-600"><i
                                class="bi bi-person mr-2"></i>Profil Saya</x-dropdown-link>
                        <div class="border-t border-gray-100 my-1"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link href="{{ route('logout') }}"
                                onclick="event.preventDefault(); this.closest('form').submit();"
                                class="text-red-600 font-bold hover:text-red-800 hover:bg-red-50">
                                <i class="bi bi-box-arrow-right mr-2"></i>Keluar Aplikasi
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </header>
@endif

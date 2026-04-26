<footer class="w-full">
    <div class="bg-gray-900 text-gray-300 px-6 py-12 rounded-t-3xl">

        <div class="max-w-7xl mx-auto grid md:grid-cols-3 gap-10">

            <!-- BRAND -->
            <div>
                <h2 class="text-xl font-bold text-white mb-3">Drivora</h2>
                <p class="text-sm text-gray-400">
                    Sistem peminjaman kendaraan berbasis web untuk meningkatkan efisiensi operasional.
                </p>

                <div class="mt-4 text-xs text-green-400 flex items-center gap-2">
                    <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                    System Online
                </div>
            </div>

            <!-- ROLE BASED MENU -->
            <div>
                <h3 class="text-white font-semibold mb-4 text-sm uppercase">Menu</h3>

                <ul class="space-y-2 text-sm">

                    @guest
                        <li><a href="/" class="footer-link">Home</a></li>
                        <li><a href="#fitur" class="footer-link">Fitur</a></li>
                        <li><a href="{{ route('login') }}" class="footer-link">Login</a></li>
                    @endguest

                    @auth

                        @if(Auth::user()->role === 'superadmin')
                            <li><a href="{{ route('dashboard') }}" class="footer-link">Dashboard</a></li>
                            <li><a href="{{ route('superadmin.users.index') }}" class="footer-link">Manajemen User</a></li>
                            <li><a href="{{ route('laporan.index') }}" class="footer-link">Laporan</a></li>

                        @elseif(Auth::user()->role === 'kepala_admin')
                            <li><a href="{{ route('dashboard') }}" class="footer-link">Dashboard</a></li>
                            <li><a href="{{ route('laporan.index') }}" class="footer-link">Validasi Pengajuan</a></li>

                        @elseif(Auth::user()->role === 'spsi')
                            <li><a href="{{ route('dashboard') }}" class="footer-link">Dashboard</a></li>
                            <li><a href="{{ route('superadmin.kendaraan.index') }}" class="footer-link">Kelola Kendaraan</a></li>
                            <li><a href="{{ route('superadmin.pengemudi.index') }}" class="footer-link">Kelola Pengemudi</a></li>

                        @elseif(Auth::user()->role === 'keuangan')
                            <li><a href="{{ route('dashboard') }}" class="footer-link">Dashboard</a></li>
                            <li><a href="{{ route('laporan.index') }}" class="footer-link">Verifikasi RAB</a></li>

                        @endif

                    @endauth

                </ul>
            </div>

            <!-- INFO USER -->
            <div>
                <h3 class="text-white font-semibold mb-4 text-sm uppercase">Informasi</h3>

                <div class="text-sm text-gray-400 space-y-2">

                    <div class="flex justify-between">
                        <span>Versi</span>
                        <span class="text-white font-semibold">v1.0</span>
                    </div>

                    @auth
                    <div class="flex justify-between">
                        <span>Login</span>
                        <span class="text-white font-semibold">{{ Auth::user()->name }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span>Role</span>
                        <span class="text-blue-400 font-semibold capitalize">
                            {{ Auth::user()->role }}
                        </span>
                    </div>
                    @endauth

                    <div class="flex justify-between">
                        <span>Tahun</span>
                        <span class="text-white font-semibold">{{ date('Y') }}</span>
                    </div>

                </div>
            </div>

        </div>

        <!-- BOTTOM -->
        <div class="border-t border-gray-700 mt-10 pt-5 text-center text-xs text-gray-500">
            © {{ date('Y') }} Drivora — Sistem Peminjaman Kendaraan
        </div>

    </div>
</footer>
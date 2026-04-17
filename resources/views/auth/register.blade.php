<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center px-6 bg-[#0f172a] relative">

        <!-- Background Blur -->
        <div class="absolute inset-0 -z-10">
            <div class="absolute w-[500px] h-[500px] bg-blue-500 opacity-20 blur-3xl rounded-full top-[-100px] left-[-100px]"></div>
            <div class="absolute w-[400px] h-[400px] bg-purple-500 opacity-20 blur-3xl rounded-full bottom-[-100px] right-[-100px]"></div>
        </div>

        <!-- Container -->
        <div class="w-full max-w-7xl grid grid-cols-1 md:grid-cols-2 bg-white/10 backdrop-blur-2xl rounded-3xl shadow-2xl border border-white/10 overflow-hidden">

            <!-- LEFT SIDE -->
            <div class="hidden md:flex flex-col justify-center p-16 text-white bg-gradient-to-br from-purple-600 to-blue-600">
                <h1 class="text-4xl font-bold mb-4 leading-tight">
                    Bergabung dengan Sistem
                </h1>

                <p class="text-gray-200 mb-10">
                    Daftarkan akun untuk mulai menggunakan sistem peminjaman kendaraan secara digital.
                </p>

                <div class="space-y-5 text-sm">
                    <div class="flex items-center gap-3">
                        <i class="bi bi-person-plus text-green-300 text-lg"></i>
                        <span>Registrasi cepat & mudah</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <i class="bi bi-shield-check text-green-300 text-lg"></i>
                        <span>Keamanan data terjamin</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <i class="bi bi-clock-history text-green-300 text-lg"></i>
                        <span>Riwayat peminjaman tersimpan</span>
                    </div>
                </div>
            </div>

            <!-- RIGHT SIDE -->
            <div class="bg-white p-10 md:p-14">

                <!-- Title -->
                <div class="mb-8">
                    <h2 class="text-3xl font-bold text-gray-800">Create Account</h2>
                    <p class="text-gray-500 text-sm">Silakan isi data untuk mendaftar</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <!-- Name -->
                    <div class="relative">
                        <i class="bi bi-person absolute left-4 top-3 text-gray-400"></i>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            placeholder="Nama Lengkap"
                            class="pl-11 w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 py-2">
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Email -->
                    <div class="relative">
                        <i class="bi bi-envelope absolute left-4 top-3 text-gray-400"></i>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            placeholder="Email"
                            class="pl-11 w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 py-2">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="relative">
                        <i class="bi bi-lock absolute left-4 top-3 text-gray-400"></i>
                        <input id="password" type="password" name="password" required
                            placeholder="Password"
                            class="pl-11 w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 py-2">

                        <button type="button" id="togglePassword"
                            class="absolute right-4 top-3 text-gray-400">
                            <i class="bi bi-eye"></i>
                        </button>

                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Confirm Password -->
                    <div class="relative">
                        <i class="bi bi-lock-fill absolute left-4 top-3 text-gray-400"></i>
                        <input id="confirmPassword" type="password" name="password_confirmation" required
                            placeholder="Konfirmasi Password"
                            class="pl-11 w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 py-2">

                        <button type="button" id="toggleConfirmPassword"
                            class="absolute right-4 top-3 text-gray-400">
                            <i class="bi bi-eye"></i>
                        </button>

                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <!-- Button -->
                    <button type="submit"
                        class="w-full bg-gradient-to-r from-purple-600 to-blue-600 text-white py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
                        Daftar Sekarang
                    </button>

                    <!-- Login -->
                    <p class="text-sm text-gray-500">
                        Sudah punya akun?
                        <a href="{{ route('login') }}"
                            class="text-purple-600 font-medium hover:underline">
                            Login
                        </a>
                    </p>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Toggle Password Script -->
    <script>
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');

        togglePassword.addEventListener('click', () => {
            const type = password.type === 'password' ? 'text' : 'password';
            password.type = type;
            togglePassword.innerHTML = type === 'text'
                ? '<i class="bi bi-eye-slash"></i>'
                : '<i class="bi bi-eye"></i>';
        });

        const toggleConfirm = document.getElementById('toggleConfirmPassword');
        const confirmPassword = document.getElementById('confirmPassword');

        toggleConfirm.addEventListener('click', () => {
            const type = confirmPassword.type === 'password' ? 'text' : 'password';
            confirmPassword.type = type;
            toggleConfirm.innerHTML = type === 'text'
                ? '<i class="bi bi-eye-slash"></i>'
                : '<i class="bi bi-eye"></i>';
        });
    </script>
</x-guest-layout>
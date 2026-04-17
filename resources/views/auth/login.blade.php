<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center px-6 bg-gradient-to-br from-slate-900 via-blue-900 to-purple-900">

        <!-- Container -->
        <div class="w-full max-w-7xl grid grid-cols-2 bg-white/10 backdrop-blur-2xl rounded-3xl shadow-2xl border border-white/10 overflow-hidden">

        <div class="absolute inset-0 -z-10">
            <div class="absolute w-[500px] h-[500px] bg-blue-500 opacity-20 blur-3xl rounded-full top-[-100px] left-[-100px]"></div>
            <div class="absolute w-[400px] h-[400px] bg-purple-500 opacity-20 blur-3xl rounded-full bottom-[-100px] right-[-100px]"></div>
        </div>

            <!-- LEFT SIDE -->
            <div class="flex flex-col justify-center p-16 text-white bg-gradient-to-br from-blue-600 to-purple-700">
                <h1 class="text-4xl font-bold mb-4 leading-tight">
                    Sistem Peminjaman Kendaraan
                </h1>

                <p class="text-gray-200 mb-10">
                    Kelola peminjaman kendaraan dengan cepat, transparan, dan modern.
                </p>

                <div class="space-y-5 text-sm">
                    <div class="flex items-center gap-3">
                        <i class="bi bi-speedometer2 text-green-300 text-lg"></i>
                        <span>Proses pengajuan cepat</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <i class="bi bi-check2-circle text-green-300 text-lg"></i>
                        <span>Persetujuan terintegrasi</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <i class="bi bi-graph-up text-green-300 text-lg"></i>
                        <span>Monitoring real-time</span>
                    </div>
                </div>
            </div>

            <!-- RIGHT SIDE -->
            <div class="bg-white p-14">

                <!-- Title -->
                <div class="mb-8">
                    <h2 class="text-3xl font-bold text-gray-800">Welcome Back</h2>
                    <p class="text-gray-500 text-sm">Silakan login ke sistem</p>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <!-- Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <!-- Email -->
                    <div class="relative">
                        <i class="bi bi-envelope absolute left-4 top-3 text-gray-400"></i>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            placeholder="Email"
                            class="pl-11 w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 py-2">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="relative">
                        <i class="bi bi-lock absolute left-4 top-3 text-gray-400"></i>
                        <input id="password" type="password" name="password" required
                            placeholder="Password"
                            class="pl-11 w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 py-2">

                        <button type="button" id="togglePassword"
                            class="absolute right-4 top-3 text-gray-400">
                            <i class="bi bi-eye"></i>
                        </button>

                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember + Forgot -->
                    <div class="flex justify-between text-sm">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="remember"
                                class="rounded border-gray-300 text-blue-600">
                            <span>Remember me</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                                class="text-blue-600 hover:underline">
                                Lupa password?
                            </a>
                        @endif
                    </div>

                    <!-- Button -->
                    <button type="submit"
                        class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
                        Login
                    </button>

                    <!-- Register -->
                    <p class="text-sm text-gray-500">
                        Belum punya akun?
                        <a href="{{ route('register') }}"
                            class="text-blue-600 font-medium hover:underline">
                            Daftar
                        </a>
                    </p>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Toggle Password -->
    <script>
        const toggle = document.getElementById('togglePassword');
        const input = document.getElementById('password');

        toggle.addEventListener('click', () => {
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            toggle.innerHTML = isPassword
                ? '<i class="bi bi-eye-slash"></i>'
                : '<i class="bi bi-eye"></i>';
        });
    </script>
</x-guest-layout>
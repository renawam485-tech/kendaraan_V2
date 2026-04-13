<x-guest-layout>
    <div class="text-center mb-6">
        <div class="inline-flex items-center justify-center w-12 h-12 bg-blue-50 text-blue-600 rounded-xl mb-3">
            <i class="bi bi-shield-lock text-2xl"></i>
        </div>
        <h2 class="text-2xl font-black text-gray-900">Selamat Datang</h2>
        <p class="text-sm text-gray-500 mt-1">Silakan masuk menggunakan kredensial Anda</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Alamat Email')" class="font-semibold text-gray-700" />
            <div class="relative mt-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="bi bi-envelope text-gray-400"></i>
                </div>
                <x-text-input id="email" class="block w-full pl-10 border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="contoh@email.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <div class="flex justify-between items-center">
                <x-input-label for="password" :value="__('Kata Sandi')" class="font-semibold text-gray-700" />
                @if (Route::has('password.request'))
                    <a class="text-xs font-medium text-blue-600 hover:text-blue-500 transition" href="{{ route('password.request') }}">
                        Lupa kata sandi?
                    </a>
                @endif
            </div>
            <div class="relative mt-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="bi bi-key text-gray-400"></i>
                </div>
                <x-text-input id="password" class="block w-full pl-10 border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm"
                                type="password"
                                name="password"
                                required autocomplete="current-password" placeholder="••••••••" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-4">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500 cursor-pointer" name="remember">
                <span class="ms-2 text-sm text-gray-600 font-medium">Ingat Saya</span>
            </label>
        </div>

        <div>
            <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                <i class="bi bi-box-arrow-in-right mr-2"></i> Masuk Sekarang
            </button>
        </div>
        
        @if (Route::has('register'))
            <div class="mt-6 text-center text-sm text-gray-600">
                Belum memiliki akun? 
                <a href="{{ route('register') }}" class="font-bold text-blue-600 hover:text-blue-500 transition">Daftar disini</a>
            </div>
        @endif
    </form>
</x-guest-layout>
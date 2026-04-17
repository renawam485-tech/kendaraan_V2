<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Peminjaman Kendaraan</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <style>
        html { scroll-behavior: smooth; }
    </style>
</head>

<body class="bg-gray-50 text-gray-800">

<!-- Navbar -->
<header class="flex justify-between items-center px-8 py-5 bg-white/80 backdrop-blur shadow-sm sticky top-0 z-50">
    <h1 class="text-2xl font-bold text-blue-600">Drivora</h1>

    <nav class="hidden md:flex gap-6 font-medium">
        <a href="#" class="hover:text-blue-600">Home</a>
        <a href="#fitur" class="hover:text-blue-600">Fitur</a>
        <a href="#proses" class="hover:text-blue-600">Proses</a>
    </nav>

    <div class="flex gap-3">
        @auth
            <a href="{{ url('/dashboard') }}" class="bg-blue-600 text-white px-4 py-2 rounded-xl">Dashboard</a>
        @else
            <a href="{{ route('login') }}" class="px-4 py-2">Login</a>
            <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-xl">Daftar</a>
        @endauth
    </div>
</header>

<!-- Hero -->
<section class="text-center py-28 px-6 bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 text-white">
    <h2 class="text-4xl md:text-6xl font-bold mb-6" data-aos="fade-up">
        Sistem Peminjaman Kendaraan Modern
    </h2>
    <p class="max-w-2xl mx-auto text-lg opacity-90 mb-8" data-aos="fade-up" data-aos-delay="100">
        Kelola peminjaman kendaraan dengan cepat, transparan, dan efisien dalam satu platform.
    </p>

    <div class="flex justify-center gap-4" data-aos="fade-up" data-aos-delay="200">
        @auth
            <a href="{{ url('/dashboard') }}" class="bg-white text-blue-600 px-6 py-3 rounded-xl font-semibold shadow hover:scale-105 transition">
                Ajukan Peminjaman
            </a>
        @else
            <a href="{{ route('login') }}" class="bg-white text-blue-600 px-6 py-3 rounded-xl font-semibold shadow hover:scale-105 transition">
                Ajukan Peminjaman
            </a>
        @endauth

        <a href="#fitur" class="px-6 py-3 border border-white rounded-xl hover:bg-white hover:text-blue-600 transition">
            Pelajari
        </a>
    </div>
</section>

<!-- Fitur -->
<section id="fitur" class="py-20 px-6 max-w-6xl mx-auto">
    <div class="text-center mb-12" data-aos="fade-up">
        <h3 class="text-3xl font-bold">Fitur Unggulan</h3>
        <p class="text-gray-500">Semua kebutuhan peminjaman dalam satu sistem</p>
    </div>

    <div class="grid md:grid-cols-3 gap-8">
        <div class="p-6 bg-white rounded-2xl shadow hover:shadow-lg transition" data-aos="zoom-in">
            <i class="bi bi-calendar-check text-3xl text-blue-600"></i>
            <h4 class="text-xl font-semibold mt-4">Pengajuan Online</h4>
            <p class="mt-2 text-gray-600">Ajukan peminjaman kendaraan dengan cepat tanpa proses manual.</p>
        </div>

        <div class="p-6 bg-white rounded-2xl shadow hover:shadow-lg transition" data-aos="zoom-in" data-aos-delay="100">
            <i class="bi bi-patch-check text-3xl text-green-600"></i>
            <h4 class="text-xl font-semibold mt-4">Approval Sistem</h4>
            <p class="mt-2 text-gray-600">Persetujuan terstruktur dari admin atau atasan secara digital.</p>
        </div>

        <div class="p-6 bg-white rounded-2xl shadow hover:shadow-lg transition" data-aos="zoom-in" data-aos-delay="200">
            <i class="bi bi-graph-up text-3xl text-purple-600"></i>
            <h4 class="text-xl font-semibold mt-4">Monitoring</h4>
            <p class="mt-2 text-gray-600">Pantau status peminjaman secara real-time dan transparan.</p>
        </div>
    </div>
</section>

<!-- Proses -->
<section id="proses" class="bg-gradient-to-b from-gray-100 to-white py-20 px-6">
    <div class="text-center mb-12" data-aos="fade-up">
        <h3 class="text-3xl font-bold">Alur Peminjaman</h3>
        <p class="text-gray-500">Langkah mudah untuk meminjam kendaraan</p>
    </div>

    <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">
        <div class="bg-white p-6 rounded-2xl shadow text-center" data-aos="fade-up">
            <i class="bi bi-pencil-square text-3xl text-blue-600"></i>
            <h4 class="text-xl font-semibold mt-4">Ajukan</h4>
            <p class="mt-2 text-gray-600">Isi form peminjaman kendaraan sesuai kebutuhan.</p>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow text-center" data-aos="fade-up" data-aos-delay="100">
            <i class="bi bi-check2-circle text-3xl text-green-600"></i>
            <h4 class="text-xl font-semibold mt-4">Persetujuan</h4>
            <p class="mt-2 text-gray-600">Admin memverifikasi dan menyetujui pengajuan.</p>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow text-center" data-aos="fade-up" data-aos-delay="200">
            <i class="bi bi-car-front text-3xl text-purple-600"></i>
            <h4 class="text-xl font-semibold mt-4">Gunakan</h4>
            <p class="mt-2 text-gray-600">Kendaraan siap digunakan sesuai jadwal.</p>
        </div>
    </div>
</section>

<section class="py-20 px-6 bg-gray-100">
    <div class="max-w-5xl mx-auto text-center">
        <h3 class="text-3xl font-bold mb-6">Kenapa Menggunakan Drivora?</h3>

        <div class="grid md:grid-cols-3 gap-6 text-left mt-10">

            <div>
                <h4 class="font-bold">Lebih Cepat</h4>
                <p class="text-gray-500 text-sm">Tanpa proses manual & kertas</p>
            </div>

            <div>
                <h4 class="font-bold">Transparan</h4>
                <p class="text-gray-500 text-sm">Semua status bisa dipantau</p>
            </div>

            <div>
                <h4 class="font-bold">Terkontrol</h4>
                <p class="text-gray-500 text-sm">Approval berjenjang & rapi</p>
            </div>

        </div>
    </div>
</section>

<!-- CTA -->
<section class="text-center py-20 bg-blue-600 text-white">
    <h3 class="text-3xl font-bold mb-4" data-aos="fade-up">Mulai Sekarang</h3>
    <p class="mb-6 opacity-90" data-aos="fade-up" data-aos-delay="100">Gunakan sistem untuk mempermudah peminjaman kendaraan</p>

    @guest
    <a href="{{ route('register') }}" class="bg-white text-blue-600 px-6 py-3 rounded-xl font-semibold shadow hover:scale-105 transition" data-aos="fade-up" data-aos-delay="200">
        Daftar Sekarang
    </a>
    @endguest
</section>

<!-- Footer -->
<footer class="text-center py-8 text-gray-500 text-sm">
    © {{ date('Y') }} Drivora. All rights reserved.
</footer>

<!-- AOS Script -->
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
    AOS.init({ duration: 800, once: true });
</script>

</body>
</html>

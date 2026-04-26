<x-app-layout>

    <div class="py-6 md:py-10">
        <div class="w-full px-4 sm:px-6 lg:px-8">

            <!-- HEADER -->
            <div class="relative overflow-hidden rounded-2xl mb-8 p-6 text-white shadow-lg bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600">

                <div class="absolute w-[300px] h-[300px] bg-white/10 blur-3xl rounded-full -top-20 -right-20"></div>

                <div class="relative flex items-center gap-5">
                    <div class="hidden sm:flex p-4 bg-white/20 rounded-xl">
                        <i class="bi bi-speedometer2 text-3xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold">
                            Selamat Datang, {{ Auth::user()->name }}
                        </h3>
                        <p class="text-sm text-white/80 mt-1">
                            Dashboard Sistem Peminjaman Kendaraan
                        </p>
                    </div>
                </div>
            </div>

            <!-- STATS -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

                @if (Auth::user()->role === 'kepala_admin')

                    <div class="card">
                        <div class="icon bg-gradient-to-br from-blue-500 to-indigo-500">
                            <i class="bi bi-collection"></i>
                        </div>
                        <div>
                            <span>Total Pengajuan</span>
                            <h2>{{ $stats['total_semua'] }}</h2>
                        </div>
                    </div>

                    <div class="card">
                        <div class="icon bg-gradient-to-br from-blue-500 to-cyan-500">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div>
                            <span>Menunggu Validasi</span>
                            <h2>{{ $stats['menunggu_validasi'] }}</h2>
                        </div>
                    </div>

                    <div class="card">
                        <div class="icon bg-gradient-to-br from-indigo-500 to-purple-500">
                            <i class="bi bi-file-earmark-check"></i>
                        </div>
                        <div>
                            <span>Finalisasi</span>
                            <h2>{{ $stats['menunggu_finalisasi'] }}</h2>
                        </div>
                    </div>

                @elseif(Auth::user()->role === 'spsi')

                    <div class="card">
                        <div class="icon bg-gradient-to-br from-green-500 to-emerald-500">
                            <i class="bi bi-truck"></i>
                        </div>
                        <div>
                            <span>Butuh Armada</span>
                            <h2>{{ $stats['menunggu_alokasi'] }}</h2>
                        </div>
                    </div>

                    <div class="card">
                        <div class="icon bg-gradient-to-br from-green-500 to-teal-500">
                            <i class="bi bi-car-front"></i>
                        </div>
                        <div>
                            <span>Mobil Tersedia</span>
                            <h2>{{ $stats['mobil_tersedia'] }}</h2>
                        </div>
                    </div>

                    <div class="card">
                        <div class="icon bg-gradient-to-br from-teal-500 to-green-500">
                            <i class="bi bi-person-vcard"></i>
                        </div>
                        <div>
                            <span>Supir Tersedia</span>
                            <h2>{{ $stats['supir_tersedia'] }}</h2>
                        </div>
                    </div>

                @elseif(Auth::user()->role === 'keuangan')

                    <div class="card">
                        <div class="icon bg-gradient-to-br from-yellow-500 to-orange-500">
                            <i class="bi bi-cash-coin"></i>
                        </div>
                        <div>
                            <span>Persetujuan RAB</span>
                            <h2>{{ $stats['menunggu_rab'] }}</h2>
                        </div>
                    </div>

                    <div class="card">
                        <div class="icon bg-gradient-to-br from-orange-500 to-red-500">
                            <i class="bi bi-arrow-repeat"></i>
                        </div>
                        <div>
                            <span>Verifikasi</span>
                            <h2>{{ $stats['menunggu_verifikasi'] }}</h2>
                        </div>
                    </div>

                    <div class="card">
                        <div class="icon bg-gradient-to-br from-green-500 to-emerald-500">
                            <i class="bi bi-wallet2"></i>
                        </div>
                        <div>
                            <span>RAB Disetujui</span>
                            <h2>Rp {{ number_format($stats['rab_disetujui'], 0, ',', '.') }}</h2>
                        </div>
                    </div>

                @endif

            </div>

            <!-- TABLE -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

                <div class="p-6 border-b flex justify-between items-center">
                    <h3 class="font-bold flex items-center gap-2">
                        <i class="bi bi-list-task text-blue-600"></i>
                        Tugas Menunggu
                    </h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm border-separate border-spacing-y-2 px-4">
                        <thead>
                            <tr class="text-gray-500 text-xs uppercase">
                                <th class="px-4 py-3">No</th>
                                <th>Pemohon</th>
                                <th>Tujuan</th>
                                <th>Update</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($tugasTerbaru as $index => $tugas)

                                <tr class="bg-gray-50 hover:bg-gray-100 rounded-xl transition">
                                    <td class="px-4 py-3">{{ $index + 1 }}</td>
                                    <td class="font-semibold">{{ $tugas->nama_pic }}</td>
                                    <td>{{ $tugas->tujuan }}</td>
                                    <td>{{ $tugas->updated_at->diffForHumans() }}</td>
                                    <td>
                                        <a href="{{ route('permohonan.show', $tugas->id) }}"
                                            class="btn-primary">
                                            Proses
                                        </a>
                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-10 text-gray-400">
                                        Tidak ada data
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>

        </div>
    </div>

    <!-- STYLE TAMBAHAN -->
    <style>
        .card {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 20px;
            border-radius: 16px;
            background: rgba(255,255,255,0.7);
            backdrop-filter: blur(10px);
            border: 1px solid #eee;
            transition: 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        .card .icon {
            padding: 12px;
            border-radius: 12px;
            color: white;
            font-size: 20px;
        }

        .card span {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
        }

        .card h2 {
            font-size: 24px;
            font-weight: bold;
            color: #111;
        }

        .btn-primary {
            background: linear-gradient(to right, #2563eb, #4f46e5);
            color: white;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: bold;
            transition: 0.2s;
        }

        .btn-primary:hover {
            opacity: 0.85;
        }
    </style>

    <!-- ICON -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <x-footer />
</x-app-layout>
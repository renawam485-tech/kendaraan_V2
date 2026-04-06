<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard Super Admin</h2>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm">
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
            @endif

            {{-- STATS UTAMA --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm text-center">
                    <p class="text-3xl font-black text-purple-700">{{ $stats['total_permohonan'] }}</p>
                    <p class="text-xs text-gray-500 mt-1 font-medium uppercase tracking-wide">Total Permohonan</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm text-center">
                    <p class="text-3xl font-black text-blue-700">{{ $stats['permohonan_aktif'] }}</p>
                    <p class="text-xs text-gray-500 mt-1 font-medium uppercase tracking-wide">Aktif / On-Process</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm text-center">
                    <p class="text-3xl font-black text-green-700">{{ $stats['permohonan_selesai'] }}</p>
                    <p class="text-xs text-gray-500 mt-1 font-medium uppercase tracking-wide">Selesai</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm text-center">
                    <p class="text-3xl font-black text-red-700">{{ $stats['permohonan_ditolak'] }}</p>
                    <p class="text-xs text-gray-500 mt-1 font-medium uppercase tracking-wide">Ditolak</p>
                </div>
            </div>

            {{-- STATS ARMADA & KEUANGAN --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                    <h3 class="font-bold text-gray-700 mb-3 text-sm uppercase tracking-wider">Status Armada</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm"><span class="text-gray-600">Total Kendaraan</span><strong>{{ $stats['total_kendaraan'] }}</strong></div>
                        <div class="flex justify-between text-sm"><span class="text-green-600">✓ Tersedia</span><strong class="text-green-700">{{ $stats['kendaraan_tersedia'] }}</strong></div>
                        <div class="flex justify-between text-sm"><span class="text-blue-600">⬤ Dipinjam</span><strong class="text-blue-700">{{ $stats['kendaraan_dipinjam'] }}</strong></div>
                        <div class="flex justify-between text-sm"><span class="text-orange-600">⚙ Maintenance</span><strong class="text-orange-700">{{ $stats['kendaraan_maintenance'] }}</strong></div>
                    </div>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                    <h3 class="font-bold text-gray-700 mb-3 text-sm uppercase tracking-wider">Status Pengemudi</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm"><span class="text-gray-600">Total Pengemudi</span><strong>{{ $stats['total_pengemudi'] }}</strong></div>
                        <div class="flex justify-between text-sm"><span class="text-green-600">✓ Tersedia</span><strong class="text-green-700">{{ $stats['pengemudi_tersedia'] }}</strong></div>
                        <div class="flex justify-between text-sm"><span class="text-blue-600">⬤ Bertugas</span><strong class="text-blue-700">{{ $stats['total_pengemudi'] - $stats['pengemudi_tersedia'] }}</strong></div>
                    </div>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                    <h3 class="font-bold text-gray-700 mb-3 text-sm uppercase tracking-wider">Total Anggaran Disetujui</h3>
                    <p class="text-2xl font-black text-purple-700">Rp {{ number_format($stats['total_rab'], 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500 mt-1">Dari permohonan Disetujui & Selesai</p>
                    <div class="mt-3 border-t pt-3">
                        <p class="text-sm text-gray-600">Total Pengguna Terdaftar: <strong>{{ $stats['total_pengguna'] }}</strong></p>
                    </div>
                </div>
            </div>

            {{-- QUICK LINKS --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ route('superadmin.kendaraan.index') }}" class="bg-purple-600 hover:bg-purple-700 text-white rounded-xl p-4 shadow text-center transition">
                    <div class="text-3xl mb-1">🚗</div>
                    <div class="font-bold text-sm">Kelola Kendaraan</div>
                </a>
                <a href="{{ route('superadmin.pengemudi.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white rounded-xl p-4 shadow text-center transition">
                    <div class="text-3xl mb-1">👤</div>
                    <div class="font-bold text-sm">Kelola Pengemudi</div>
                </a>
                <a href="{{ route('superadmin.users.index') }}" class="bg-green-600 hover:bg-green-700 text-white rounded-xl p-4 shadow text-center transition">
                    <div class="text-3xl mb-1">👥</div>
                    <div class="font-bold text-sm">Manajemen Pengguna</div>
                </a>
                <a href="{{ route('laporan.index') }}" class="bg-orange-500 hover:bg-orange-600 text-white rounded-xl p-4 shadow text-center transition">
                    <div class="text-3xl mb-1">📊</div>
                    <div class="font-bold text-sm">Laporan & Export</div>
                </a>
            </div>

            {{-- STATUS KENDARAAN --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="p-4 border-b bg-gray-50 flex justify-between items-center">
                    <h3 class="font-bold text-gray-800">Status Armada Kendaraan Saat Ini</h3>
                    <a href="{{ route('superadmin.kendaraan.index') }}" class="text-xs text-purple-600 font-bold hover:underline">Lihat Semua →</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-100 text-xs uppercase text-gray-600">
                            <tr>
                                <th class="px-4 py-3">Nama Kendaraan</th>
                                <th class="px-4 py-3">Plat Nomor</th>
                                <th class="px-4 py-3">Kapasitas</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kendaraanList as $k)
                                @php
                                    $sc = match($k->status_kendaraan) {
                                        'Tersedia'    => 'bg-green-100 text-green-800',
                                        'Dipinjam'    => 'bg-blue-100 text-blue-800',
                                        'Maintenance' => 'bg-orange-100 text-orange-800',
                                    };
                                @endphp
                                <tr class="border-b hover:bg-gray-50 transition">
                                    <td class="px-4 py-3 font-medium text-gray-800">{{ $k->nama_kendaraan }}</td>
                                    <td class="px-4 py-3">{{ $k->plat_nomor }}</td>
                                    <td class="px-4 py-3">{{ $k->kapasitas_penumpang }} Org</td>
                                    <td class="px-4 py-3"><span class="px-2 py-0.5 rounded-full text-xs font-bold {{ $sc }}">{{ $k->status_kendaraan }}</span></td>
                                    <td class="px-4 py-3 text-center">
                                        <a href="{{ route('superadmin.kendaraan.edit', $k->id) }}" class="text-purple-600 hover:underline text-xs font-bold">Edit</a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center py-6 text-gray-400">Belum ada data kendaraan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- PERMOHONAN TERBARU --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="p-4 border-b bg-gray-50 flex justify-between items-center">
                    <h3 class="font-bold text-gray-800">Permohonan Terbaru</h3>
                    <a href="{{ route('laporan.index') }}" class="text-xs text-purple-600 font-bold hover:underline">Lihat Laporan Lengkap →</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-100 text-xs uppercase text-gray-600">
                            <tr>
                                <th class="px-4 py-3">Pemohon</th>
                                <th class="px-4 py-3">Tujuan</th>
                                <th class="px-4 py-3">Berangkat</th>
                                <th class="px-4 py-3">Kategori</th>
                                <th class="px-4 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($permohonanTerbaru as $p)
                                @php
                                    $sc = match($p->status_permohonan) {
                                        'Disetujui'  => 'bg-blue-100 text-blue-800',
                                        'Selesai'    => 'bg-green-100 text-green-800',
                                        'Ditolak'    => 'bg-red-100 text-red-800',
                                        default      => 'bg-gray-100 text-gray-800',
                                    };
                                @endphp
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-3 font-medium">{{ $p->nama_pic }}</td>
                                    <td class="px-4 py-3 text-gray-600">{{ $p->tujuan }}</td>
                                    <td class="px-4 py-3 text-xs">{{ \Carbon\Carbon::parse($p->waktu_berangkat)->format('d/m/Y H:i') }}</td>
                                    <td class="px-4 py-3 text-xs">{{ $p->kategori_kegiatan ?? '-' }}</td>
                                    <td class="px-4 py-3"><span class="px-2 py-0.5 rounded-full text-xs font-bold {{ $sc }}">{{ $p->status_permohonan }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center py-6 text-gray-400">Belum ada permohonan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
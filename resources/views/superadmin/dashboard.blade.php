<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Super Admin') }}
        </h2>
    </x-slot>

    <div class="py-6 md:py-10 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            @if (session('success'))
                <div
                    class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-sm flex items-center gap-3">
                    <i class="bi bi-check-circle-fill text-xl"></i>
                    <p class="text-sm font-bold">{{ session('success') }}</p>
                </div>
            @endif

            {{-- STATS UTAMA (KLIK MENUJU LAPORAN) --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ route('laporan.index') }}"
                    class="bg-white rounded-xl border border-gray-100 border-l-4 border-l-blue-600 p-5 shadow-sm text-center hover:bg-blue-50 hover:border-l-blue-700 transition block">
                    <p class="text-3xl font-black text-gray-800">{{ $stats['total_permohonan'] }}</p>
                    <p class="text-xs text-gray-500 mt-1 font-bold uppercase tracking-wide">Total Permohonan</p>
                </a>
                <a href="{{ route('laporan.index') }}"
                    class="bg-white rounded-xl border border-gray-100 border-l-4 border-l-blue-400 p-5 shadow-sm text-center hover:bg-blue-50 transition block">
                    <p class="text-3xl font-black text-gray-800">{{ $stats['permohonan_aktif'] }}</p>
                    <p class="text-xs text-gray-500 mt-1 font-bold uppercase tracking-wide">Aktif / On-Process</p>
                </a>
                <a href="{{ route('laporan.index') }}"
                    class="bg-white rounded-xl border border-gray-100 border-l-4 border-l-green-500 p-5 shadow-sm text-center hover:bg-green-50 transition block">
                    <p class="text-3xl font-black text-gray-800">{{ $stats['permohonan_selesai'] }}</p>
                    <p class="text-xs text-gray-500 mt-1 font-bold uppercase tracking-wide">Telah Selesai</p>
                </a>
                <a href="{{ route('laporan.index') }}"
                    class="bg-white rounded-xl border border-gray-100 border-l-4 border-l-red-500 p-5 shadow-sm text-center hover:bg-red-50 transition block">
                    <p class="text-3xl font-black text-gray-800">{{ $stats['permohonan_ditolak'] }}</p>
                    <p class="text-xs text-gray-500 mt-1 font-bold uppercase tracking-wide">Ditolak / Batal</p>
                </a>
            </div>

            {{-- STATS ARMADA & KEUANGAN --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm">
                    <h3 class="font-bold text-gray-800 mb-4 text-sm uppercase tracking-wider flex items-center gap-2"><i
                            class="bi bi-truck text-blue-600 text-lg"></i> Status Armada</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm pb-2 border-b border-gray-50"><span
                                class="text-gray-600 font-medium">Total Kendaraan</span><strong
                                class="text-gray-800">{{ $stats['total_kendaraan'] }}</strong></div>
                        <div class="flex justify-between text-sm pb-2 border-b border-gray-50"><span
                                class="text-gray-600 font-medium flex items-center gap-1"><i
                                    class="bi bi-check-circle text-green-500"></i> Tersedia</span><strong
                                class="text-green-600">{{ $stats['kendaraan_tersedia'] }}</strong></div>
                        <div class="flex justify-between text-sm pb-2 border-b border-gray-50"><span
                                class="text-gray-600 font-medium flex items-center gap-1"><i
                                    class="bi bi-geo-alt text-blue-500"></i> Dipinjam</span><strong
                                class="text-blue-600">{{ $stats['kendaraan_dipinjam'] }}</strong></div>
                        <div class="flex justify-between text-sm"><span
                                class="text-gray-600 font-medium flex items-center gap-1"><i
                                    class="bi bi-tools text-orange-500"></i> Maintenance</span><strong
                                class="text-orange-600">{{ $stats['kendaraan_maintenance'] }}</strong></div>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm">
                    <h3 class="font-bold text-gray-800 mb-4 text-sm uppercase tracking-wider flex items-center gap-2"><i
                            class="bi bi-person-badge text-blue-600 text-lg"></i> Status Pengemudi</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm pb-2 border-b border-gray-50"><span
                                class="text-gray-600 font-medium">Total Pengemudi</span><strong
                                class="text-gray-800">{{ $stats['total_pengemudi'] }}</strong></div>
                        <div class="flex justify-between text-sm pb-2 border-b border-gray-50"><span
                                class="text-gray-600 font-medium flex items-center gap-1"><i
                                    class="bi bi-check-circle text-green-500"></i> Standby (Tersedia)</span><strong
                                class="text-green-600">{{ $stats['pengemudi_tersedia'] }}</strong></div>
                        <div class="flex justify-between text-sm"><span
                                class="text-gray-600 font-medium flex items-center gap-1"><i
                                    class="bi bi-cone-striped text-blue-500"></i> Sedang Bertugas</span><strong
                                class="text-blue-600">{{ $stats['total_pengemudi'] - $stats['pengemudi_tersedia'] }}</strong>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm flex flex-col justify-center">
                    <h3 class="font-bold text-gray-800 mb-2 text-sm uppercase tracking-wider flex items-center gap-2"><i
                            class="bi bi-wallet2 text-blue-600 text-lg"></i> Anggaran Berjalan</h3>
                    <p class="text-3xl font-black text-blue-700">Rp
                        {{ number_format($stats['total_rab'], 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500 mt-2 font-medium">Dari permohonan berstatus Disetujui & Selesai</p>
                    <div class="mt-4 border-t border-gray-100 pt-4 flex items-center gap-2">
                        <div class="p-2 bg-blue-50 rounded text-blue-600"><i class="bi bi-people-fill"></i></div>
                        <p class="text-sm text-gray-600 font-medium">Total Pengguna Sistem: <strong
                                class="text-gray-800">{{ $stats['total_pengguna'] }}</strong></p>
                    </div>
                </div>
            </div>

            {{-- QUICK LINKS (AKSI CEPAT) --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ route('superadmin.kendaraan.index') }}"
                    class="bg-white border border-gray-100 hover:border-blue-300 hover:bg-blue-50 text-gray-700 hover:text-blue-700 rounded-xl p-5 shadow-sm text-center transition group">
                    <i
                        class="bi bi-car-front text-3xl mb-2 text-blue-500 group-hover:scale-110 transition-transform inline-block"></i>
                    <div class="font-bold text-sm">Kelola Kendaraan</div>
                </a>
                <a href="{{ route('superadmin.pengemudi.index') }}"
                    class="bg-white border border-gray-100 hover:border-blue-300 hover:bg-blue-50 text-gray-700 hover:text-blue-700 rounded-xl p-5 shadow-sm text-center transition group">
                    <i
                        class="bi bi-person-vcard text-3xl mb-2 text-blue-500 group-hover:scale-110 transition-transform inline-block"></i>
                    <div class="font-bold text-sm">Kelola Pengemudi</div>
                </a>
                <a href="{{ route('superadmin.users.index') }}"
                    class="bg-white border border-gray-100 hover:border-blue-300 hover:bg-blue-50 text-gray-700 hover:text-blue-700 rounded-xl p-5 shadow-sm text-center transition group">
                    <i
                        class="bi bi-people text-3xl mb-2 text-blue-500 group-hover:scale-110 transition-transform inline-block"></i>
                    <div class="font-bold text-sm">Manajemen Pengguna</div>
                </a>
                <a href="{{ route('laporan.index') }}"
                    class="bg-white border border-gray-100 hover:border-blue-300 hover:bg-blue-50 text-gray-700 hover:text-blue-700 rounded-xl p-5 shadow-sm text-center transition group">
                    <i
                        class="bi bi-file-earmark-bar-graph text-3xl mb-2 text-blue-500 group-hover:scale-110 transition-transform inline-block"></i>
                    <div class="font-bold text-sm">Laporan & Export</div>
                </a>
            </div>

            {{-- STATUS KENDARAAN TABEL --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-gray-100 bg-white flex justify-between items-center">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2"><i
                            class="bi bi-car-front text-blue-600"></i> Status Armada Kendaraan Saat Ini</h3>
                    <a href="{{ route('superadmin.kendaraan.index') }}"
                        class="text-xs text-blue-600 font-bold hover:underline">Lihat Detail &rarr;</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-500 border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-4">Kendaraan</th>
                                <th class="px-6 py-4">Plat Nomor</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kendaraanList as $k)
                                @php
                                    $sc = match ($k->status_kendaraan) {
                                        'Tersedia' => 'bg-green-50 text-green-700 border border-green-200',
                                        'Dipinjam' => 'bg-blue-50 text-blue-700 border border-blue-200',
                                        'Maintenance' => 'bg-orange-50 text-orange-700 border border-orange-200',
                                        default => 'bg-gray-50 text-gray-700 border border-gray-200',
                                    };
                                @endphp
                                <tr class="border-b border-gray-50 hover:bg-blue-50/30 transition">
                                    <td class="px-6 py-4 font-bold text-gray-800">{{ $k->nama_kendaraan }} <span
                                            class="block text-xs text-gray-400 font-normal mt-1">{{ $k->kapasitas_penumpang }}
                                            Penumpang</span></td>
                                    <td class="px-6 py-4 font-mono text-gray-600">{{ $k->plat_nomor }}</td>
                                    <td class="px-6 py-4"><span
                                            class="px-2.5 py-1 rounded text-[11px] font-bold {{ $sc }}">{{ $k->status_kendaraan }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('superadmin.kendaraan.edit', $k->id) }}"
                                            class="text-blue-600 hover:text-blue-800 text-xs font-bold hover:underline"><i
                                                class="bi bi-pencil-square"></i> Edit</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-8 text-gray-400"><i
                                            class="bi bi-inbox text-3xl block mb-2"></i> Belum ada data kendaraan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- PERMOHONAN TERBARU --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-gray-100 bg-white flex justify-between items-center">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2"><i
                            class="bi bi-clock-history text-blue-600"></i> Permohonan Terbaru</h3>
                    <a href="{{ route('laporan.index') }}"
                        class="text-xs text-blue-600 font-bold hover:underline">Lihat Laporan &rarr;</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-500 border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-4">Pemohon</th>
                                <th class="px-6 py-4">Tujuan</th>
                                <th class="px-6 py-4">Berangkat</th>
                                <th class="px-6 py-4">Kategori</th>
                                <th class="px-6 py-4">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($permohonanTerbaru as $p)
                                @php
                                    $sc = match ($p->status_permohonan) {
                                        'Disetujui' => 'bg-blue-50 text-blue-700 border border-blue-200',
                                        'Selesai' => 'bg-green-50 text-green-700 border border-green-200',
                                        'Ditolak' => 'bg-red-50 text-red-700 border border-red-200',
                                        'Menunggu Mulai Perjalanan' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                        'Perjalanan Berlangsung' => 'bg-teal-50 text-teal-700 border-teal-200',
                                        'Menunggu Konfirmasi Kembali'
                                            => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                        'Menunggu Penyelesaian' => 'bg-purple-50 text-purple-700 border-purple-200',
                                        default => 'bg-gray-50 text-gray-700 border border-gray-200',
                                    };
                                @endphp
                                <tr class="border-b border-gray-50 hover:bg-blue-50/30 transition">
                                    <td class="px-6 py-4 font-bold text-gray-800">{{ $p->nama_pic }}</td>
                                    <td class="px-6 py-4 text-gray-600">{{ $p->tujuan }}</td>
                                    <td class="px-6 py-4 text-xs">
                                        {{ \Carbon\Carbon::parse($p->waktu_berangkat)->format('d/m/Y H:i') }}</td>
                                    <td class="px-6 py-4 font-medium">{{ $p->kategori_kegiatan ?? '-' }}</td>
                                    <td class="px-6 py-4"><span
                                            class="px-2.5 py-1 rounded text-[11px] font-bold {{ $sc }}">{{ $p->status_permohonan }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-8 text-gray-400"><i
                                            class="bi bi-inbox text-3xl block mb-2"></i> Belum ada permohonan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

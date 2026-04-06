<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">
                @php
                    $judul = match(Auth::user()->role) {
                        'super_admin'  => 'Laporan Rekap Seluruh Permohonan',
                        'kepala_admin' => 'Laporan Aktivitas Validasi & Persetujuan',
                        'spsi'         => 'Laporan Penggunaan Armada Kendaraan',
                        'keuangan'     => 'Laporan Rekapitulasi Anggaran & RAB',
                        'pengguna'     => 'Riwayat Pengajuan Saya',
                        default        => 'Laporan',
                    };
                @endphp
                {{ $judul }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('laporan.export.excel', request()->query()) }}"
                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg text-sm shadow flex items-center gap-1 transition">
                    📊 Export Excel
                </a>
                <a href="{{ route('laporan.export.pdf', request()->query()) }}" target="_blank"
                    class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg text-sm shadow flex items-center gap-1 transition">
                    📄 Export PDF
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- STATS CARDS --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($stats as $label => $nilai)
                    @php
                        $labelMap = [
                            'total'              => ['Total', 'text-gray-700'],
                            'disetujui'          => ['Disetujui/Selesai', 'text-green-700'],
                            'selesai'            => ['Selesai', 'text-green-700'],
                            'ditolak'            => ['Ditolak', 'text-red-700'],
                            'proses'             => ['Dalam Proses', 'text-blue-700'],
                            'total_rab'          => ['Total RAB (Rp)', 'text-purple-700'],
                            'total_realisasi'    => ['Realisasi (Rp)', 'text-blue-700'],
                            'total_sisa'         => ['Sisa Dana (Rp)', 'text-orange-700'],
                            'jumlah_transaksi'   => ['Transaksi', 'text-gray-700'],
                            'total_kendaraan'    => ['Total Kendaraan', 'text-gray-700'],
                            'kendaraan_tersedia' => ['Kendaraan Tersedia', 'text-green-700'],
                            'kendaraan_dipinjam' => ['Sedang Dipinjam', 'text-blue-700'],
                            'total_pengemudi'    => ['Total Pengemudi', 'text-gray-700'],
                            'pengemudi_bertugas' => ['Pengemudi Bertugas', 'text-blue-700'],
                            'total_perjalanan'   => ['Total Perjalanan', 'text-purple-700'],
                        ];
                        $lbl   = $labelMap[$label][0] ?? $label;
                        $color = $labelMap[$label][1] ?? 'text-gray-700';
                        $isRupiah = str_contains($label, 'rab') || str_contains($label, 'realisasi') || str_contains($label, 'sisa');
                    @endphp
                    <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm text-center">
                        <p class="text-xl font-black {{ $color }}">
                            {{ $isRupiah ? 'Rp ' . number_format($nilai, 0, ',', '.') : $nilai }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">{{ $lbl }}</p>
                    </div>
                @endforeach
            </div>

            {{-- FILTER --}}
            <form method="GET" action="{{ route('laporan.index') }}" class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1 uppercase tracking-wide">Dari Tanggal</label>
                        <input type="date" name="dari" value="{{ $request->dari }}"
                            class="w-full border-gray-300 rounded-md text-sm focus:ring-purple-500 focus:border-purple-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1 uppercase tracking-wide">Sampai Tanggal</label>
                        <input type="date" name="sampai" value="{{ $request->sampai }}"
                            class="w-full border-gray-300 rounded-md text-sm focus:ring-purple-500 focus:border-purple-500">
                    </div>

                    @if(in_array(Auth::user()->role, ['super_admin','kepala_admin','keuangan']))
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-1 uppercase tracking-wide">Status</label>
                            <select name="status" class="w-full border-gray-300 rounded-md text-sm focus:ring-purple-500 focus:border-purple-500">
                                <option value="">Semua Status</option>
                                @foreach(['Menunggu Validasi Admin','Menunggu Proses SPSI','Menunggu Proses Keuangan','Menunggu Finalisasi','Disetujui','Menunggu Pengembalian Dana','Menunggu Verifikasi Pengembalian','Selesai','Ditolak'] as $s)
                                    <option value="{{ $s }}" {{ $request->status === $s ? 'selected' : '' }}>{{ $s }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    @if(Auth::user()->role === 'super_admin')
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-1 uppercase tracking-wide">Kategori</label>
                            <select name="kategori" class="w-full border-gray-300 rounded-md text-sm focus:ring-purple-500 focus:border-purple-500">
                                <option value="">Semua Kategori</option>
                                <option value="Dinas SITH" {{ $request->kategori === 'Dinas SITH' ? 'selected' : '' }}>Dinas SITH</option>
                                <option value="Non SITH"   {{ $request->kategori === 'Non SITH'   ? 'selected' : '' }}>Non SITH</option>
                            </select>
                        </div>
                    @endif

                    <div class="flex items-end gap-2 md:col-span-{{ in_array(Auth::user()->role, ['super_admin']) ? '1' : '1' }}">
                        <button type="submit" class="bg-purple-600 text-white font-bold py-2 px-5 rounded-md text-sm hover:bg-purple-700 transition w-full">Terapkan Filter</button>
                        <a href="{{ route('laporan.index') }}" class="bg-white border border-gray-300 text-gray-600 font-bold py-2 px-4 rounded-md text-sm hover:bg-gray-50 transition whitespace-nowrap">Reset</a>
                    </div>
                </div>
            </form>

            {{-- TABEL DATA --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="p-4 border-b bg-gray-50 flex justify-between items-center">
                    <p class="text-sm text-gray-600">Menampilkan <strong>{{ $data->count() }}</strong> data</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-100 text-xs uppercase text-gray-600">
                            @if(Auth::user()->role === 'keuangan')
                                <tr>
                                    <th class="px-4 py-3">No</th>
                                    <th class="px-4 py-3">Pemohon</th>
                                    <th class="px-4 py-3">Tujuan</th>
                                    <th class="px-4 py-3">Kategori</th>
                                    <th class="px-4 py-3 text-right">RAB Disetujui</th>
                                    <th class="px-4 py-3 text-right">Biaya Aktual</th>
                                    <th class="px-4 py-3 text-right">Selisih</th>
                                    <th class="px-4 py-3">Mekanisme</th>
                                    <th class="px-4 py-3">Status</th>
                                </tr>
                            @elseif(Auth::user()->role === 'spsi')
                                <tr>
                                    <th class="px-4 py-3">No</th>
                                    <th class="px-4 py-3">Pemohon</th>
                                    <th class="px-4 py-3">Tujuan</th>
                                    <th class="px-4 py-3">Kendaraan</th>
                                    <th class="px-4 py-3">Pengemudi</th>
                                    <th class="px-4 py-3">Berangkat</th>
                                    <th class="px-4 py-3">Kembali</th>
                                    <th class="px-4 py-3">Status</th>
                                </tr>
                            @elseif(Auth::user()->role === 'pengguna')
                                <tr>
                                    <th class="px-4 py-3">No</th>
                                    <th class="px-4 py-3">Tujuan</th>
                                    <th class="px-4 py-3">Berangkat</th>
                                    <th class="px-4 py-3">Kembali</th>
                                    <th class="px-4 py-3">Kendaraan</th>
                                    <th class="px-4 py-3">Status</th>
                                    <th class="px-4 py-3">Aksi</th>
                                </tr>
                            @else
                                <tr>
                                    <th class="px-4 py-3">No</th>
                                    <th class="px-4 py-3">Pemohon</th>
                                    <th class="px-4 py-3">Tujuan</th>
                                    <th class="px-4 py-3">Berangkat</th>
                                    <th class="px-4 py-3">Kategori</th>
                                    <th class="px-4 py-3">Armada</th>
                                    <th class="px-4 py-3 text-right">RAB (Rp)</th>
                                    <th class="px-4 py-3">Status</th>
                                </tr>
                            @endif
                        </thead>
                        <tbody>
                            @forelse($data as $i => $p)
                                @php
                                    $sc = match($p->status_permohonan) {
                                        'Disetujui'  => 'bg-blue-100 text-blue-800',
                                        'Selesai'    => 'bg-green-100 text-green-800',
                                        'Ditolak'    => 'bg-red-100 text-red-800',
                                        'Menunggu Pengembalian Dana' => 'bg-orange-100 text-orange-800',
                                        'Menunggu Verifikasi Pengembalian' => 'bg-yellow-100 text-yellow-800',
                                        default      => 'bg-gray-100 text-gray-700',
                                    };
                                @endphp
                                @if(Auth::user()->role === 'keuangan')
                                    @php $selisih = ($p->rab_disetujui ?? 0) - ($p->biaya_aktual ?? 0); @endphp
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-4 py-3 text-gray-500">{{ $i + 1 }}</td>
                                        <td class="px-4 py-3 font-medium">{{ $p->nama_pic }}</td>
                                        <td class="px-4 py-3 text-gray-600">{{ $p->tujuan }}</td>
                                        <td class="px-4 py-3"><span class="text-xs font-bold text-purple-700">{{ $p->kategori_kegiatan ?? '-' }}</span></td>
                                        <td class="px-4 py-3 text-right font-mono">{{ $p->rab_disetujui ? 'Rp '.number_format($p->rab_disetujui,0,',','.') : '-' }}</td>
                                        <td class="px-4 py-3 text-right font-mono">{{ $p->biaya_aktual ? 'Rp '.number_format($p->biaya_aktual,0,',','.') : '-' }}</td>
                                        <td class="px-4 py-3 text-right font-mono {{ $selisih > 0 ? 'text-orange-600' : 'text-gray-600' }}">{{ $p->rab_disetujui ? 'Rp '.number_format($selisih,0,',','.') : '-' }}</td>
                                        <td class="px-4 py-3 text-xs">{{ $p->mekanisme_pembayaran ?? '-' }}</td>
                                        <td class="px-4 py-3"><span class="px-2 py-0.5 rounded-full text-xs font-bold {{ $sc }}">{{ $p->status_permohonan }}</span></td>
                                    </tr>
                                @elseif(Auth::user()->role === 'spsi')
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-4 py-3 text-gray-500">{{ $i + 1 }}</td>
                                        <td class="px-4 py-3 font-medium">{{ $p->nama_pic }}</td>
                                        <td class="px-4 py-3 text-gray-600">{{ $p->tujuan }}</td>
                                        <td class="px-4 py-3 text-xs">{{ $p->kendaraan?->nama_kendaraan ?? $p->kendaraan_vendor ?? '-' }}</td>
                                        <td class="px-4 py-3 text-xs">{{ $p->pengemudi?->nama_pengemudi ?? 'Tanpa Supir' }}</td>
                                        <td class="px-4 py-3 text-xs">{{ \Carbon\Carbon::parse($p->waktu_berangkat)->format('d/m/Y H:i') }}</td>
                                        <td class="px-4 py-3 text-xs">{{ \Carbon\Carbon::parse($p->waktu_kembali)->format('d/m/Y H:i') }}</td>
                                        <td class="px-4 py-3"><span class="px-2 py-0.5 rounded-full text-xs font-bold {{ $sc }}">{{ $p->status_permohonan }}</span></td>
                                    </tr>
                                @elseif(Auth::user()->role === 'pengguna')
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-4 py-3 text-gray-500">{{ $i + 1 }}</td>
                                        <td class="px-4 py-3 font-medium text-gray-800">{{ $p->tujuan }}</td>
                                        <td class="px-4 py-3 text-xs">{{ \Carbon\Carbon::parse($p->waktu_berangkat)->format('d M Y H:i') }}</td>
                                        <td class="px-4 py-3 text-xs">{{ \Carbon\Carbon::parse($p->waktu_kembali)->format('d M Y H:i') }}</td>
                                        <td class="px-4 py-3 text-xs">{{ $p->kendaraan?->nama_kendaraan ?? $p->kendaraan_vendor ?? '-' }}</td>
                                        <td class="px-4 py-3"><span class="px-2 py-0.5 rounded-full text-xs font-bold {{ $sc }}">{{ $p->status_permohonan }}</span></td>
                                        <td class="px-4 py-3">
                                            <a href="{{ route('permohonan.show', $p->id) }}" class="text-purple-600 hover:underline font-bold text-xs">Detail</a>
                                        </td>
                                    </tr>
                                @else
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-4 py-3 text-gray-500">{{ $i + 1 }}</td>
                                        <td class="px-4 py-3 font-medium">{{ $p->nama_pic }}</td>
                                        <td class="px-4 py-3 text-gray-600">{{ $p->tujuan }}</td>
                                        <td class="px-4 py-3 text-xs">{{ \Carbon\Carbon::parse($p->waktu_berangkat)->format('d/m/Y H:i') }}</td>
                                        <td class="px-4 py-3 text-xs"><span class="font-bold text-purple-700">{{ $p->kategori_kegiatan ?? '-' }}</span></td>
                                        <td class="px-4 py-3 text-xs">{{ $p->kendaraan?->nama_kendaraan ?? $p->kendaraan_vendor ?? '-' }}</td>
                                        <td class="px-4 py-3 text-right font-mono text-xs">{{ $p->rab_disetujui ? 'Rp '.number_format($p->rab_disetujui,0,',','.') : '-' }}</td>
                                        <td class="px-4 py-3"><span class="px-2 py-0.5 rounded-full text-xs font-bold {{ $sc }}">{{ $p->status_permohonan }}</span></td>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-12 text-gray-400">
                                        <div class="text-4xl mb-2">📊</div>
                                        <p>Tidak ada data untuk ditampilkan.</p>
                                        <p class="text-xs mt-1">Coba ubah filter di atas.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <p class="text-xs text-gray-400 text-center">Laporan digenerate pada {{ now()->translatedFormat('d F Y, H:i') }} WIB</p>
        </div>
    </div>
</x-app-layout>
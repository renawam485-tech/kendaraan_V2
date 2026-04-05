<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Kepala Administrasi') }}
        </h2>
    </x-slot>

    <div class="py-6 md:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm" role="alert">
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="p-4 md:p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">{{ $judul ?? 'Daftar Permohonan Kendaraan' }}</h3>

                    {{-- MOBILE VIEW --}}
                    <div class="block md:hidden space-y-4">
                        @forelse($permohonans as $p)
                            @php
                                $badgeClass = match($p->status_permohonan) {
                                    'Disetujui'                        => 'bg-blue-100 text-blue-800',
                                    'Selesai'                          => 'bg-green-100 text-green-800',
                                    'Ditolak'                          => 'bg-red-100 text-red-800',
                                    'Menunggu Pengembalian Dana'        => 'bg-orange-100 text-orange-800',
                                    'Menunggu Verifikasi Pengembalian'  => 'bg-yellow-100 text-yellow-800',
                                    default                            => 'bg-blue-100 text-blue-800',
                                };
                            @endphp
                            <div class="bg-gray-50 border border-gray-200 p-4 rounded-lg shadow-sm">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-bold text-gray-800 text-base">{{ $p->nama_pic }}</h4>
                                    <span class="px-2 py-1 text-[10px] font-bold rounded-full text-center {{ $badgeClass }}">{{ $p->status_permohonan }}</span>
                                </div>
                                <p class="text-xs text-gray-600 mb-1">📍 {{ $p->tujuan }}</p>
                                <p class="text-xs text-gray-600 mb-1">🕒 {{ \Carbon\Carbon::parse($p->waktu_berangkat)->format('d M y H:i') }}</p>
                                <p class="text-xs text-gray-800 font-medium mb-4">🚗
                                    @if($p->kendaraan_id)
                                        {{ $p->kendaraan->nama_kendaraan }}
                                    @elseif($p->kendaraan_vendor)
                                        {{ $p->kendaraan_vendor }} <span class="text-orange-600 font-bold">(Vendor)</span>
                                    @else
                                        <span class="text-gray-400 italic">Belum dialokasikan</span>
                                    @endif
                                </p>

                                {{-- FIX BUG 13: aksi kondisional di mobile --}}
                                @if($p->status_permohonan === 'Menunggu Validasi Admin')
                                    <a href="{{ route('permohonan.validasi_admin', $p->id) }}" class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 rounded-md text-sm shadow-sm transition">Validasi Data</a>
                                @elseif($p->status_permohonan === 'Menunggu Finalisasi')
                                    <a href="{{ route('permohonan.finalisasi_admin', $p->id) }}" class="block w-full text-center bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 rounded-md text-sm shadow-sm transition">Finalisasi Penerbitan</a>
                                @else
                                    <a href="{{ route('permohonan.show', $p->id) }}" class="block w-full text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2 rounded-md text-sm border border-gray-300 transition">Lihat Detail</a>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500 text-sm border border-dashed rounded-lg">Tidak ada data.</div>
                        @endforelse
                    </div>

                    {{-- DESKTOP VIEW --}}
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-100 rounded-t-lg">
                                <tr>
                                    <th class="px-6 py-3">Pemohon & Tujuan</th>
                                    <th class="px-6 py-3">Waktu Berangkat</th>
                                    <th class="px-6 py-3">Armada / Kendaraan</th>
                                    <th class="px-6 py-3">Status</th>
                                    <th class="px-6 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($permohonans as $p)
                                    @php
                                        $badgeClass = match($p->status_permohonan) {
                                            'Disetujui'                        => 'bg-blue-100 text-blue-800',
                                            'Selesai'                          => 'bg-green-100 text-green-800',
                                            'Ditolak'                          => 'bg-red-100 text-red-800',
                                            'Menunggu Pengembalian Dana'        => 'bg-orange-100 text-orange-800',
                                            'Menunggu Verifikasi Pengembalian'  => 'bg-yellow-100 text-yellow-800',
                                            default                            => 'bg-blue-100 text-blue-800',
                                        };
                                    @endphp
                                    <tr class="bg-white border-b hover:bg-gray-50 transition">
                                        <td class="px-6 py-4">
                                            <strong class="text-gray-800">{{ $p->nama_pic }}</strong><br>
                                            <span class="text-xs text-gray-500">{{ $p->tujuan }}</span>
                                        </td>
                                        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($p->waktu_berangkat)->format('d/m/Y H:i') }}</td>
                                        <td class="px-6 py-4 text-gray-900 font-medium">
                                            @if($p->kendaraan_id)
                                                {{ $p->kendaraan->nama_kendaraan }}
                                            @elseif($p->kendaraan_vendor)
                                                {{ $p->kendaraan_vendor }} <br><span class="text-[10px] text-orange-600 font-bold bg-orange-50 px-1 rounded border border-orange-200">VENDOR</span>
                                            @else
                                                <span class="text-gray-400 italic text-xs">Belum ada</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full whitespace-nowrap {{ $badgeClass }}">{{ $p->status_permohonan }}</span>
                                        </td>
                                        {{-- FIX BUG 13: ganti dash(-) dengan tombol Lihat Detail --}}
                                        <td class="px-6 py-4 text-center">
                                            @if($p->status_permohonan === 'Menunggu Validasi Admin')
                                                <a href="{{ route('permohonan.validasi_admin', $p->id) }}" class="text-white bg-blue-600 hover:bg-blue-700 px-3 py-1 rounded text-xs font-bold shadow-sm whitespace-nowrap">Validasi</a>
                                            @elseif($p->status_permohonan === 'Menunggu Finalisasi')
                                                <a href="{{ route('permohonan.finalisasi_admin', $p->id) }}" class="text-white bg-purple-600 hover:bg-purple-700 px-3 py-1 rounded text-xs font-bold shadow-sm whitespace-nowrap">Finalisasi</a>
                                            @else
                                                <a href="{{ route('permohonan.show', $p->id) }}" class="text-gray-600 hover:text-gray-800 border border-gray-300 hover:bg-gray-50 px-3 py-1 rounded text-xs font-medium whitespace-nowrap transition">Lihat Detail</a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center py-8 text-gray-500">Tidak ada data.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
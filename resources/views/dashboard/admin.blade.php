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
                    <h3 class="text-lg font-bold mb-4">Daftar Permohonan Kendaraan</h3>
                    
                    <div class="block md:hidden space-y-4">
                        @foreach($permohonans as $p)
                            <div class="bg-gray-50 border border-gray-200 p-4 rounded-lg shadow-sm">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-bold text-gray-800 text-base">{{ $p->nama_pic }}</h4>
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-[10px] font-bold rounded-full text-center">{{ $p->status_permohonan }}</span>
                                </div>
                                <p class="text-xs text-gray-600 mb-1">📍 {{ $p->tujuan }}</p>
                                <p class="text-xs text-gray-600 mb-4">🕒 {{ \Carbon\Carbon::parse($p->waktu_berangkat)->format('d M y H:i') }}</p>
                                
                                @if($p->status_permohonan === 'Menunggu Validasi Admin')
                                    <a href="{{ route('permohonan.validasi_admin', $p->id) }}" class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 rounded-md text-sm shadow-sm transition">Validasi Data</a>
                                @elseif($p->status_permohonan === 'Menunggu Finalisasi')
                                    <a href="{{ route('permohonan.finalisasi_admin', $p->id) }}" class="block w-full text-center bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 rounded-md text-sm shadow-sm transition">Finalisasi Penerbitan</a>
                                @else
                                    <div class="text-center text-xs text-gray-400 py-2 border border-dashed rounded-md">Tidak ada aksi</div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-100 rounded-t-lg">
                                <tr>
                                    <th class="px-6 py-3">Pemohon & Tujuan</th>
                                    <th class="px-6 py-3">Waktu Berangkat</th>
                                    <th class="px-6 py-3">Status</th>
                                    <th class="px-6 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($permohonans as $p)
                                    <tr class="bg-white border-b hover:bg-gray-50 transition">
                                        <td class="px-6 py-4">
                                            <strong class="text-gray-800">{{ $p->nama_pic }}</strong><br>
                                            <span class="text-xs text-gray-500">{{ $p->tujuan }}</span>
                                        </td>
                                        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($p->waktu_berangkat)->format('d/m/Y H:i') }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full whitespace-nowrap">{{ $p->status_permohonan }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($p->status_permohonan === 'Menunggu Validasi Admin')
                                                <a href="{{ route('permohonan.validasi_admin', $p->id) }}" class="text-white bg-blue-600 hover:bg-blue-700 px-3 py-1 rounded text-xs font-bold shadow-sm whitespace-nowrap">Validasi</a>
                                            @elseif($p->status_permohonan === 'Menunggu Finalisasi')
                                                <a href="{{ route('permohonan.finalisasi_admin', $p->id) }}" class="text-white bg-purple-600 hover:bg-purple-700 px-3 py-1 rounded text-xs font-bold shadow-sm whitespace-nowrap">Finalisasi</a>
                                            @else
                                                <span class="text-gray-300">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
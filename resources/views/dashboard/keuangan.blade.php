<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Kasubag Keuangan') }}
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
                    <h3 class="text-lg font-bold mb-4">Daftar Persetujuan Anggaran</h3>
                    
                    <div class="block md:hidden space-y-4">
                        @foreach($permohonans as $p)
                            <div class="bg-gray-50 border border-gray-200 p-4 rounded-lg shadow-sm">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-bold text-gray-800 text-base">{{ $p->nama_pic }}</h4>
                                    <span class="px-2 py-1 bg-orange-100 text-orange-800 text-[10px] font-bold rounded-full text-center">{{ $p->status_permohonan }}</span>
                                </div>
                                <p class="text-xs text-purple-600 font-bold mb-1">{{ $p->kategori_kegiatan }}</p>
                                <p class="text-xs text-gray-500 mb-2">Sumber: {{ $p->anggaran_diajukan }}</p>
                                <div class="bg-white p-3 border rounded-md mb-4 flex justify-between items-center">
                                    <span class="text-xs text-gray-500 font-medium">Estimasi SPSI:</span>
                                    <span class="font-black text-red-600 text-sm">Rp {{ number_format($p->estimasi_biaya_operasional, 0, ',', '.') }}</span>
                                </div>
                                
                                @if($p->status_permohonan === 'Menunggu Proses Keuangan')
                                    <a href="{{ route('permohonan.proses_keuangan', $p->id) }}" class="block w-full text-center bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 rounded-md text-sm shadow-sm transition">Proses RAB</a>
                                @else
                                    <div class="text-center text-xs text-gray-400 py-2 border border-dashed rounded-md">Diproses</div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-100 rounded-t-lg">
                                <tr>
                                    <th class="px-6 py-3">Pemohon & Kategori</th>
                                    <th class="px-6 py-3">Estimasi Biaya (SPSI)</th>
                                    <th class="px-6 py-3">Status</th>
                                    <th class="px-6 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($permohonans as $p)
                                    <tr class="bg-white border-b hover:bg-gray-50 transition">
                                        <td class="px-6 py-4">
                                            <strong class="text-gray-800">{{ $p->nama_pic }}</strong><br>
                                            <span class="text-xs text-purple-600 font-bold block">{{ $p->kategori_kegiatan }}</span>
                                            <span class="text-[11px] text-gray-500">Sumber: {{ $p->anggaran_diajukan }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="font-black text-red-600 bg-red-50 px-2 py-1 rounded">Rp {{ number_format($p->estimasi_biaya_operasional, 0, ',', '.') }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 bg-orange-100 text-orange-800 text-xs font-semibold rounded-full whitespace-nowrap">{{ $p->status_permohonan }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($p->status_permohonan === 'Menunggu Proses Keuangan')
                                                <a href="{{ route('permohonan.proses_keuangan', $p->id) }}" class="text-white bg-orange-500 hover:bg-orange-600 px-3 py-1 rounded text-xs font-bold shadow-sm whitespace-nowrap">Proses RAB</a>
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
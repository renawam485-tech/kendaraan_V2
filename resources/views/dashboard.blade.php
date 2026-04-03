<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Utama') }}
        </h2>
    </x-slot>

    <div class="py-6 md:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 border-l-4 border-purple-500">
                <div class="p-6 text-gray-900 flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Selamat Datang, {{ Auth::user()->name }}!</h3>
                        <p class="mt-1 text-sm text-gray-600">Berikut adalah ringkasan informasi dan tugas Anda saat ini.</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                
                @if(Auth::user()->role === 'kepala_admin')
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 flex flex-col justify-center items-center text-center">
                        <span class="text-sm font-bold text-gray-500 mb-1">Total Semua Pengajuan</span>
                        <span class="text-3xl font-black text-purple-600">{{ $stats['total_semua'] }}</span>
                    </div>
                    <div class="bg-blue-50 p-6 rounded-lg shadow-sm border border-blue-100 flex flex-col justify-center items-center text-center">
                        <span class="text-sm font-bold text-blue-700 mb-1">Menunggu Validasi</span>
                        <span class="text-3xl font-black text-blue-600">{{ $stats['menunggu_validasi'] }}</span>
                    </div>
                    <div class="bg-purple-50 p-6 rounded-lg shadow-sm border border-purple-100 flex flex-col justify-center items-center text-center">
                        <span class="text-sm font-bold text-purple-700 mb-1">Menunggu Finalisasi</span>
                        <span class="text-3xl font-black text-purple-600">{{ $stats['menunggu_finalisasi'] }}</span>
                    </div>

                @elseif(Auth::user()->role === 'spsi')
                    <div class="bg-green-50 p-6 rounded-lg shadow-sm border border-green-100 flex flex-col justify-center items-center text-center">
                        <span class="text-sm font-bold text-green-700 mb-1">Tugas Menunggu Alokasi</span>
                        <span class="text-3xl font-black text-green-600">{{ $stats['menunggu_alokasi'] }}</span>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 flex flex-col justify-center items-center text-center">
                        <span class="text-sm font-bold text-gray-500 mb-1">Mobil Tersedia</span>
                        <span class="text-3xl font-black text-gray-800">{{ $stats['mobil_tersedia'] }}</span>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 flex flex-col justify-center items-center text-center">
                        <span class="text-sm font-bold text-gray-500 mb-1">Pengemudi Tersedia</span>
                        <span class="text-3xl font-black text-gray-800">{{ $stats['supir_tersedia'] }}</span>
                    </div>

                @elseif(Auth::user()->role === 'keuangan')
                    <div class="bg-orange-50 p-6 rounded-lg shadow-sm border border-orange-100 flex flex-col justify-center items-center text-center">
                        <span class="text-sm font-bold text-orange-700 mb-1">Tugas Menunggu RAB</span>
                        <span class="text-3xl font-black text-orange-600">{{ $stats['menunggu_rab'] }}</span>
                    </div>
                    <div class="md:col-span-2 bg-white p-6 rounded-lg shadow-sm border border-gray-100 flex flex-col justify-center items-start">
                        <span class="text-sm font-bold text-gray-500 mb-1">Total RAB Disetujui (Keseluruhan)</span>
                        <span class="text-3xl font-black text-green-600">Rp {{ number_format($stats['rab_disetujui'], 0, ',', '.') }}</span>
                    </div>
                @endif
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="p-4 md:p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold">Sekilas Tugas yang Belum Diselesaikan</h3>
                    </div>
                    
                    <div class="block md:hidden space-y-3">
                        @forelse($tugasTerbaru as $tugas)
                            @php 
                                // Sembunyikan item ke 6 sampai 10 di HP
                                $hideMobile = $loop->iteration > 5 ? 'hidden' : ''; 
                            @endphp
                            <div class="bg-gray-50 border border-gray-200 p-4 rounded-lg shadow-sm {{ $hideMobile }}">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-bold text-gray-800 text-sm">{{ $tugas->nama_pic }}</h4>
                                    <span class="px-2 py-1 bg-red-100 text-red-800 text-[10px] font-bold rounded-full text-center">{{ $tugas->status_permohonan }}</span>
                                </div>
                                <p class="text-xs text-gray-600 mb-1">Tujuan: {{ $tugas->tujuan }}</p>
                                <p class="text-xs text-gray-400">Masuk: {{ $tugas->updated_at->diffForHumans() }}</p>
                            </div>
                        @empty
                            <div class="text-center py-6 text-gray-500 text-sm">Tidak ada tugas yang menunggu. Bagus!</div>
                        @endforelse
                    </div>

                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3">Pemohon</th>
                                    <th class="px-6 py-3">Tujuan & Waktu</th>
                                    <th class="px-6 py-3">Waktu Masuk</th>
                                    <th class="px-6 py-3">Status Terkini</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tugasTerbaru as $tugas)
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <td class="px-6 py-4 font-bold text-gray-800">{{ $tugas->nama_pic }}</td>
                                        <td class="px-6 py-4">
                                            {{ $tugas->tujuan }} <br>
                                            <span class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($tugas->waktu_berangkat)->format('d M Y H:i') }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-xs">{{ $tugas->updated_at->diffForHumans() }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded-full">{{ $tugas->status_permohonan }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="px-6 py-8 text-center text-gray-500">Tidak ada tugas yang menunggu. Bagus!</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6 border-t pt-4">
                        <a href="{{ $ruteTugas }}" class="block w-full text-center bg-gray-800 hover:bg-black text-white font-bold py-3 rounded-lg shadow transition">
                            Lihat Semua Tugas Saya & Kerjakan &rarr;
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
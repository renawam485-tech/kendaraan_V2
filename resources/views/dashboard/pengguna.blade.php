<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Pemohon') }}
        </h2>
    </x-slot>

    <div class="py-6 md:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm" role="alert">
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
            @endif

            <div class="mb-6 flex justify-end">
                <a href="{{ route('permohonan.create') }}" class="w-full md:w-auto text-center bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 md:py-2 px-4 rounded-lg shadow transition">
                    + Buat Permohonan Baru
                </a>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="p-4 md:p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">Riwayat Permohonan Saya</h3>
                    
                    <div class="block md:hidden space-y-4">
                        @forelse($permohonans as $p)
                            <div class="bg-gray-50 border border-gray-200 p-4 rounded-lg shadow-sm">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-bold text-gray-800 text-base">{{ $p->tujuan }}</h4>
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-[10px] font-bold rounded-full text-center">{{ $p->status_permohonan }}</span>
                                </div>
                                <p class="text-xs text-gray-600 mb-1">🚗 {{ $p->kendaraan_dibutuhkan }}</p>
                                <p class="text-xs text-gray-600 mb-3">🕒 {{ \Carbon\Carbon::parse($p->waktu_berangkat)->format('d M Y H:i') }}</p>
                                <a href="{{ route('permohonan.show', $p->id) }}" class="block w-full text-center bg-indigo-50 text-indigo-700 hover:bg-indigo-100 border border-indigo-200 font-bold py-2 rounded-md text-sm transition">
                                    Lihat Detail
                                </a>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500 text-sm">Belum ada riwayat permohonan.</div>
                        @endforelse
                    </div>

                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-100 rounded-t-lg">
                                <tr>
                                    <th class="px-6 py-3 rounded-tl-lg">Tujuan & Waktu</th>
                                    <th class="px-6 py-3">Kendaraan</th>
                                    <th class="px-6 py-3">Status</th>
                                    <th class="px-6 py-3 rounded-tr-lg text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($permohonans as $p)
                                    <tr class="bg-white border-b hover:bg-gray-50 transition">
                                        <td class="px-6 py-4">
                                            <strong class="text-gray-800">{{ $p->tujuan }}</strong><br>
                                            <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($p->waktu_berangkat)->format('d M Y H:i') }}</span>
                                        </td>
                                        <td class="px-6 py-4">{{ $p->kendaraan_dibutuhkan }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full whitespace-nowrap">{{ $p->status_permohonan }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <a href="{{ route('permohonan.show', $p->id) }}" class="text-indigo-600 hover:text-indigo-900 font-bold text-xs hover:underline">Lihat Detail</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="px-6 py-8 text-center text-gray-500">Belum ada riwayat permohonan.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Kasubag SPSI') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">Daftar Penugasan Kendaraan</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3">Pemohon & Tujuan</th>
                                    <th class="px-6 py-3">Jadwal</th>
                                    <th class="px-6 py-3">Rekomendasi Admin</th>
                                    <th class="px-6 py-3">Status</th>
                                    <th class="px-6 py-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($permohonans as $p)
                                    <tr class="bg-white border-b">
                                        <td class="px-6 py-4">
                                            <strong>{{ $p->nama_pic }}</strong><br>
                                            {{ $p->tujuan }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ \Carbon\Carbon::parse($p->waktu_berangkat)->format('d/m/y H:i') }} <br>
                                            s/d {{ \Carbon\Carbon::parse($p->waktu_kembali)->format('d/m/y H:i') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-xs font-semibold text-purple-600">{{ $p->kategori_kegiatan }}</span><br>
                                            {{ $p->rekomendasi_admin ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full">
                                                {{ $p->status_permohonan }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($p->status_permohonan === 'Menunggu Proses SPSI')
                                                <a href="{{ route('permohonan.proses_spsi', $p->id) }}" class="text-white bg-green-600 hover:bg-green-700 px-3 py-1 rounded text-xs font-bold whitespace-nowrap">Alokasi Armada</a>
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
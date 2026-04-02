<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Kepala Administrasi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">Daftar Permohonan Kendaraan</h3>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3">Pemohon & Tujuan</th>
                                    <th class="px-6 py-3">Waktu</th>
                                    <th class="px-6 py-3">Status</th>
                                    <th class="px-6 py-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($permohonans as $p)
                                    <tr class="bg-white border-b">
                                        <td class="px-6 py-4">
                                            <strong>{{ $p->nama_pic }}</strong><br>
                                            <span class="text-xs">Tujuan: {{ $p->tujuan }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            Berangkat:
                                            {{ \Carbon\Carbon::parse($p->waktu_berangkat)->format('d/m/Y H:i') }}<br>
                                            Kembali: {{ \Carbon\Carbon::parse($p->waktu_kembali)->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">
                                                {{ $p->status_permohonan }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if ($p->status_permohonan === 'Menunggu Validasi Admin')
                                                <a href="{{ route('permohonan.validasi_admin', $p->id) }}"
                                                    class="text-white bg-blue-600 hover:bg-blue-700 px-3 py-1 rounded text-xs font-bold">Validasi</a>
                                            @elseif($p->status_permohonan === 'Menunggu Finalisasi')
                                                <a href="{{ route('permohonan.finalisasi_admin', $p->id) }}"
                                                    class="text-white bg-purple-600 hover:bg-purple-700 px-3 py-1 rounded text-xs font-bold whitespace-nowrap">Finalisasi</a>
                                            @else
                                                <span class="text-gray-400 text-xs">-</span>
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

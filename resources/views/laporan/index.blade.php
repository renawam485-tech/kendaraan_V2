<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 flex items-center gap-2">
                    @if (Auth::user()->role === 'pengguna')
                        Riwayat Pengajuan
                    @else
                        @php
                            $judul = match (Auth::user()->role) {
                                'super_admin' => 'Laporan',
                                'kepala_admin' => 'Laporan',
                                'spsi' => 'Laporan',
                                'keuangan' => 'Laporan',
                                default => 'Laporan',
                            };
                        @endphp
                        {{ $judul }}
                    @endif
                </h2>
                @if (Auth::user()->role !== 'pengguna')
                    <p class="text-sm text-gray-500 mt-1">Ringkasan dan statistik data permohonan</p>
                @endif
            </div>

            {{-- TOMBOL AKSI --}}
            <div class="flex gap-2 w-full sm:w-auto flex-wrap">
                @if (Auth::user()->role !== 'pengguna')
                    {{-- TOMBOL EXPORT --}}
                    <a href="{{ route('laporan.export.excel', request()->query()) }}"
                        class="flex-1 sm:flex-none justify-center bg-white border border-green-600 text-green-700 hover:bg-green-50 font-bold py-2 px-4 rounded-lg text-sm shadow-sm flex items-center gap-2 transition">
                        <i class="bi bi-file-earmark-spreadsheet text-lg"></i> Excel
                    </a>
                    <a href="{{ route('laporan.export.pdf', request()->query()) }}" target="_blank"
                        class="flex-1 sm:flex-none justify-center bg-white border border-red-600 text-red-700 hover:bg-red-50 font-bold py-2 px-4 rounded-lg text-sm shadow-sm flex items-center gap-2 transition">
                        <i class="bi bi-file-earmark-pdf text-lg"></i> PDF
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="w-full px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- ============================================================ --}}
            {{-- ROUTER KE PARTIAL BERDASARKAN ROLE                           --}}
            {{-- ============================================================ --}}
            @if (Auth::user()->role === 'pengguna')
                @include('laporan.partials.pengguna')
            @elseif (Auth::user()->role === 'spsi')
                @include('laporan.partials.spsi')
            @elseif (Auth::user()->role === 'keuangan')
                @include('laporan.partials.keuangan')
            @elseif (in_array(Auth::user()->role, ['kepala_admin', 'super_admin']))
                @include('laporan.partials.admin')
            @endif

            @if (Auth::user()->role !== 'pengguna')
                <p class="text-xs text-gray-400 text-center font-mono mt-4">Data diakses pada:
                    {{ now()->translatedFormat('d F Y, H:i') }} WIB</p>
            @endif

        </div>
    </div>
</x-app-layout>

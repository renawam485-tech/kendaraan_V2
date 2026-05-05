{{-- ============================================================ --}}
{{-- PARTIAL: _stats.blade.php                                  --}}
{{-- Digunakan oleh: _admin, _spsi, _keuangan                   --}}
{{-- ============================================================ --}}
@if (isset($stats) && count($stats) > 0)
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach ($stats as $label => $nilai)
            @php
                $labelMap = [
                    'total' => ['Total Pengajuan', 'text-gray-800'],
                    'disetujui' => ['Disetujui/Selesai', 'text-gray-800'],
                    'selesai' => ['Telah Selesai', 'text-gray-800'],
                    'ditolak' => ['Ditolak / Batal', 'text-gray-800'],
                    'proses' => ['Sedang Diproses', 'text-gray-800'],
                    'total_rab' => ['Total RAB', 'text-blue-700'],
                    'total_realisasi' => ['Total Realisasi', 'text-green-700'],
                    'total_sisa' => ['Sisa Anggaran', 'text-orange-700'],
                    'jumlah_transaksi' => ['Jumlah Transaksi', 'text-gray-800'],
                    'total_kendaraan' => ['Total Kendaraan', 'text-gray-800'],
                    'kendaraan_tersedia' => ['Kendaraan Tersedia', 'text-gray-800'],
                    'kendaraan_dipinjam' => ['Kendaraan Keluar', 'text-gray-800'],
                    'total_pengemudi' => ['Total Pengemudi', 'text-gray-800'],
                    'pengemudi_bertugas' => ['Supir Bertugas', 'text-gray-800'],
                    'total_perjalanan' => ['Total Perjalanan', 'text-gray-800'],
                ];
                $lbl = $labelMap[$label][0] ?? $label;
                $textColor = $labelMap[$label][1] ?? 'text-gray-800';
                $isRupiah =
                    str_contains($label, 'rab') ||
                    str_contains($label, 'realisasi') ||
                    str_contains($label, 'sisa');
            @endphp
            <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm hover:shadow transition flex flex-col justify-center">
                <p class="text-2xl sm:text-3xl font-black {{ $textColor }}">
                    {{ $isRupiah ? 'Rp ' . number_format($nilai, 0, ',', '.') : $nilai }}
                </p>
                <p class="text-xs text-gray-500 mt-1 font-bold uppercase tracking-wide">{{ $lbl }}</p>
            </div>
        @endforeach
    </div>
@endif

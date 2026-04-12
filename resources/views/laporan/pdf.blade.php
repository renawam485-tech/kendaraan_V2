<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $judul }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 9pt; color: #1f2937; }
        .header { text-align: center; border-bottom: 2px solid #6d28d9; padding-bottom: 12px; margin-bottom: 18px; }
        .header h1 { font-size: 14pt; font-weight: bold; text-transform: uppercase; color: #1f2937; letter-spacing: 1px; }
        .header h2 { font-size: 11pt; color: #6d28d9; margin-top: 4px; }
        .header p  { font-size: 8pt; color: #6b7280; margin-top: 3px; }
        .stats-grid { display: table; width: 100%; margin-bottom: 16px; border-collapse: separate; border-spacing: 4px; }
        .stat-box { display: table-cell; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 4px; padding: 8px 10px; text-align: center; }
        .stat-val  { font-size: 14pt; font-weight: bold; color: #6d28d9; }
        .stat-lbl  { font-size: 7pt; color: #6b7280; text-transform: uppercase; margin-top: 2px; }
        table { width: 100%; border-collapse: collapse; margin-top: 4px; }
        thead tr { background-color: #6d28d9; color: white; }
        thead th { padding: 7px 8px; text-align: left; font-size: 8pt; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; }
        tbody tr:nth-child(even) { background: #f5f3ff; }
        tbody td { padding: 6px 8px; font-size: 8pt; border-bottom: 1px solid #e5e7eb; }
        .badge { padding: 2px 6px; border-radius: 10px; font-size: 7pt; font-weight: bold; }
        .badge-green  { background: #d1fae5; color: #065f46; }
        .badge-blue   { background: #dbeafe; color: #1e40af; }
        .badge-red    { background: #fee2e2; color: #991b1b; }
        .badge-orange { background: #ffedd5; color: #9a3412; }
        .badge-indigo { background: #e0e7ff; color: #3730a3; }
        .badge-purple { background: #f3e8ff; color: #6b21a8; }
        .badge-teal   { background: #ccfbf1; color: #134e4a; }
        .badge-gray   { background: #f3f4f6; color: #374151; }
        .footer { text-align: right; font-size: 7.5pt; color: #9ca3af; margin-top: 20px; border-top: 1px solid #e5e7eb; padding-top: 8px; }
    </style>
</head>
<body>
    @use('App\Enums\StatusPermohonan')

    @php
        $pdfBadgeMap = [
            StatusPermohonan::SELESAI->value                      => 'badge-green',
            StatusPermohonan::DISETUJUI->value                    => 'badge-blue',
            StatusPermohonan::DITOLAK->value                      => 'badge-red',
            StatusPermohonan::MENUNGGU_PENGEMBALIAN_DANA->value   => 'badge-orange',
            StatusPermohonan::MENUNGGU_VERIFIKASI_KEMBALI->value  => 'badge-orange',
            StatusPermohonan::PERJALANAN_BERLANGSUNG->value       => 'badge-teal',
            StatusPermohonan::MENUNGGU_MULAI_PERJALANAN->value    => 'badge-indigo',
            StatusPermohonan::MENUNGGU_KONFIRMASI_KEMBALI->value  => 'badge-indigo',
            StatusPermohonan::MENUNGGU_PENYELESAIAN->value        => 'badge-purple',
        ];
    @endphp

    <div class="header">
        <h1>NAMA INSTANSI ANDA</h1>
        <h2>{{ $judul }}</h2>
        <p>Dicetak pada: {{ now()->translatedFormat('l, d F Y H:i') }} WIB &nbsp;|&nbsp; Oleh: {{ $user->name }} ({{ ucfirst(str_replace('_', ' ', $user->role)) }})</p>
    </div>

    @if(!empty($stats))
        <div class="stats-grid">
            @foreach($stats as $label => $nilai)
                @php
                    $labelMap = [
                        'total'              => 'Total',
                        'disetujui'          => 'Disetujui',
                        'selesai'            => 'Selesai',
                        'ditolak'            => 'Ditolak',
                        'proses'             => 'Proses',
                        'total_rab'          => 'Total RAB',
                        'total_realisasi'    => 'Realisasi',
                        'total_sisa'         => 'Sisa Dana',
                        'jumlah_transaksi'   => 'Transaksi',
                        'total_kendaraan'    => 'Kendaraan',
                        'kendaraan_tersedia' => 'Tersedia',
                        'kendaraan_dipinjam' => 'Dipinjam',
                        'total_pengemudi'    => 'Pengemudi',
                        'pengemudi_bertugas' => 'Bertugas',
                        'total_perjalanan'   => 'Perjalanan',
                    ];
                    $isRupiah = str_contains($label, 'rab') || str_contains($label, 'realisasi') || str_contains($label, 'sisa');
                @endphp
                <div class="stat-box">
                    <div class="stat-val">{{ $isRupiah ? 'Rp '.number_format($nilai,0,',','.') : $nilai }}</div>
                    <div class="stat-lbl">{{ $labelMap[$label] ?? $label }}</div>
                </div>
            @endforeach
        </div>
    @endif

    <table>
        <thead>
            <tr>
                @if($user->role === 'keuangan')
                    <th>No</th><th>Pemohon</th><th>Tujuan</th><th>Kategori</th>
                    <th>RAB (Rp)</th><th>Aktual (Rp)</th><th>Selisih (Rp)</th><th>Mekanisme</th><th>Status</th>
                @elseif($user->role === 'spsi')
                    <th>No</th><th>Pemohon</th><th>Tujuan</th><th>Kendaraan</th><th>Pengemudi</th><th>Berangkat</th><th>Status</th>
                @elseif($user->role === 'pengguna')
                    <th>No</th><th>Tujuan</th><th>Berangkat</th><th>Kembali</th><th>Kendaraan</th><th>Status</th>
                @else
                    <th>No</th><th>Pemohon</th><th>Tujuan</th><th>Berangkat</th><th>Kategori</th><th>Armada</th><th>RAB (Rp)</th><th>Status</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($data as $i => $p)
                @php
                    $statusValue = $p->status_permohonan instanceof StatusPermohonan
                        ? $p->status_permohonan->value
                        : $p->status_permohonan;
                    $badgeClass = $pdfBadgeMap[$statusValue] ?? 'badge-gray';
                @endphp

                @if($user->role === 'keuangan')
                    @php $selisih = ($p->rab_disetujui ?? 0) - ($p->biaya_aktual ?? 0); @endphp
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td>{{ $p->nama_pic }}</td>
                        <td>{{ $p->tujuan }}</td>
                        <td>{{ $p->kategori_kegiatan ?? '-' }}</td>
                        <td>{{ $p->rab_disetujui ? number_format($p->rab_disetujui,0,',','.') : '-' }}</td>
                        <td>{{ $p->biaya_aktual ? number_format($p->biaya_aktual,0,',','.') : '-' }}</td>
                        <td>{{ $p->rab_disetujui ? number_format($selisih,0,',','.') : '-' }}</td>
                        <td>{{ $p->mekanisme_pembayaran ?? '-' }}</td>
                        <td><span class="badge {{ $badgeClass }}">{{ $statusValue }}</span></td>
                    </tr>
                @elseif($user->role === 'spsi')
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td>{{ $p->nama_pic }}</td>
                        <td>{{ $p->tujuan }}</td>
                        <td>{{ $p->kendaraan?->nama_kendaraan ?? $p->kendaraanVendor?->nama_kendaraan ?? '-' }}</td>
                        <td>{{ $p->pengemudi?->nama_pengemudi ?? 'Tanpa Supir' }}</td>
                        <td>{{ \Carbon\Carbon::parse($p->waktu_berangkat)->format('d/m/Y H:i') }}</td>
                        <td><span class="badge {{ $badgeClass }}">{{ $statusValue }}</span></td>
                    </tr>
                @elseif($user->role === 'pengguna')
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td>{{ $p->tujuan }}</td>
                        <td>{{ \Carbon\Carbon::parse($p->waktu_berangkat)->format('d/m/Y H:i') }}</td>
                        <td>{{ \Carbon\Carbon::parse($p->waktu_kembali)->format('d/m/Y H:i') }}</td>
                        <td>{{ $p->kendaraan?->nama_kendaraan ?? $p->kendaraanVendor?->nama_kendaraan ?? '-' }}</td>
                        <td><span class="badge {{ $badgeClass }}">{{ $statusValue }}</span></td>
                    </tr>
                @else
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td>{{ $p->nama_pic }}</td>
                        <td>{{ $p->tujuan }}</td>
                        <td>{{ \Carbon\Carbon::parse($p->waktu_berangkat)->format('d/m/Y H:i') }}</td>
                        <td>{{ $p->kategori_kegiatan ?? '-' }}</td>
                        <td>{{ $p->kendaraan?->nama_kendaraan ?? $p->kendaraanVendor?->nama_kendaraan ?? '-' }}</td>
                        <td>{{ $p->rab_disetujui ? number_format($p->rab_disetujui,0,',','.') : '-' }}</td>
                        <td><span class="badge {{ $badgeClass }}">{{ $statusValue }}</span></td>
                    </tr>
                @endif
            @empty
                <tr><td colspan="10" style="text-align:center; padding:20px; color:#9ca3af;">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Total data: {{ $data->count() }} &nbsp;|&nbsp; {{ config('app.name') }} &copy; {{ now()->year }}
    </div>
</body>
</html>
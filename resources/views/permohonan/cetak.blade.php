<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Izin Perjalanan - {{ $permohonan->nama_pic }}</title>
    <style>
        /* TAMPILAN LAYAR (PREVIEW MODE) */
        body { 
            background: #525659; /* Warna abu-abu PDF viewer */
            font-family: Arial, sans-serif; 
            margin: 0; 
            padding-top: 70px; 
            padding-bottom: 40px;
        }
        
        .toolbar { 
            position: fixed; top: 0; left: 0; right: 0; 
            background: #323639; padding: 12px 30px; 
            display: flex; justify-content: space-between; align-items: center; 
            z-index: 1000; box-shadow: 0 2px 8px rgba(0,0,0,0.3); 
        }
        .toolbar-title { color: white; font-weight: bold; font-size: 15px; }
        .btn-group { display: flex; gap: 10px; }
        .btn { 
            padding: 8px 16px; border-radius: 4px; border: none; 
            font-weight: bold; cursor: pointer; font-size: 13px; text-decoration: none;
            transition: opacity 0.2s;
        }
        .btn:hover { opacity: 0.8; }
        .btn-print { background: #8ab4f8; color: #202124; }
        .btn-close { background: #ea4335; color: white; }

        /* KERTAS A4 */
        .page { 
            background: white; 
            width: 210mm; 
            min-height: 297mm; 
            padding: 2cm; 
            margin: 0 auto; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.4); 
            box-sizing: border-box; 
            font-family: 'Times New Roman', Times, serif; 
            color: #000;
            position: relative;
        }

        /* KONTEN DOKUMEN */
        .doc-content { position: relative; z-index: 1; }

        .kop-surat { text-align: center; border-bottom: 3px solid black; padding-bottom: 12px; margin-bottom: 25px; }
        .kop-surat h1 { margin: 0; font-size: 16pt; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
        .kop-surat h2 { margin: 0; font-size: 13pt; font-weight: normal; margin-top: 4px; }
        .kop-surat p { margin: 5px 0 0; font-size: 10pt; }

        .title-doc { text-align: center; margin-bottom: 30px; }
        .title-doc h3 { margin: 0; font-size: 14pt; text-decoration: underline; text-transform: uppercase; font-weight: bold;}
        .title-doc p { margin: 5px 0 0; font-size: 11pt; }

        .content-text { font-size: 12pt; line-height: 1.5; text-align: justify; }
        
        table { width: 100%; border-collapse: collapse; margin: 15px 0; font-size: 12pt; }
        td { padding: 6px; vertical-align: top; }
        .label { width: 32%; font-weight: bold; }
        .titikdua { width: 3%; text-align: center; font-weight: bold; }
        .section-title { font-weight: bold; text-decoration: underline; padding-top: 15px; }

        .footer { margin-top: 40px; width: 100%; display: table; }
        .signature-box { display: table-cell; width: 50%; text-align: center; vertical-align: bottom; }
        .spacer { height: 90px; }

        /* TAMPILAN KHUSUS SAAT DI-PRINT KE KERTAS/PDF */
        @media print {
            @page { size: A4; margin: 0; }
            body { background: white; padding: 0; }
            .toolbar { display: none !important; }
            .page { margin: 0; box-shadow: none; width: 100%; min-height: 100vh; }
        }
    </style>
</head>
<body>

    <div class="toolbar no-print">
        <div class="toolbar-title">📄 Preview Dokumen Izin</div>
        <div class="btn-group">
            <button class="btn btn-print" onclick="window.print()">🖨️ Cetak / Simpan PDF</button>
            <button class="btn btn-close" onclick="window.close()">Tutup Tab Ini</button>
        </div>
    </div>

    <div class="page">
        <div class="doc-content">
            <div class="kop-surat">
                <h1>NAMA INSTANSI / PERUSAHAAN ANDA</h1>
                <h2>Bagian Administrasi Umum dan Perlengkapan</h2>
                <p>Jl. Contoh Alamat Gedung No. 123, Kota Anda, Provinsi, Kode Pos 12345 | Telp: (021) 1234567</p>
            </div>

            <div class="title-doc">
                <h3>BUKTI PERSETUJUAN PERJALANAN</h3>
                <p>Nomor Referensi: {{ $permohonan->kode_permohonan ?? 'BPP/'. \Carbon\Carbon::now()->format('Y/m') .'/'. str_pad($permohonan->id, 4, '0', STR_PAD_LEFT) }}</p>
            </div>

            <div class="content-text">
                <p>Berdasarkan hasil evaluasi dan validasi pengajuan kendaraan operasional, dengan ini Kepala Administrasi menyatakan telah <strong>MEMBERIKAN IZIN</strong> atas pelaksanaan kegiatan perjalanan dengan rincian data sebagai berikut:</p>

                <table>
                    <tr><td colspan="3" class="section-title">A. INFORMASI KEGIATAN & PEMOHON</td></tr>
                    <tr><td class="label">Nama Pemohon (PIC)</td><td class="titikdua">:</td><td>{{ $permohonan->nama_pic }}</td></tr>
                    <tr><td class="label">Kategori Kegiatan</td><td class="titikdua">:</td><td>{{ $permohonan->kategori_kegiatan ?? '-' }}</td></tr>
                    <tr><td class="label">Tujuan / Lokasi</td><td class="titikdua">:</td><td>{{ $permohonan->tujuan }}</td></tr>
                    <tr><td class="label">Jadwal Perjalanan</td><td class="titikdua">:</td>
                        <td>
                            {{ \Carbon\Carbon::parse($permohonan->waktu_berangkat)->translatedFormat('l, d F Y (H:i)') }} <br> s/d <br> 
                            {{ \Carbon\Carbon::parse($permohonan->waktu_kembali)->translatedFormat('l, d F Y (H:i)') }}
                        </td>
                    </tr>
                    <tr><td class="label">Jumlah Rombongan</td><td class="titikdua">:</td><td>{{ $permohonan->jumlah_penumpang }} Orang</td></tr>
                    
                    <tr><td colspan="3" class="section-title">B. FASILITAS ARMADA DIBERIKAN</td></tr>
                    <tr><td class="label">Kendaraan Operasional</td><td class="titikdua">:</td>
                        <td>
                            @if($permohonan->kendaraan_id)
                                <strong>{{ $permohonan->kendaraan->nama_kendaraan }}</strong> (Plat: {{ $permohonan->kendaraan->plat_nomor }})
                            @else
                                <span style="font-style: italic;">Menggunakan kendaraan pribadi / Tidak disediakan</span>
                            @endif
                        </td>
                    </tr>
                    <tr><td class="label">Status Pengemudi</td><td class="titikdua">:</td>
                        <td>
                            @if($permohonan->pengemudi_id)
                                Disediakan Instansi (Nama: <strong>{{ $permohonan->pengemudi->nama_pengemudi }}</strong>)
                            @else
                                <strong>Tanpa Pengemudi (Dikendarai Sendiri oleh Pemohon)</strong>
                            @endif
                        </td>
                    </tr>
                </table>

                <p style="margin-top: 25px;">
                    Dokumen ini berfungsi sebagai bukti sah persetujuan izin perjalanan operasional. Pemohon diwajibkan untuk senantiasa mematuhi peraturan keselamatan berlalu lintas, menjaga aset kendaraan (jika disediakan), dan mengembalikan kendaraan tepat waktu sesuai jadwal yang telah disetujui.
                </p>
            </div>

            <div class="footer">
                <div class="signature-box">
                    <p>Pemohon (PIC),</p>
                    <div class="spacer"></div>
                    <p><strong><u>{{ $permohonan->nama_pic }}</u></strong></p>
                </div>
                <div class="signature-box">
                    <p>Diterbitkan di: Nama Kota<br>Tanggal: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                    <p>Kepala Administrasi,</p>
                    <div class="spacer"></div>
                    <p><strong><u>(Nama Kepala Admin)</u></strong><br>NIP. .............................</p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
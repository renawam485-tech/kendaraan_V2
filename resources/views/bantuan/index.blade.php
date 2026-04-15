<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 flex items-center gap-2">
            <i class="bi bi-info-circle text-blue-600"></i> Pusat Bantuan & FAQ
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="w-full px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- HERO CARD --}}
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-100 border-l-4 border-l-blue-600 p-6 sm:p-8 flex items-center gap-5">
                <div>
                    <h3 class="text-2xl font-black text-gray-800 mb-1">Halo, {{ Auth::user()->name }}!</h3>
                    <p class="text-gray-600 text-sm">Anda masuk sebagai <strong
                            class="text-blue-700">{{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }}</strong>. Di
                        bawah ini tersedia panduan dan FAQ khusus untuk peran Anda.</p>
                </div>
            </div>

            @php $role = Auth::user()->role; @endphp

            {{-- =============================================
                 PENGGUNA
            ============================================= --}}
            @if ($role === 'pengguna')

                {{-- ALUR PENGAJUAN --}}
                {{-- ALUR PENGAJUAN --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 bg-blue-50 flex items-center gap-2">
        <i class="bi bi-map text-blue-600 text-lg"></i>
        <h3 class="font-bold text-gray-800">Alur Pengajuan Kendaraan</h3>
    </div>
    <div class="p-6">
        <p class="text-sm text-gray-500 mb-5">Berikut adalah tahapan yang akan dilalui pengajuan Anda
            dari awal hingga perjalanan selesai.</p>
        <ol class="relative border-l-2 border-blue-200 space-y-0 ml-3">

            @foreach ([
                ['Buat Pengajuan', 'Isi form pengajuan kendaraan: nama PIC, kontak, kendaraan yang dibutuhkan, titik jemput, tujuan, jadwal keberangkatan & kembali, jumlah penumpang, estimasi anggaran, serta unggah Surat Penugasan (PDF/gambar, maks. 2MB). Kode permohonan akan digenerate otomatis.', 'bi-pencil-square', 'blue'],
                ['Validasi Admin', 'Kepala Admin akan memeriksa kelengkapan dan mengategorikan kegiatan Anda sebagai Dinas SITH (ditanggung instansi) atau Non SITH (biaya pribadi). Admin juga dapat memberikan instruksi khusus untuk tim SPSI.', 'bi-shield-check', 'indigo'],
                ['Alokasi Armada (SPSI)', 'Tim SPSI mengalokasikan kendaraan (aset kampus atau vendor) dan pengemudi sesuai kebutuhan. Untuk kegiatan Dinas SITH, estimasi biaya operasional juga diisi di tahap ini.', 'bi-truck-front', 'violet'],
                ['Persetujuan Anggaran (Keuangan)', 'Khusus kegiatan Dinas SITH — Tim Keuangan meninjau estimasi biaya dan menetapkan RAB resmi beserta mekanisme pembayaran (Cash, Cashless, atau Reimburse). Kegiatan Non SITH melewati tahap ini.', 'bi-cash-coin', 'emerald'],
                ['Finalisasi & Penerbitan Surat', 'Kepala Admin melakukan pengecekan akhir lalu menerbitkan surat izin perjalanan. Status berubah menjadi Disetujui dan Anda menerima notifikasi.', 'bi-patch-check', 'green'],
                ['Cetak Dokumen & Laksanakan Perjalanan', 'Setelah status Disetujui, Anda dapat mencetak Bukti Persetujuan Perjalanan dari halaman detail permohonan. Gunakan dokumen ini sebagai surat jalan resmi.', 'bi-printer', 'teal'],
                ['Selesaikan & Laporan Perjalanan', 'Setelah perjalanan usai, klik tombol Selesaikan Perjalanan di halaman detail. Untuk Dinas SITH, wajib mengisi biaya aktual dan mengunggah bukti LPJ. Mekanisme Reimburse langsung Selesai; Cash/Cashless akan dicek sisa dana.', 'bi-check2-circle', 'cyan'],
                ['Pengembalian Dana (jika ada)', 'Jika biaya aktual lebih kecil dari RAB yang disetujui dan mekanisme Cash/Cashless, Anda wajib mengunggah bukti pengembalian sisa dana. Tim Keuangan akan memverifikasi dan tiket resmi ditutup.', 'bi-cash-stack', 'amber'],
            ] as $i => [$judul, $deskripsi, $icon, $warna])
                @php
                    $colors = [
                        'blue' => [
                            'dot' => 'bg-blue-500',
                            'icon' => 'text-blue-600',
                            'badge' => 'bg-blue-50 text-blue-700 border-blue-200',
                        ],
                        'indigo' => [
                            'dot' => 'bg-indigo-500',
                            'icon' => 'text-indigo-600',
                            'badge' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                        ],
                        'violet' => [
                            'dot' => 'bg-violet-500',
                            'icon' => 'text-violet-600',
                            'badge' => 'bg-violet-50 text-violet-700 border-violet-200',
                        ],
                        'emerald' => [
                            'dot' => 'bg-emerald-500',
                            'icon' => 'text-emerald-600',
                            'badge' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                        ],
                        'green' => [
                            'dot' => 'bg-green-500',
                            'icon' => 'text-green-600',
                            'badge' => 'bg-green-50 text-green-700 border-green-200',
                        ],
                        'teal' => [
                            'dot' => 'bg-teal-500',
                            'icon' => 'text-teal-600',
                            'badge' => 'bg-teal-50 text-teal-700 border-teal-200',
                        ],
                        'cyan' => [
                            'dot' => 'bg-cyan-500',
                            'icon' => 'text-cyan-600',
                            'badge' => 'bg-cyan-50 text-cyan-700 border-cyan-200',
                        ],
                        'amber' => [
                            'dot' => 'bg-amber-500',
                            'icon' => 'text-amber-600',
                            'badge' => 'bg-amber-50 text-amber-700 border-amber-200',
                        ],
                    ];
                    $c = $colors[$warna];
                @endphp
                <li class="ml-6 pb-7 last:pb-0">
                    <span class="absolute -left-[9px] flex items-center justify-center w-4 h-4 rounded-full {{ $c['dot'] }} ring-4 ring-white"></span>
                    <div class="flex flex-wrap items-start justify-between gap-2">
                        <h4 class="font-bold text-gray-800 text-sm flex items-center gap-1.5">
                            <i class="bi {{ $icon }} {{ $c['icon'] }}"></i>
                            {{ $i + 1 }}. {{ $judul }}
                        </h4>
                        {{-- BADGE STATUS TELAH DIHAPUS --}}
                    </div>
                    <p class="mt-1 text-sm text-gray-500 leading-relaxed">{{ $deskripsi }}</p>
                </li>
            @endforeach
        </ol>
    </div>
</div>

                {{-- STATUS PERMOHONAN --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 bg-slate-50 flex items-center gap-2">
        <i class="bi bi-tags text-blue-600"></i>
        <h3 class="font-bold text-gray-800">Arti Setiap Status Permohonan</h3>
    </div>
    <div class="divide-y divide-gray-100">
        @foreach ([
            ['Menunggu Validasi Admin', 'bg-yellow-50 text-yellow-700 border-yellow-200', 'Pengajuan Anda sudah diterima dan sedang antre untuk ditinjau oleh Kepala Admin.'],
            ['Menunggu Proses SPSI', 'bg-amber-50 text-amber-700 border-amber-200', 'Admin telah menyetujui. Tim SPSI sedang mengalokasikan kendaraan dan pengemudi.'],
            ['Menunggu Proses Keuangan', 'bg-orange-50 text-orange-700 border-orange-200', 'Armada sudah dialokasikan. Tim Keuangan sedang menetapkan RAB dan mekanisme pembayaran.'],
            ['Menunggu Finalisasi', 'bg-purple-50 text-purple-700 border-purple-200', 'Semua proses selesai. Kepala Admin sedang melakukan pengecekan akhir sebelum menerbitkan surat izin.'],
            ['Disetujui', 'bg-blue-50 text-blue-700 border-blue-200', 'Permohonan resmi disetujui! Anda bisa mencetak surat izin perjalanan dan melaksanakan perjalanan.'],
            ['Menunggu Mulai Perjalanan', 'bg-yellow-50 text-yellow-700 border-yellow-200', 'Kunci kendaraan sudah diserahkan oleh SPSI. Segera klik tombol "Mulai Perjalanan" di halaman detail untuk memulai perjalanan secara resmi.'],
            ['Perjalanan Berlangsung', 'bg-teal-50 text-teal-700 border-teal-200', 'Perjalanan sedang berjalan. Setelah selesai, klik "Selesaikan Perjalanan" di halaman detail dan unggah LPJ jika kegiatan Dinas SITH.'],
            ['Menunggu Pengembalian Dana', 'bg-rose-50 text-rose-700 border-rose-200', 'Perjalanan selesai namun ada sisa dana yang harus dikembalikan. Unggah bukti pengembalian segera.'],
            ['Menunggu Verifikasi Pengembalian', 'bg-pink-50 text-pink-700 border-pink-200', 'Bukti pengembalian dana sudah diunggah dan sedang diverifikasi oleh Tim Keuangan.'],
            ['Selesai', 'bg-emerald-50 text-emerald-700 border-emerald-200', 'Seluruh proses perjalanan dan administrasi telah selesai. Tiket ditutup.'],
            ['Ditolak', 'bg-red-50 text-red-700 border-red-200', 'Permohonan tidak disetujui oleh Admin. Anda dapat mengajukan permohonan baru jika diperlukan.'],
        ] as [$status, $sc, $desc])
            <div class="px-6 py-3.5 flex items-start gap-3">
                <span class="mt-0.5 inline-block text-[10px] font-bold px-2 py-0.5 rounded-full border whitespace-nowrap flex-shrink-0 {{ $sc }}">
                    {{ $status }}
                </span>
                <p class="text-sm text-gray-600">{{ $desc }}</p>
            </div>
        @endforeach
    </div>
</div>

                {{-- FAQ PENGGUNA --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-slate-50 flex items-center gap-2">
                        <i class="bi bi-question-circle text-blue-600"></i>
                        <h3 class="font-bold text-gray-800">Pertanyaan Umum (FAQ)</h3>
                    </div>
                    <div class="divide-y divide-gray-100" x-data="{ open: null }">
                        @foreach ([
        ['Bagaimana cara membatalkan pengajuan?', 'Pembatalan pengajuan <strong>hanya bisa dilakukan selama status masih "Menunggu Validasi Admin"</strong>, yaitu sebelum diproses oleh Admin. Setelah Admin memvalidasi, pengajuan tidak dapat dibatalkan. Fitur pembatalan sedang dalam pengembangan dan akan segera tersedia di halaman detail permohonan Anda.'],
        ['Format nomor kontak yang benar seperti apa?', 'Nomor kontak harus diawali dengan <strong>+62</strong> (kode negara Indonesia) diikuti 8–15 digit angka tanpa spasi atau tanda hubung. Contoh: <code class="bg-gray-100 px-1 rounded text-xs">+6281234567890</code>'],
        ['File apa yang bisa diunggah untuk Surat Penugasan?', 'Format yang diterima adalah <strong>PDF, JPG, JPEG, atau PNG</strong> dengan ukuran maksimal <strong>2 MB</strong>. Pastikan dokumen terbaca dengan jelas sebelum diunggah.'],
        ['Apakah saya bisa mengajukan kendaraan lebih dari satu hari?', 'Ya. Isi waktu berangkat dan waktu kembali sesuai durasi kegiatan. Waktu kembali harus lebih lambat dari waktu berangkat.'],
        ['Kapan saya bisa mencetak surat izin perjalanan?', 'Tombol cetak baru tersedia ketika status permohonan sudah <strong>Disetujui</strong>. Buka halaman detail permohonan, lalu klik tombol "Cetak Dokumen". Surat akan terbuka di tab baru dan bisa dicetak atau disimpan sebagai PDF.'],
        ['Apa itu LPJ dan kapan harus diunggah?', 'LPJ (Laporan Pertanggungjawaban) adalah bukti penggunaan anggaran perjalanan. Dokumen ini wajib diunggah saat Anda mengklik <strong>"Selesaikan Perjalanan"</strong> di halaman detail, khusus untuk kegiatan <strong>Dinas SITH</strong>. Format yang diterima: PDF, JPG, JPEG, PNG (maks. 2MB).'],
        ['Kapan saya harus mengembalikan sisa dana?', 'Jika biaya perjalanan aktual lebih kecil dari RAB yang disetujui dan mekanisme pembayaran bukan Reimburse, maka status akan berubah menjadi <strong>"Menunggu Pengembalian Dana"</strong>. Anda wajib mengunggah bukti pengembalian (transfer/tanda terima) agar tiket bisa ditutup.'],
        ['Apakah saya mendapat notifikasi setiap ada perubahan status?', 'Ya. Sistem akan mengirimkan notifikasi ke akun Anda setiap kali ada perubahan status pada permohonan. Notifikasi bisa dilihat melalui ikon lonceng di pojok kanan atas.'],
    ] as $idx => [$q, $a])
                            <div x-data="{ open: false }" class="px-6 py-4">
                                <button @click="open = !open"
                                    class="w-full flex items-center justify-between gap-3 text-left">
                                    <span class="font-semibold text-sm text-gray-800">{{ $q }}</span>
                                    <i class="bi flex-shrink-0 text-gray-400 transition-transform duration-200"
                                        :class="open ? 'bi-dash-circle text-blue-500' : 'bi-plus-circle'"></i>
                                </button>
                                <div x-show="open" x-collapse class="mt-2">
                                    <p class="text-sm text-gray-600 leading-relaxed">{!! $a !!}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- =============================================
                 KEPALA ADMIN
            ============================================= --}}
            @elseif($role === 'kepala_admin')
                {{-- RINGKASAN TUGAS --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    @foreach ([['Validasi Permohonan', 'Tinjau dan kategorikan setiap pengajuan masuk sebelum diteruskan ke SPSI.', 'bi-clipboard2-check', 'blue'], ['Finalisasi Penerbitan', 'Konfirmasi akhir dan terbitkan surat izin setelah SPSI dan Keuangan selesai.', 'bi-send-check', 'purple'], ['Pantau Arsip', 'Lihat seluruh riwayat permohonan yang sudah diproses, disetujui, atau ditolak.', 'bi-archive', 'slate']] as [$judul, $desc, $icon, $warna])
                        @php
                            $map = [
                                'blue' => 'bg-blue-50 text-blue-600 border-blue-100',
                                'purple' => 'bg-purple-50 text-purple-600 border-purple-100',
                                'slate' => 'bg-slate-100 text-slate-600 border-slate-200',
                            ];
                        @endphp
                        <div
                            class="bg-white rounded-xl border {{ str_contains($map[$warna], 'blue') ? 'border-blue-100' : (str_contains($map[$warna], 'purple') ? 'border-purple-100' : 'border-slate-200') }} shadow-sm p-5">
                            <div
                                class="w-10 h-10 rounded-lg {{ $map[$warna] }} flex items-center justify-center mb-3">
                                <i class="bi {{ $icon }} text-lg"></i>
                            </div>
                            <h4 class="font-bold text-gray-800 text-sm mb-1">{{ $judul }}</h4>
                            <p class="text-xs text-gray-500 leading-relaxed">{{ $desc }}</p>
                        </div>
                    @endforeach
                </div>

                {{-- PANDUAN VALIDASI --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-blue-50 flex items-center gap-2">
                        <i class="bi bi-shield-check text-blue-600 text-lg"></i>
                        <h3 class="font-bold text-gray-800">Panduan: Validasi Permohonan Masuk</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <p class="text-sm text-gray-500">Menu <strong>Validasi Permohonan</strong> menampilkan semua
                            pengajuan dengan status <span
                                class="text-xs font-bold bg-yellow-50 text-yellow-700 border border-yellow-200 px-2 py-0.5 rounded-full">Menunggu
                                Validasi Admin</span>. Berikut langkah-langkahnya:</p>
                        <ol class="space-y-3">
                            @foreach ([
        ['Klik tombol <strong>"Validasi"</strong> pada baris permohonan yang ingin ditinjau.', 'bi-1-circle'],
        ['Periksa data pemohon: nama PIC, kontak, rute, jadwal, jumlah penumpang, anggaran, dan surat penugasan yang diunggah (klik "Lihat Dokumen" untuk membuka file).', 'bi-2-circle'],
        ['Pilih <strong>Kategori Kegiatan</strong>:<ul class="mt-1.5 ml-4 space-y-1 list-none"><li class="text-xs"><span class="font-bold text-green-700">Dinas SITH</span> → Biaya ditanggung instansi, akan melalui proses Keuangan.</li><li class="text-xs"><span class="font-bold text-gray-600">Non SITH</span> → Biaya pribadi pemohon, proses Keuangan dilewati otomatis dan anggaran di-nol-kan.</li></ul>', 'bi-3-circle'],
        ['(Opsional) Isi kolom <strong>Instruksi untuk SPSI</strong> jika ada arahan khusus terkait kendaraan atau pengemudi.', 'bi-4-circle'],
        ['Klik <strong>"Setujui & Teruskan ke SPSI"</strong> untuk meneruskan, atau <strong>"Tolak Pengajuan"</strong> jika tidak memenuhi syarat. Konfirmasi akan muncul sebelum penolakan final.', 'bi-5-circle'],
    ] as [$step, $ic])
                                <li class="flex items-start gap-3">
                                    <i class="bi {{ $ic }} text-blue-500 text-base flex-shrink-0 mt-0.5"></i>
                                    <p class="text-sm text-gray-700 leading-relaxed">{!! $step !!}</p>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                </div>

                {{-- PANDUAN FINALISASI --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-purple-50 flex items-center gap-2">
                        <i class="bi bi-patch-check text-purple-600 text-lg"></i>
                        <h3 class="font-bold text-gray-800">Panduan: Finalisasi Penerbitan Surat</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <p class="text-sm text-gray-500">Menu <strong>Finalisasi</strong> menampilkan permohonan dengan
                            status <span
                                class="text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200 px-2 py-0.5 rounded-full">Menunggu
                                Finalisasi</span>, yaitu permohonan yang sudah melalui proses SPSI dan/atau Keuangan dan
                            siap diterbitkan.</p>
                        <ol class="space-y-3">
                            @foreach ([['Klik tombol <strong>"Finalisasi"</strong> pada baris yang akan diterbitkan.', 'bi-1-circle'], ['Tinjau <strong>Rangkuman Akhir</strong>: pastikan data pemohon, kendaraan yang dialokasikan, pengemudi, RAB yang disetujui, dan mekanisme pembayaran sudah benar.', 'bi-2-circle'], ['Perhatikan kolom <strong>Mekanisme Bayar</strong>: untuk Non SITH akan tertulis "Ditanggung Pemohon" dan RAB bernilai 0.', 'bi-3-circle'], ['Klik <strong>"Setujui & Terbitkan Surat ke Pengguna"</strong>. Status berubah menjadi <span class="text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200 px-2 py-0.5 rounded-full">Disetujui</span> dan pengguna mendapat notifikasi secara otomatis.', 'bi-4-circle']] as [$step, $ic])
                                <li class="flex items-start gap-3">
                                    <i
                                        class="bi {{ $ic }} text-purple-500 text-base flex-shrink-0 mt-0.5"></i>
                                    <p class="text-sm text-gray-700 leading-relaxed">{!! $step !!}</p>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                </div>

                {{-- FAQ ADMIN --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-slate-50 flex items-center gap-2">
                        <i class="bi bi-question-circle text-blue-600"></i>
                        <h3 class="font-bold text-gray-800">Pertanyaan Umum (FAQ)</h3>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @foreach ([
        ['Apa bedanya Dinas SITH dan Non SITH?', '<strong>Dinas SITH</strong>: kegiatan resmi instansi, biaya ditanggung lembaga. Alurnya melewati proses Keuangan untuk penetapan RAB. <br><strong>Non SITH</strong>: kegiatan di luar kepentingan dinas (misalnya kegiatan mahasiswa mandiri), biaya ditanggung pemohon sendiri. Proses Keuangan dilewati otomatis dan anggaran otomatis di-nol-kan.'],
        ['Bagaimana jika permohonan yang sudah divalidasi ternyata perlu diubah?', 'Setelah divalidasi, data tidak bisa diubah melalui sistem. Hubungi Tim SPSI secara langsung jika ada koreksi sebelum armada dialokasikan.'],
        ['Di mana melihat semua riwayat permohonan yang sudah selesai?', 'Gunakan menu <strong>Arsip & Riwayat</strong> di sidebar. Menu ini menampilkan semua permohonan dengan status Disetujui, Ditolak, Menunggu Pengembalian Dana, Menunggu Verifikasi Pengembalian, dan Selesai.'],
        ['Apakah dashboard menampilkan notifikasi tugas yang menunggu?', 'Ya. Dashboard Admin menampilkan jumlah permohonan yang menunggu validasi dan yang menunggu finalisasi secara real-time. Sistem juga akan mengarahkan Anda ke antrean tugas terbanyak secara otomatis.'],
    ] as [$q, $a])
                            <div x-data="{ open: false }" class="px-6 py-4">
                                <button @click="open = !open"
                                    class="w-full flex items-center justify-between gap-3 text-left">
                                    <span class="font-semibold text-sm text-gray-800">{{ $q }}</span>
                                    <i class="bi flex-shrink-0 text-gray-400 transition-transform duration-200"
                                        :class="open ? 'bi-dash-circle text-blue-500' : 'bi-plus-circle'"></i>
                                </button>
                                <div x-show="open" x-collapse class="mt-2">
                                    <p class="text-sm text-gray-600 leading-relaxed">{!! $a !!}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- =============================================
                 SPSI
            ============================================= --}}
            @elseif($role === 'spsi')
                {{-- RINGKASAN TUGAS --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach ([['Alokasi Armada', 'Tugaskan kendaraan dan pengemudi untuk permohonan yang sudah divalidasi Admin.', 'bi-signpost-split', 'blue'], ['Monitoring Armada', 'Pantau seluruh armada yang sedang berjalan hingga perjalanan dinyatakan selesai.', 'bi-binoculars', 'slate']] as [$judul, $desc, $icon, $warna])
                        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                            <div
                                class="w-10 h-10 rounded-lg {{ $warna === 'blue' ? 'bg-blue-50 text-blue-600' : 'bg-slate-100 text-slate-600' }} flex items-center justify-center mb-3">
                                <i class="bi {{ $icon }} text-lg"></i>
                            </div>
                            <h4 class="font-bold text-gray-800 text-sm mb-1">{{ $judul }}</h4>
                            <p class="text-xs text-gray-500 leading-relaxed">{{ $desc }}</p>
                        </div>
                    @endforeach
                </div>

                {{-- PANDUAN ALOKASI --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-blue-50 flex items-center gap-2">
                        <i class="bi bi-truck-front-fill text-blue-600 text-lg"></i>
                        <h3 class="font-bold text-gray-800">Panduan: Alokasi Armada Kendaraan</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <p class="text-sm text-gray-500">Menu <strong>Penugasan Armada</strong> menampilkan semua
                            permohonan dengan status <span
                                class="text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200 px-2 py-0.5 rounded-full">Menunggu
                                Proses SPSI</span>. Ikuti langkah berikut untuk melakukan alokasi.</p>
                        <ol class="space-y-3">
                            @foreach ([
        ['Klik tombol <strong>"Alokasi Armada"</strong> pada baris permohonan yang akan diproses.', 'bi-1-circle'],
        ['Periksa <strong>Informasi Kegiatan</strong> di bagian atas: jenis kegiatan (Dinas SITH / Non-Dinas), kebutuhan awal pemohon, jumlah penumpang, dan jadwal perjalanan.', 'bi-2-circle'],
        ['Perhatikan <strong>Instruksi Admin</strong> jika ada (tampil dalam kotak kuning). Wajib diikuti.', 'bi-3-circle'],
        ['Pilih <strong>Sumber Armada</strong>:<ul class="mt-1.5 ml-4 space-y-1"><li class="text-xs"><span class="font-bold text-blue-700">Kampus</span> → Pilih dari daftar kendaraan aset internal yang tersedia.</li><li class="text-xs"><span class="font-bold text-orange-600">Vendor</span> → Pilih dari daftar kendaraan sewa vendor.</li></ul>', 'bi-4-circle'],
        ['Pilih <strong>Pengemudi</strong> dari daftar pengemudi yang tersedia (opsional — kosongkan jika pemohon mengendarai sendiri / lepas kunci).', 'bi-5-circle'],
        ['Isi <strong>Estimasi Biaya Operasional</strong> dalam Rupiah (wajib untuk Dinas SITH, otomatis 0 untuk Non SITH).', 'bi-6-circle'],
        ['Klik <strong>"Simpan Alokasi Armada"</strong>. Status permohonan akan diperbarui otomatis: Dinas SITH → Menunggu Proses Keuangan; Non SITH → Menunggu Finalisasi. Notifikasi dikirim ke pihak terkait.', 'bi-7-circle'],
    ] as [$step, $ic])
                                <li class="flex items-start gap-3">
                                    <i
                                        class="bi {{ $ic }} text-blue-500 text-base flex-shrink-0 mt-0.5"></i>
                                    <p class="text-sm text-gray-700 leading-relaxed">{!! $step !!}</p>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                </div>

                {{-- INFO KENDARAAN & ALUR SETELAH ALOKASI --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-5 py-3.5 border-b border-gray-100 bg-slate-50 flex items-center gap-2">
                            <i class="bi bi-car-front text-blue-600"></i>
                            <h3 class="font-bold text-gray-800 text-sm">Status Kendaraan & Pengemudi</h3>
                        </div>
                        <div class="p-5 space-y-3 text-sm text-gray-600">
                            <p>Saat armada dialokasikan, sistem otomatis mengubah status kendaraan menjadi
                                <strong>"Dipinjam"</strong> dan pengemudi menjadi <strong>"Bertugas"</strong>.
                            </p>
                            <p>Status kendaraan dan pengemudi akan kembali menjadi <strong>"Tersedia"</strong> secara
                                otomatis ketika pemohon mengklik <em>"Selesaikan Perjalanan"</em>.</p>
                            <p>Hanya kendaraan dengan status <strong>"Tersedia"</strong> yang tampil di dropdown
                                pilihan. Kendaraan dalam status <em>Maintenance</em> tidak akan muncul.</p>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-5 py-3.5 border-b border-gray-100 bg-slate-50 flex items-center gap-2">
                            <i class="bi bi-diagram-3 text-blue-600"></i>
                            <h3 class="font-bold text-gray-800 text-sm">Alur Setelah Alokasi SPSI</h3>
                        </div>
                        <div class="p-5 space-y-2 text-sm">
                            <div class="flex items-center gap-2">
                                <span
                                    class="text-xs font-bold bg-green-50 text-green-700 border border-green-200 px-2 py-0.5 rounded-full whitespace-nowrap">Dinas
                                    SITH</span>
                                <i class="bi bi-arrow-right text-gray-400"></i>
                                <span class="text-gray-600 text-xs">Tim Keuangan menetapkan RAB</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span
                                    class="text-xs font-bold bg-gray-50 text-gray-600 border border-gray-200 px-2 py-0.5 rounded-full whitespace-nowrap">Non
                                    SITH</span>
                                <i class="bi bi-arrow-right text-gray-400"></i>
                                <span class="text-gray-600 text-xs">Langsung ke Finalisasi Admin</span>
                            </div>
                            <hr class="my-2">
                            <p class="text-xs text-gray-500">Gunakan menu <strong>Pantauan & Riwayat Armada</strong>
                                untuk memantau kendaraan yang sedang bertugas hingga perjalanan ditutup.</p>
                        </div>
                    </div>
                </div>

                {{-- FAQ SPSI --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-slate-50 flex items-center gap-2">
                        <i class="bi bi-question-circle text-blue-600"></i>
                        <h3 class="font-bold text-gray-800">Pertanyaan Umum (FAQ)</h3>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @foreach ([['Tidak ada kendaraan kampus yang tersedia, apa yang dilakukan?', 'Pilih sumber armada <strong>Vendor</strong> dan pilih kendaraan dari daftar mitra vendor yang tersedia. Jika tidak ada kendaraan vendor pula, hubungi Kepala Admin untuk tindak lanjut.'], ['Apakah pengemudi wajib diisi?', 'Tidak wajib. Jika pemohon mengendarai sendiri atau menggunakan sistem lepas kunci, biarkan kolom pengemudi kosong (pilih opsi "— Tanpa Pengemudi —").'], ['Bagaimana jika ada instruksi khusus dari Admin?', 'Instruksi Admin akan tampil dalam kotak kuning di halaman alokasi. Baca dan ikuti instruksi tersebut sebelum menentukan pilihan kendaraan dan pengemudi.'], ['Apakah estimasi biaya harus diisi untuk kegiatan Non SITH?', 'Tidak. Untuk kegiatan Non SITH, kolom estimasi biaya tersembunyi dan nilainya otomatis 0 (nol) karena biaya ditanggung pemohon sendiri.']] as [$q, $a])
                            <div x-data="{ open: false }" class="px-6 py-4">
                                <button @click="open = !open"
                                    class="w-full flex items-center justify-between gap-3 text-left">
                                    <span class="font-semibold text-sm text-gray-800">{{ $q }}</span>
                                    <i class="bi flex-shrink-0 text-gray-400"
                                        :class="open ? 'bi-dash-circle text-blue-500' : 'bi-plus-circle'"></i>
                                </button>
                                <div x-show="open" x-collapse class="mt-2">
                                    <p class="text-sm text-gray-600 leading-relaxed">{!! $a !!}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- =============================================
                 KEUANGAN
            ============================================= --}}
            @elseif($role === 'keuangan')
                {{-- RINGKASAN TUGAS --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach ([['Persetujuan RAB', 'Tinjau estimasi biaya dari SPSI dan tetapkan RAB resmi serta mekanisme pembayaran.', 'bi-receipt-cutoff', 'blue'], ['Monitoring & Verifikasi', 'Pantau pengeluaran, verifikasi LPJ, dan konfirmasi pengembalian sisa dana pemohon.', 'bi-graph-up-arrow', 'emerald']] as [$judul, $desc, $icon, $warna])
                        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                            <div
                                class="w-10 h-10 rounded-lg {{ $warna === 'blue' ? 'bg-blue-50 text-blue-600' : 'bg-emerald-50 text-emerald-600' }} flex items-center justify-center mb-3">
                                <i class="bi {{ $icon }} text-lg"></i>
                            </div>
                            <h4 class="font-bold text-gray-800 text-sm mb-1">{{ $judul }}</h4>
                            <p class="text-xs text-gray-500 leading-relaxed">{{ $desc }}</p>
                        </div>
                    @endforeach
                </div>

                {{-- PANDUAN RAB --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-blue-50 flex items-center gap-2">
                        <i class="bi bi-cash-coin text-blue-600 text-lg"></i>
                        <h3 class="font-bold text-gray-800">Panduan: Persetujuan Anggaran (RAB)</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <p class="text-sm text-gray-500">Menu <strong>Persetujuan Anggaran</strong> menampilkan
                            permohonan Dinas SITH dengan status <span
                                class="text-xs font-bold bg-orange-50 text-orange-700 border border-orange-200 px-2 py-0.5 rounded-full">Menunggu
                                Proses Keuangan</span>. Hanya kategori Dinas SITH yang masuk ke antrian ini.</p>
                        <ol class="space-y-3">
                            @foreach ([
        ['Klik tombol <strong>"Proses RAB"</strong> pada baris permohonan yang akan ditinjau.', 'bi-1-circle'],
        ['Periksa <strong>Ringkasan dari SPSI</strong>: data pemohon, tujuan, kategori, kendaraan yang dialokasikan, dan estimasi biaya operasional dari SPSI. Nilai estimasi tersebut akan menjadi nilai default pada kolom RAB.', 'bi-2-circle'],
        ['Isi <strong>RAB Disetujui</strong>: sesuaikan nominal jika diperlukan. Nilai ini akan menjadi batas anggaran resmi yang digunakan untuk cek sisa dana setelah perjalanan.', 'bi-3-circle'],
        ['Pilih <strong>Mekanisme Pembayaran</strong>:<ul class="mt-1.5 ml-4 space-y-1"><li class="text-xs"><span class="font-bold">Cash (Tunai)</span> → Dana tunai diberikan kepada pemohon sebelum perjalanan.</li><li class="text-xs"><span class="font-bold">Cashless (Transfer/E-Toll)</span> → Pembayaran via transfer bank atau e-toll.</li><li class="text-xs"><span class="font-bold">Reimburse</span> → Pemohon menalangi biaya dulu, diganti setelah perjalanan berdasarkan bukti pengeluaran. Tidak ada pengembalian sisa dana.</li></ul>', 'bi-4-circle'],
        ['Klik <strong>"Setujui & Teruskan ke Admin"</strong>. Status berubah menjadi Menunggu Finalisasi dan Admin menerima notifikasi untuk melakukan penerbitan akhir.', 'bi-5-circle'],
    ] as [$step, $ic])
                                <li class="flex items-start gap-3">
                                    <i
                                        class="bi {{ $ic }} text-blue-500 text-base flex-shrink-0 mt-0.5"></i>
                                    <p class="text-sm text-gray-700 leading-relaxed">{!! $step !!}</p>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                </div>

                {{-- PANDUAN VERIFIKASI PENGEMBALIAN --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-emerald-50 flex items-center gap-2">
                        <i class="bi bi-cash-stack text-emerald-600 text-lg"></i>
                        <h3 class="font-bold text-gray-800">Panduan: Verifikasi Pengembalian Dana</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <p class="text-sm text-gray-500">Permohonan dengan mekanisme Cash atau Cashless yang memiliki
                            sisa dana (biaya aktual &lt; RAB) akan masuk ke menu <strong>Pantauan Anggaran</strong>
                            dengan status <span
                                class="text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200 px-2 py-0.5 rounded-full">Menunggu
                                Verifikasi Pengembalian</span> setelah pemohon mengunggah bukti.</p>
                        <ol class="space-y-3">
                            @foreach ([['Di menu <strong>Pantauan Anggaran</strong>, filter atau cari permohonan dengan status "Menunggu Verifikasi Pengembalian".', 'bi-1-circle'], ['Buka detail permohonan, periksa bukti pengembalian dana yang diunggah pemohon (foto/scan bukti transfer atau tanda terima).', 'bi-2-circle'], ['Bandingkan nominal bukti dengan selisih <em>RAB Disetujui − Biaya Aktual</em>.', 'bi-3-circle'], ['Jika sesuai, klik tombol <strong>"Verifikasi Pengembalian"</strong>. Status akan berubah menjadi <span class="text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 px-1 rounded">Selesai</span> dan pemohon mendapat notifikasi bahwa tiket resmi ditutup.', 'bi-4-circle']] as [$step, $ic])
                                <li class="flex items-start gap-3">
                                    <i
                                        class="bi {{ $ic }} text-emerald-500 text-base flex-shrink-0 mt-0.5"></i>
                                    <p class="text-sm text-gray-700 leading-relaxed">{!! $step !!}</p>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                </div>

                {{-- TABEL MEKANISME PEMBAYARAN --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-slate-50 flex items-center gap-2">
                        <i class="bi bi-credit-card text-blue-600"></i>
                        <h3 class="font-bold text-gray-800">Panduan Mekanisme Pembayaran</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-slate-50 border-b border-gray-200">
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Mekanisme</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Cara Kerja</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Pengembalian Sisa?</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr class="hover:bg-slate-50">
                                    <td class="px-4 py-3 font-bold text-gray-700">Cash (Tunai)</td>
                                    <td class="px-4 py-3 text-gray-600">Dana tunai diberikan ke pemohon sesuai RAB
                                        sebelum perjalanan.</td>
                                    <td class="px-4 py-3"><span
                                            class="text-xs font-bold text-rose-600 bg-rose-50 border border-rose-200 px-2 py-0.5 rounded-full">Ya,
                                            jika biaya aktual &lt; RAB</span></td>
                                </tr>
                                <tr class="hover:bg-slate-50">
                                    <td class="px-4 py-3 font-bold text-gray-700">Cashless (Transfer/E-Toll)</td>
                                    <td class="px-4 py-3 text-gray-600">Pembayaran langsung via transfer bank atau
                                        kartu e-toll instansi.</td>
                                    <td class="px-4 py-3"><span
                                            class="text-xs font-bold text-rose-600 bg-rose-50 border border-rose-200 px-2 py-0.5 rounded-full">Ya,
                                            jika biaya aktual &lt; RAB</span></td>
                                </tr>
                                <tr class="hover:bg-slate-50">
                                    <td class="px-4 py-3 font-bold text-gray-700">Reimburse</td>
                                    <td class="px-4 py-3 text-gray-600">Pemohon menalangi biaya lebih dulu. Keuangan
                                        mengganti berdasarkan bukti setelah perjalanan.</td>
                                    <td class="px-4 py-3"><span
                                            class="text-xs font-bold text-emerald-600 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-full">Tidak
                                            — langsung Selesai</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- FAQ KEUANGAN --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-slate-50 flex items-center gap-2">
                        <i class="bi bi-question-circle text-blue-600"></i>
                        <h3 class="font-bold text-gray-800">Pertanyaan Umum (FAQ)</h3>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @foreach ([
        ['Apakah permohonan Non SITH masuk ke antrian Keuangan?', 'Tidak. Permohonan dengan kategori <strong>Non SITH</strong> otomatis melewati proses Keuangan. Nilai RAB di-nol-kan dan langsung masuk ke tahap Finalisasi Admin. Permohonan Non SITH tidak akan muncul di menu Persetujuan RAB.'],
        ['Nilai RAB boleh diubah dari estimasi SPSI?', 'Ya. Nilai estimasi dari SPSI hanya sebagai acuan awal. Anda berwenang menyesuaikan nilai RAB resmi sesuai kebijakan keuangan instansi. Nilai yang Anda masukkan inilah yang digunakan sistem untuk menghitung sisa dana setelah perjalanan.'],
        ['Bagaimana jika pemohon tidak mengunggah bukti pengembalian?', 'Status permohonan akan tetap berada di <strong>"Menunggu Pengembalian Dana"</strong> sampai pemohon mengunggah buktinya. Anda dapat memantau antrean ini di menu Pantauan Anggaran dan menghubungi pemohon secara langsung jika diperlukan.'],
        ['Di mana bisa melihat total anggaran yang sudah disetujui?', 'Dashboard Keuangan menampilkan statistik <strong>Total RAB Disetujui</strong> secara kumulatif untuk semua permohonan yang sudah berstatus Disetujui atau Selesai.'],
    ] as [$q, $a])
                            <div x-data="{ open: false }" class="px-6 py-4">
                                <button @click="open = !open"
                                    class="w-full flex items-center justify-between gap-3 text-left">
                                    <span class="font-semibold text-sm text-gray-800">{{ $q }}</span>
                                    <i class="bi flex-shrink-0 text-gray-400"
                                        :class="open ? 'bi-dash-circle text-blue-500' : 'bi-plus-circle'"></i>
                                </button>
                                <div x-show="open" x-collapse class="mt-2">
                                    <p class="text-sm text-gray-600 leading-relaxed">{!! $a !!}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                {{-- FALLBACK UNTUK ROLE LAIN (SUPER ADMIN, DLL) --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 text-center text-gray-400">
                    <i class="bi bi-person-badge text-5xl block mb-3 text-gray-300"></i>
                    <p class="font-medium text-gray-500">Panduan khusus untuk peran Anda belum tersedia.</p>
                    <p class="text-sm text-gray-400 mt-1">Silakan hubungi administrator sistem.</p>
                </div>
            @endif

            {{-- FOOTER BANTUAN --}}
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex flex-col sm:flex-row items-center justify-between gap-3 text-center sm:text-left">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-blue-50 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="bi bi-headset text-blue-600"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-700">Butuh bantuan lebih lanjut?</p>
                        <p class="text-xs text-gray-400">Hubungi administrator sistem atau bagian IT.</p>
                    </div>
                </div>
                <p class="text-xs text-gray-300">Panduan diperbarui: {{ now()->format('d M Y') }}</p>
            </div>

        </div>
    </div>
</x-app-layout>

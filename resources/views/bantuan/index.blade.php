<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 flex items-center gap-2">
            <i class="bi bi-info-circle text-blue-600"></i> Pusat Bantuan & Panduan Penggunaan
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- HERO CARD (Gaya Enterprise: Putih dengan Border Kiri Biru) --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 border-l-4 border-l-blue-600 p-6 sm:p-8 flex items-center gap-5">
                <div class="hidden sm:flex p-4 bg-blue-50 rounded-full text-blue-600">
                    <i class="bi bi-person-raised-up text-4xl"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-black text-gray-800 mb-1">Halo, {{ Auth::user()->name }}!</h3>
                    <p class="text-gray-600 text-sm">Anda masuk sebagai <strong class="text-blue-700">{{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }}</strong>. Di bawah ini tersedia panduan dan FAQ khusus untuk peran Anda.</p>
                </div>
            </div>

            {{-- PANDUAN PER ROLE --}}
            @php
                $role = Auth::user()->role;
                $panduan = match($role) {
                    'pengguna' => [
                        ['bi bi-car-front', 'Cara Mengajukan Permohonan Kendaraan', [
                            'Klik tombol "+ Buat Pengajuan Baru" di halaman Dashboard.',
                            'Isi data Informasi Pemohon: Nama PIC, Nomor Kontak (format +62).',
                            'Pilih Titik Jemput dan Tujuan perjalanan.',
                            'Atur Waktu Keberangkatan dan Waktu Kembali menggunakan kalender.',
                            'Pilih kebutuhan tipe kendaraan dari daftar yang tersedia.',
                            'Isi Anggaran yang diajukan (bisa dikosongkan jika kegiatan Non-Dinas).',
                            'Unggah Surat Penugasan dalam format PDF, JPG, atau PNG (maks. 2MB).',
                            'Klik Kirim Permohonan. Notifikasi akan dikirim ke Admin.',
                        ]],
                        ['bi bi-card-checklist', 'Memantau Status Permohonan', [
                            'Masuk ke Dashboard → lihat kolom Status di tabel riwayat.',
                            'Klik "Lihat Detail" pada permohonan untuk melihat perkembangan.',
                            'Notifikasi real-time akan muncul di ikon lonceng di navbar atas.',
                            'Urutan status: Menunggu Validasi → SPSI → Keuangan → Finalisasi → Disetujui.',
                        ]],
                        ['bi bi-printer', 'Mencetak Surat Perjalanan (SPJ)', [
                            'SPJ hanya dapat dicetak setelah permohonan berstatus Disetujui.',
                            'Klik tombol "Cetak Surat Jalan" di Dashboard atau halaman Detail.',
                            'Halaman cetak akan terbuka di tab baru. Gunakan tombol cetak browser / Simpan PDF.',
                        ]],
                        ['bi bi-check2-circle', 'Menyelesaikan Perjalanan (LPJ)', [
                            'Setelah perjalanan selesai, buka halaman Detail permohonan.',
                            'Isi Total Biaya Aktual yang dikeluarkan dan unggah Struk/Nota.',
                            'Jika ada sisa anggaran (RAB > Aktual), sistem akan minta pengembalian dana.',
                            'Unggah bukti transfer pengembalian. Keuangan akan memverifikasi dan menutup tiket.',
                        ]],
                    ],
                    'kepala_admin' => [
                        ['bi bi-check-circle', 'Memvalidasi Permohonan Masuk', [
                            'Buka menu "Tugas Utama → Validasi Masuk".',
                            'Klik tombol "Validasi" pada permohonan yang masuk.',
                            'Tentukan Kategori Kegiatan: Dinas SITH (ditanggung instansi) atau Non-Dinas.',
                            'Non-Dinas akan otomatis memotong anggaran menjadi 0 dan bypass proses Keuangan.',
                            'Tambahkan Instruksi untuk SPSI jika diperlukan (opsional).',
                            'Klik "Setujui & Teruskan" atau "Tolak Pengajuan".',
                        ]],
                        ['bi bi-file-earmark-check', 'Finalisasi & Penerbitan Surat', [
                            'Buka menu "Tugas Utama → Finalisasi Surat".',
                            'Periksa rangkuman: armada, pengemudi, RAB, dan mekanisme pembayaran.',
                            'Klik "Setujui & Terbitkan ke Pengguna" untuk menyelesaikan alur.',
                            'Pengguna akan menerima notifikasi dan dapat mencetak SPJ.',
                        ]],
                        ['bi bi-bar-chart-line', 'Laporan & Arsip', [
                            'Buka menu Laporan Filter untuk melihat rekap semua permohonan.',
                            'Gunakan filter Tanggal dan Status untuk mempersempit data.',
                            'Klik "Export Excel" atau "Export PDF" untuk mengunduh laporan.',
                            'Arsip riwayat detail tersedia di "Tugas Utama → Arsip Riwayat".',
                        ]],
                    ],
                    'spsi' => [
                        ['bi bi-truck-front', 'Mengalokasikan Armada', [
                            'Buka menu "Operasional → Penugasan Armada".',
                            'Klik "Alokasi Armada" pada permohonan yang menunggu.',
                            'Pilih Sumber Armada: Mobil Kampus (aset internal) atau Sewa Vendor Luar.',
                            'Jika Kampus: pilih kendaraan dari daftar yang Tersedia.',
                            'Jika Vendor: pilih tipe kendaraan dari dropdown vendor.',
                            'Pilih Pengemudi (opsional, bisa kosong jika lepas kunci).',
                            'Isi Estimasi Biaya Operasional (tidak diperlukan untuk Non-Dinas).',
                            'Klik "Simpan Alokasi Armada".',
                        ]],
                        ['bi bi-eye', 'Memantau Penggunaan Armada', [
                            'Buka "Operasional → Pantauan Armada" untuk melihat semua armada yang sedang digunakan.',
                            'Status kendaraan Dipinjam akan otomatis kembali ke Tersedia setelah LPJ diselesaikan pengguna.',
                        ]],
                    ],
                    'keuangan' => [
                        ['bi bi-cash-coin', 'Menyetujui RAB Anggaran', [
                            'Buka menu "Keuangan → Persetujuan RAB".',
                            'Klik "Proses RAB" pada permohonan yang menunggu.',
                            'Periksa estimasi biaya dari SPSI.',
                            'Isi RAB Disetujui (bisa berbeda dari estimasi SPSI).',
                            'Pilih Mekanisme Pembayaran: Cash, Cashless, atau Reimburse.',
                            'Klik "Setujui Anggaran & Teruskan".',
                        ]],
                        ['bi bi-search', 'Verifikasi Pengembalian Dana', [
                            'Notifikasi akan masuk saat pemohon mengunggah bukti transfer.',
                            'Buka halaman Detail permohonan via menu "Verifikasi Refund" atau notifikasi.',
                            'Klik link "Lihat Bukti Transfer" untuk memverifikasi dokumen bank.',
                            'Jika valid, klik "Verifikasi & Tutup Tiket Selesai".',
                        ]],
                    ],
                    'super_admin' => [
                        ['bi bi-car-front', 'Mengelola Kendaraan (Master Data)', [
                            'Buka menu "Master Data → Mobil Internal".',
                            'Klik "+ Tambah Kendaraan" untuk menambah armada baru.',
                            'Isi Nama Kendaraan, Plat Nomor, Kapasitas, dan Status awal.',
                            'Status dapat diubah ke Maintenance jika kendaraan sedang dalam perawatan.',
                            'Tombol Edit dapat mengubah semua data termasuk status kapan saja.',
                            'Hapus kendaraan hanya diizinkan jika statusnya BUKAN Dipinjam.',
                        ]],
                        ['bi bi-person-vcard', 'Mengelola Pengemudi (Master Data)', [
                            'Buka menu "Master Data → Pengemudi".',
                            'Tambah, edit, dan hapus data pengemudi beserta kontaknya.',
                            'Hapus pengemudi hanya diizinkan jika statusnya BUKAN Bertugas.',
                        ]],
                        ['bi bi-people', 'Manajemen Pengguna & Role', [
                            'Buka menu "Master Data → Data Pengguna" untuk melihat semua akun.',
                            'Gunakan filter Search atau Role untuk mencari pengguna tertentu.',
                            'Klik Edit untuk mengubah nama, email, password, atau role pengguna.',
                            'Role yang tersedia: Pengguna, Kepala Admin, SPSI, Keuangan, Super Admin.',
                            'Akun Anda sendiri tidak dapat dihapus dari halaman ini demi keamanan sistem.',
                        ]],
                    ],
                    default => [],
                };
            @endphp

            @foreach($panduan as [$icon, $title, $steps])
                <div x-data="{ open: false }" class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                    <button @click="open = !open" class="w-full flex justify-between items-center p-5 text-left hover:bg-blue-50/50 transition focus:outline-none">
                        <div class="flex items-center gap-3">
                            <i class="{{ $icon }} text-2xl text-blue-600"></i>
                            <span class="font-bold text-gray-800">{{ $title }}</span>
                        </div>
                        <svg :class="open ? 'rotate-180' : ''" class="w-5 h-5 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-transition style="display:none" class="px-5 pb-5 border-t border-gray-100 bg-white">
                        <ol class="mt-4 space-y-3">
                            @foreach($steps as $idx => $step)
                                <li class="flex gap-3 text-sm text-gray-700 items-start">
                                    <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-700 rounded-full text-xs flex items-center justify-center font-bold mt-0.5">{{ $idx + 1 }}</span>
                                    <span class="leading-relaxed">{{ $step }}</span>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                </div>
            @endforeach

            {{-- FAQ UMUM --}}
            <div class="pt-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="bi bi-question-circle text-blue-600"></i> Pertanyaan yang Sering Diajukan (FAQ)
                </h3>
                <div class="space-y-3">
                    @php
                        $faqs = [
                            ['Bagaimana cara melihat notifikasi?', 'Klik ikon lonceng di pojok kanan atas navbar. Notifikasi baru akan ditandai dengan titik merah dan muncul sebagai pop-up toast. Anda juga dapat mengklik notifikasi untuk langsung menuju halaman terkait.'],
                            ['Apakah saya bisa membatalkan permohonan?', 'Permohonan yang sudah masuk tidak dapat dibatalkan sendiri oleh pengguna. Hubungi Admin untuk pembatalan. Namun Admin dapat menolak permohonan selama masih dalam status "Menunggu Validasi Admin".'],
                            ['Berapa lama proses persetujuan?', 'Bergantung pada kecepatan masing-masing bagian. Alur normal: Admin (1 hari kerja) → SPSI (1 hari) → Keuangan (1 hari) → Finalisasi Admin. Total estimasi 2-3 hari kerja.'],
                            ['Apa itu "Non SITH" vs "Dinas SITH"?', '"Dinas SITH" berarti kegiatan resmi instansi yang dibiayai anggaran SITH. "Non SITH" berarti kegiatan pribadi/mandiri dimana biaya ditanggung pemohon sendiri. Kategori ini ditentukan oleh Kepala Admin.'],
                            ['Apa yang terjadi jika biaya aktual lebih kecil dari RAB?', 'Selisih dana (RAB - Biaya Aktual) harus dikembalikan ke rekening SITH. Sistem akan meminta Anda mengunggah bukti transfer pada form LPJ, lalu Keuangan akan memverifikasinya.'],
                            ['Format file apa yang diterima untuk upload?', 'Surat Penugasan, Bukti LPJ, dan Bukti Pengembalian Dana: JPG, PNG, atau PDF. Ukuran maksimum 2MB per lampiran.'],
                            ['Bagaimana jika lupa password?', 'Gunakan fitur "Lupa Password" di halaman login jika tersedia, atau hubungi Super Admin untuk mereset kata sandi Anda.'],
                        ];
                    @endphp
                    @foreach($faqs as [$q, $a])
                        <div x-data="{ open: false }" class="bg-white border border-gray-100 rounded-lg shadow-sm overflow-hidden">
                            <button @click="open = !open" class="w-full flex justify-between items-center p-4 text-left hover:bg-gray-50 transition focus:outline-none">
                                <span class="font-semibold text-gray-800 text-sm">{{ $q }}</span>
                                <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 text-gray-400 flex-shrink-0 ml-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="open" x-transition style="display:none" class="px-4 pb-4 border-t border-gray-50 bg-gray-50/50">
                                <p class="text-sm text-gray-600 mt-3 leading-relaxed">{{ $a }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- KONTAK BANTUAN --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 mt-8">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="bi bi-headset text-blue-600"></i> Hubungi Tim Dukungan
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-gray-50 rounded-lg p-5 text-center border border-gray-200 hover:border-blue-300 transition">
                        <div class="text-blue-600 mb-2"><i class="bi bi-envelope-paper-fill text-3xl"></i></div>
                        <p class="font-bold text-gray-800 text-sm">Email Resmi</p>
                        <p class="text-blue-700 text-sm mt-1 font-medium">admin@instansi.ac.id</p>
                        <p class="text-xs text-gray-500 mt-1">Respon 1x24 jam kerja</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-5 text-center border border-gray-200 hover:border-green-300 transition">
                        <div class="text-green-500 mb-2"><i class="bi bi-whatsapp text-3xl"></i></div>
                        <p class="font-bold text-gray-800 text-sm">WhatsApp</p>
                        <a href="https://wa.me/6281234567890" target="_blank" class="text-green-600 font-medium text-sm mt-1 block hover:underline">+62 812-3456-7890</a>
                        <p class="text-xs text-gray-500 mt-1">Senin–Jumat, 08.00–16.00</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-5 text-center border border-gray-200 hover:border-gray-400 transition">
                        <div class="text-gray-500 mb-2"><i class="bi bi-building-fill text-3xl"></i></div>
                        <p class="font-bold text-gray-800 text-sm">Datang Langsung</p>
                        <p class="text-gray-700 text-sm mt-1 font-medium">Gedung Administrasi Lt. 2</p>
                        <p class="text-xs text-gray-500 mt-1">Senin–Jumat, 08.00–15.30</p>
                    </div>
                </div>
            </div>

            <p class="text-center text-xs text-gray-400 mt-8">Sistem Manajemen Kendaraan Operasional &copy; {{ now()->year }}</p>
        </div>
    </div>
</x-app-layout>
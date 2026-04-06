<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Pusat Bantuan & Panduan Penggunaan</h2>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- HERO CARD --}}
            <div class="bg-gradient-to-r from-purple-600 to-purple-800 rounded-2xl p-8 text-white shadow-lg">
                <h3 class="text-2xl font-black mb-2">Halo, {{ Auth::user()->name }}! 👋</h3>
                <p class="text-purple-100 text-sm">Anda masuk sebagai <strong class="text-white">{{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }}</strong>. Di bawah ini tersedia panduan dan FAQ khusus untuk peran Anda.</p>
            </div>

            {{-- PANDUAN PER ROLE --}}
            @php
                $role = Auth::user()->role;
                $panduan = match($role) {
                    'pengguna' => [
                        ['🚗', 'Cara Mengajukan Permohonan Kendaraan', [
                            'Klik tombol "+ Buat Pengajuan Baru" di halaman Dashboard.',
                            'Isi data Informasi Pemohon: Nama PIC, Nomor Kontak (format +62).',
                            'Pilih Titik Jemput dan Tujuan perjalanan.',
                            'Atur Waktu Keberangkatan dan Waktu Kembali menggunakan kalender.',
                            'Pilih kebutuhan tipe kendaraan dari daftar yang tersedia.',
                            'Isi Anggaran yang diajukan (bisa dikosongkan jika kegiatan Non-Dinas).',
                            'Unggah Surat Penugasan dalam format PDF, JPG, atau PNG (maks. 2MB).',
                            'Klik Kirim Permohonan. Notifikasi akan dikirim ke Admin.',
                        ]],
                        ['📋', 'Memantau Status Permohonan', [
                            'Masuk ke Dashboard → lihat kolom Status di tabel riwayat.',
                            'Klik "Lihat Detail" pada permohonan untuk melihat perkembangan.',
                            'Notifikasi real-time akan muncul di ikon lonceng (🔔) di navbar.',
                            'Urutan status: Menunggu Validasi → SPSI → Keuangan → Finalisasi → Disetujui.',
                        ]],
                        ['🖨️', 'Mencetak Surat Perjalanan (SPJ)', [
                            'SPJ hanya dapat dicetak setelah permohonan berstatus Disetujui.',
                            'Klik tombol "Cetak Surat Jalan" di Dashboard atau halaman Detail.',
                            'Halaman cetak akan terbuka di tab baru. Gunakan tombol "Cetak / Simpan PDF".',
                        ]],
                        ['✅', 'Menyelesaikan Perjalanan (LPJ)', [
                            'Setelah perjalanan selesai, buka halaman Detail permohonan.',
                            'Isi Total Biaya Aktual yang dikeluarkan dan unggah Struk/Nota.',
                            'Jika ada sisa anggaran (RAB > Aktual), sistem akan minta pengembalian dana.',
                            'Unggah bukti transfer pengembalian. Keuangan akan memverifikasi dan menutup tiket.',
                        ]],
                    ],
                    'kepala_admin' => [
                        ['✅', 'Memvalidasi Permohonan Masuk', [
                            'Buka menu "Tugas Administrasi → Validasi Masuk".',
                            'Klik tombol "Validasi" pada permohonan yang masuk.',
                            'Tentukan Kategori Kegiatan: Dinas SITH (ditanggung instansi) atau Non-Dinas.',
                            'Non-Dinas akan otomatis memotong anggaran menjadi 0 dan bypass proses Keuangan.',
                            'Tambahkan Instruksi untuk SPSI jika diperlukan (opsional).',
                            'Klik "Setujui & Teruskan" atau "Tolak Pengajuan".',
                        ]],
                        ['📝', 'Finalisasi & Penerbitan Surat', [
                            'Buka menu "Tugas Administrasi → Finalisasi".',
                            'Periksa rangkuman: armada, pengemudi, RAB, dan mekanisme pembayaran.',
                            'Klik "Setujui & Terbitkan ke Pengguna" untuk menyelesaikan alur.',
                            'Pengguna akan menerima notifikasi dan dapat mencetak SPJ.',
                        ]],
                        ['📊', 'Laporan & Arsip', [
                            'Buka menu Laporan untuk melihat rekap semua permohonan.',
                            'Gunakan filter Tanggal dan Status untuk mempersempit data.',
                            'Klik "Export Excel" atau "Export PDF" untuk mengunduh laporan.',
                            'Arsip riwayat tersedia di "Tugas Administrasi → Arsip Riwayat".',
                        ]],
                    ],
                    'spsi' => [
                        ['🚐', 'Mengalokasikan Armada', [
                            'Buka menu "Kelola Armada → Penugasan Armada".',
                            'Klik "Alokasi Armada" pada permohonan yang menunggu.',
                            'Pilih Sumber Armada: Mobil Kampus (aset internal) atau Sewa Vendor Luar.',
                            'Jika Kampus: pilih kendaraan dari daftar yang Tersedia.',
                            'Jika Vendor: pilih tipe kendaraan dari dropdown vendor.',
                            'Pilih Pengemudi (opsional, bisa kosong jika lepas kunci).',
                            'Isi Estimasi Biaya Operasional (tidak diperlukan untuk Non-Dinas).',
                            'Klik "Simpan Alokasi Armada".',
                        ]],
                        ['👁️', 'Memantau Penggunaan Armada', [
                            'Buka "Kelola Armada → Pantauan & Riwayat" untuk melihat semua armada yang sedang digunakan.',
                            'Status kendaraan Dipinjam akan otomatis kembali ke Tersedia setelah LPJ masuk.',
                        ]],
                    ],
                    'keuangan' => [
                        ['💰', 'Menyetujui RAB Anggaran', [
                            'Buka menu "Kelola Anggaran → Persetujuan RAB".',
                            'Klik "Proses RAB" pada permohonan yang menunggu.',
                            'Periksa estimasi biaya dari SPSI.',
                            'Isi RAB Disetujui (bisa berbeda dari estimasi SPSI).',
                            'Pilih Mekanisme Pembayaran: Cash, Cashless, atau Reimburse.',
                            'Klik "Setujui Anggaran & Teruskan".',
                        ]],
                        ['🔍', 'Verifikasi Pengembalian Dana', [
                            'Notifikasi akan masuk saat pemohon mengunggah bukti transfer.',
                            'Buka halaman Detail permohonan via dashboard atau notifikasi.',
                            'Klik link "Lihat Bukti Transfer" untuk memverifikasi.',
                            'Jika valid, klik "Verifikasi & Tutup Tiket Selesai".',
                        ]],
                    ],
                    'super_admin' => [
                        ['🚗', 'Mengelola Kendaraan (Master Data)', [
                            'Buka menu "Master Data → Kendaraan".',
                            'Klik "+ Tambah Kendaraan" untuk menambah armada baru.',
                            'Isi Nama Kendaraan, Plat Nomor, Kapasitas, dan Status awal.',
                            'Status dapat diubah ke Maintenance jika kendaraan sedang dalam perawatan.',
                            'Tombol Edit dapat mengubah semua data termasuk status kapan saja.',
                            'Hapus kendaraan hanya diizinkan jika statusnya BUKAN Dipinjam.',
                        ]],
                        ['👤', 'Mengelola Pengemudi (Master Data)', [
                            'Buka menu "Master Data → Pengemudi".',
                            'Tambah, edit, dan hapus data pengemudi beserta kontaknya.',
                            'Hapus pengemudi hanya diizinkan jika statusnya BUKAN Bertugas.',
                        ]],
                        ['👥', 'Manajemen Pengguna & Role', [
                            'Buka menu "Master Data → Pengguna" untuk melihat semua akun.',
                            'Gunakan filter Search atau Role untuk mencari pengguna tertentu.',
                            'Klik Edit untuk mengubah nama, email, password, atau role pengguna.',
                            'Role yang tersedia: Pengguna, Kepala Admin, SPSI, Keuangan, Super Admin.',
                            'Akun Anda sendiri tidak dapat dihapus dari halaman ini.',
                        ]],
                    ],
                    default => [],
                };
            @endphp

            @foreach($panduan as [$icon, $title, $steps])
                <div x-data="{ open: false }" class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <button @click="open = !open" class="w-full flex justify-between items-center p-5 text-left hover:bg-gray-50 transition focus:outline-none">
                        <div class="flex items-center gap-3">
                            <span class="text-2xl">{{ $icon }}</span>
                            <span class="font-bold text-gray-800">{{ $title }}</span>
                        </div>
                        <svg :class="open ? 'rotate-180' : ''" class="w-5 h-5 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-transition style="display:none" class="px-5 pb-5 border-t border-gray-100">
                        <ol class="mt-4 space-y-2">
                            @foreach($steps as $idx => $step)
                                <li class="flex gap-3 text-sm text-gray-700">
                                    <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-700 rounded-full text-xs flex items-center justify-center font-bold">{{ $idx + 1 }}</span>
                                    <span>{{ $step }}</span>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                </div>
            @endforeach

            {{-- FAQ UMUM --}}
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-4">❓ Pertanyaan yang Sering Diajukan (FAQ)</h3>
                <div class="space-y-3">
                    @php
                        $faqs = [
                            ['Bagaimana cara melihat notifikasi?', 'Klik ikon lonceng 🔔 di pojok kanan atas navbar. Notifikasi baru akan ditandai dengan titik merah dan muncul sebagai pop-up toast. Anda juga dapat mengklik notifikasi untuk langsung menuju halaman terkait.'],
                            ['Apakah saya bisa membatalkan permohonan?', 'Permohonan yang sudah masuk tidak dapat dibatalkan sendiri oleh pengguna. Hubungi Admin untuk pembatalan. Namun Admin dapat menolak permohonan selama masih dalam status "Menunggu Validasi Admin".'],
                            ['Berapa lama proses persetujuan?', 'Bergantung pada kecepatan masing-masing bagian. Alur normal: Admin (1 hari kerja) → SPSI (1 hari) → Keuangan (1 hari) → Finalisasi Admin. Total estimasi 2-3 hari kerja.'],
                            ['Apa itu "Non SITH" vs "Dinas SITH"?', '"Dinas SITH" berarti kegiatan resmi instansi yang dibiayai anggaran SITH. "Non SITH" berarti kegiatan pribadi/mandiri dimana biaya ditanggung pemohon sendiri. Kategori ini ditentukan oleh Kepala Admin.'],
                            ['Apa yang terjadi jika biaya aktual lebih kecil dari RAB?', 'Selisih dana (RAB - Biaya Aktual) harus dikembalikan ke rekening SITH. Sistem akan meminta Anda mengunggah bukti transfer, lalu Keuangan akan memverifikasinya.'],
                            ['Format file apa yang diterima untuk upload?', 'Surat Penugasan, Bukti LPJ, dan Bukti Pengembalian Dana: JPG, PNG, atau PDF. Ukuran maksimum 2MB per file.'],
                            ['Bagaimana jika lupa password?', 'Gunakan fitur "Lupa Password" di halaman login. Sistem akan mengirim link reset ke email yang terdaftar.'],
                        ];
                    @endphp
                    @foreach($faqs as [$q, $a])
                        <div x-data="{ open: false }" class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                            <button @click="open = !open" class="w-full flex justify-between items-center p-4 text-left hover:bg-gray-50 transition focus:outline-none">
                                <span class="font-semibold text-gray-800 text-sm">{{ $q }}</span>
                                <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 text-gray-400 flex-shrink-0 ml-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="open" x-transition style="display:none" class="px-4 pb-4 border-t border-gray-100">
                                <p class="text-sm text-gray-600 mt-3 leading-relaxed">{{ $a }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- KONTAK BANTUAN --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">📞 Hubungi Tim Dukungan</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-purple-50 rounded-lg p-4 text-center border border-purple-100">
                        <div class="text-2xl mb-2">📧</div>
                        <p class="font-bold text-gray-800 text-sm">Email</p>
                        <p class="text-purple-700 text-sm mt-1">admin@instansi.ac.id</p>
                        <p class="text-xs text-gray-500 mt-1">Respon 1x24 jam kerja</p>
                    </div>
                    <div class="bg-green-50 rounded-lg p-4 text-center border border-green-100">
                        <div class="text-2xl mb-2">📱</div>
                        <p class="font-bold text-gray-800 text-sm">WhatsApp</p>
                        <a href="https://wa.me/6281234567890" target="_blank" class="text-green-700 text-sm mt-1 block hover:underline">+62 812-3456-7890</a>
                        <p class="text-xs text-gray-500 mt-1">Senin–Jumat, 08.00–16.00</p>
                    </div>
                    <div class="bg-blue-50 rounded-lg p-4 text-center border border-blue-100">
                        <div class="text-2xl mb-2">🏢</div>
                        <p class="font-bold text-gray-800 text-sm">Datang Langsung</p>
                        <p class="text-blue-700 text-sm mt-1">Gedung Administrasi Lt. 2</p>
                        <p class="text-xs text-gray-500 mt-1">Senin–Jumat, 08.00–15.30</p>
                    </div>
                </div>
            </div>

            <p class="text-center text-xs text-gray-400">Sistem Manajemen Kendaraan Operasional &copy; {{ now()->year }}</p>
        </div>
    </div>
</x-app-layout>
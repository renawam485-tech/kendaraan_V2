<x-app-layout>
    <div class="py-6 bg-slate-50 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- BREADCRUMB --}}
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-5">
                <a href="{{ route('admin.validasi') }}" class="hover:text-blue-600 transition flex items-center gap-1">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <span class="text-gray-300">/</span>
                <span class="text-gray-700 font-medium">Validasi Permohonan</span>
                @if($permohonan->kode_permohonan)
                    <span class="text-gray-300">/</span>
                    <span class="font-black text-blue-700 bg-blue-50 border border-blue-200 px-2 py-0.5 rounded-md text-xs tracking-widest">{{ $permohonan->kode_permohonan }}</span>
                @endif
            </div>

            {{-- DATA PEMOHON --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-5">
                <div class="px-6 py-4 border-b border-gray-100 bg-slate-50 flex items-center justify-between">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        <i class="bi bi-person-lines-fill text-blue-600"></i> Data Permohonan
                    </h3>
                    <span class="text-xs text-gray-400">Diajukan {{ $permohonan->created_at->diffForHumans() }}</span>
                </div>
                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Nama PIC</p>
                        <p class="font-semibold text-gray-800">{{ $permohonan->nama_pic }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $permohonan->kontak_pic }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Kebutuhan Kendaraan</p>
                        <p class="font-semibold text-gray-800">{{ $permohonan->kendaraan_dibutuhkan }}</p>
                        <p class="text-xs text-gray-500"><i class="bi bi-people mr-0.5"></i>{{ $permohonan->jumlah_penumpang }} orang</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Rute</p>
                        <p class="text-sm text-gray-700">{{ $permohonan->titik_jemput }}</p>
                        <p class="text-sm text-gray-500"><i class="bi bi-arrow-right mr-0.5"></i>{{ $permohonan->tujuan }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Jadwal</p>
                        <p class="text-sm font-medium text-gray-700">{{ \Carbon\Carbon::parse($permohonan->waktu_berangkat)->format('d M Y, H:i') }}</p>
                        <p class="text-xs text-gray-500">s/d {{ \Carbon\Carbon::parse($permohonan->waktu_kembali)->format('d M Y, H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Anggaran Diajukan</p>
                        <p class="font-bold text-gray-800">Rp {{ number_format($permohonan->anggaran_diajukan,0,',','.') }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Surat Penugasan</p>
                        @if($permohonan->file_surat_penugasan)
                            <a href="{{ asset('storage/'.$permohonan->file_surat_penugasan) }}" target="_blank"
                               class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200 px-3 py-1.5 rounded-lg transition">
                                <i class="bi bi-file-earmark-pdf"></i> Lihat Dokumen
                            </a>
                        @else
                            <span class="text-gray-400 italic text-sm">Tidak ada</span>
                        @endif
                    </div>
                    @if($permohonan->catatan_pemohon)
                        <div class="sm:col-span-2 lg:col-span-3">
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Catatan dari Pemohon</p>
                            <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 text-sm text-amber-800">
                                <i class="bi bi-chat-quote mr-1"></i>{{ $permohonan->catatan_pemohon }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- FORM VALIDASI --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-slate-50 flex items-center gap-2">
                    <i class="bi bi-pencil-square text-blue-600"></i>
                    <h3 class="font-bold text-gray-800">Keputusan Admin</h3>
                </div>
                <div class="p-6">
                    <form action="{{ route('permohonan.validasi_admin_proses', $permohonan->id) }}" method="POST" class="space-y-5">
                        @csrf @method('PUT')

                        @if($errors->any())
                            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                <p class="text-sm font-bold text-red-700 mb-1 flex items-center gap-1"><i class="bi bi-exclamation-triangle"></i> Perbaiki kesalahan:</p>
                                <ul class="text-sm text-red-600 list-disc list-inside space-y-0.5">
                                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                                </ul>
                            </div>
                        @endif

                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-2">Kategori Kegiatan <span class="text-red-500">*</span></label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach([
                                    ['Dinas SITH','Ditanggung Instansi','bi-building-check','text-green-600','bg-green-50 border-green-300'],
                                    ['Non SITH','Biaya Pribadi Pemohon','bi-person-check','text-gray-600','bg-gray-50 border-gray-300'],
                                ] as [$val,$desc,$icon,$tc,$bc])
                                    <label class="flex items-start gap-3 p-4 rounded-xl border-2 cursor-pointer transition hover:shadow-md {{ $bc }} has-[:checked]:ring-2 has-[:checked]:ring-blue-400">
                                        <input type="radio" name="kategori_kegiatan" value="{{ $val }}"
                                            {{ old('kategori_kegiatan') === $val ? 'checked' : '' }}
                                            class="mt-0.5 text-blue-600 focus:ring-blue-500">
                                        <div>
                                            <p class="font-bold text-gray-800 text-sm flex items-center gap-1.5"><i class="bi {{ $icon }} {{ $tc }}"></i> {{ $val }}</p>
                                            <p class="text-xs text-gray-500 mt-0.5">{{ $desc }}</p>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            <p class="text-xs text-gray-500 mt-2"><i class="bi bi-info-circle mr-1"></i>Memilih <strong>Non SITH</strong> akan otomatis mem-bypass proses Keuangan dan menol-kan anggaran.</p>
                        </div>

                        <div>
                            <label for="rekomendasi_admin" class="block text-sm font-bold text-gray-800 mb-1.5">Instruksi untuk SPSI <span class="text-gray-400 font-normal">(opsional)</span></label>
                            <textarea id="rekomendasi_admin" name="rekomendasi_admin" rows="3"
                                placeholder="Contoh: Gunakan HiAce, prioritaskan pengemudi senior..."
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm transition">{{ old('rekomendasi_admin') }}</textarea>
                        </div>

                        <div class="flex items-center gap-3 pt-2">
                            <button type="submit" name="status_permohonan" value="Menunggu Proses SPSI"
                                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-lg shadow-sm transition text-sm">
                                <i class="bi bi-check2-circle"></i> Setujui & Teruskan ke SPSI
                            </button>
                            <button type="submit" name="status_permohonan" value="Ditolak"
                                onclick="return confirm('Yakin ingin MENOLAK permohonan ini?')"
                                class="inline-flex items-center gap-2 bg-white hover:bg-red-50 text-red-600 border border-red-300 font-bold py-2.5 px-6 rounded-lg transition text-sm">
                                <i class="bi bi-x-circle"></i> Tolak Pengajuan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
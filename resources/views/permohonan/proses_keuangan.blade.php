<x-app-layout>
    <div class="py-6 bg-slate-50 min-h-screen">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex items-center gap-2 text-sm text-gray-500 mb-5">
                <a href="{{ route('keuangan.rab') }}" class="hover:text-blue-600 transition flex items-center gap-1">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <span class="text-gray-300">/</span>
                <span class="text-gray-700 font-medium">Persetujuan RAB</span>
                @if($permohonan->kode_permohonan)
                    <span class="text-gray-300">/</span>
                    <span class="font-black text-blue-700 bg-blue-50 border border-blue-200 px-2 py-0.5 rounded-md text-xs tracking-widest">{{ $permohonan->kode_permohonan }}</span>
                @endif
            </div>

            {{-- RINGKASAN SPSI --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-5">
                <div class="px-6 py-4 border-b border-gray-100 bg-slate-50 flex items-center gap-2">
                    <i class="bi bi-receipt text-blue-600"></i>
                    <h3 class="font-bold text-gray-800 text-sm">Ringkasan dari SPSI</h3>
                </div>
                <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Pemohon</p>
                        <p class="font-semibold text-gray-800">{{ $permohonan->nama_pic }}</p>
                        <p class="text-xs text-gray-500 mt-0.5"><i class="bi bi-geo-alt mr-0.5"></i>{{ $permohonan->tujuan }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Kategori</p>
                        @if($permohonan->kategori_kegiatan)
                            <span class="inline-block text-xs font-bold px-2.5 py-1 rounded-md border {{ $permohonan->kategori_kegiatan === 'Dinas SITH' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-gray-50 text-gray-600 border-gray-200' }}">{{ $permohonan->kategori_kegiatan }}</span>
                        @endif
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Armada Dialokasikan</p>
                        @if($permohonan->kendaraan_id)
                            <p class="font-semibold text-gray-800 text-sm">{{ $permohonan->kendaraan->nama_kendaraan }}</p>
                            <p class="text-xs text-gray-500">{{ $permohonan->kendaraan->plat_nomor }}</p>
                        @elseif($permohonan->kendaraan_vendor)
                            <p class="font-semibold text-gray-800 text-sm">{{ $permohonan->kendaraan_vendor }}</p>
                            <span class="text-[10px] font-bold text-orange-600 bg-orange-50 border border-orange-100 px-1 rounded">VENDOR</span>
                        @endif
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Pengemudi</p>
                        <p class="font-medium text-gray-700 text-sm">{{ $permohonan->pengemudi->nama_pengemudi ?? 'Tanpa Pengemudi' }}</p>
                    </div>
                    <div class="sm:col-span-2 bg-blue-50 border border-blue-200 rounded-xl p-4">
                        <p class="text-xs font-semibold text-blue-600 uppercase tracking-wider mb-1">Estimasi Biaya dari SPSI</p>
                        <p class="text-2xl font-black text-gray-900">Rp {{ number_format($permohonan->estimasi_biaya_operasional,0,',','.') }}</p>
                        <p class="text-xs text-blue-600 mt-0.5">Gunakan nilai ini sebagai acuan RAB atau sesuaikan di bawah</p>
                    </div>
                </div>
            </div>

            {{-- FORM RAB --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-slate-50 flex items-center gap-2">
                    <i class="bi bi-pen-fill text-blue-600"></i>
                    <h3 class="font-bold text-gray-800 text-sm">Form Persetujuan RAB</h3>
                </div>
                <div class="p-6">
                    <form action="{{ route('permohonan.proses_keuangan_submit', $permohonan->id) }}" method="POST" class="space-y-5">
                        @csrf @method('PUT')

                        @if($errors->any())
                            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                <p class="text-sm font-bold text-red-700 mb-1"><i class="bi bi-exclamation-triangle mr-1"></i>Perbaiki kesalahan:</p>
                                <ul class="text-sm text-red-600 list-disc list-inside">
                                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-bold text-gray-800 mb-1.5">RAB Disetujui (Rp) <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm font-medium">Rp</span>
                                    <input type="number" name="rab_disetujui" required min="0"
                                        value="{{ old('rab_disetujui', $permohonan->estimasi_biaya_operasional) }}"
                                        class="w-full pl-10 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm transition">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-800 mb-1.5">Mekanisme Pembayaran <span class="text-red-500">*</span></label>
                                <select name="mekanisme_pembayaran" required
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm transition">
                                    <option value="">— Pilih Mekanisme —</option>
                                    @foreach(['Cash (Tunai)' => 'Cash (Uang Tunai)', 'Cashless (Transfer/E-Toll)' => 'Cashless (Transfer / E-Toll)', 'Reimburse' => 'Reimburse (Ditalangi Pemohon)'] as $v => $l)
                                        <option value="{{ $v }}" {{ old('mekanisme_pembayaran') === $v ? 'selected' : '' }}>{{ $l }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
                            <button type="submit"
                                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-lg shadow-sm transition text-sm">
                                <i class="bi bi-check2-all"></i> Setujui & Teruskan ke Admin
                            </button>
                            <a href="{{ route('keuangan.rab') }}" class="text-sm text-gray-500 hover:text-gray-700 transition">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
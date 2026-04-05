<x-app-layout>
    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 border-t-4 border-purple-600">
                <div class="p-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Finalisasi Penerbitan Surat (Langkah Akhir)</h2>

                    <div class="bg-gray-100 p-6 rounded-md mb-6 text-sm">
                        <h3 class="font-bold text-lg mb-4">Rangkuman Sistem:</h3>
                        <ul class="list-disc pl-5 space-y-2">
                            <li><strong>Pemohon:</strong> {{ $permohonan->nama_pic }} (Rute: {{ $permohonan->tujuan }})
                            </li>
                            <li><strong>Kategori & Sumber Dana:</strong> <span
                                    class="text-purple-600 font-bold">{{ $permohonan->kategori_kegiatan }}</span>
                                ({{ $permohonan->anggaran_diajukan }})</li>
                            <li>
                                <strong>Kendaraan Assigned:</strong>
                                @if ($permohonan->kendaraan_id)
                                    {{ $permohonan->kendaraan->nama_kendaraan }}
                                    ({{ $permohonan->kendaraan->plat_nomor }})
                                @else
                                    {{ $permohonan->kendaraan_vendor }} <span
                                        class="text-xs text-orange-600 font-bold">(VENDOR LUAR)</span>
                                @endif
                            </li>
                            <li><strong>Pengemudi:</strong>
                                {{ $permohonan->pengemudi->nama_pengemudi ?? 'Tanpa Supir' }}</li>
<li>
                                <strong>RAB Disetujui Keuangan:</strong> 
                                @if($permohonan->kategori_kegiatan === 'Non SITH')
                                    <span class="text-gray-500 italic">Rp 0 (Bypass Keuangan)</span>
                                @else
                                    Rp {{ number_format($permohonan->rab_disetujui, 0, ',', '.') }}
                                @endif
                            </li>
                            <li>
                                <strong>Mekanisme Pembayaran:</strong> 
                                @if($permohonan->mekanisme_pembayaran)
                                    {{ $permohonan->mekanisme_pembayaran }}
                                @elseif($permohonan->kategori_kegiatan === 'Non SITH')
                                    <span class="text-xs text-orange-700 font-bold bg-orange-50 px-2 py-0.5 rounded border border-orange-200">Biaya Ditanggung Pemohon (Non-Dinas)</span>
                                @else
                                    <span class="text-gray-400 italic">Belum ditentukan</span>
                                @endif
                            </li>
                        </ul>
                    </div>

                    <form action="{{ route('permohonan.finalisasi_admin_submit', $permohonan->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <p class="text-gray-600 mb-4 text-sm">Dengan menekan tombol di bawah ini, Anda memastikan semua
                            data benar dan informasi akan diteruskan secara lengkap ke Dasbor Pengguna.</p>

                        <button type="submit"
                            class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-6 rounded shadow text-center">
                            Setujui & Terbitkan ke Pengguna
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

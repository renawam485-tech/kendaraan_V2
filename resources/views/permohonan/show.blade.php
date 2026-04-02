<x-app-layout>
    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-8 border-b border-gray-200">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Detail Permohonan Anda</h2>
                    <p class="mb-6">Status: <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-sm font-semibold rounded-full">{{ $permohonan->status_permohonan }}</span></p>

                    @if($permohonan->status_permohonan === 'Disetujui' || $permohonan->status_permohonan === 'Selesai')
                    <div class="bg-green-50 border border-green-200 p-6 rounded-md mb-6">
                        <h3 class="font-bold text-green-800 text-lg mb-2">Informasi Kendaraan (Disetujui)</h3>
                        <p class="text-sm"><strong>Mobil:</strong> {{ $permohonan->kendaraan->nama_kendaraan ?? '-' }} ({{ $permohonan->kendaraan->plat_nomor ?? '-' }})</p>
                        <p class="text-sm"><strong>Pengemudi:</strong> {{ $permohonan->pengemudi->nama_pengemudi ?? 'Tanpa Pengemudi' }} ({{ $permohonan->pengemudi->kontak ?? '-' }})</p>
                        <p class="text-sm"><strong>Mekanisme Pembayaran:</strong> {{ $permohonan->mekanisme_pembayaran }}</p>
                    </div>
                    @endif

                    @if($permohonan->status_permohonan === 'Disetujui')
                    <form action="{{ route('permohonan.selesai', $permohonan->id) }}" method="POST" class="mt-6">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded shadow" onclick="return confirm('Selesaikan perjalanan ini?')">
                            Selesaikan Perjalanan (Klik jika sudah selesai)
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
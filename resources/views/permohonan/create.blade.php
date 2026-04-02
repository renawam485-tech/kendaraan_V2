<x-app-layout>
    <div class="py-10 bg-[#F0EBFA] min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            <form action="{{ route('permohonan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="bg-white overflow-hidden shadow sm:rounded-lg border-t-8 border-purple-600 mb-4">
                    <div class="p-8">
                        <h1 class="text-3xl font-normal text-gray-800 mb-2">Formulir Permohonan Kendaraan SITH</h1>
                        <p class="text-gray-600 text-sm">Silakan isi data dengan lengkap untuk pengajuan peminjaman/sewa kendaraan sesuai prosedur.</p>
                        <p class="text-red-500 text-xs mt-4">* Menunjukkan pertanyaan yang wajib diisi</p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow sm:rounded-lg mb-4 hover:border-l-4 hover:border-purple-500 transition-all">
                    <div class="p-6">
                        <label class="block font-normal text-gray-800 text-base mb-4">Nama / PIC Pemohon <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_pic" required placeholder="Jawaban Anda"
                            class="w-full md:w-1/2 border-0 border-b border-gray-300 focus:ring-0 focus:border-purple-600 px-0 py-2 bg-transparent">
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow sm:rounded-lg mb-4 hover:border-l-4 hover:border-purple-500 transition-all">
                    <div class="p-6">
                        <label class="block font-normal text-gray-800 text-base mb-4">Kontak (No HP / WhatsApp) <span class="text-red-500">*</span></label>
                        <input type="text" name="kontak_pic" required placeholder="Jawaban Anda"
                            class="w-full md:w-1/2 border-0 border-b border-gray-300 focus:ring-0 focus:border-purple-600 px-0 py-2 bg-transparent">
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow sm:rounded-lg mb-4 hover:border-l-4 hover:border-purple-500 transition-all">
                    <div class="p-6">
                        <label class="block font-normal text-gray-800 text-base mb-4">Kendaraan yang dibutuhkan <span class="text-red-500">*</span></label>
                        <input type="text" name="kendaraan_dibutuhkan" required placeholder="Misal: Minibus / Hiace / Innova"
                            class="w-full md:w-1/2 border-0 border-b border-gray-300 focus:ring-0 focus:border-purple-600 px-0 py-2 bg-transparent">
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow sm:rounded-lg mb-4 hover:border-l-4 hover:border-purple-500 transition-all">
                    <div class="p-6">
                        <label class="block font-normal text-gray-800 text-base mb-4">Jumlah Penumpang <span class="text-red-500">*</span></label>
                        <input type="number" name="jumlah_penumpang" min="1" required placeholder="Jawaban Anda"
                            class="w-full md:w-1/4 border-0 border-b border-gray-300 focus:ring-0 focus:border-purple-600 px-0 py-2 bg-transparent">
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow sm:rounded-lg mb-4 hover:border-l-4 hover:border-purple-500 transition-all">
                    <div class="p-6">
                        <label class="block font-normal text-gray-800 text-base mb-4">Titik Jemput <span class="text-red-500">*</span></label>
                        <input type="text" name="titik_jemput" required placeholder="Jawaban Anda"
                            class="w-full md:w-2/3 border-0 border-b border-gray-300 focus:ring-0 focus:border-purple-600 px-0 py-2 bg-transparent">
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow sm:rounded-lg mb-4 hover:border-l-4 hover:border-purple-500 transition-all">
                    <div class="p-6">
                        <label class="block font-normal text-gray-800 text-base mb-4">Tujuan <span class="text-red-500">*</span></label>
                        <input type="text" name="tujuan" required placeholder="Jawaban Anda"
                            class="w-full md:w-2/3 border-0 border-b border-gray-300 focus:ring-0 focus:border-purple-600 px-0 py-2 bg-transparent">
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow sm:rounded-lg mb-4 hover:border-l-4 hover:border-purple-500 transition-all">
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block font-normal text-gray-800 text-base mb-4">Waktu Keberangkatan <span class="text-red-500">*</span></label>
                            <input type="datetime-local" name="waktu_berangkat" required 
                                class="w-full border-0 border-b border-gray-300 focus:ring-0 focus:border-purple-600 px-0 py-2 text-gray-700">
                        </div>
                        <div>
                            <label class="block font-normal text-gray-800 text-base mb-4">Waktu Kembali <span class="text-red-500">*</span></label>
                            <input type="datetime-local" name="waktu_kembali" required 
                                class="w-full border-0 border-b border-gray-300 focus:ring-0 focus:border-purple-600 px-0 py-2 text-gray-700">
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow sm:rounded-lg mb-4 hover:border-l-4 hover:border-purple-500 transition-all">
                    <div class="p-6">
                        <label class="block font-normal text-gray-800 text-base mb-4">Anggaran yang digunakan <span class="text-red-500">*</span></label>
                        <input type="text" name="anggaran_diajukan" required placeholder="Misal: Dana Penelitian / Mandiri"
                            class="w-full md:w-1/2 border-0 border-b border-gray-300 focus:ring-0 focus:border-purple-600 px-0 py-2 bg-transparent">
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow sm:rounded-lg mb-6 hover:border-l-4 hover:border-purple-500 transition-all">
                    <div class="p-6">
                        <label class="block font-normal text-gray-800 text-base mb-4">Upload Surat Penugasan (PDF/JPG, Max 2MB) <span class="text-red-500">*</span></label>
                        <input type="file" name="file_surat" required 
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                    </div>
                </div>

                <div class="flex justify-between items-center mb-10">
                    <button type="submit" class="bg-purple-700 hover:bg-purple-800 text-white font-medium py-2 px-6 rounded shadow transition-colors">
                        Kirim
                    </button>
                    <button type="reset" class="text-purple-700 hover:bg-purple-50 font-medium py-2 px-4 rounded transition-colors">
                        Kosongkan formulir
                    </button>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>
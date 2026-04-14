<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            <i class="bi bi-person-circle text-blue-600 mr-2"></i> Detail Pengguna
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                
                <div class="p-6 border-b border-gray-100 bg-gray-50">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-2xl font-bold">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">{{ $user->name }}</h3>
                            <p class="text-gray-500">{{ $user->email }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider">Role / Hak Akses</label>
                            <p class="mt-1 text-gray-800 font-medium">
                                {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider">Tanggal Terdaftar</label>
                            <p class="mt-1 text-gray-800">{{ $user->created_at->format('d F Y H:i') }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider">Terakhir Update</label>
                            <p class="mt-1 text-gray-800">{{ $user->updated_at->format('d F Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-6 border-t border-gray-100 bg-gray-50 flex gap-3">
                    <a href="{{ route('spsi.users.index') }}" 
                       class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-5 rounded-lg text-sm transition">
                        <i class="bi bi-arrow-left mr-1"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
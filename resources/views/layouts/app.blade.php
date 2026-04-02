<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')
        @isset($header)
            <header class="bg-white shadow"><div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">{{ $header }}</div></header>
        @endisset
        <main>{{ $slot }}</main>
    </div>

    @auth
    <div x-data="{ 
            open: false, 
            bacaSemua() { fetch('{{ route('notif.baca_semua') }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } }).then(() => window.location.reload()); },
            hapusTerbaca() { fetch('{{ route('notif.hapus_terbaca') }}', { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } }).then(() => window.location.reload()); }
         }" @open-notif-panel.window="open = true">
         <div x-show="open" class="fixed inset-0 bg-black bg-opacity-30 z-40" @click="open = false"></div>
         <div class="fixed top-0 right-0 h-full w-80 bg-white shadow-2xl z-50 transform transition-transform" :class="open ? 'translate-x-0' : 'translate-x-full'">
              <div class="p-4 border-b flex justify-between items-center bg-gray-50">
                  <h3 class="font-bold">Notifikasi ({{ auth()->user()->unreadNotifications->count() }})</h3>
                  <button @click="open = false" class="text-gray-400 text-2xl font-bold">&times;</button>
              </div>
              <div class="flex border-b text-xs">
                  <button @click="bacaSemua()" class="flex-1 py-3 text-blue-600 font-bold border-r transition">✓ Baca Semua</button>
                  <button @click="hapusTerbaca()" class="flex-1 py-3 text-red-600 font-bold transition">🗑 Hapus Terbaca</button>
              </div>
              <div class="flex-1 overflow-y-auto">
                  @forelse(auth()->user()->notifications as $notif)
                     <div class="p-4 border-b {{ $notif->read_at ? 'opacity-50' : 'bg-purple-50' }}">
                         <div class="font-bold text-xs">{{ $notif->data['status'] ?? 'Info' }}</div>
                         <div class="text-sm">{{ $notif->data['pesan'] }}</div>
                     </div>
                  @empty
                     <div class="p-8 text-center text-gray-400 text-sm">Kosong.</div>
                  @endforelse
              </div>
         </div>
    </div>

    <div id="toast-container" class="fixed bottom-5 right-5 z-50 flex flex-col gap-3"></div>
    <audio id="notif-sound" src="{{ asset('sounds/notif.mp3') }}" preload="auto"></audio>

    <script type="module">
        window.showCompactToast = function(title, message) {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className = 'bg-white border shadow-xl rounded-lg p-3 w-80 transform transition-all translate-x-[120%]';
            toast.innerHTML = `<strong>${title}</strong><p class="text-xs">${message}</p>`;
            container.appendChild(toast);
            document.getElementById('notif-sound').play().catch(() => {});
            setTimeout(() => toast.classList.remove('translate-x-[120%]'), 50);
            setTimeout(() => { toast.classList.add('translate-x-[120%]'); setTimeout(() => toast.remove(), 500); }, 5000);
        };

        document.addEventListener('DOMContentLoaded', function () {
            if (window.Echo) {
                window.Echo.private('App.Models.User.' + {{ auth()->id() }})
                    .notification((notification) => {
                        window.showCompactToast(notification.status || 'Info', notification.pesan);
                    });
            }
        });
    </script>
    @endauth
</body>
</html>
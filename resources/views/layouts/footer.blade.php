<footer 
    :class="{
        'md:ml-64': !sidebarCollapsed && !{{ $isPengguna ? 'true' : 'false' }},
        'md:ml-20': sidebarCollapsed && !{{ $isPengguna ? 'true' : 'false' }},
        'ml-0': {{ $isPengguna ? 'true' : 'false' }}
    }"
    class="bg-white border-t border-gray-200 py-6 transition-all duration-300 ease-in-out mt-auto"
>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="text-sm text-gray-500 text-center md:text-left">
                &copy; {{ date('Y') }} <span class="font-bold text-blue-700 tracking-tight">{{ config('app.name') }}</span>. 
                <span class="hidden sm:inline">All rights reserved.</span>
            </div>
        </div>
    </div>
</footer>
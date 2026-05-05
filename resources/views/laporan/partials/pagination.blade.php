{{-- ============================================================ --}}
{{-- PARTIAL: _pagination.blade.php                             --}}
{{-- Digunakan oleh: _admin, _spsi, _keuangan                   --}}
{{-- $appendQuery (opsional): array query tambahan, mis. ['search' => $search] --}}
{{-- ============================================================ --}}
@if ($data->hasPages())
    <div class="px-5 py-3 border-t border-gray-100 flex items-center justify-between">
        <p class="text-xs text-gray-400">
            Menampilkan {{ $data->firstItem() ?? 0 }} - {{ $data->lastItem() ?? 0 }}
            dari {{ $data->total() }} data
        </p>
        <div class="flex items-center gap-2">
            @if ($data->onFirstPage())
                <span class="px-3 py-2 text-xs font-bold text-gray-300 cursor-not-allowed">
                    <i class="bi bi-chevron-left"></i>
                </span>
            @else
                <a href="{{ $data->previousPageUrl() }}"
                    class="px-3 py-2 text-xs font-bold text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                    <i class="bi bi-chevron-left"></i>
                </a>
            @endif

            <div class="hidden md:flex items-center gap-1">
                @php
                    $current = $data->currentPage();
                    $last    = $data->lastPage();
                    $start   = max(1, $current - 1);
                    $end     = min($last, $current + 1);

                    if ($current <= 2) { $start = 1; $end = min(3, $last); }
                    if ($current >= $last - 1) { $start = max(1, $last - 2); $end = $last; }
                @endphp

                @if ($start > 1)
                    <a href="{{ $data->url(1) }}"
                        class="w-8 h-8 flex items-center justify-center rounded-lg text-xs font-bold text-gray-500 hover:bg-gray-100">1</a>
                    @if ($start > 2)
                        <span class="w-8 h-8 flex items-center justify-center text-gray-400">...</span>
                    @endif
                @endif

                @for ($page = $start; $page <= $end; $page++)
                    @if ($page == $current)
                        <span class="w-8 h-8 flex items-center justify-center rounded-lg text-xs font-bold bg-blue-600 text-white shadow-sm">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $data->url($page) }}"
                            class="w-8 h-8 flex items-center justify-center rounded-lg text-xs font-bold text-gray-500 hover:bg-gray-100">
                            {{ $page }}
                        </a>
                    @endif
                @endfor

                @if ($end < $last)
                    @if ($end < $last - 1)
                        <span class="w-8 h-8 flex items-center justify-center text-gray-400">...</span>
                    @endif
                    <a href="{{ $data->url($last) }}"
                        class="w-8 h-8 flex items-center justify-center rounded-lg text-xs font-bold text-gray-500 hover:bg-gray-100">
                        {{ $last }}
                    </a>
                @endif
            </div>

            @if ($data->hasMorePages())
                <a href="{{ $data->nextPageUrl() }}"
                    class="px-3 py-2 text-xs font-bold text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                    <i class="bi bi-chevron-right"></i>
                </a>
            @else
                <span class="px-3 py-2 text-xs font-bold text-gray-300 cursor-not-allowed">
                    <i class="bi bi-chevron-right"></i>
                </span>
            @endif
        </div>
        <p class="text-xs text-gray-400">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
    </div>
@endif

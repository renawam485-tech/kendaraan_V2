{{--
    resources/views/components/status-badge.blade.php
    
    Usage:
        <x-status-badge :status="$p->status_permohonan" />
    
    $status bisa berupa StatusPermohonan enum ATAU string.
--}}

@php
    use App\Enums\StatusPermohonan;
    $enum  = $status instanceof StatusPermohonan ? $status : StatusPermohonan::from($status);
    $label = $enum->value;
    $class = $enum->badgeClass();
@endphp

<span class="inline-block text-[11px] font-bold px-2.5 py-1 rounded-md border {{ $class }}">
    {{ $label }}
</span>

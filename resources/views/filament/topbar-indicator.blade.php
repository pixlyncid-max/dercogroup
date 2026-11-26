@php
    $tenant = \Filament\Facades\Filament::getTenant();
    $accentColor = match ($tenant?->slug) {
        'korin-berkat-energy' => '#D49E35',
        'korin-bara-energy' => '#D97706',
        'beramal-sukses-sejahtera' => '#0284C7',
        default => '#B36829',
    };
@endphp
@if ($tenant)
    <div class="flex items-center gap-2 pl-2 md:pl-4 py-1 text-xs">
        <span class="text-slate-500 font-medium hidden sm:inline">Perusahaan aktif:</span>
        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold text-white"
              style="background-color: {{ $accentColor }};">
            {{ $tenant->name }}
        </span>
    </div>
@endif

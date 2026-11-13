@php
    $tenant = \Filament\Facades\Filament::getTenant();
    $accentColor = match ($tenant?->slug) {
        'korin-berkat-energy' => '#EDB856',
        'korin-bara-energy' => '#F59E0B',
        'beramal-sukses-sejahtera' => '#0284C7',
        default => '#C87A38',
    };
@endphp
@if ($tenant)
    <div class="flex items-center gap-2 pl-2 md:pl-4 py-1 text-xs">
        <span class="text-slate-400 font-medium hidden sm:inline">Anda sedang mengedit:</span>
        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold uppercase tracking-wider text-white shadow-sm"
              style="background-color: {{ $accentColor }};">
            <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
            {{ $tenant->name }}
        </span>
    </div>
@endif


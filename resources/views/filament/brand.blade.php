@php
    $logoUrl = config('branding.logos.korin-berkat.dark') 
        ?? config('branding.logos.holding.dark') 
        ?? '/images/logo/Logo-KorinBerkatEnergy-light.png';
@endphp
<div class="cms-sidebar-brand flex items-center gap-3 py-1">
    <img src="{{ asset($logoUrl) }}"
         alt="{{ config('branding.logos.korin-berkat.alt', 'Korin Berkat Energy') }}"
         class="h-8 md:h-9 w-auto object-contain select-none" />
</div>

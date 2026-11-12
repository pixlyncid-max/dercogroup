@php
    $logoUrl = file_exists(public_path('images/cms/korin-logo-light.png'))
        ? '/images/cms/korin-logo-light.png'
        : (config('branding.logos.korin-berkat.dark') ?? '/images/logo/Logo-KorinBerkatEnergy-light.png');
@endphp
<div class="cms-sidebar-brand flex items-center gap-3 py-1">
    <img src="{{ $logoUrl }}"
         alt="{{ config('branding.logos.korin-berkat.alt', 'Korin Berkat Energy') }}"
         class="h-8 md:h-9 w-auto object-contain select-none" />
</div>

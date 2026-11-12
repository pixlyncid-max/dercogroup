@php
    $logoUrl = file_exists(public_path('images/cms/derco-logo-light.png'))
        ? '/images/cms/derco-logo-light.png'
        : (config('branding.logos.holding.dark') ?? '/images/logo/Logo-DercoBaraEnergy-light.png');
@endphp
<div class="cms-sidebar-brand flex items-center gap-3 py-1">
    <img src="{{ $logoUrl }}"
         alt="{{ config('branding.logos.holding.alt', 'PT Derco Bara Energy') }}"
         class="h-8 md:h-9 w-auto object-contain select-none" />
</div>

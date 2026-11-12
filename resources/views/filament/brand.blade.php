@php
    $logoRelative = file_exists(public_path('images/cms/derco-logo-light.png'))
        ? 'images/cms/derco-logo-light.png'
        : ltrim(config('branding.logos.holding.dark', 'images/logo/Logo-DercoBaraEnergy-light.png'), '/');
    $logoUrl = \App\Support\FilamentThemeResolver::resolveAssetUrl($logoRelative);
@endphp
<div class="cms-sidebar-brand flex items-center gap-3 py-1" style="max-height: 2.5rem; overflow: hidden;">
    <img src="{{ $logoUrl }}"
         alt="{{ config('branding.logos.holding.alt', 'PT Derco Bara Energy') }}"
         class="h-8 md:h-9 w-auto object-contain select-none"
         style="max-height: 2.25rem; width: auto; max-width: 13rem; object-fit: contain;" />
</div>

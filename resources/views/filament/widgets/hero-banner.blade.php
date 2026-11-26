@php
    $company = $this->getCompany();
    $companyName = $company?->name ?? 'PT Derco Bara Energy';
    $tagline = $company ? ($company->translateField('tagline', 'id') ?: 'Pertambangan, Perdagangan & Logistik Energi') : 'Pertambangan, Perdagangan & Logistik Energi';
    $description = $company ? ($company->translateField('about_short', 'id') ?: 'Kelola profil, produk, operasi, dan informasi kontak perusahaan melalui panel ini.') : 'Kelola profil, produk, operasi, dan informasi kontak perusahaan melalui panel ini.';
    $heroBgUrl = \App\Support\FilamentThemeResolver::resolveAssetUrl('images/cms/dashboard-hero-banner.jpg');
    $publicUrl = $this->getPublicUrl();
    $isPublished = $company?->status?->value === 'published';
@endphp
<div class="cms-hero-banner relative overflow-hidden rounded-xl min-h-[240px] flex flex-col justify-between p-6 sm:p-8 bg-slate-900 border border-slate-200">
    <img src="{{ $heroBgUrl }}"
         alt=""
         aria-hidden="true"
         class="absolute inset-0 h-full w-full object-cover object-center opacity-45" />
    <div class="absolute inset-0 bg-gradient-to-r from-slate-950/95 via-slate-900/85 to-slate-900/50"></div>

    <div class="relative z-10 space-y-2 max-w-xl">
        <div class="flex flex-wrap items-center gap-2">
            <span class="text-xs font-semibold text-[#EDB856]">
                {{ $companyName }}
            </span>
            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium {{ $isPublished ? 'bg-emerald-500/20 text-emerald-200 border border-emerald-400/30' : 'bg-amber-500/20 text-amber-200 border border-amber-400/30' }}">
                {{ $isPublished ? 'Website Aktif' : 'Mode Draft' }}
            </span>
        </div>
        <h2 class="text-xl sm:text-2xl font-semibold text-white tracking-tight leading-snug">
            {{ $tagline }}
        </h2>
        <p class="text-xs sm:text-sm text-slate-300 leading-relaxed line-clamp-2">
            {{ $description }}
        </p>
    </div>

    <div class="relative z-10 flex flex-wrap items-center gap-3 mt-6 pt-2">
        <a href="{{ $publicUrl }}" target="_blank" rel="noopener noreferrer"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-[#EDB856] hover:bg-[#D49E35] text-slate-950 text-xs sm:text-sm font-semibold transition-colors">
            <span>Lihat Website Publik</span>
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
            </svg>
        </a>
    </div>
</div>

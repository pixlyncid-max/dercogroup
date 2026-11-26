@php
    $tenant = \Filament\Facades\Filament::getTenant();
    $textsMediaUrl = $tenant
        ? \App\Filament\Pages\ManageSiteTextsAndMedia::getUrl(tenant: $tenant)
        : '#';
@endphp
<div class="cms-quick-actions bg-white rounded-xl border border-slate-200 p-5 sm:p-6 flex flex-col justify-between h-full">
    <div class="mb-4">
        <h3 class="text-sm font-semibold text-slate-900">Aksi Cepat</h3>
        <p class="text-xs text-slate-500 mt-0.5">Pintasan menu yang paling sering digunakan</p>
    </div>

    <div class="grid grid-cols-2 gap-3 flex-1">
        <a href="{{ $this->getPagesUrl() }}"
           class="flex flex-col items-start justify-between p-3.5 rounded-lg border border-slate-200 bg-slate-50/60 hover:bg-slate-100 hover:border-slate-300 transition-colors">
            <svg class="w-4 h-4 text-slate-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <div>
                <span class="block text-xs font-semibold text-slate-900">Edit Halaman</span>
                <span class="block text-[11px] text-slate-500 mt-0.5">Ubah isi & foto</span>
            </div>
        </a>

        <a href="{{ $textsMediaUrl }}"
           class="flex flex-col items-start justify-between p-3.5 rounded-lg border border-slate-200 bg-slate-50/60 hover:bg-slate-100 hover:border-slate-300 transition-colors">
            <svg class="w-4 h-4 text-slate-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
            </svg>
            <div>
                <span class="block text-xs font-semibold text-slate-900">Teks & Media</span>
                <span class="block text-[11px] text-slate-500 mt-0.5">Semua teks & gambar</span>
            </div>
        </a>

        @if ($productUrl = $this->getProductUrl())
            <a href="{{ $productUrl }}"
               class="flex flex-col items-start justify-between p-3.5 rounded-lg border border-slate-200 bg-slate-50/60 hover:bg-slate-100 hover:border-slate-300 transition-colors">
                <svg class="w-4 h-4 text-slate-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <div>
                    <span class="block text-xs font-semibold text-slate-900">Produk & Layanan</span>
                    <span class="block text-[11px] text-slate-500 mt-0.5">Katalog & spesifikasi</span>
                </div>
            </a>
        @else
            <a href="{{ $this->getMessagesUrl() }}"
               class="flex flex-col items-start justify-between p-3.5 rounded-lg border border-slate-200 bg-slate-50/60 hover:bg-slate-100 hover:border-slate-300 transition-colors">
                <svg class="w-4 h-4 text-slate-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <div>
                    <span class="block text-xs font-semibold text-slate-900">Pesan Masuk</span>
                    <span class="block text-[11px] text-slate-500 mt-0.5">Formulir kontak</span>
                </div>
            </a>
        @endif

        <a href="{{ $this->getProfileUrl() }}"
           class="flex flex-col items-start justify-between p-3.5 rounded-lg border border-slate-200 bg-slate-50/60 hover:bg-slate-100 hover:border-slate-300 transition-colors">
            <svg class="w-4 h-4 text-slate-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <div>
                <span class="block text-xs font-semibold text-slate-900">Pengaturan</span>
                <span class="block text-[11px] text-slate-500 mt-0.5">Identitas & SEO</span>
            </div>
        </a>
    </div>
</div>

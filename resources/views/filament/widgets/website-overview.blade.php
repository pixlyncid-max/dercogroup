@php
    $tenant = \Filament\Facades\Filament::getTenant();
    $pages = $tenant
        ? $tenant->pages()->where('show_in_nav', true)->orderBy('sort_order')->get()
        : collect();
    $pageKeyToRouteParam = [
        'home' => 'beranda',
        'about' => 'tentang-kami',
        'business' => 'bisnis-kami',
        'products' => 'produk',
        'operations' => 'operasi',
        'contact' => 'hubungi-kami',
    ];
@endphp
<div class="cms-website-overview bg-white rounded-xl border border-slate-200 p-5 sm:p-6 flex flex-col justify-between h-full">
    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
        <div>
            <h3 class="text-sm font-semibold text-slate-900">Daftar Halaman Website</h3>
            <p class="text-xs text-slate-500 mt-0.5">Pilih halaman di bawah ini untuk langsung mengubah teks dan gambarnya</p>
        </div>
        <span class="text-xs font-medium text-slate-500 tabular-nums">
            {{ $pages->count() }} halaman aktif
        </span>
    </div>

    <div class="divide-y divide-slate-100 mt-2">
        @forelse ($pages as $page)
            @php
                $routeParam = $pageKeyToRouteParam[$page->key->value] ?? 'beranda';
                $editUrl = $tenant
                    ? route('filament.admin.pages.halaman', ['tenant' => $tenant, 'pageKey' => $routeParam])
                    : '#';
                $blockCount = $page->blocks()->count();
            @endphp
            <div class="flex items-center justify-between gap-4 py-3">
                <div class="min-w-0">
                    <div class="text-sm font-medium text-slate-900 truncate">
                        {{ $page->translateField('title', 'id') }}
                    </div>
                    <div class="text-xs text-slate-500">
                        {{ $blockCount }} bagian konten · {{ $page->is_published ? 'Tayang di website' : 'Masih draft' }}
                    </div>
                </div>

                <a href="{{ $editUrl }}"
                   class="shrink-0 inline-flex items-center px-3 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-xs font-semibold text-slate-700 transition-colors">
                    Edit Halaman
                </a>
            </div>
        @empty
            <div class="py-8 text-center text-xs text-slate-500">
                Belum ada halaman terdaftar untuk perusahaan ini.
            </div>
        @endforelse
    </div>
</div>

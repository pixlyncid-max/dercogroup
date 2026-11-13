<x-filament-panels::page>
    @php
        $tenant = \Filament\Facades\Filament::getTenant();
        $theme = $tenant?->theme ?? \App\Domain\Company\Enums\CompanyTheme::Holding;
        $sections = $this->getSections();
        $accentColor = match ($tenant?->slug) {
            'korin-berkat-energy' => '#EDB856',
            'korin-bara-energy' => '#F59E0B',
            'beramal-sukses-sejahtera' => '#0284C7',
            default => '#C87A38',
        };
        $publicUrl = $tenant ? url('/' . $tenant->slug) : url('/id');
    @endphp

    <div class="space-y-6">
        {{-- Sticky / Top Bar Controls --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 p-4 rounded-2xl bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border border-slate-200/80 dark:border-white/10 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-white shadow"
                     style="background-color: {{ $accentColor }};">
                    <x-filament::icon icon="heroicon-o-document-text" class="w-6 h-6 text-white" />
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white tracking-tight">
                            {{ $this->getTitle() }}
                        </h2>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold uppercase tracking-wider bg-slate-100 text-slate-700 dark:bg-white/10 dark:text-slate-300">
                            {{ count($sections) }} Section
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        {{ $this->getSubheading() }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                {{-- Language Toggle --}}
                <div class="inline-flex items-center p-1 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-white/10 text-xs font-semibold">
                    <button type="button"
                            wire:click="$set('locale', 'id')"
                            class="px-3 py-1.5 rounded-lg transition-all {{ $locale === 'id' ? 'bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-sm font-bold' : 'text-slate-500 hover:text-slate-900 dark:hover:text-white' }}">
                        🇮🇩 Indonesia
                    </button>
                    <button type="button"
                            wire:click="$set('locale', 'en')"
                            class="px-3 py-1.5 rounded-lg transition-all {{ $locale === 'en' ? 'bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-sm font-bold' : 'text-slate-500 hover:text-slate-900 dark:hover:text-white' }}">
                        🇬🇧 English
                    </button>
                </div>

                {{-- View Website Public Link --}}
                <a href="{{ $publicUrl }}"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-800 border border-slate-200 dark:border-white/10 hover:bg-slate-50 dark:hover:bg-slate-700 transition shadow-sm">
                    <x-filament::icon icon="heroicon-o-arrow-top-right-on-square" class="w-4 h-4 text-slate-400" />
                    <span>Lihat Website</span>
                </a>
            </div>
        </div>

        {{-- Sections List --}}
        <div class="grid grid-cols-1 gap-6">
            @forelse ($sections as $section)
                <div class="rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 shadow-sm overflow-hidden transition-all hover:border-slate-300 dark:hover:border-white/20">
                    <div class="p-5 sm:p-6 border-b border-slate-100 dark:border-white/5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2.5">
                                <span class="w-2 h-2 rounded-full" style="background-color: {{ $accentColor }};"></span>
                                <h3 class="text-base font-bold text-slate-900 dark:text-white">
                                    {{ $section['label'] }}
                                </h3>
                                @if ($section['is_company_scoped'])
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-blue-50 text-blue-700 dark:bg-blue-950/50 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                                        Global Perusahaan
                                    </span>
                                @endif
                                @if ($section['is_relational'])
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-amber-50 text-amber-700 dark:bg-amber-950/50 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                                        Data Terkait
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-400">
                                {{ $section['help_text'] }}
                            </p>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                                Aktif Tayang
                            </span>
                        </div>
                    </div>

                    <div class="p-5 sm:p-6 bg-slate-50/50 dark:bg-slate-950/20">
                        <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                            <span class="font-mono text-[11px] text-slate-400">ID Section: section-{{ $section['section_key'] }}</span>
                            <span class="italic">Pengaturan konten section disesuaikan untuk pengguna awam (tata letak paten).</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10">
                    <p class="text-sm text-slate-500">Tidak ada section yang dikonfigurasikan untuk halaman ini.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-filament-panels::page>

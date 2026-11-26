<x-filament-panels::page>
    @php
        $tenant = \Filament\Facades\Filament::getTenant();
        $accentColor = match ($tenant?->slug) {
            'korin-berkat-energy' => '#EDB856',
            'korin-bara-energy' => '#F59E0B',
            'beramal-sukses-sejahtera' => '#0284C7',
            default => '#C87A38',
        };
        $groupedTexts = $this->getFilteredGroupedTexts();
        $availableGroups = $this->getAvailableGroups();
        $mediaSlots = $this->getMediaSlots();
        $overrideIdCount = $this->getOverrideCountForLocale('id');
        $overrideEnCount = $this->getOverrideCountForLocale('en');
    @endphp

    <div x-data="{ unsaved: false }"
         @keydown.window.prevent.ctrl.s="$wire.save(); unsaved = false;"
         @keydown.window.prevent.cmd.s="$wire.save(); unsaved = false;"
         @input="unsaved = true;"
         class="space-y-6">

        {{-- Sticky Header Bar --}}
        <div class="sticky top-16 z-20 flex flex-col md:flex-row md:items-center md:justify-between gap-4 p-4 md:px-6 rounded-xl bg-white border border-slate-200 shadow-xs">
            <div class="flex items-center gap-3.5 min-w-0">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center font-semibold text-white shrink-0"
                     style="background-color: {{ $accentColor }};">
                    <x-filament::icon icon="heroicon-o-language" class="w-5 h-5 text-white" />
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-2">
                        <h1 class="text-lg font-semibold text-slate-900 tracking-tight truncate">
                            Teks & Media Website
                        </h1>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-slate-100 text-slate-600">
                            {{ $tenant?->name }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 truncate">
                        Ubah seluruh teks dan gambar website dengan mudah tanpa menyentuh kode
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                {{-- Tab Switcher --}}
                <div class="inline-flex items-center p-1 rounded-lg bg-slate-100 border border-slate-200">
                    <button type="button"
                            wire:click="setTab('texts')"
                            class="px-3 py-1.5 text-xs font-semibold rounded-md transition-colors {{ $activeTab === 'texts' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                        Semua Teks
                    </button>
                    <button type="button"
                            wire:click="setTab('media')"
                            class="px-3 py-1.5 text-xs font-semibold rounded-md transition-colors {{ $activeTab === 'media' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                        Gambar & Media
                    </button>
                </div>

                @if ($activeTab === 'texts')
                    {{-- Language Switcher --}}
                    <div class="inline-flex items-center p-1 rounded-lg bg-slate-100 border border-slate-200">
                        <button type="button"
                                wire:click="setLocale('id')"
                                class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-md transition-colors {{ $locale === 'id' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                            <span>Indonesia</span>
                            @if ($overrideIdCount > 0)
                                <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-slate-800 text-white tabular-nums">{{ $overrideIdCount }}</span>
                            @endif
                        </button>
                        <button type="button"
                                wire:click="setLocale('en')"
                                class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-md transition-colors {{ $locale === 'en' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                            <span>English</span>
                            @if ($overrideEnCount > 0)
                                <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-slate-800 text-white tabular-nums">{{ $overrideEnCount }}</span>
                            @endif
                        </button>
                    </div>
                @endif

                {{-- Save Button --}}
                <button type="button"
                        wire:click="save"
                        wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-semibold text-white transition-opacity hover:opacity-90 disabled:opacity-50"
                        style="background-color: {{ $accentColor }};">
                    <x-filament::icon icon="heroicon-o-check" class="w-4 h-4" />
                    <span wire:loading.remove wire:target="save">Simpan Perubahan</span>
                    <span wire:loading wire:target="save">Menyimpan...</span>
                </button>
            </div>
        </div>

        @if ($activeTab === 'texts')
            {{-- Filter & Search Bar --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10">
                <div class="flex-1">
                    <input type="search"
                           wire:model.live.debounce.250ms="searchQuery"
                           placeholder="Cari teks apa saja di website (contoh: nikel, sulfur, tombol, footer, kontak)..."
                           class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-4 py-2 text-sm text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-sky-500" />
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <select wire:model.live="selectedGroup"
                            class="rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-3 py-2 text-xs font-semibold text-slate-700 dark:text-slate-200">
                        <option value="all">Semua Kategori</option>
                        @foreach ($availableGroups as $groupName)
                            <option value="{{ $groupName }}">{{ $groupName }}</option>
                        @endforeach
                    </select>

                    <button type="button"
                            wire:click="resetAllTextsForLocale"
                            class="px-3 py-2 rounded-xl border border-slate-300 dark:border-slate-700 text-xs font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800">
                        Reset Bahasa {{ strtoupper($locale) }} ke Bawaan
                    </button>
                </div>
            </div>

            {{-- Grouped Text Fields --}}
            @forelse ($groupedTexts as $groupTitle => $items)
                <div class="rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 overflow-hidden shadow-xs">
                    <div class="px-6 py-4 border-b border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-800/60 flex items-center justify-between">
                        <h2 class="text-sm font-bold text-slate-900 dark:text-white">{{ $groupTitle }}</h2>
                        <span class="text-xs font-medium text-slate-500 dark:text-slate-400">{{ count($items) }} teks</span>
                    </div>

                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                        @foreach ($items as $dotKey => $val)
                            @php
                                $fieldKey = \App\Filament\Pages\ManageSiteTextsAndMedia::encodeKey($dotKey);
                                $defaultVal = $defaults[$locale][$dotKey] ?? '';
                                $isModified = $val !== $defaultVal;
                                $isLong = mb_strlen($defaultVal) > 75 || mb_strlen((string) $val) > 75;
                            @endphp
                            <div wire:key="ui-text-{{ $locale }}-{{ $fieldKey }}"
                                 class="space-y-1.5 {{ $isLong ? 'md:col-span-2' : '' }}">
                                <div class="flex items-center justify-between gap-2">
                                    <label class="text-xs font-semibold text-slate-800">
                                        {{ \App\Domain\Company\Services\SiteCustomizationService::humanLabelForKey($dotKey) }}
                                    </label>
                                    @if ($isModified)
                                        <button type="button"
                                                wire:click="resetTextKey('{{ $dotKey }}')"
                                                class="text-[11px] font-semibold text-amber-700 hover:underline">
                                            Reset bawaan
                                        </button>
                                    @endif
                                </div>

                                @if ($isLong)
                                    <textarea rows="2"
                                              wire:model.defer="texts.{{ $locale }}.{{ $fieldKey }}"
                                              class="w-full rounded-xl border {{ $isModified ? 'border-amber-400 dark:border-amber-500' : 'border-slate-300 dark:border-slate-700' }} bg-white dark:bg-slate-950 px-3.5 py-2 text-sm text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-sky-500"></textarea>
                                @else
                                    <input type="text"
                                           wire:model.defer="texts.{{ $locale }}.{{ $fieldKey }}"
                                           class="w-full rounded-xl border {{ $isModified ? 'border-amber-400 dark:border-amber-500' : 'border-slate-300 dark:border-slate-700' }} bg-white dark:bg-slate-950 px-3.5 py-2 text-sm text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-sky-500" />
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="p-10 text-center rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 text-sm text-slate-500">
                    Tidak ada teks yang cocok dengan pencarian "{{ $searchQuery }}".
                </div>
            @endforelse
        @else
            {{-- Media & Images Control Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($mediaSlots as $slotKey => $slotMeta)
                    @php
                        $hasCustom = ! empty($mediaPaths[$slotKey]);
                        $previewUrl = $this->getMediaPreviewUrl($slotKey);
                    @endphp
                    <div wire:key="media-slot-{{ $slotKey }}"
                         class="flex flex-col justify-between rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 overflow-hidden shadow-xs">
                        <div>
                            <div class="relative aspect-video bg-slate-100 dark:bg-slate-800 overflow-hidden">
                                @if (! empty($tempMediaUploads[$slotKey]) && method_exists($tempMediaUploads[$slotKey], 'temporaryUrl'))
                                    <img src="{{ $tempMediaUploads[$slotKey]->temporaryUrl() }}"
                                         alt="{{ $slotMeta['label'] }}"
                                         class="w-full h-full object-cover" />
                                @else
                                    <img src="{{ $previewUrl }}"
                                         alt="{{ $slotMeta['label'] }}"
                                         class="w-full h-full object-cover" />
                                @endif

                                <span class="absolute top-2.5 left-2.5 px-2.5 py-1 rounded-full text-[11px] font-bold {{ $hasCustom ? 'bg-emerald-600 text-white' : 'bg-slate-900/80 text-white' }}">
                                    {{ $hasCustom ? 'Gambar Kustom CMS' : 'Gambar Bawaan' }}
                                </span>
                            </div>

                            <div class="p-4 space-y-1.5">
                                <h3 class="text-sm font-bold text-slate-900 dark:text-white">
                                    {{ $slotMeta['label'] }}
                                </h3>
                                <p class="text-xs leading-5 text-slate-500 dark:text-slate-400">
                                    {{ $slotMeta['description'] }}
                                </p>
                            </div>
                        </div>

                        <div class="p-4 pt-0 space-y-2.5">
                            <input type="file"
                                   accept="image/png,image/jpeg,image/webp"
                                   wire:model="tempMediaUploads.{{ $slotKey }}"
                                   class="block w-full text-xs text-slate-600 dark:text-slate-300 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 dark:file:bg-slate-800 dark:file:text-slate-200" />

                            <div class="flex items-center justify-between text-[11px] text-slate-500">
                                <span>JPG/PNG/WebP · Maks 5MB</span>
                                @if ($hasCustom)
                                    <button type="button"
                                            wire:click="removeMediaSlot('{{ $slotKey }}')"
                                            class="font-semibold text-rose-600 hover:underline">
                                        Kembalikan Bawaan
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-filament-panels::page>

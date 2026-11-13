<x-filament-panels::page>
    @php
        $tenant = \Filament\Facades\Filament::getTenant();
        $sections = $this->getSections();
        $accentColor = match ($tenant?->slug) {
            'korin-berkat-energy' => '#EDB856',
            'korin-bara-energy' => '#F59E0B',
            'beramal-sukses-sejahtera' => '#0284C7',
            default => '#C87A38',
        };
        $accentHover = match ($tenant?->slug) {
            'korin-berkat-energy' => '#D49E35',
            'korin-bara-energy' => '#D97706',
            'beramal-sukses-sejahtera' => '#0369A1',
            default => '#B36829',
        };
        $emptyId = $this->getEmptyFieldsCount('id');
        $emptyEn = $this->getEmptyFieldsCount('en');
        $linkOptions = $tenant ? \App\Domain\Page\Schema\SectionSchema::getLinkPickerOptions($tenant) : [];
        $publicUrl = $this->getPublicUrl();
    @endphp

    <div x-data="{
            unsaved: false,
            previewOpen: @entangle('showPreview'),
            device: @entangle('previewDevice'),
            init() {
                window.addEventListener('beforeunload', (e) => {
                    if (this.unsaved) {
                        e.preventDefault();
                        e.returnValue = '';
                    }
                });
            }
         }"
         @keydown.window.prevent.ctrl.s="$wire.save(); unsaved = false;"
         @keydown.window.prevent.cmd.s="$wire.save(); unsaved = false;"
         @input="unsaved = true;"
         class="space-y-6">

        {{-- 5.1 Sticky Action Header Bar --}}
        <div class="sticky top-16 z-20 flex flex-col md:flex-row md:items-center md:justify-between gap-4 p-4 md:px-6 rounded-2xl bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border border-slate-200/90 dark:border-white/10 shadow-sm transition-all">
            <div class="flex items-center gap-3.5 min-w-0">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center font-bold text-white shadow shrink-0"
                     style="background-color: {{ $accentColor }};">
                    <x-filament::icon icon="heroicon-o-document-text" class="w-6 h-6 text-white" />
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-2">
                        <h1 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight truncate">
                            {{ $this->getTitle() }}
                        </h1>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold uppercase tracking-wider bg-slate-100 text-slate-700 dark:bg-white/10 dark:text-slate-300 shrink-0">
                            {{ count($sections) }} Section
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 truncate">
                        {{ $this->getSubheading() }}
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                {{-- Status Simpan Indicator --}}
                <div class="hidden lg:flex items-center gap-1.5 px-3 py-1.5 text-xs text-slate-500 font-medium">
                    <span wire:loading.remove wire:target="save">
                        <template x-if="!unsaved">
                            <span class="inline-flex items-center gap-1.5 text-emerald-600 font-semibold">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Tersimpan</span>
                            </span>
                        </template>
                        <template x-if="unsaved">
                            <span class="inline-flex items-center gap-1.5 text-amber-600 font-semibold">
                                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                                <span>Ada perubahan</span>
                            </span>
                        </template>
                    </span>
                    <span wire:loading wire:target="save" class="inline-flex items-center gap-1.5 text-blue-600 font-semibold">
                        <svg class="animate-spin w-3.5 h-3.5 text-blue-600" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Menyimpan...</span>
                    </span>
                </div>

                {{-- Toggle Preview Website Button --}}
                <button type="button"
                        @click="previewOpen = !previewOpen"
                        class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-800 border border-slate-200 dark:border-white/10 hover:bg-slate-50 transition shadow-sm cursor-pointer">
                    <x-filament::icon icon="heroicon-o-device-phone-mobile" class="w-4 h-4 text-slate-400" />
                    <span x-text="previewOpen ? 'Tutup Preview' : 'Preview Website'"></span>
                </button>

                {{-- Lihat Website Public Link --}}
                <a href="{{ $publicUrl }}"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-800 border border-slate-200 dark:border-white/10 hover:bg-slate-50 transition shadow-sm">
                    <x-filament::icon icon="heroicon-o-arrow-top-right-on-square" class="w-4 h-4 text-slate-400" />
                    <span>Lihat Website</span>
                </a>

                {{-- Tombol Utama Simpan Perubahan --}}
                <button type="button"
                        wire:click="save"
                        wire:loading.attr="disabled"
                        @click="unsaved = false"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-white transition-all shadow-md cursor-pointer hover:shadow-lg disabled:opacity-50"
                        style="background-color: {{ $accentColor }};">
                    <svg wire:loading.remove wire:target="save" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                    </svg>
                    <svg wire:loading wire:target="save" class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Simpan Perubahan</span>
                    <span class="hidden sm:inline-block px-1.5 py-0.5 rounded bg-black/20 text-[10px] font-mono tracking-wider opacity-80">Ctrl+S</span>
                </button>
            </div>
        </div>

        {{-- 5.2 Bilah Bahasa --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 p-3 px-4 rounded-xl bg-slate-100/80 dark:bg-slate-800/80 border border-slate-200/80 dark:border-white/5 text-xs">
            <div class="flex items-center gap-2">
                <span class="text-slate-500 font-medium">Bahasa Konten Editor:</span>
                <div class="inline-flex items-center p-1 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 shadow-sm">
                    <button type="button"
                            wire:click="$set('locale', 'id')"
                            class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg transition-all {{ $locale === 'id' ? 'bg-slate-900 text-white font-bold shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                        <span>🇮🇩 Bahasa Indonesia</span>
                        @if ($emptyId > 0)
                            <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-amber-100 text-amber-800 font-semibold">{{ $emptyId }} kosong</span>
                        @endif
                    </button>
                    <button type="button"
                            wire:click="$set('locale', 'en')"
                            class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg transition-all {{ $locale === 'en' ? 'bg-slate-900 text-white font-bold shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                        <span>🇬🇧 English</span>
                        @if ($emptyEn > 0)
                            <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-amber-100 text-amber-800 font-semibold">{{ $emptyEn }} kosong</span>
                        @endif
                    </button>
                </div>
            </div>
            <p class="text-[11px] text-slate-500 italic">
                Form otomatis beralih menampilkan teks bahasa terpilih tanpa memuat ulang halaman.
            </p>
        </div>

        {{-- 5.3 Kartu Preview Website (Responsive Iframe) --}}
        <div x-show="previewOpen" x-cloak class="rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/90 shadow-md p-4 transition-all">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-3 border-b border-slate-100 dark:border-white/10">
                <div class="flex items-center gap-2">
                    <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-ping"></div>
                    <h3 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">
                        Live Preview Website (Mode Draft)
                    </h3>
                </div>
                <div class="flex items-center gap-2">
                    {{-- Device Selector --}}
                    <div class="inline-flex items-center p-0.5 rounded-lg bg-slate-100 dark:bg-slate-800 text-xs">
                        <button type="button"
                                @click="device = 'desktop'"
                                :class="device === 'desktop' ? 'bg-white shadow text-slate-900 font-bold' : 'text-slate-500'"
                                class="px-2.5 py-1 rounded-md transition text-xs flex items-center gap-1">
                            <x-filament::icon icon="heroicon-o-computer-desktop" class="w-3.5 h-3.5" />
                            <span>Desktop</span>
                        </button>
                        <button type="button"
                                @click="device = 'tablet'"
                                :class="device === 'tablet' ? 'bg-white shadow text-slate-900 font-bold' : 'text-slate-500'"
                                class="px-2.5 py-1 rounded-md transition text-xs flex items-center gap-1">
                            <x-filament::icon icon="heroicon-o-device-tablet" class="w-3.5 h-3.5" />
                            <span>Tablet</span>
                        </button>
                        <button type="button"
                                @click="device = 'mobile'"
                                :class="device === 'mobile' ? 'bg-white shadow text-slate-900 font-bold' : 'text-slate-500'"
                                class="px-2.5 py-1 rounded-md transition text-xs flex items-center gap-1">
                            <x-filament::icon icon="heroicon-o-device-phone-mobile" class="w-3.5 h-3.5" />
                            <span>Mobile</span>
                        </button>
                    </div>

                    <button type="button"
                            @click="$refs.previewIframe.src = $refs.previewIframe.src"
                            title="Segarkan Preview"
                            class="p-1.5 rounded-lg border border-slate-200 text-slate-500 hover:text-slate-900 hover:bg-slate-50">
                        <x-filament::icon icon="heroicon-o-arrow-path" class="w-4 h-4" />
                    </button>

                    <button type="button"
                            @click="previewOpen = false"
                            class="text-xs text-slate-400 hover:text-slate-600 px-2 py-1">
                        ✕ Tutup
                    </button>
                </div>
            </div>

            <div class="pt-4 flex justify-center bg-slate-100/70 dark:bg-slate-950/40 rounded-xl overflow-hidden p-2 min-h-[460px]">
                <div :class="{
                    'w-full max-w-full': device === 'desktop',
                    'w-[768px] max-w-full shadow-2xl rounded-xl border border-slate-300': device === 'tablet',
                    'w-[375px] max-w-full shadow-2xl rounded-2xl border-4 border-slate-800': device === 'mobile'
                }" class="transition-all duration-300 bg-white overflow-hidden">
                    <iframe x-ref="previewIframe"
                            src="{{ $publicUrl }}?preview=1"
                            class="w-full h-[520px] border-0"
                            loading="lazy"></iframe>
                </div>
            </div>
        </div>

        {{-- 5.4 Kartu-Kartu Form per Section --}}
        <div class="space-y-6">
            @foreach ($sections as $section)
                @php
                    $secKey = $section['section_key'];
                    $blockType = $section['block_type'];
                    $blockClass = \App\Domain\Page\Blocks\BlockRegistry::for($blockType);
                    $fields = $blockClass::fields();
                    $isToggled = $this->visibility[$secKey] ?? true;
                @endphp

                <div wire:key="section-card-{{ $secKey }}"
                     class="rounded-2xl bg-white dark:bg-slate-900 border transition-all duration-200 shadow-sm overflow-hidden {{ $isToggled ? 'border-slate-200/90 dark:border-white/10' : 'border-slate-200/50 bg-slate-50/50 opacity-70' }}">
                    
                    {{-- Card Header --}}
                    <div class="p-5 sm:p-6 border-b border-slate-100 dark:border-white/5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2.5">
                                <span class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $accentColor }};"></span>
                                <h2 class="text-base font-bold text-slate-900 dark:text-white">
                                    {{ $section['label'] }}
                                </h2>
                                @if ($section['is_company_scoped'])
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold tracking-wider uppercase bg-blue-50 text-blue-700 dark:bg-blue-950/50 dark:text-blue-300 border border-blue-200">
                                        Global Perusahaan
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-400">
                                {{ $section['help_text'] }}
                            </p>
                        </div>

                        {{-- Toggle Switch on Optional Sections --}}
                        <div class="flex items-center gap-3 shrink-0">
                            @if ($section['is_toggleable'])
                                <label class="relative inline-flex items-center cursor-pointer gap-2">
                                    <input type="checkbox"
                                           wire:model.live="visibility.{{ $secKey }}"
                                           class="sr-only peer">
                                    <div class="w-10 h-5.5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4.5 after:w-4.5 after:transition-all peer-checked:bg-emerald-500"></div>
                                    <span class="text-xs font-semibold {{ $isToggled ? 'text-slate-800' : 'text-slate-400' }}">
                                        {{ $isToggled ? 'Tampil di Website' : 'Disembunyikan' }}
                                    </span>
                                </label>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 dark:bg-white/10 dark:text-slate-300">
                                    Section Wajib
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Card Body --}}
                    @if ($isToggled)
                        <div class="p-5 sm:p-6 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach ($fields as $field)
                                    @php
                                        $fName = $field['name'];
                                        $fType = $field['type'] ?? 'text';
                                        $fLabel = $field['label'] ?? ucfirst($fName);
                                        $fHelp = $field['help_text'] ?? null;
                                        $isFullWidth = in_array($fType, ['translatable_textarea', 'translatable_richtext', 'repeater', 'image'], true);
                                    @endphp

                                    <div class="{{ $isFullWidth ? 'md:col-span-2' : 'md:col-span-1' }} space-y-2">
                                        {{-- 1. Translatable Short Text --}}
                                        @if (in_array($fType, ['translatable_text', 'text'], true))
                                            <div class="space-y-1.5" x-data="{ len: 0 }">
                                                <div class="flex items-center justify-between">
                                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                                                        {{ $fLabel }}
                                                        @if (!empty($field['required'])) <span class="text-rose-500">*</span> @endif
                                                    </label>
                                                    <span class="text-[10px] text-slate-400 font-mono" x-text="len + ' kar'"></span>
                                                </div>
                                                <input type="text"
                                                       wire:model.defer="formData.{{ $secKey }}.{{ $fName }}.{{ $locale }}"
                                                       x-init="len = $el.value.length"
                                                       @input="len = $el.value.length"
                                                       placeholder="Ketik {{ strtolower($fLabel) }} ({{ strtoupper($locale) }})..."
                                                       class="w-full px-3.5 py-2.5 rounded-xl text-sm bg-white dark:bg-slate-800 border border-slate-200 dark:border-white/10 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 text-slate-900 dark:text-white transition shadow-sm placeholder:text-slate-400" />
                                                @if ($fHelp)
                                                    <p class="text-[11px] text-slate-500">{{ $fHelp }}</p>
                                                @endif
                                            </div>

                                        {{-- 2. Translatable Long Textarea / Richtext --}}
                                        @elseif (in_array($fType, ['translatable_textarea', 'translatable_richtext'], true))
                                            <div class="space-y-1.5" x-data="{ len: 0 }">
                                                <div class="flex items-center justify-between">
                                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                                                        {{ $fLabel }}
                                                        @if (!empty($field['required'])) <span class="text-rose-500">*</span> @endif
                                                    </label>
                                                    <span class="text-[10px] text-slate-400 font-mono" x-text="len + ' kar'"></span>
                                                </div>
                                                <textarea rows="3"
                                                          wire:model.defer="formData.{{ $secKey }}.{{ $fName }}.{{ $locale }}"
                                                          x-init="len = $el.value.length"
                                                          @input="len = $el.value.length"
                                                          placeholder="Tulis deskripsi atau isi teks {{ strtolower($fLabel) }}..."
                                                          class="w-full px-3.5 py-2.5 rounded-xl text-sm bg-white dark:bg-slate-800 border border-slate-200 dark:border-white/10 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 text-slate-900 dark:text-white transition shadow-sm placeholder:text-slate-400"></textarea>
                                                @if ($fHelp)
                                                    <p class="text-[11px] text-slate-500">{{ $fHelp }}</p>
                                                @endif
                                            </div>

                                        {{-- 3. Boolean Switch --}}
                                        @elseif ($fType === 'boolean')
                                            <div class="flex items-center justify-between p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/40 border border-slate-200/80">
                                                <div>
                                                    <span class="text-xs font-bold text-slate-800 dark:text-slate-200 block">{{ $fLabel }}</span>
                                                    @if ($fHelp)
                                                        <span class="text-[11px] text-slate-500">{{ $fHelp }}</span>
                                                    @endif
                                                </div>
                                                <label class="relative inline-flex items-center cursor-pointer">
                                                    <input type="checkbox"
                                                           wire:model.defer="formData.{{ $secKey }}.{{ $fName }}"
                                                           class="sr-only peer">
                                                    <div class="w-10 h-5.5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4.5 after:w-4.5 after:transition-all peer-checked:bg-amber-500"></div>
                                                </label>
                                            </div>

                                        {{-- 4. Link Picker --}}
                                        @elseif ($fType === 'link')
                                            <div class="space-y-1.5">
                                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-200">
                                                    {{ $fLabel }}
                                                </label>
                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                                    <select wire:model.defer="formData.{{ $secKey }}.{{ $fName }}"
                                                            class="w-full px-3 py-2 rounded-xl text-xs bg-white dark:bg-slate-800 border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white">
                                                        <option value="">-- Pilih Halaman Tujuan --</option>
                                                        @foreach ($linkOptions as $groupName => $options)
                                                            <optgroup label="{{ $groupName }}">
                                                                @foreach ($options as $targetUrl => $targetTitle)
                                                                    <option value="{{ $targetUrl }}">{{ $targetTitle }}</option>
                                                                @endforeach
                                                            </optgroup>
                                                        @endforeach
                                                    </select>
                                                    <input type="text"
                                                           wire:model.defer="formData.{{ $secKey }}.{{ $fName }}"
                                                           placeholder="Atau ketik URL kustom (https://...)"
                                                           class="w-full px-3 py-2 rounded-xl text-xs bg-white dark:bg-slate-800 border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white" />
                                                </div>
                                                @if ($fHelp)
                                                    <p class="text-[11px] text-slate-500">{{ $fHelp }}</p>
                                                @endif
                                            </div>

                                        {{-- 5. Image Slot with Alt Text (ID & EN) --}}
                                        @elseif ($fType === 'image')
                                            @php
                                                $imgVal = $this->formData[$secKey][$fName] ?? null;
                                                $imgUrl = $imgVal ? \App\Support\ImageSource::resolve($imgVal)['url'] : null;
                                            @endphp
                                            <div class="p-4 rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50/50 dark:bg-slate-800/30 space-y-4">
                                                <div class="flex items-center justify-between">
                                                    <div>
                                                        <span class="text-xs font-bold text-slate-800 dark:text-slate-200 block">{{ $fLabel }}</span>
                                                        <span class="text-[11px] text-slate-500">Rekomendasi: format JPG, PNG, WebP, SVG (maks 5MB). Otomatis dikonversi ke WebP.</span>
                                                    </div>
                                                    @if ($imgVal)
                                                        <button type="button"
                                                                wire:click="removeImage('{{ $secKey }}', '{{ $fName }}')"
                                                                class="text-xs font-semibold text-rose-600 hover:text-rose-800">
                                                            Hapus Foto
                                                        </button>
                                                    @endif
                                                </div>

                                                <div class="flex flex-col sm:flex-row items-center gap-4">
                                                    @if ($imgUrl)
                                                        <div class="w-24 h-24 rounded-xl border border-slate-300 dark:border-white/20 overflow-hidden bg-white shrink-0 p-1 flex items-center justify-center shadow-sm">
                                                            <img src="{{ $imgUrl }}" alt="Preview" class="w-full h-full object-contain" />
                                                        </div>
                                                    @endif

                                                    <div class="flex-1 w-full">
                                                        <input type="file"
                                                               wire:model="tempUploads.{{ $secKey }}.{{ $fName }}"
                                                               accept="image/png,image/jpeg,image/webp,image/svg+xml"
                                                               class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-900 file:text-white hover:file:bg-slate-800 cursor-pointer" />
                                                        <span wire:loading wire:target="tempUploads.{{ $secKey }}.{{ $fName }}" class="text-[11px] text-blue-600">
                                                            Mengunggah foto...
                                                        </span>
                                                    </div>
                                                </div>

                                                {{-- Alt Text Fields (Wajib untuk SEO & Aksesibilitas) --}}
                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2 border-t border-slate-200/60 dark:border-white/5">
                                                    <div class="space-y-1">
                                                        <label class="text-[11px] font-semibold text-slate-600 dark:text-slate-300 flex items-center gap-1">
                                                            <span>🇮🇩 Teks Alt (Indonesia)</span>
                                                            <span class="text-rose-500 font-bold">*</span>
                                                        </label>
                                                        <input type="text"
                                                               wire:model.defer="formData.{{ $secKey }}.{{ $fName }}_alt.id"
                                                               placeholder="Deskripsi foto dalam Bahasa Indonesia..."
                                                               class="w-full px-3 py-1.5 rounded-lg text-xs bg-white dark:bg-slate-800 border border-slate-200 text-slate-900 dark:text-white" />
                                                    </div>
                                                    <div class="space-y-1">
                                                        <label class="text-[11px] font-semibold text-slate-600 dark:text-slate-300 flex items-center gap-1">
                                                            <span>🇬🇧 Alt Text (English)</span>
                                                            <span class="text-rose-500 font-bold">*</span>
                                                        </label>
                                                        <input type="text"
                                                               wire:model.defer="formData.{{ $secKey }}.{{ $fName }}_alt.en"
                                                               placeholder="Image description in English..."
                                                               class="w-full px-3 py-1.5 rounded-lg text-xs bg-white dark:bg-slate-800 border border-slate-200 text-slate-900 dark:text-white" />
                                                    </div>
                                                </div>
                                            </div>

                                        {{-- 6. Repeater / Daftar Item Diseret (Drag-and-Drop / Dashed Add Button) --}}
                                        @elseif ($fType === 'repeater')
                                            @php
                                                $items = $this->formData[$secKey][$fName] ?? [];
                                                $subFields = $field['fields'] ?? [];
                                            @endphp
                                            <div class="space-y-3 pt-2">
                                                <div class="flex items-center justify-between">
                                                    <div>
                                                        <span class="text-xs font-bold text-slate-800 dark:text-slate-200 block">{{ $fLabel }}</span>
                                                        <span class="text-[11px] text-slate-500">Urutkan posisi atau tambah item daftar sesuai kebutuhan.</span>
                                                    </div>
                                                    <span class="text-xs text-slate-400 font-semibold">{{ count($items) }} item</span>
                                                </div>

                                                <div class="space-y-3">
                                                    @foreach ($items as $idx => $itemRow)
                                                        <div wire:key="rep-{{ $secKey }}-{{ $fName }}-{{ $idx }}"
                                                             class="p-4 rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50/40 dark:bg-slate-800/20 space-y-3">
                                                            <div class="flex items-center justify-between pb-2 border-b border-slate-200/60 dark:border-white/5">
                                                                <span class="text-xs font-bold text-slate-700 dark:text-slate-300">
                                                                    #{{ $idx + 1 }} {{ $itemRow['label'][$locale] ?? $itemRow['title'][$locale] ?? 'Item' }}
                                                                </span>
                                                                <div class="flex items-center gap-1">
                                                                    @if ($idx > 0)
                                                                        <button type="button"
                                                                                wire:click="moveRepeaterItem('{{ $secKey }}', '{{ $fName }}', {{ $idx }}, {{ $idx - 1 }})"
                                                                                title="Geser ke Atas"
                                                                                class="p-1 rounded text-slate-400 hover:text-slate-600">
                                                                            ▲
                                                                        </button>
                                                                    @endif
                                                                    @if ($idx < count($items) - 1)
                                                                        <button type="button"
                                                                                wire:click="moveRepeaterItem('{{ $secKey }}', '{{ $fName }}', {{ $idx }}, {{ $idx + 1 }})"
                                                                                title="Geser ke Bawah"
                                                                                class="p-1 rounded text-slate-400 hover:text-slate-600">
                                                                            ▼
                                                                        </button>
                                                                    @endif
                                                                    <button type="button"
                                                                            wire:click="removeRepeaterItem('{{ $secKey }}', '{{ $fName }}', {{ $idx }})"
                                                                            class="text-xs font-semibold text-rose-600 hover:text-rose-800 px-2 py-0.5 ml-2">
                                                                        Hapus
                                                                    </button>
                                                                </div>
                                                            </div>

                                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                                                @foreach ($subFields as $sub)
                                                                    @php
                                                                        $sName = $sub['name'];
                                                                        $sType = $sub['type'] ?? 'text';
                                                                        $sLabel = $sub['label'] ?? ucfirst($sName);
                                                                    @endphp
                                                                    <div class="space-y-1">
                                                                        <label class="text-[11px] font-semibold text-slate-600 dark:text-slate-300">{{ $sLabel }}</label>
                                                                        @if (in_array($sType, ['translatable_text', 'text'], true))
                                                                            <input type="text"
                                                                                   wire:model.defer="formData.{{ $secKey }}.{{ $fName }}.{{ $idx }}.{{ $sName }}.{{ $locale }}"
                                                                                   placeholder="{{ $sLabel }}..."
                                                                                   class="w-full px-3 py-1.5 rounded-lg text-xs bg-white dark:bg-slate-800 border border-slate-200 text-slate-900 dark:text-white" />
                                                                        @elseif ($sType === 'link')
                                                                            <input type="text"
                                                                                   wire:model.defer="formData.{{ $secKey }}.{{ $fName }}.{{ $idx }}.{{ $sName }}"
                                                                                   placeholder="Tautan (page:about / https://...)"
                                                                                   class="w-full px-3 py-1.5 rounded-lg text-xs bg-white dark:bg-slate-800 border border-slate-200 text-slate-900 dark:text-white" />
                                                                        @elseif ($sType === 'boolean')
                                                                            <label class="relative inline-flex items-center cursor-pointer mt-1">
                                                                                <input type="checkbox"
                                                                                       wire:model.defer="formData.{{ $secKey }}.{{ $fName }}.{{ $idx }}.{{ $sName }}"
                                                                                       class="sr-only peer">
                                                                                <div class="w-8 h-4.5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-3.5 after:w-3.5 after:transition-all peer-checked:bg-amber-500"></div>
                                                                            </label>
                                                                        @elseif ($sType === 'image')
                                                                            @php
                                                                                $subImgVal = $itemRow[$sName] ?? null;
                                                                                $subImgUrl = $subImgVal ? \App\Support\ImageSource::resolve($subImgVal)['url'] : null;
                                                                            @endphp
                                                                            <div class="col-span-full p-2.5 rounded-lg border border-slate-200 dark:border-white/10 bg-white/70 dark:bg-slate-800/40 space-y-2">
                                                                                <div class="flex items-center justify-between">
                                                                                    <span class="text-[11px] font-semibold text-slate-700 dark:text-slate-200">{{ $sLabel }}</span>
                                                                                    @if ($subImgVal)
                                                                                        <button type="button"
                                                                                                wire:click="removeRepeaterImage('{{ $secKey }}', '{{ $fName }}', {{ $idx }}, '{{ $sName }}')"
                                                                                                class="text-[10px] font-semibold text-rose-600 hover:text-rose-800">
                                                                                            Hapus Foto
                                                                                        </button>
                                                                                    @endif
                                                                                </div>
                                                                                <div class="flex items-center gap-3">
                                                                                    @if ($subImgUrl)
                                                                                        <div class="w-12 h-12 rounded-lg border border-slate-300 dark:border-white/20 overflow-hidden bg-white shrink-0 p-0.5 flex items-center justify-center">
                                                                                            <img src="{{ $subImgUrl }}" alt="Preview" class="w-full h-full object-cover rounded" />
                                                                                        </div>
                                                                                    @endif
                                                                                    <div class="flex-1">
                                                                                        <input type="file"
                                                                                               wire:model="tempUploads.{{ $secKey }}.{{ $fName }}.{{ $idx }}.{{ $sName }}"
                                                                                               accept="image/png,image/jpeg,image/webp,image/svg+xml"
                                                                                               class="block w-full text-[11px] text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-slate-900 file:text-white hover:file:bg-slate-800 cursor-pointer" />
                                                                                    </div>
                                                                                </div>
                                                                                {{-- Alt text for repeater image --}}
                                                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 pt-1 border-t border-slate-200/50">
                                                                                    <div>
                                                                                        <label class="text-[10px] font-semibold text-slate-500">🇮🇩 Teks Alt (ID) *</label>
                                                                                        <input type="text"
                                                                                               wire:model.defer="formData.{{ $secKey }}.{{ $fName }}.{{ $idx }}.{{ $sName }}_alt.id"
                                                                                               placeholder="Deskripsi foto..."
                                                                                               class="w-full px-2 py-1 rounded text-[11px] bg-white dark:bg-slate-800 border border-slate-200 text-slate-900 dark:text-white" />
                                                                                    </div>
                                                                                    <div>
                                                                                        <label class="text-[10px] font-semibold text-slate-500">🇬🇧 Alt Text (EN) *</label>
                                                                                        <input type="text"
                                                                                               wire:model.defer="formData.{{ $secKey }}.{{ $fName }}.{{ $idx }}.{{ $sName }}_alt.en"
                                                                                               placeholder="Image description..."
                                                                                               class="w-full px-2 py-1 rounded text-[11px] bg-white dark:bg-slate-800 border border-slate-200 text-slate-900 dark:text-white" />
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>

                                                {{-- Dashed Add Button --}}
                                                <button type="button"
                                                        wire:click="addRepeaterItem('{{ $secKey }}', '{{ $fName }}')"
                                                        class="w-full py-3 px-4 rounded-xl border-2 border-dashed border-slate-300 dark:border-white/20 hover:border-amber-500 dark:hover:border-amber-400 text-slate-600 dark:text-slate-300 hover:text-amber-600 text-xs font-bold transition flex items-center justify-center gap-2 cursor-pointer bg-slate-50/50 hover:bg-amber-50/30">
                                                    <span class="text-base font-extrabold">+</span>
                                                    <span>Tambah {{ $fLabel }}</span>
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        {{-- Dimmed notice when section is toggled off --}}
                        <div class="p-6 bg-slate-50/50 dark:bg-slate-950/20 text-center space-y-1">
                            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400">
                                Section ini sedang dinonaktifkan dan tidak akan tampil ke pengunjung website publik.
                            </p>
                            <p class="text-[11px] text-slate-400">
                                Nyalakan saklar di kanan atas kartu bila ingin menampilkannya kembali.
                            </p>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</x-filament-panels::page>

<x-filament-panels::page>
    @php
        $tenant = \Filament\Facades\Filament::getTenant();
        $isSuperAdmin = auth()->user()?->isSuperAdmin() ?? false;
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
        $emptyId = $this->getMissingCountForLocale('id');
        $emptyEn = $this->getMissingCountForLocale('en');
    @endphp

    <div x-data="{
            unsaved: false,
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

        {{-- Sticky Header Bar --}}
        <div class="sticky top-16 z-20 flex flex-col md:flex-row md:items-center md:justify-between gap-4 p-4 md:px-6 rounded-2xl bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border border-slate-200/90 dark:border-white/10 shadow-sm transition-all">
            <div class="flex items-center gap-3.5 min-w-0">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center font-bold text-white shadow shrink-0"
                     style="background-color: {{ $accentColor }};">
                    <x-filament::icon icon="heroicon-o-wrench-screwdriver" class="w-6 h-6 text-white" />
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-2">
                        <h1 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight truncate">
                            Pengaturan Website
                        </h1>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300">
                            {{ $tenant?->name }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 truncate">
                        Kelola identitas dasar perusahaan, email kontak, dan SEO bawaan mesin pencari
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3 shrink-0">
                {{-- Language Switcher Pill Bar --}}
                <div class="inline-flex items-center p-1 rounded-xl bg-slate-100 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700">
                    <button type="button"
                            wire:click="setLocale('id')"
                            class="relative flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg transition-all {{ $locale === 'id' ? 'bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900' }}">
                        <span>🇮🇩 Indonesia</span>
                        @if ($locale !== 'id' && $emptyId > 0)
                            <span class="inline-flex items-center justify-center px-1.5 py-0.2 rounded-full text-[10px] font-bold bg-amber-500 text-white" title="{{ $emptyId }} kolom belum diisi">
                                {{ $emptyId }}
                            </span>
                        @endif
                    </button>
                    <button type="button"
                            wire:click="setLocale('en')"
                            class="relative flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg transition-all {{ $locale === 'en' ? 'bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900' }}">
                        <span>🇬🇧 English</span>
                        @if ($locale !== 'en' && $emptyEn > 0)
                            <span class="inline-flex items-center justify-center px-1.5 py-0.2 rounded-full text-[10px] font-bold bg-amber-500 text-white" title="{{ $emptyEn }} kolom belum diisi">
                                {{ $emptyEn }}
                            </span>
                        @endif
                    </button>
                </div>

                {{-- Save Button --}}
                <button type="button"
                        wire:click="save"
                        wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 px-5 py-2 rounded-xl text-xs font-bold text-white shadow hover:shadow-md transition-all active:scale-[0.98] disabled:opacity-50"
                        style="background-color: {{ $accentColor }};">
                    <x-filament::icon icon="heroicon-o-check" class="w-4 h-4" />
                    <span wire:loading.remove wire:target="save">Simpan Pengaturan</span>
                    <span wire:loading wire:target="save">Menyimpan...</span>
                </button>
            </div>
        </div>

        {{-- Form Cards Container --}}
        <div class="space-y-6">

            {{-- KARTU 1: Identitas Perusahaan --}}
            <div class="rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-white/10 p-6 shadow-sm space-y-5">
                <div class="flex items-center gap-3 border-b border-slate-100 dark:border-slate-800 pb-3">
                    <div class="p-2 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                        <x-filament::icon icon="heroicon-o-building-office-2" class="w-5 h-5" />
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-900 dark:text-white">Identitas Perusahaan</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Nama resmi, tagline, dan deskripsi singkat profil perusahaan</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Nama Resmi Perusahaan <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               wire:model.defer="name"
                               required
                               placeholder="Contoh: PT Korin Berkat Energy"
                               class="w-full px-3.5 py-2 text-sm rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-colors" />
                        @error('name') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div x-data="{ count: $wire.entangle('tagline.{{ $locale }}').defer ? ($wire.entangle('tagline.{{ $locale }}').defer.length || 0) : 0 }">
                        <div class="flex items-center justify-between mb-1">
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                                Tagline Singkat ({{ strtoupper($locale) }})
                            </label>
                            <span class="text-[11px] text-slate-400 font-mono" x-text="`${count}/255`"></span>
                        </div>
                        <input type="text"
                               wire:model.defer="tagline.{{ $locale }}"
                               x-on:input="count = $el.value.length"
                               maxlength="255"
                               placeholder="{{ $locale === 'id' ? 'Contoh: Mitra Terpercaya Solusi Energi dan Batubara' : 'Example: Trusted Partner in Energy & Coal Solutions' }}"
                               class="w-full px-3.5 py-2 text-sm rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-colors" />
                    </div>

                    <div x-data="{ count: $wire.entangle('about_short.{{ $locale }}').defer ? ($wire.entangle('about_short.{{ $locale }}').defer.length || 0) : 0 }">
                        <div class="flex items-center justify-between mb-1">
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                                Ringkasan Tentang Perusahaan ({{ strtoupper($locale) }})
                            </label>
                            <span class="text-[11px] text-slate-400 font-mono" x-text="`${count}/1000`"></span>
                        </div>
                        <textarea wire:model.defer="about_short.{{ $locale }}"
                                  x-on:input="count = $el.value.length"
                                  rows="3"
                                  maxlength="1000"
                                  placeholder="{{ $locale === 'id' ? 'Ringkasan singkat mengenai visi, misi, dan kegiatan usaha perusahaan...' : 'Brief summary of the company vision, mission, and activities...' }}"
                                  class="w-full px-3.5 py-2 text-sm rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-colors"></textarea>
                    </div>
                </div>
            </div>

            {{-- KARTU 2: Kontak & Formulir Website --}}
            <div class="rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-white/10 p-6 shadow-sm space-y-5">
                <div class="flex items-center gap-3 border-b border-slate-100 dark:border-slate-800 pb-3">
                    <div class="p-2 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                        <x-filament::icon icon="heroicon-o-envelope" class="w-5 h-5" />
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-900 dark:text-white">Kontak & Formulir Masuk</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Pengaturan tujuan pesan dari formulir kontak website</p>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                        Email Tujuan Formulir Kontak <span class="text-rose-500">*</span>
                    </label>
                    <input type="email"
                           wire:model.defer="contact_email"
                           required
                           placeholder="corsec@dercobara.co.id"
                           class="w-full px-3.5 py-2 text-sm rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-colors" />
                    <p class="text-[11px] text-slate-500 mt-1">
                        Setiap pesan yang dikirimkan oleh pengunjung melalui formulir halaman Kontak akan otomatis dikirimkan ke alamat email ini.
                    </p>
                    @error('contact_email') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- KARTU 3: SEO Bawaan (Google & Media Sosial) --}}
            <div class="rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-white/10 p-6 shadow-sm space-y-5">
                <div class="flex items-center gap-3 border-b border-slate-100 dark:border-slate-800 pb-3">
                    <div class="p-2 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                        <x-filament::icon icon="heroicon-o-magnifying-glass" class="w-5 h-5" />
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-900 dark:text-white">SEO Bawaan & Pratinjau Sosial</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Tampilan website saat ditemukan di Google atau dibagikan ke media sosial (WhatsApp, LinkedIn)</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div x-data="{ count: $wire.entangle('seo_title.{{ $locale }}').defer ? ($wire.entangle('seo_title.{{ $locale }}').defer.length || 0) : 0 }">
                        <div class="flex items-center justify-between mb-1">
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                                Judul di Google / Meta Title ({{ strtoupper($locale) }})
                            </label>
                            <span class="text-[11px] text-slate-400 font-mono" x-text="`${count}/100`"></span>
                        </div>
                        <input type="text"
                               wire:model.defer="seo_title.{{ $locale }}"
                               x-on:input="count = $el.value.length"
                               maxlength="100"
                               placeholder="Contoh: PT Korin Berkat Energy | Solusi Energi Terintegrasi"
                               class="w-full px-3.5 py-2 text-sm rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-colors" />
                        <p class="text-[11px] text-slate-400 mt-1">Disarankan 50–60 karakter agar tidak terpotong di hasil pencarian Google.</p>
                    </div>

                    <div x-data="{ count: $wire.entangle('seo_description.{{ $locale }}').defer ? ($wire.entangle('seo_description.{{ $locale }}').defer.length || 0) : 0 }">
                        <div class="flex items-center justify-between mb-1">
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                                Deskripsi di Google / Meta Description ({{ strtoupper($locale) }})
                            </label>
                            <span class="text-[11px] text-slate-400 font-mono" x-text="`${count}/255`"></span>
                        </div>
                        <textarea wire:model.defer="seo_description.{{ $locale }}"
                                  x-on:input="count = $el.value.length"
                                  rows="2"
                                  maxlength="255"
                                  placeholder="Deskripsi singkat yang tampil di bawah judul pada pencarian Google..."
                                  class="w-full px-3.5 py-2 text-sm rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-colors"></textarea>
                        <p class="text-[11px] text-slate-400 mt-1">Disarankan 120–160 karakter agar relevan dan menarik bagi pengunjung.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Gambar Pratinjau Media Sosial (Open Graph Image)
                        </label>
                        <div class="flex flex-col sm:flex-row gap-4 items-start">
                            @if ($seo_og_image || $tempOgImage)
                                <div class="relative w-40 h-24 rounded-xl overflow-hidden border border-slate-200 dark:border-slate-700 bg-slate-100 shrink-0 shadow-sm group">
                                    <img src="{{ $tempOgImage ? $tempOgImage->temporaryUrl() : \App\Support\FilamentThemeResolver::resolveAssetUrl($seo_og_image) }}"
                                         alt="Pratinjau Media Sosial"
                                         class="w-full h-full object-cover" />
                                    <button type="button"
                                            wire:click="removeOgImage"
                                            class="absolute top-1 right-1 p-1 rounded-full bg-rose-600 text-white shadow hover:bg-rose-700 transition-colors"
                                            title="Hapus gambar">
                                        <x-filament::icon icon="heroicon-o-trash" class="w-3.5 h-3.5" />
                                    </button>
                                </div>
                            @endif

                            <div class="flex-1 space-y-1">
                                <input type="file"
                                       wire:model="tempOgImage"
                                       accept="image/jpeg,image/png,image/webp"
                                       class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-100 dark:file:bg-slate-800 file:text-slate-700 dark:file:text-slate-300 hover:file:bg-slate-200 cursor-pointer" />
                                <p class="text-[11px] text-slate-400">
                                    Rekomendasi ukuran: 1200 x 630 px (rasio 1.91:1). Maksimal 5 MB. Otomatis dikonversi ke WebP berkualitas tinggi.
                                </p>
                                <div wire:loading wire:target="tempOgImage" class="text-xs text-amber-500 font-semibold">
                                    Mengunggah dan memproses gambar...
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KARTU 4: Pengaturan Sistem (Hanya Super Admin) --}}
            <div class="rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-white/10 p-6 shadow-sm space-y-5 {{ ! $isSuperAdmin ? 'opacity-80' : '' }}">
                <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3">
                    <div class="flex items-center gap-3">
                        <div class="p-2 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                            <x-filament::icon icon="heroicon-o-shield-check" class="w-5 h-5" />
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-900 dark:text-white">Pengaturan Sistem</h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Status penayangan publik dan tema visual perusahaan</p>
                        </div>
                    </div>

                    @if (! $isSuperAdmin)
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                            <x-filament::icon icon="heroicon-o-lock-closed" class="w-3.5 h-3.5" />
                            Khusus Super Admin
                        </span>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Status Tayang Website
                        </label>
                        <select wire:model.defer="status"
                                {{ ! $isSuperAdmin ? 'disabled' : '' }}
                                class="w-full px-3.5 py-2 text-sm rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-colors {{ ! $isSuperAdmin ? 'cursor-not-allowed bg-slate-100 dark:bg-slate-800/50' : '' }}">
                            <option value="draft">Draf (Belum Tayang)</option>
                            <option value="published">Tayang Publik (Published)</option>
                        </select>
                        <p class="text-[11px] text-slate-400 mt-1">
                            Jika berstatus draf, website hanya dapat diakses melalui pratinjau CMS.
                        </p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Tema Visual Website
                        </label>
                        <select wire:model.defer="theme"
                                {{ ! $isSuperAdmin ? 'disabled' : '' }}
                                class="w-full px-3.5 py-2 text-sm rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-colors {{ ! $isSuperAdmin ? 'cursor-not-allowed bg-slate-100 dark:bg-slate-800/50' : '' }}">
                            <option value="holding">Holding (PT Derco Bara Energy)</option>
                            <option value="korin-berkat">Korin Berkat Energy</option>
                            <option value="korin-bara">Korin Bara Energy</option>
                            <option value="beramal">Beramal Sukses Sejahtera</option>
                        </select>
                        <p class="text-[11px] text-slate-400 mt-1">
                            Menentukan struktur halaman, palet warna, tipografi, dan komponen section.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-filament-panels::page>

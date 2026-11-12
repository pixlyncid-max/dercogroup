<div class="cms-group-summary bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-sm">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
        <div class="flex items-center gap-2.5">
            <span class="w-2.5 h-6 rounded-full bg-[#EDB856]"></span>
            <div>
                <h3 class="text-base font-bold text-slate-900 tracking-tight">Ringkasan Grup Perusahaan</h3>
                <p class="text-xs text-slate-500">Kelola holding dan seluruh anak perusahaan dalam satu pintu administrasi</p>
            </div>
        </div>
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            1 Admin Mengelola Semua
        </span>
    </div>

    <div class="overflow-x-auto rounded-xl border border-slate-100">
        <table class="w-full text-left text-xs sm:text-sm">
            <thead class="bg-slate-50/80 text-slate-600 font-semibold uppercase tracking-wider text-[11px] border-b border-slate-100">
                <tr>
                    <th class="py-3 px-4">Perusahaan</th>
                    <th class="py-3 px-4">Status Tayang</th>
                    <th class="py-3 px-4 text-center">Pesan Baru</th>
                    <th class="py-3 px-4 text-center">Halaman Draft</th>
                    <th class="py-3 px-4 text-right">Aksi Cepat</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 font-normal">
                @php
                    $currentSlug = $this->getCurrentTenantSlug();
                @endphp
                @foreach ($this->getSummary() as $item)
                    @php
                        $isCurrent = $currentSlug === $item['slug'];
                    @endphp
                    <tr class="hover:bg-slate-50/60 transition-colors {{ $isCurrent ? 'bg-amber-50/30' : '' }}">
                        <td class="py-3.5 px-4">
                            <div class="flex items-center gap-3">
                                <div>
                                    <div class="font-bold text-slate-900 flex items-center gap-2">
                                        {{ $item['name'] }}
                                        @if ($item['is_holding'])
                                            <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase tracking-wider bg-slate-900 text-[#EDB856]">Holding</span>
                                        @endif
                                    </div>
                                    <div class="text-[11px] text-slate-400 font-mono">/{{ $item['slug'] }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-3.5 px-4">
                            @if ($item['is_published'])
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Published
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200/60">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    Draft
                                </span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            @if ($item['new_messages_count'] > 0)
                                <span class="inline-flex items-center justify-center min-w-[1.5rem] h-6 px-2 rounded-full text-xs font-bold bg-rose-100 text-rose-700">
                                    {{ $item['new_messages_count'] }}
                                </span>
                            @else
                                <span class="text-slate-400 font-medium">0</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            @if ($item['draft_pages_count'] > 0)
                                <span class="inline-flex items-center justify-center min-w-[1.5rem] h-6 px-2 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                                    {{ $item['draft_pages_count'] }}
                                </span>
                            @else
                                <span class="text-slate-400 font-medium">0</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-right">
                            @if ($isCurrent)
                                <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200 cursor-default">
                                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Sedang Aktif
                                </span>
                            @else
                                <a href="{{ $item['switch_url'] }}"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-[#EDB856] hover:bg-[#D9A038] text-slate-950 shadow-sm transition-all">
                                    <span>Kelola</span>
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                    </svg>
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


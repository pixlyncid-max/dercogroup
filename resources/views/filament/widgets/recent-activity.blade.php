@php
    $activities = $this->getActivities();
@endphp
<div class="cms-recent-activity bg-white rounded-xl border border-slate-200 p-5 sm:p-6 flex flex-col justify-between h-full">
    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
        <div>
            <h3 class="text-sm font-semibold text-slate-900">Aktivitas Terbaru</h3>
            <p class="text-xs text-slate-500 mt-0.5">Pesan masuk dan pembaruan terakhir</p>
        </div>

        <a href="{{ \App\Filament\Resources\ContactMessageResource::getUrl('index') }}"
           class="text-xs font-semibold text-slate-700 hover:text-slate-900">
            Lihat Semua
        </a>
    </div>

    <div class="divide-y divide-slate-100 mt-2 flex-1">
        @forelse ($activities as $item)
            <a href="{{ $item['url'] }}"
               class="flex items-start justify-between gap-3 py-3 hover:bg-slate-50 -mx-2 px-2 rounded-lg transition-colors">
                <div class="min-w-0 space-y-0.5">
                    <div class="text-xs font-semibold text-slate-900 truncate">
                        {{ $item['title'] }}
                    </div>
                    <div class="text-xs text-slate-500 truncate">
                        {{ $item['description'] }}
                    </div>
                </div>

                <span class="text-[11px] font-medium text-slate-400 shrink-0 tabular-nums">
                    {{ $item['time'] }}
                </span>
            </a>
        @empty
            <div class="py-8 text-center text-xs text-slate-500">
                Belum ada aktivitas terbaru untuk perusahaan ini.
            </div>
        @endforelse
    </div>
</div>

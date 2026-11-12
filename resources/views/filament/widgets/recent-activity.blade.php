<div class="cms-recent-activity bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-sm flex flex-col justify-between h-full">
    <!-- Header -->
    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h3 class="text-base font-bold text-slate-900 tracking-tight">Recent Activity</h3>
        </div>

        <a href="{{ \App\Filament\Resources\ContactMessageResource::getUrl('index') }}"
           class="text-xs font-semibold text-blue-600 hover:text-blue-700 flex items-center gap-1">
            <span>View All</span>
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
            </svg>
        </a>
    </div>

    <!-- Timeline List -->
    <div class="relative pl-6 space-y-5 pt-5 before:absolute before:left-2 before:top-6 before:bottom-4 before:w-0.5 before:bg-slate-100">
        @foreach ($this->getActivities() as $item)
            <div class="relative flex items-start justify-between gap-3 group">
                <!-- Blue Timeline Dot -->
                <div class="absolute -left-6 mt-1 w-2.5 h-2.5 rounded-full bg-blue-600 ring-4 ring-blue-50"></div>

                <div class="space-y-0.5">
                    <div class="text-xs font-bold text-slate-900 group-hover:text-blue-600 transition-colors">
                        {{ $item['title'] }}
                    </div>
                    <div class="text-xs text-slate-500 max-w-[240px] truncate">
                        {{ $item['description'] }}
                    </div>
                </div>

                <span class="text-[11px] font-medium text-slate-400 shrink-0">
                    {{ $item['time'] }}
                </span>
            </div>
        @endforeach
    </div>
</div>


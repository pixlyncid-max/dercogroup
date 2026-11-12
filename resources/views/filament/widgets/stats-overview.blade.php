<div class="cms-stat-cards grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    @foreach ($this->getMetrics() as $stat)
        <div class="cms-stat-card bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm hover:shadow-md transition-shadow flex items-center justify-between gap-4">
            <div class="space-y-3">
                <div class="flex items-center gap-3">
                    @if ($stat['color'] === 'blue')
                        <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    @elseif ($stat['color'] === 'amber')
                        <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                            </svg>
                        </div>
                    @elseif ($stat['color'] === 'purple')
                        <div class="w-11 h-11 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @else
                        <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                    @endif

                    <div>
                        <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            {{ $stat['label'] }}
                        </div>
                        <div class="flex items-baseline gap-2 mt-0.5">
                            <span class="text-2xl font-black text-slate-900 tracking-tight font-mono">
                                {{ $stat['value'] }}
                            </span>
                            <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full inline-flex items-center">
                                {{ $stat['trend'] }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mini Sparkline Curve -->
            <div class="w-20 h-10 shrink-0">
                @if ($stat['color'] === 'blue')
                    <svg viewBox="0 0 80 40" class="w-full h-full text-blue-500 overflow-visible" fill="none">
                        <path d="M0,32 Q15,38 30,22 T60,18 T80,8" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M0,32 Q15,38 30,22 T60,18 T80,8 L80,40 L0,40 Z" fill="currentColor" fill-opacity="0.08"/>
                    </svg>
                @elseif ($stat['color'] === 'amber')
                    <svg viewBox="0 0 80 40" class="w-full h-full text-amber-500 overflow-visible" fill="none">
                        <path d="M0,35 Q20,12 40,24 T80,10" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M0,35 Q20,12 40,24 T80,10 L80,40 L0,40 Z" fill="currentColor" fill-opacity="0.08"/>
                    </svg>
                @elseif ($stat['color'] === 'purple')
                    <svg viewBox="0 0 80 40" class="w-full h-full text-purple-500 overflow-visible" fill="none">
                        <path d="M0,30 Q25,35 45,15 T80,12" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M0,30 Q25,35 45,15 T80,12 L80,40 L0,40 Z" fill="currentColor" fill-opacity="0.08"/>
                    </svg>
                @else
                    <svg viewBox="0 0 80 40" class="w-full h-full text-emerald-500 overflow-visible" fill="none">
                        <path d="M0,28 Q20,32 40,18 T80,20" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M0,28 Q20,32 40,18 T80,20 L80,40 L0,40 Z" fill="currentColor" fill-opacity="0.08"/>
                    </svg>
                @endif
            </div>
        </div>
    @endforeach
</div>


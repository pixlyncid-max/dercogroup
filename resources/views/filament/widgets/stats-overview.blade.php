<div class="cms-stat-cards grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    @foreach ($this->getMetrics() as $stat)
        <a href="{{ $stat['url'] }}"
           class="cms-stat-card bg-white rounded-xl border border-slate-200 p-5 hover:border-slate-300 hover:bg-slate-50/50 transition-colors flex flex-col justify-between gap-2 block">
            <div class="text-xs font-medium text-slate-500">
                {{ $stat['label'] }}
            </div>
            <div class="flex items-baseline justify-between gap-2">
                <span class="text-2xl font-semibold text-slate-900 tracking-tight tabular-nums">
                    {{ $stat['value'] }}
                </span>
                @if (! empty($stat['trend']))
                    <span class="text-xs font-medium text-slate-500">
                        {{ $stat['trend'] }}
                    </span>
                @endif
            </div>
        </a>
    @endforeach
</div>

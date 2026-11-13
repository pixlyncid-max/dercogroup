@php
    $user = auth()->user();
    $tenant = \Filament\Facades\Filament::getTenant();
    $siteUrl = $tenant ? url('/' . $tenant->slug) : url('/id');
@endphp
<div class="cms-sidebar-bottom relative mt-auto px-4 pb-4 pt-3 border-t border-white/10">
    {{-- User brief profile --}}
    @if ($user)
        <div class="flex items-center gap-3 px-3 py-2.5 mb-2.5 rounded-xl bg-white/5 border border-white/10 text-white">
            <div class="w-8 h-8 rounded-full bg-amber-500/20 text-amber-300 font-bold flex items-center justify-center text-xs border border-amber-500/30 shrink-0">
                {{ strtoupper(substr($user->name, 0, 2)) }}
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-xs font-semibold text-white truncate">{{ $user->name }}</p>
                <p class="text-[10px] text-slate-400 truncate">{{ $user->roles->first()?->name ?? 'User' }}</p>
            </div>
        </div>
    @endif

    <div class="space-y-1.5">
        <a href="{{ $siteUrl }}" target="_blank" rel="noopener noreferrer"
           class="flex items-center justify-between gap-3 px-3.5 py-2 rounded-xl border border-white/10 bg-white/5 hover:bg-white/10 text-white text-xs font-semibold tracking-wide transition-all shadow-sm">
            <span class="flex items-center gap-2">
                <svg class="w-4 h-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <circle cx="12" cy="12" r="10" stroke-width="2"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2 12h20M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/>
                </svg>
                <span>Lihat Website</span>
            </span>
            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
            </svg>
        </a>

        <form method="POST" action="{{ filament()->getLogoutUrl() }}">
            @csrf
            <button type="submit"
                    class="w-full flex items-center gap-2.5 px-3.5 py-2 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 text-xs font-medium transition-colors cursor-pointer">
                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span>Logout</span>
            </button>
        </form>
    </div>
</div>

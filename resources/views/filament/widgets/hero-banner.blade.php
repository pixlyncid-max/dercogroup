<div class="cms-hero-banner relative overflow-hidden rounded-2xl min-h-[260px] md:min-h-[290px] flex flex-col justify-between p-6 sm:p-8 bg-slate-900 border border-slate-200/50 shadow-md">
    <!-- Mining Background Photo -->
    <div class="absolute inset-0 bg-cover bg-center"
         style="background-image: url('{{ asset('images/cms/dashboard-hero-banner.jpg') }}');"></div>

    <!-- Dark gradient overlay for text readability -->
    <div class="absolute inset-0 bg-gradient-to-r from-slate-950/95 via-slate-900/80 to-transparent"></div>

    <!-- Top Badge / Subtitle -->
    <div class="relative z-10 space-y-2">
        <span class="inline-block text-xs font-bold uppercase tracking-wider text-[#EDB856]">
            Korin Berkat Energy
        </span>
        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white tracking-tight leading-tight">
            Mining · Trading · Nickel
        </h2>
        <p class="text-xs sm:text-sm text-slate-300 max-w-md leading-relaxed mt-1">
            Building a sustainable future through responsible mining, strategic trading, and nickel resources.
        </p>
    </div>

    <!-- Bottom Actions & Indicators -->
    <div class="relative z-10 flex items-center justify-between mt-6 pt-2">
        <!-- Golden Button -->
        <a href="{{ url('/id') }}" target="_blank" rel="noopener noreferrer"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#EDB856] hover:bg-[#D9A038] text-slate-950 text-xs sm:text-sm font-bold shadow-md hover:shadow-lg transition-all">
            <span>View Website</span>
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
            </svg>
        </a>

        <!-- Carousel Pagination Dots -->
        <div class="flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-[#EDB856]"></span>
            <span class="w-2 h-2 rounded-full bg-white/30"></span>
            <span class="w-2 h-2 rounded-full bg-white/30"></span>
            <span class="w-2 h-2 rounded-full bg-white/30"></span>
        </div>
    </div>
</div>


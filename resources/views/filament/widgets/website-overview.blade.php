<div class="cms-website-overview bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-sm flex flex-col justify-between h-full">
    <!-- Card Header -->
    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
            <h3 class="text-base font-bold text-slate-900 tracking-tight">Website Overview</h3>
        </div>

        <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full border border-slate-200 bg-slate-50 text-xs font-semibold text-slate-700">
            <span>Last 30 Days</span>
            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>
    </div>

    <!-- Chart & Metrics Split -->
    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 pt-5 items-center">
        <!-- Left: Line Spline Chart -->
        <div class="md:col-span-8 flex flex-col justify-between">
            <div class="relative w-full h-44">
                <svg viewBox="0 0 400 160" class="w-full h-full overflow-visible" preserveAspectRatio="none">
                    <defs>
                        <linearGradient id="chartGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                            <stop offset="0%" stop-color="#3B82F6" stop-opacity="0.25"/>
                            <stop offset="100%" stop-color="#3B82F6" stop-opacity="0.0"/>
                        </linearGradient>
                    </defs>

                    <!-- Horizontal Grid lines -->
                    <line x1="0" y1="20" x2="400" y2="20" stroke="#F1F5F9" stroke-width="1"/>
                    <line x1="0" y1="70" x2="400" y2="70" stroke="#F1F5F9" stroke-width="1"/>
                    <line x1="0" y1="120" x2="400" y2="120" stroke="#F1F5F9" stroke-width="1"/>

                    <!-- Spline Area Fill -->
                    <path d="M 0,110 C 50,110 70,60 110,65 C 150,70 170,105 210,85 C 250,65 280,30 330,35 C 360,40 380,20 400,25 L 400,160 L 0,160 Z"
                          fill="url(#chartGradient)"/>

                    <!-- Spline Curve Line -->
                    <path d="M 0,110 C 50,110 70,60 110,65 C 150,70 170,105 210,85 C 250,65 280,30 330,35 C 360,40 380,20 400,25"
                          fill="none" stroke="#2563EB" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>

                    <!-- Data Point Circles -->
                    <circle cx="0" cy="110" r="4" fill="#FFFFFF" stroke="#2563EB" stroke-width="2.5"/>
                    <circle cx="110" cy="65" r="4" fill="#FFFFFF" stroke="#2563EB" stroke-width="2.5"/>
                    <circle cx="210" cy="85" r="4" fill="#FFFFFF" stroke="#2563EB" stroke-width="2.5"/>
                    <circle cx="330" cy="35" r="4" fill="#FFFFFF" stroke="#2563EB" stroke-width="2.5"/>
                    <circle cx="400" cy="25" r="4" fill="#FFFFFF" stroke="#2563EB" stroke-width="2.5"/>
                </svg>
            </div>

            <!-- X-Axis Labels -->
            <div class="flex justify-between text-[11px] font-semibold text-slate-400 mt-2">
                <span>Oct 12</span>
                <span>Oct 19</span>
                <span>Oct 26</span>
                <span>Nov 02</span>
                <span>Nov 09</span>
            </div>
        </div>

        <!-- Right: 3 Key Metrics List -->
        <div class="md:col-span-4 flex flex-col justify-center space-y-4 border-t md:border-t-0 md:border-l border-slate-100 pt-4 md:pt-0 md:pl-5">
            <!-- Metric 1 -->
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Total Visitors</div>
                        <div class="text-sm font-bold text-slate-900 font-mono">28,540</div>
                    </div>
                </div>
                <span class="text-xs font-semibold text-emerald-600">↑ +12.4%</span>
            </div>

            <!-- Metric 2 -->
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Avg. Session</div>
                        <div class="text-sm font-bold text-slate-900 font-mono">2m 18s</div>
                    </div>
                </div>
                <span class="text-xs font-semibold text-emerald-600">↑ +8.6%</span>
            </div>

            <!-- Metric 3 -->
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center shrink-0">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Bounce Rate</div>
                        <div class="text-sm font-bold text-slate-900 font-mono">42.3%</div>
                    </div>
                </div>
                <span class="text-xs font-semibold text-rose-600">↓ -1.2%</span>
            </div>
        </div>
    </div>
</div>


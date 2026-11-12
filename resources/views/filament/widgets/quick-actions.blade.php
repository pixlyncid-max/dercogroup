<div class="cms-quick-actions bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-sm flex flex-col justify-between h-full">
    <div class="flex items-center gap-2 mb-4">
        <span class="text-amber-500 font-bold text-lg">⚡</span>
        <h3 class="text-base font-bold text-slate-900 tracking-tight">Quick Actions</h3>
    </div>

    <div class="grid grid-cols-2 gap-3 flex-1">
        <!-- 1. Add Product -->
        <a href="{{ route('filament.admin.resources.products.index') }}"
           class="flex flex-col items-center justify-center p-3.5 rounded-xl border border-slate-100 bg-slate-50/60 hover:bg-slate-100/80 hover:border-blue-200 text-center transition-all group">
            <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
            </div>
            <span class="text-xs font-semibold text-slate-700 leading-tight">Add Product</span>
        </a>

        <!-- 2. Manage Pages -->
        <a href="{{ route('filament.admin.resources.pages.index') }}"
           class="flex flex-col items-center justify-center p-3.5 rounded-xl border border-slate-100 bg-slate-50/60 hover:bg-slate-100/80 hover:border-blue-200 text-center transition-all group">
            <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <span class="text-xs font-semibold text-slate-700 leading-tight">Manage Pages</span>
        </a>

        <!-- 3. Operations -->
        <a href="{{ route('filament.admin.resources.operations.index') }}"
           class="flex flex-col items-center justify-center p-3.5 rounded-xl border border-slate-100 bg-slate-50/60 hover:bg-slate-100/80 hover:border-emerald-200 text-center transition-all group">
            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <span class="text-xs font-semibold text-slate-700 leading-tight">Operations</span>
        </a>

        <!-- 4. Certifications -->
        <a href="{{ route('filament.admin.resources.certifications.index') }}"
           class="flex flex-col items-center justify-center p-3.5 rounded-xl border border-slate-100 bg-slate-50/60 hover:bg-slate-100/80 hover:border-purple-200 text-center transition-all group">
            <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <span class="text-xs font-semibold text-slate-700 leading-tight">Compliance</span>
        </a>

        <!-- 5. Messages -->
        <a href="{{ route('filament.admin.resources.contact-messages.index') }}"
           class="flex flex-col items-center justify-center p-3.5 rounded-xl border border-slate-100 bg-slate-50/60 hover:bg-slate-100/80 hover:border-emerald-200 text-center transition-all group">
            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <span class="text-xs font-semibold text-slate-700 leading-tight">Inquiries</span>
        </a>

        <!-- 6. View Website -->
        <a href="{{ url('/id') }}" target="_blank" rel="noopener noreferrer"
           class="flex flex-col items-center justify-center p-3.5 rounded-xl border border-slate-100 bg-slate-50/60 hover:bg-slate-100/80 hover:border-slate-300 text-center transition-all group">
            <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
            </div>
            <span class="text-xs font-semibold text-slate-700 leading-tight">View Website</span>
        </a>
    </div>
</div>

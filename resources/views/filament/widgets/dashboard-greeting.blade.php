<div class="cms-greeting-section flex flex-col md:flex-row md:items-center justify-between gap-4 py-2">
    <div>
        <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-2">
            <span>{{ $this->getGreeting() }}, {{ $this->getUserName() }}</span>
            <span class="inline-block animate-wave origin-[70%_70%]">👋</span>
        </h1>
        <p class="text-sm text-slate-500 mt-1 font-medium">
            Manage your website content and business information with ease.
        </p>
    </div>

    <div class="flex items-center md:items-end flex-col gap-1 text-slate-600"
         x-data="{
             time: '{{ $this->getFormattedTime() }}',
             init() {
                 setInterval(() => {
                     const d = new Date();
                     let h = d.getHours();
                     const m = String(d.getMinutes()).padStart(2, '0');
                     const ampm = h >= 12 ? 'PM' : 'AM';
                     h = h % 12 || 12;
                     this.time = `${String(h).padStart(2, '0')}:${m} ${ampm}`;
                 }, 1000);
             }
         }">
        <div class="flex items-center gap-1.5 text-xs text-slate-500 font-medium">
            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" stroke-width="2"/>
                <line x1="16" y1="2" x2="16" y2="6" stroke-width="2"/>
                <line x1="8" y1="2" x2="8" y2="6" stroke-width="2"/>
                <line x1="3" y1="10" x2="21" y2="10" stroke-width="2"/>
            </svg>
            <span>{{ $this->getFormattedDate() }}</span>
        </div>
        <div class="text-2xl font-extrabold text-slate-900 tracking-tight font-mono" x-text="time">
            {{ $this->getFormattedTime() }}
        </div>
    </div>
</div>


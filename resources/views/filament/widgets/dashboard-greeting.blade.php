<div class="cms-greeting-section flex flex-col md:flex-row md:items-center justify-between gap-4 py-1">
    <div>
        <h1 class="text-xl md:text-2xl font-semibold text-slate-900 tracking-tight">
            {{ $this->getGreeting() }}, {{ $this->getUserName() }}
        </h1>
        <p class="text-sm text-slate-500 mt-0.5">
            Kelola halaman, teks, gambar, dan informasi perusahaan dari satu tempat.
        </p>
    </div>

    <div class="flex items-center md:items-end flex-col gap-0.5 text-slate-600"
         x-data="{
             time: '{{ $this->getFormattedTime() }}',
             init() {
                 setInterval(() => {
                     const d = new Date();
                     const h = String(d.getHours()).padStart(2, '0');
                     const m = String(d.getMinutes()).padStart(2, '0');
                     this.time = `${h}:${m} WIB`;
                 }, 10000);
             }
         }">
        <div class="text-xs text-slate-500 font-medium">
            {{ $this->getFormattedDate() }}
        </div>
        <div class="text-lg font-semibold text-slate-900 tabular-nums" x-text="time">
            {{ $this->getFormattedTime() }}
        </div>
    </div>
</div>

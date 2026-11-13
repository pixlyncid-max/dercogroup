@php
    $tenant = \Filament\Facades\Filament::getTenant();
    $unreadMessages = $tenant ? \App\Domain\Contact\Models\ContactMessage::where('company_id', $tenant->id)->where('status', \App\Domain\Contact\Enums\ContactMessageStatus::New)->count() : 0;
    $messagesUrl = $tenant ? route('filament.admin.resources.contact-messages.index', ['tenant' => $tenant]) : '#';
@endphp
<div class="flex items-center mr-2">
    <a href="{{ $messagesUrl }}"
       title="{{ $unreadMessages }} Pesan Masuk Baru"
       class="relative p-2 rounded-xl text-slate-400 hover:text-slate-200 hover:bg-white/5 transition-colors">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>
        @if ($unreadMessages > 0)
            <span class="absolute top-1.5 right-1.5 flex h-4 min-w-4 items-center justify-center rounded-full bg-rose-500 px-1 text-[10px] font-bold text-white shadow">
                {{ $unreadMessages }}
            </span>
        @endif
    </a>
</div>


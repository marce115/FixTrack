@extends('layouts.app')

@section('title', 'Semua Notifikasi')
@section('header-title', 'Pusat Notifikasi')
@section('header-subtitle', 'Daftar riwayat pemberitahuan status tiket dan interaksi diskusi')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Top Action Card -->
    <div class="p-6 rounded-2xl bg-[#1C2330] border border-[#263143] shadow-lg flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-lg font-bold text-white flex items-center gap-2">
                <i data-lucide="bell" class="w-5 h-5 text-blue-400"></i>
                <span>Pemberitahuan Akun Anda</span>
            </h1>
            <p class="text-xs text-slate-400 mt-1">
                Pantau pembaruan status perbaikan, catatan admin, dan tanggapan diskusi tiket.
            </p>
        </div>

        @if(Auth::user()->unreadNotificationsCount() > 0)
            <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                @csrf
                <button type="submit" class="px-4 py-2 rounded-xl bg-[#121620] hover:bg-[#222B3C] border border-[#263143] text-xs font-semibold text-blue-400 hover:text-blue-300 transition flex items-center gap-2">
                    <i data-lucide="check-check" class="w-4 h-4"></i>
                    <span>Tandai Semua Dibaca</span>
                </button>
            </form>
        @endif
    </div>

    <!-- Notification List -->
    <div class="rounded-2xl bg-[#1C2330] border border-[#263143] shadow-xl overflow-hidden">
        <div class="divide-y divide-[#263143]/60">
            @forelse($notifications as $notif)
                <div class="p-4 sm:p-5 hover:bg-[#222B3C]/50 transition flex items-start justify-between gap-4 {{ !$notif->is_read ? 'bg-blue-600/5' : '' }}">
                    <div class="flex items-start gap-4 flex-1">
                        <div class="p-2.5 rounded-xl {{ $notif->type === 'status_updated' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : ($notif->type === 'new_comment' ? 'bg-blue-500/10 text-blue-400 border border-blue-500/20' : 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20') }} shrink-0">
                            @if($notif->type === 'status_updated')
                                <i data-lucide="refresh-cw" class="w-5 h-5"></i>
                            @elseif($notif->type === 'new_comment')
                                <i data-lucide="message-square" class="w-5 h-5"></i>
                            @else
                                <i data-lucide="file-plus" class="w-5 h-5"></i>
                            @endif
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap mb-1">
                                <h3 class="text-sm font-bold {{ !$notif->is_read ? 'text-white' : 'text-slate-300' }}">
                                    {{ $notif->title }}
                                </h3>
                                @if(!$notif->is_read)
                                    <span class="px-2 py-0.5 rounded-full bg-blue-500/10 text-blue-400 border border-blue-500/20 text-[10px] font-bold">
                                        Baru
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs text-slate-300 leading-relaxed">
                                {{ $notif->message }}
                            </p>
                            <span class="text-[11px] text-slate-500 font-mono mt-2 block">
                                {{ $notif->created_at->isoFormat('D MMMM Y, HH:mm') }} &bull; {{ $notif->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>

                    @if($notif->link)
                        <a href="{{ route('notifications.read', $notif) }}" 
                           class="px-3 py-1.5 rounded-xl bg-blue-600/10 hover:bg-blue-600 text-blue-400 hover:text-white border border-blue-500/20 text-xs font-semibold transition shrink-0 flex items-center gap-1.5 self-center">
                            <span>Buka</span>
                            <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                        </a>
                    @endif
                </div>
            @empty
                <div class="p-12 text-center text-slate-400">
                    <i data-lucide="bell-off" class="w-12 h-12 text-slate-600 mx-auto mb-3"></i>
                    <h3 class="text-sm font-bold text-white mb-1">Belum Ada Notifikasi</h3>
                    <p class="text-xs text-slate-400">Pemberitahuan terkait status tiket dan obrolan akan muncul di sini.</p>
                </div>
            @endforelse
        </div>

        @if($notifications->hasPages())
            <div class="p-4 border-t border-[#263143] bg-[#151922]">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>

</div>
@endsection


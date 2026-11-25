@extends('layouts.app')

@section('title', 'Detail Laporan ' . $report->ticket_number)
@section('header-title', 'Detail Tiket ' . $report->ticket_number)
@section('header-subtitle', 'Informasi lengkap masalah, komparasi foto Before & After, dan ruang diskusi tiket')

@section('content')
<div class="space-y-6" x-data="{ photoModal: false, completionModal: false, statusModal: false, selectedStatus: '{{ $report->status }}' }">

    <!-- Top Action & Status Bar -->
    <div class="p-5 sm:p-6 rounded-2xl bg-[#1C2330] border border-[#263143] shadow-lg flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex flex-wrap items-center gap-2 mb-1.5">
                <span class="font-mono text-base font-bold text-blue-400">{{ $report->ticket_number }}</span>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $report->priority_badge_color }}">
                    Prioritas: {{ $report->priority }}
                </span>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $report->status_badge_color }}">
                    Status: {{ $report->status }}
                </span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-white tracking-tight">{{ $report->item_name }}</h1>
            <p class="text-xs text-slate-400 mt-0.5">
                Diajukan oleh <span class="text-slate-200 font-medium">{{ $report->user->name }}</span> pada {{ $report->created_at->isoFormat('D MMMM Y, HH:mm') }} WIB
            </p>
        </div>

        <div class="flex items-center gap-2 shrink-0 flex-wrap">
            <a href="{{ route('reports.index') }}" 
               class="px-3.5 py-2 rounded-xl bg-[#121620] hover:bg-[#263143] text-slate-300 hover:text-white border border-[#263143] text-xs font-semibold transition flex items-center gap-1.5">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                <span>Kembali</span>
            </a>

            <a href="#discussion" 
               class="px-3.5 py-2 rounded-xl bg-[#121620] hover:bg-[#263143] text-blue-400 hover:text-blue-300 border border-[#263143] text-xs font-semibold transition flex items-center gap-1.5">
                <i data-lucide="message-square" class="w-4 h-4"></i>
                <span>Diskusi ({{ $report->comments->count() }})</span>
            </a>

            @if(Auth::user()->isAdmin())
                <button @click="statusModal = true" 
                        class="px-3.5 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold transition shadow-lg shadow-blue-600/20 flex items-center gap-1.5">
                    <i data-lucide="sliders" class="w-4 h-4"></i>
                    <span>Ubah Status & Foto Bukti</span>
                </button>
            @endif
        </div>
    </div>

    <!-- Catatan Penanganan Admin Banner (Jika Ada) -->
    @if($report->handling_notes)
        <div class="p-5 rounded-2xl bg-blue-950/20 border border-blue-500/30 text-blue-300 shadow-lg">
            <div class="flex items-center gap-2 font-bold text-xs uppercase tracking-wider text-blue-400 mb-1.5">
                <i data-lucide="message-square-check" class="w-4 h-4"></i>
                <span>Catatan Penanganan dari Admin</span>
            </div>
            <p class="text-sm text-slate-200 leading-relaxed whitespace-pre-line">{{ $report->handling_notes }}</p>
        </div>
    @endif

    <!-- Main Grid: Info Details on Left, Timeline on Right -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left 2 Cols: Details, Before vs After, and Discussion -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Problem Description Card -->
            <div class="p-6 rounded-2xl bg-[#1C2330] border border-[#263143] shadow-lg">
                <h3 class="text-xs font-bold uppercase tracking-wider text-blue-400 mb-3 flex items-center gap-2">
                    <i data-lucide="file-text" class="w-4 h-4"></i>
                    <span>Deskripsi Masalah</span>
                </h3>

                <div class="p-4 rounded-xl bg-[#121620] border border-[#263143] text-sm text-slate-200 whitespace-pre-line leading-relaxed">
                    {{ $report->description }}
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-5 gap-4 mt-4 pt-4 border-t border-[#263143]/60 text-xs">
                    <div>
                        <span class="text-slate-400 block text-[11px]">Kode Inventaris</span>
                        <span class="font-mono font-semibold text-blue-400 mt-0.5 inline-block">{{ $report->inventory_code ?: '-' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[11px]">Jenis Masalah</span>
                        <span class="font-semibold text-white mt-0.5 inline-block">{{ $report->problem_type }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[11px]">Jumlah Barang</span>
                        <span class="font-semibold text-white mt-0.5 inline-block">{{ $report->quantity }} Unit</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[11px]">Kategori</span>
                        <span class="font-semibold text-white mt-0.5 inline-block">{{ $report->category->name ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[11px]">Lokasi</span>
                        <span class="font-semibold text-white mt-0.5 inline-block">{{ $report->location }}</span>
                    </div>
                </div>
            </div>

            <!-- Before vs After Comparison Card -->
            <div class="p-6 rounded-2xl bg-[#1C2330] border border-[#263143] shadow-lg space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-emerald-400 flex items-center gap-2">
                        <i data-lucide="split" class="w-4 h-4"></i>
                        <span>Komparasi Visual: Before vs After</span>
                    </h3>
                    <span class="text-[11px] text-slate-400">Bukti kondisi sebelum & sesudah penanganan</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- BEFORE CARD -->
                    <div class="p-4 rounded-xl bg-[#121620] border border-[#263143] space-y-3 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-rose-500/10 text-rose-400 border border-rose-500/20">
                                    Before (Kerusakan)
                                </span>
                                <span class="text-[10px] text-slate-500 font-mono">Foto Pelapor</span>
                            </div>

                            @if($report->photo_path)
                                <div class="relative group cursor-pointer w-full h-48 rounded-xl overflow-hidden border border-[#263143] bg-black"
                                     @click="photoModal = true">
                                    <img src="{{ asset('storage/' . $report->photo_path) }}" 
                                         alt="Foto Kerusakan Awal" 
                                         class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white text-xs font-semibold gap-1.5">
                                        <i data-lucide="zoom-in" class="w-5 h-5"></i>
                                        <span>Perbesar (Before)</span>
                                    </div>
                                </div>
                            @else
                                <div class="h-48 flex flex-col items-center justify-center text-slate-500 text-xs border border-dashed border-[#263143] rounded-xl">
                                    <i data-lucide="image-off" class="w-8 h-8 mb-1"></i>
                                    <span>Tidak ada foto kerusakan awal</span>
                                </div>
                            @endif
                        </div>

                        <p class="text-[11px] text-slate-400">Diunggah oleh pelapor saat pembuatan tiket insiden.</p>
                    </div>

                    <!-- AFTER CARD -->
                    <div class="p-4 rounded-xl bg-[#121620] border border-[#263143] space-y-3 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                    After (Hasil Selesai)
                                </span>
                                <span class="text-[10px] text-slate-500 font-mono">Foto Admin</span>
                            </div>

                            @if($report->completion_photo_path)
                                <div class="relative group cursor-pointer w-full h-48 rounded-xl overflow-hidden border border-emerald-500/30 bg-black"
                                     @click="completionModal = true">
                                    <img src="{{ asset('storage/' . $report->completion_photo_path) }}" 
                                         alt="Foto Hasil Selesai" 
                                         class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white text-xs font-semibold gap-1.5">
                                        <i data-lucide="zoom-in" class="w-5 h-5"></i>
                                        <span>Perbesar (After)</span>
                                    </div>
                                </div>
                            @else
                                <div class="h-48 flex flex-col items-center justify-center text-slate-500 text-xs border border-dashed border-[#263143] rounded-xl text-center px-4">
                                    <i data-lucide="camera" class="w-8 h-8 mb-1 text-slate-600"></i>
                                    <span class="font-medium text-slate-400">Belum ada foto penyelesaian</span>
                                    <span class="text-[10px] text-slate-500 mt-1">Admin dapat mengunggahnya saat memperbarui status laporan menjadi Selesai.</span>
                                </div>
                            @endif
                        </div>

                        <p class="text-[11px] text-slate-400">Bukti fisik bahwa penanganan perbaikan atau penggantian telah tuntas.</p>
                    </div>
                </div>
            </div>

            <!-- Discussion & Comments Section -->
            <div id="discussion" class="p-6 rounded-2xl bg-[#1C2330] border border-[#263143] shadow-lg space-y-5">
                <div class="flex items-center justify-between border-b border-[#263143] pb-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-blue-400 flex items-center gap-2">
                        <i data-lucide="messages-square" class="w-4 h-4"></i>
                        <span>Ruang Diskusi & Komunikasi Tiket</span>
                    </h3>
                    <span class="px-2.5 py-0.5 rounded-full bg-blue-500/10 text-blue-400 border border-blue-500/20 text-xs font-bold font-mono">
                        {{ $report->comments->count() }} Pesan
                    </span>
                </div>

                <!-- Comment Thread -->
                <div class="space-y-4 max-h-[500px] overflow-y-auto pr-1">
                    @forelse($report->comments as $comment)
                        <div id="comment-{{ $comment->id }}" class="flex items-start gap-3.5 {{ $comment->user_id === Auth::id() ? 'flex-row-reverse' : '' }}">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center text-xs font-bold shrink-0 {{ $comment->user->isAdmin() ? 'bg-amber-500/20 text-amber-400 border border-amber-500/30' : 'bg-blue-600/20 text-blue-400 border border-blue-500/30' }}">
                                {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                            </div>

                            <div class="max-w-[80%] space-y-1 {{ $comment->user_id === Auth::id() ? 'items-end' : '' }}">
                                <div class="flex items-center gap-2 {{ $comment->user_id === Auth::id() ? 'justify-end' : '' }}">
                                    <span class="text-xs font-bold text-white">{{ $comment->user->name }}</span>
                                    <span class="px-2 py-0.2 rounded-full text-[9px] font-bold uppercase tracking-wider {{ $comment->user->isAdmin() ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : 'bg-blue-500/10 text-blue-400 border border-blue-500/20' }}">
                                        {{ $comment->user->role }}
                                    </span>
                                    <span class="text-[10px] text-slate-500 font-mono">
                                        {{ $comment->created_at->diffForHumans() }}
                                    </span>
                                </div>

                                <div class="p-3.5 rounded-2xl text-xs sm:text-sm leading-relaxed whitespace-pre-line {{ $comment->user_id === Auth::id() ? 'bg-blue-600 text-white rounded-tr-none shadow-lg shadow-blue-600/20' : ($comment->user->isAdmin() ? 'bg-[#151922] border border-amber-500/30 text-slate-200 rounded-tl-none' : 'bg-[#121620] border border-[#263143] text-slate-200 rounded-tl-none') }}">
                                    {{ $comment->comment }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-slate-400 text-xs border border-dashed border-[#263143] rounded-xl bg-[#121620]">
                            <i data-lucide="message-square-dashed" class="w-8 h-8 text-slate-600 mx-auto mb-2"></i>
                            <p class="font-medium text-slate-300">Belum ada diskusi untuk tiket ini.</p>
                            <p class="text-slate-500 text-[11px] mt-0.5">Mulai obrolan untuk mengonfirmasi detail atau bertanya seputar proses perbaikan.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Comment Input Form -->
                <form action="{{ route('reports.comments.store', $report) }}" method="POST" class="pt-4 border-t border-[#263143] space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1.5">Tulis Komentar / Tanggapan</label>
                        <textarea name="comment" rows="3" required placeholder="Tuliskan pertanyaan, informasi tambahan, atau respon penanganan di sini..." class="w-full px-3.5 py-2.5 bg-[#121620] border border-[#263143] rounded-xl text-xs sm:text-sm text-white focus:outline-none focus:border-blue-500 transition leading-relaxed"></textarea>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-[11px] text-slate-500">Pesan dapat dibaca oleh Admin dan Pelapor laporan ini.</span>
                        <button type="submit" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold transition shadow-lg shadow-blue-600/20 flex items-center gap-1.5">
                            <i data-lucide="send" class="w-3.5 h-3.5"></i>
                            <span>Kirim Tanggapan</span>
                        </button>
                    </div>
                </form>
            </div>

        </div>

        <!-- Right Col: Timeline & Reporter Details -->
        <div class="space-y-6">

            <!-- Reporter Profile Widget -->
            <div class="p-5 rounded-2xl bg-[#1C2330] border border-[#263143] shadow-lg">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Informasi Pelapor</h4>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-600/20 text-blue-400 font-bold flex items-center justify-center border border-blue-500/30">
                        {{ strtoupper(substr($report->user->name, 0, 1)) }}
                    </div>
                    <div class="text-xs">
                        <div class="font-bold text-white">{{ $report->user->name }}</div>
                        <div class="text-slate-400">{{ $report->user->email }}</div>
                        <div class="text-[11px] text-slate-500 mt-0.5">Kontak: {{ $report->user->phone ?? '-' }}</div>
                    </div>
                </div>
            </div>

            <!-- Status Progression Timeline -->
            <div class="p-6 rounded-2xl bg-[#1C2330] border border-[#263143] shadow-lg">
                <h3 class="text-xs font-bold uppercase tracking-wider text-purple-400 flex items-center gap-2 mb-4">
                    <i data-lucide="git-commit" class="w-4 h-4"></i>
                    <span>Riwayat Perkembangan Status</span>
                </h3>

                <div class="relative pl-6 space-y-5 before:absolute before:left-2.5 before:top-2 before:bottom-2 before:w-0.5 before:bg-[#263143]">
                    @forelse($report->histories as $hist)
                        <div class="relative">
                            <div class="absolute -left-6 top-0 w-5 h-5 rounded-full bg-[#151922] border-2 border-blue-500 flex items-center justify-center">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-400"></span>
                            </div>

                            <div>
                                <div class="flex items-center justify-between text-xs mb-0.5">
                                    <span class="font-bold text-white">{{ $hist->action }}</span>
                                    <span class="text-[10px] text-slate-500">{{ $hist->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="text-[11px] text-slate-400 mb-1">
                                    Oleh: <span class="text-slate-300 font-medium">{{ $hist->user->name ?? 'Sistem' }}</span>
                                </div>
                                @if($hist->notes)
                                    <div class="p-2.5 rounded-lg bg-[#121620] border border-[#263143] text-xs text-slate-300">
                                        {{ $hist->notes }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 py-3 text-center">Belum ada riwayat aktivitas.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Modal 1: Image Lightbox (Before) -->
    @if($report->photo_path)
        <div x-show="photoModal" 
             x-transition 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/85 backdrop-blur-md" 
             style="display: none;"
             @click.self="photoModal = false">
            <div class="relative max-w-4xl max-h-[90vh] rounded-2xl overflow-hidden border border-[#263143] bg-[#1C2330]">
                <div class="p-3 bg-[#151922] border-b border-[#263143] flex items-center justify-between text-xs text-slate-300">
                    <span class="font-semibold text-rose-400 flex items-center gap-2">
                        <i data-lucide="image" class="w-4 h-4"></i>
                        <span>Foto Kerusakan (Before) &mdash; {{ $report->ticket_number }}</span>
                    </span>
                    <button @click="photoModal = false" class="text-slate-400 hover:text-white p-1">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                <div class="p-2">
                    <img src="{{ asset('storage/' . $report->photo_path) }}" alt="Foto Kerusakan Penuh" class="max-h-[75vh] w-auto mx-auto object-contain rounded-lg">
                </div>
            </div>
        </div>
    @endif

    <!-- Modal 2: Image Lightbox (After) -->
    @if($report->completion_photo_path)
        <div x-show="completionModal" 
             x-transition 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/85 backdrop-blur-md" 
             style="display: none;"
             @click.self="completionModal = false">
            <div class="relative max-w-4xl max-h-[90vh] rounded-2xl overflow-hidden border border-[#263143] bg-[#1C2330]">
                <div class="p-3 bg-[#151922] border-b border-[#263143] flex items-center justify-between text-xs text-slate-300">
                    <span class="font-semibold text-emerald-400 flex items-center gap-2">
                        <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                        <span>Foto Hasil Penanganan (After) &mdash; {{ $report->ticket_number }}</span>
                    </span>
                    <button @click="completionModal = false" class="text-slate-400 hover:text-white p-1">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                <div class="p-2">
                    <img src="{{ asset('storage/' . $report->completion_photo_path) }}" alt="Foto Selesai Penuh" class="max-h-[75vh] w-auto mx-auto object-contain rounded-lg">
                </div>
            </div>
        </div>
    @endif

    <!-- Modal 3: Update Status & Handling Notes & Upload Completion Photo (Admin Only) -->
    @if(Auth::user()->isAdmin())
        <div x-show="statusModal" 
             x-transition 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm" 
             style="display: none;"
             @click.self="statusModal = false">
            <div class="w-full max-w-lg bg-[#1C2330] border border-[#263143] rounded-2xl shadow-2xl p-6">
                <div class="flex items-center justify-between pb-4 border-b border-[#263143] mb-4">
                    <h3 class="text-sm font-bold text-white flex items-center gap-2">
                        <i data-lucide="sliders" class="w-4 h-4 text-blue-400"></i>
                        <span>Ubah Status & Foto Bukti Selesai</span>
                    </h3>
                    <button @click="statusModal = false" class="text-slate-400 hover:text-white">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <form action="{{ route('reports.update-status', $report) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1.5">Status Laporan <span class="text-rose-400">*</span></label>
                        <select name="status" x-model="selectedStatus" required class="w-full px-3.5 py-2.5 bg-[#121620] border border-[#263143] rounded-xl text-xs sm:text-sm text-white focus:outline-none focus:border-blue-500">
                            <option value="Pending">Pending (Laporan Baru Diterima)</option>
                            <option value="Sedang Diproses">Sedang Diproses (Sedang Ditangani)</option>
                            <option value="Selesai">Selesai (Masalah Telah Diselesaikan)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1.5">Ubah Prioritas (Opsional)</label>
                        <select name="priority" class="w-full px-3.5 py-2.5 bg-[#121620] border border-[#263143] rounded-xl text-xs sm:text-sm text-white focus:outline-none focus:border-blue-500">
                            <option value="">-- Tetap ({{ $report->priority }}) --</option>
                            <option value="Rendah">Rendah</option>
                            <option value="Sedang">Sedang</option>
                            <option value="Tinggi">Tinggi</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1.5">Catatan Penanganan</label>
                        <textarea name="handling_notes" rows="3" placeholder="Tuliskan catatan tindak lanjut, langkah perbaikan, atau alasan penolakan..." class="w-full px-3.5 py-2.5 bg-[#121620] border border-[#263143] rounded-xl text-xs sm:text-sm text-white focus:outline-none focus:border-blue-500">{{ old('handling_notes', $report->handling_notes) }}</textarea>
                        <p class="text-[11px] text-slate-500 mt-1">Catatan ini akan dapat dibaca langsung oleh pelapor laporan.</p>
                    </div>

                    <!-- Upload Foto Hasil Penanganan (After) -->
                    <div class="p-3.5 rounded-xl bg-[#121620] border border-[#263143] space-y-2">
                        <label class="block text-xs font-semibold text-emerald-400 flex items-center gap-1.5">
                            <i data-lucide="camera" class="w-4 h-4"></i>
                            <span>Unggah Foto Bukti Selesai (After) (Opsional)</span>
                        </label>
                        <input type="file" name="completion_photo" accept="image/*" class="w-full text-xs text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-600/20 file:text-emerald-400 hover:file:bg-emerald-600/30 file:cursor-pointer bg-[#151922] border border-[#263143] rounded-xl">
                        <p class="text-[10px] text-slate-500">Format: JPG, PNG, WEBP (Maksimal 5MB). Foto ini akan dipajang pada komparasi Before vs After.</p>
                    </div>

                    <div class="pt-3 border-t border-[#263143] flex items-center justify-end gap-2">
                        <button type="button" @click="statusModal = false" class="px-4 py-2 rounded-xl bg-[#121620] text-slate-300 text-xs font-semibold">Batal</button>
                        <button type="submit" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold transition">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
@endsection

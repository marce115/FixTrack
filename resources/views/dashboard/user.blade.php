@extends('layouts.app')

@section('title', 'Dashboard Saya')
@section('header-title', 'Dashboard Pelapor')
@section('header-subtitle', 'Pantau status penanganan dan riwayat laporan kerusakan barang Anda')

@section('content')
<div class="space-y-6">

    <!-- Top Action Banner -->
    <div class="p-6 rounded-2xl bg-gradient-to-r from-blue-900/40 via-[#1C2330] to-[#1C2330] border border-blue-500/20 shadow-xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-white flex items-center gap-2">
                <span>Halo, {{ Auth::user()->name }}</span>
            </h1>
            <p class="text-xs sm:text-sm text-slate-400 mt-1">
                Ada barang rusak, hilang, atau kekurangan stok di area kerja Anda? Buat laporan segera.
            </p>
        </div>
        <a href="{{ route('reports.create') }}" 
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-semibold text-xs sm:text-sm transition shadow-lg shadow-blue-600/30 shrink-0">
            <i data-lucide="plus-circle" class="w-4 h-4"></i>
            <span>Buat Laporan Baru</span>
        </a>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Laporan Saya -->
        <div class="p-5 rounded-2xl bg-[#1C2330] border border-[#263143] shadow-lg flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-400">Total Laporan Saya</p>
                <h3 class="text-2xl font-extrabold text-white mt-1">{{ $totalReports }}</h3>
            </div>
            <div class="w-11 h-11 rounded-xl bg-blue-500/10 text-blue-400 flex items-center justify-center">
                <i data-lucide="clipboard-list" class="w-5 h-5"></i>
            </div>
        </div>

        <!-- Pending -->
        <div class="p-5 rounded-2xl bg-[#1C2330] border border-[#263143] shadow-lg flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-400">Menunggu (Pending)</p>
                <h3 class="text-2xl font-extrabold text-blue-400 mt-1">{{ $pendingReports }}</h3>
            </div>
            <div class="w-11 h-11 rounded-xl bg-blue-500/10 text-blue-400 flex items-center justify-center">
                <i data-lucide="clock" class="w-5 h-5"></i>
            </div>
        </div>

        <!-- Dalam Proses -->
        <div class="p-5 rounded-2xl bg-[#1C2330] border border-[#263143] shadow-lg flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-400">Sedang Diproses</p>
                <h3 class="text-2xl font-extrabold text-amber-400 mt-1">{{ $inProgressReports }}</h3>
            </div>
            <div class="w-11 h-11 rounded-xl bg-amber-500/10 text-amber-400 flex items-center justify-center">
                <i data-lucide="wrench" class="w-5 h-5"></i>
            </div>
        </div>

        <!-- Selesai -->
        <div class="p-5 rounded-2xl bg-[#1C2330] border border-[#263143] shadow-lg flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-400">Telah Selesai</p>
                <h3 class="text-2xl font-extrabold text-emerald-400 mt-1">{{ $completedReports }}</h3>
            </div>
            <div class="w-11 h-11 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center">
                <i data-lucide="check-circle-2" class="w-5 h-5"></i>
            </div>
        </div>
    </div>

    <!-- Riwayat Laporan Terbaru Milik User -->
    <div class="p-5 sm:p-6 rounded-2xl bg-[#1C2330] border border-[#263143] shadow-lg flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-bold text-white flex items-center gap-2">
                        <i data-lucide="history" class="w-4 h-4 text-blue-400"></i>
                        <span>Riwayat Laporan Terbaru Milik Anda</span>
                    </h3>
                    <p class="text-xs text-slate-400 mt-0.5">Pantau perkembangan status tiket yang telah Anda buat</p>
                </div>
                <a href="{{ route('reports.index') }}" class="text-xs text-blue-400 hover:text-blue-300 font-medium">Buka Semua Riwayat &rarr;</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-[#263143] text-slate-400 uppercase text-[10px] tracking-wider">
                            <th class="pb-3 font-semibold">No. Tiket</th>
                            <th class="pb-3 font-semibold">Nama Barang</th>
                            <th class="pb-3 font-semibold">Jenis Masalah</th>
                            <th class="pb-3 font-semibold">Prioritas</th>
                            <th class="pb-3 font-semibold">Status</th>
                            <th class="pb-3 font-semibold">Tanggal</th>
                            <th class="pb-3 text-right font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#263143]/50">
                        @forelse($recentReports as $rep)
                            <tr class="hover:bg-[#222B3C]/50 transition">
                                <td class="py-3 font-mono font-medium text-blue-400">
                                    {{ $rep->ticket_number }}
                                </td>
                                <td class="py-3 font-medium text-white max-w-[180px] truncate">
                                    {{ $rep->item_name }}
                                    <div class="text-[10px] text-slate-400 font-normal truncate">{{ $rep->location }}</div>
                                </td>
                                <td class="py-3 text-slate-300">
                                    {{ $rep->problem_type }}
                                </td>
                                <td class="py-3">
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold {{ $rep->priority_badge_color }}">
                                        {{ $rep->priority }}
                                    </span>
                                </td>
                                <td class="py-3">
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold {{ $rep->status_badge_color }}">
                                        {{ $rep->status }}
                                    </span>
                                </td>
                                <td class="py-3 text-slate-400">
                                    {{ $rep->created_at->format('d M Y') }}
                                </td>
                                <td class="py-3 text-right">
                                    <a href="{{ route('reports.show', $rep) }}" 
                                       class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-[#121620] hover:bg-blue-600 hover:text-white border border-[#263143] text-slate-300 text-xs font-medium transition">
                                        <span>Detail</span>
                                        <i data-lucide="arrow-right" class="w-3 h-3"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center gap-2.5">
                                        <div class="w-12 h-12 rounded-2xl bg-[#121620] border border-[#263143] flex items-center justify-center text-slate-500">
                                            <i data-lucide="clipboard-list" class="w-6 h-6"></i>
                                        </div>
                                        <p class="text-sm font-bold text-white">Belum ada laporan</p>
                                        <p class="text-xs text-slate-400">Anda belum membuat laporan kerusakan atau kekurangan barang.</p>
                                        <a href="{{ route('reports.create') }}" 
                                           class="mt-1 inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold transition shadow-lg shadow-blue-600/20">
                                            <i data-lucide="plus-circle" class="w-4 h-4"></i>
                                            <span>Buat Laporan Sekarang</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="pt-4 border-t border-[#263143]/60 flex items-center justify-between text-xs text-slate-400">
            <span>Catatan penanganan dari admin akan tampil di detail tiket.</span>
            <a href="{{ route('reports.create') }}" class="text-blue-400 hover:underline">+ Buat Laporan Baru</a>
        </div>
    </div>
</div>
@endsection


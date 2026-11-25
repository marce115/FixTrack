@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('header-title', 'Dashboard Administrator IT')
@section('header-subtitle', 'Pusat pengelolaan laporan perangkat, jaringan, dan aset IT')

@section('content')
<div class="space-y-6">

    <!-- KPI Metric Cards -->
    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-4">
        <!-- Total Laporan -->
        <div class="p-5 rounded-2xl bg-[#1C2330] border border-[#263143] shadow-lg flex items-center justify-between">
            <div>
                <span class="text-xs font-medium text-slate-400">Total Laporan</span>
                <h3 class="text-2xl font-extrabold text-white mt-1">{{ $totalReports }}</h3>
                <p class="text-[11px] text-slate-500 mt-0.5">Keseluruhan tiket</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-blue-500/10 text-blue-400 flex items-center justify-center shrink-0">
                <i data-lucide="layers" class="w-5 h-5"></i>
            </div>
        </div>

        <!-- Pending -->
        <div class="p-5 rounded-2xl bg-[#1C2330] border border-[#263143] shadow-lg flex items-center justify-between">
            <div>
                <span class="text-xs font-medium text-slate-400">Menunggu</span>
                <h3 class="text-2xl font-extrabold text-blue-400 mt-1">{{ $pendingReports }}</h3>
                <p class="text-[11px] text-slate-500 mt-0.5">Laporan baru (Pending)</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-blue-500/10 text-blue-400 flex items-center justify-center shrink-0">
                <i data-lucide="clock" class="w-5 h-5"></i>
            </div>
        </div>

        <!-- Sedang Diproses -->
        <div class="p-5 rounded-2xl bg-[#1C2330] border border-[#263143] shadow-lg flex items-center justify-between">
            <div>
                <span class="text-xs font-medium text-slate-400">Sedang Diproses</span>
                <h3 class="text-2xl font-extrabold text-amber-400 mt-1">{{ $inProgressReports }}</h3>
                <p class="text-[11px] text-slate-500 mt-0.5">Dalam penanganan</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-amber-500/10 text-amber-400 flex items-center justify-center shrink-0">
                <i data-lucide="wrench" class="w-5 h-5"></i>
            </div>
        </div>

        <!-- Selesai -->
        <div class="p-5 rounded-2xl bg-[#1C2330] border border-[#263143] shadow-lg flex items-center justify-between">
            <div>
                <span class="text-xs font-medium text-slate-400">Laporan Selesai</span>
                <h3 class="text-2xl font-extrabold text-emerald-400 mt-1">{{ $completedReports }}</h3>
                <p class="text-[11px] text-slate-500 mt-0.5">Tuntas ditangani</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center shrink-0">
                <i data-lucide="check-circle-2" class="w-5 h-5"></i>
            </div>
        </div>

        <!-- Jumlah Aset Bermasalah -->
        <div class="p-5 rounded-2xl bg-[#1C2330] border border-[#263143] shadow-lg flex items-center justify-between col-span-2 md:col-span-1">
            <div>
                <span class="text-xs font-medium text-slate-400">Aset Bermasalah</span>
                <h3 class="text-2xl font-extrabold text-rose-400 mt-1">{{ $problematicAssetsCount }}</h3>
                <p class="text-[11px] text-slate-500 mt-0.5">Unit dilaporkan</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-rose-500/10 text-rose-400 flex items-center justify-center shrink-0">
                <i data-lucide="monitor-alert" class="w-5 h-5"></i>
            </div>
        </div>
    </div>

    <!-- Status Chart and Recent Reports Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Status Doughnut Chart -->
        <div class="p-5 rounded-2xl bg-[#1C2330] border border-[#263143] shadow-lg flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-bold text-white flex items-center gap-2">
                        <i data-lucide="pie-chart" class="w-4 h-4 text-blue-400"></i>
                        <span>Grafik Status Laporan</span>
                    </h3>
                </div>
                <p class="text-xs text-slate-400 mb-4">Distribusi status penanganan tiket masuk</p>

                <div class="h-56 relative flex items-center justify-center py-2">
                    @if($totalReports > 0)
                        <canvas id="adminStatusChart"></canvas>
                    @else
                        <div class="flex flex-col items-center justify-center text-center gap-2 text-slate-500">
                            <i data-lucide="pie-chart" class="w-10 h-10 text-slate-600"></i>
                            <p class="text-xs text-slate-400">Belum ada data laporan untuk ditampilkan.</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-3 gap-2 pt-4 border-t border-[#263143]/60 text-xs">
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-blue-500 shrink-0"></span>
                    <span class="text-slate-400 truncate">Pending ({{ $pendingReports }})</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500 shrink-0"></span>
                    <span class="text-slate-400 truncate">Diproses ({{ $inProgressReports }})</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 shrink-0"></span>
                    <span class="text-slate-400 truncate">Selesai ({{ $completedReports }})</span>
                </div>
            </div>
        </div>

        <!-- Daftar Laporan Terbaru -->
        <div class="lg:col-span-2 p-5 rounded-2xl bg-[#1C2330] border border-[#263143] shadow-lg flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-sm font-bold text-white flex items-center gap-2">
                            <i data-lucide="inbox" class="w-4 h-4 text-blue-400"></i>
                            <span>Daftar Laporan Terbaru</span>
                        </h3>
                        <p class="text-xs text-slate-400 mt-0.5">Tiket insiden IT yang baru saja diajukan oleh pengguna</p>
                    </div>
                    <a href="{{ route('reports.index') }}" class="text-xs text-blue-400 hover:text-blue-300 font-medium">Buka Semua Tiket &rarr;</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-[#263143] text-slate-400 uppercase text-[10px] tracking-wider">
                                <th class="pb-3 font-semibold">No. Tiket</th>
                                <th class="pb-3 font-semibold">Nama Aset</th>
                                <th class="pb-3 font-semibold">Pelapor</th>
                                <th class="pb-3 font-semibold">Prioritas</th>
                                <th class="pb-3 font-semibold">Status</th>
                                <th class="pb-3 text-right font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#263143]/50">
                            @forelse($recentReports as $rep)
                                <tr class="hover:bg-[#222B3C]/50 transition">
                                    <td class="py-3 font-mono font-medium text-blue-400 whitespace-nowrap">
                                        {{ $rep->ticket_number }}
                                        <div class="text-[10px] text-slate-500 font-sans">{{ $rep->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td class="py-3 max-w-[180px]">
                                        <div class="font-semibold text-white truncate">{{ $rep->item_name }}</div>
                                        <div class="text-[10px] text-slate-400 truncate">{{ $rep->location }}</div>
                                    </td>
                                    <td class="py-3 text-slate-300 whitespace-nowrap">
                                        {{ $rep->user->name ?? '-' }}
                                    </td>
                                    <td class="py-3 whitespace-nowrap">
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold {{ $rep->priority_badge_color }}">
                                            {{ $rep->priority }}
                                        </span>
                                    </td>
                                    <td class="py-3 whitespace-nowrap">
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold {{ $rep->status_badge_color }}">
                                            {{ $rep->status }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-right whitespace-nowrap">
                                        <a href="{{ route('reports.show', $rep) }}" 
                                           class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-[#121620] hover:bg-blue-600 hover:text-white border border-[#263143] text-slate-300 text-xs font-medium transition">
                                            <span>Detail</span>
                                            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-12 text-center text-slate-400">
                                        <div class="flex flex-col items-center justify-center gap-2">
                                            <div class="w-12 h-12 rounded-2xl bg-[#121620] border border-[#263143] flex items-center justify-center text-slate-500 mb-1">
                                                <i data-lucide="inbox" class="w-6 h-6"></i>
                                            </div>
                                            <p class="text-sm font-bold text-white">Belum ada laporan</p>
                                            <p class="text-xs text-slate-400 max-w-xs">Belum ada laporan kerusakan atau masalah aset IT yang masuk dari pengguna.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="pt-4 border-t border-[#263143]/60 flex items-center justify-between text-xs text-slate-400">
                <span>Daftar laporan terupdate secara real-time dari database.</span>
                <a href="{{ route('reports.index') }}" class="text-blue-400 hover:underline">Kelola Seluruh Laporan &rarr;</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const ctx = document.getElementById('adminStatusChart');
        if (ctx && window.Chart) {
            new window.Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Pending', 'Sedang Diproses', 'Selesai'],
                    datasets: [{
                        data: [
                            {{ $statusCounts['Pending'] }},
                            {{ $statusCounts['Sedang Diproses'] }},
                            {{ $statusCounts['Selesai'] }}
                        ],
                        backgroundColor: ['#3B82F6', '#F59E0B', '#10B981'],
                        borderColor: '#1C2330',
                        borderWidth: 3,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    cutout: '70%'
                }
            });
        }
    });
</script>
@endpush

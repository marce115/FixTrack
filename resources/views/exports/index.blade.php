@extends('layouts.app')

@section('title', 'Export Laporan Rekap')
@section('header-title', 'Pusat Ekspor Laporan & Rekapitulasi')
@section('header-subtitle', 'Unduh berkas rekap insiden aset dalam format dokumen PDF resmi atau spreadsheet Excel')

@section('content')
<div class="space-y-6">

    <!-- Header Description -->
    <div class="p-6 rounded-2xl bg-[#1C2330] border border-[#263143] shadow-lg flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-white tracking-tight">Ekspor Data & Laporan Resmi</h1>
            <p class="text-xs text-slate-400 mt-1">
                Gunakan filter di bawah untuk menyesuaikan data yang ingin disertakan dalam berkas unduhan.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <span class="text-xs px-3 py-1 rounded-full bg-blue-500/10 text-blue-400 border border-blue-500/20 font-bold">
                {{ $previewCount }} Data Siap Diekspor
            </span>
        </div>
    </div>

    <!-- Filter Configuration Card -->
    <div class="p-6 rounded-2xl bg-[#1C2330] border border-[#263143] shadow-xl">
        <h3 class="text-xs font-bold uppercase tracking-wider text-blue-400 mb-4 flex items-center gap-2">
            <i data-lucide="sliders" class="w-4 h-4"></i>
            <span>Konfigurasi Filter Ekspor</span>
        </h3>

        <form method="GET" action="{{ route('exports.index') }}" id="filterForm" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Status -->
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1.5">Status Laporan</label>
                    <select name="status" class="w-full px-3.5 py-2.5 bg-[#121620] border border-[#263143] rounded-xl text-xs sm:text-sm text-white focus:outline-none focus:border-blue-500 transition">
                        <option value="">Semua Status</option>
                        <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Sedang Diproses" {{ request('status') === 'Sedang Diproses' ? 'selected' : '' }}>Sedang Diproses</option>
                        <option value="Selesai" {{ request('status') === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1.5">Kategori Barang</label>
                    <select name="category_id" class="w-full px-3.5 py-2.5 bg-[#121620] border border-[#263143] rounded-xl text-xs sm:text-sm text-white focus:outline-none focus:border-blue-500 transition">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Date From -->
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1.5">Tanggal Awal</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-3.5 py-2.5 bg-[#121620] border border-[#263143] rounded-xl text-xs sm:text-sm text-white focus:outline-none focus:border-blue-500">
                </div>

                <!-- Date To -->
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1.5">Tanggal Akhir</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full px-3.5 py-2.5 bg-[#121620] border border-[#263143] rounded-xl text-xs sm:text-sm text-white focus:outline-none focus:border-blue-500">
                </div>
            </div>

            <!-- Submit and Download Buttons -->
            <div class="pt-4 border-t border-[#263143] flex flex-wrap items-center justify-between gap-3">
                <button type="submit" class="px-4 py-2.5 rounded-xl bg-[#121620] hover:bg-[#263143] text-slate-300 hover:text-white border border-[#263143] text-xs font-semibold transition flex items-center gap-1.5">
                    <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                    <span>Terapkan Filter & Pratinjau</span>
                </button>

                <div class="flex items-center gap-3">
                    <!-- Download PDF -->
                    <a href="{{ route('exports.pdf', request()->query()) }}" 
                       class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-500 text-white text-xs sm:text-sm font-semibold transition shadow-lg shadow-rose-600/20 flex items-center gap-2">
                        <i data-lucide="file-text" class="w-4 h-4"></i>
                        <span>Download Berkas PDF</span>
                    </a>

                    <!-- Download Excel -->
                    <a href="{{ route('exports.excel', request()->query()) }}" 
                       class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs sm:text-sm font-semibold transition shadow-lg shadow-emerald-600/20 flex items-center gap-2">
                        <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
                        <span>Download File Excel</span>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Live Preview Table -->
    <div class="p-5 rounded-2xl bg-[#1C2330] border border-[#263143] shadow-lg space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 flex items-center gap-2">
                <i data-lucide="eye" class="w-4 h-4 text-blue-400"></i>
                <span>Pratinjau Sampel Data (5 Teratas dari Total {{ $previewCount }})</span>
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#151922] border-b border-[#263143] text-slate-400 uppercase text-[10px] tracking-wider">
                    <tr>
                        <th class="py-3 px-3 font-semibold">No. Tiket</th>
                        <th class="py-3 px-3 font-semibold">Nama Barang</th>
                        <th class="py-3 px-3 font-semibold">Kategori / Lokasi</th>
                        <th class="py-3 px-3 font-semibold">Masalah</th>
                        <th class="py-3 px-3 font-semibold">Prioritas</th>
                        <th class="py-3 px-3 font-semibold">Status</th>
                        <th class="py-3 px-3 font-semibold">Pelapor</th>
                        <th class="py-3 px-3 font-semibold">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#263143]/50">
                    @forelse($recentPreview as $rep)
                        <tr class="hover:bg-[#222B3C]/50 transition">
                            <td class="py-3 px-3 font-mono text-blue-400 font-semibold">{{ $rep->ticket_number }}</td>
                            <td class="py-3 px-3 font-semibold text-white">{{ $rep->item_name }}</td>
                            <td class="py-3 px-3 text-slate-300">
                                <div>{{ $rep->category->name ?? '-' }}</div>
                                <div class="text-[10px] text-slate-400">{{ $rep->location ?? '-' }}</div>
                            </td>
                            <td class="py-3 px-3 text-slate-300">{{ $rep->problem_type }}</td>
                            <td class="py-3 px-3">
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold {{ $rep->priority_badge_color }}">
                                    {{ $rep->priority }}
                                </span>
                            </td>
                            <td class="py-3 px-3">
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold {{ $rep->status_badge_color }}">
                                    {{ $rep->status }}
                                </span>
                            </td>
                            <td class="py-3 px-3 text-slate-300">{{ $rep->user->name ?? '-' }}</td>
                            <td class="py-3 px-3 text-slate-400">{{ $rep->created_at->format('d/m/Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-slate-400">
                                Tidak ada data yang sesuai dengan filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


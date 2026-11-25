@extends('layouts.app')

@section('title', Auth::user()->isAdmin() ? 'Data Laporan' : 'Riwayat Laporan Saya')
@section('header-title', Auth::user()->isAdmin() ? 'Data Laporan Kerusakan & Masalah' : 'Riwayat Laporan Saya')
@section('header-subtitle', Auth::user()->isAdmin() ? 'Pantau dan kelola seluruh tiket laporan yang diajukan oleh pengguna' : 'Daftar seluruh laporan masalah yang telah Anda ajukan')

@section('content')
<div class="space-y-6">

    <!-- Top Action Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-white tracking-tight">
                {{ Auth::user()->isAdmin() ? 'Daftar Laporan Masuk' : 'Riwayat Laporan Saya' }}
            </h1>
            <p class="text-xs text-slate-400 mt-1">
                Menampilkan total {{ $reports->total() }} laporan tercatat.
            </p>
        </div>
        <div class="flex items-center gap-2">
            @if(Auth::user()->isAdmin())
                <a href="{{ route('exports.index') }}" 
                   class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-[#1C2330] hover:bg-[#263143] text-slate-300 hover:text-white border border-[#263143] text-xs font-semibold transition">
                    <i data-lucide="download" class="w-4 h-4"></i>
                    <span>Export Rekap</span>
                </a>
            @endif
            <a href="{{ route('reports.create') }}" 
               class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold transition shadow-lg shadow-blue-600/20">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Buat Laporan Baru</span>
            </a>
        </div>
    </div>

    <!-- Filter & Search Card -->
    <div class="p-4 rounded-2xl bg-[#1C2330] border border-[#263143] shadow-lg">
        <form method="GET" action="{{ route('reports.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <!-- Search -->
            <div class="relative lg:col-span-2">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-500">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </span>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Cari tiket, barang, pelapor, lokasi..." 
                       class="w-full pl-9 pr-3 py-2 bg-[#121620] border border-[#263143] rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 transition">
            </div>

            <!-- Status Filter -->
            <div>
                <select name="status" class="w-full px-3 py-2 bg-[#121620] border border-[#263143] rounded-xl text-xs text-white focus:outline-none focus:border-blue-500 transition">
                    <option value="">Semua Status</option>
                    <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Sedang Diproses" {{ request('status') === 'Sedang Diproses' ? 'selected' : '' }}>Sedang Diproses</option>
                    <option value="Selesai" {{ request('status') === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>

            <!-- Priority Filter -->
            <div>
                <select name="priority" class="w-full px-3 py-2 bg-[#121620] border border-[#263143] rounded-xl text-xs text-white focus:outline-none focus:border-blue-500 transition">
                    <option value="">Semua Prioritas</option>
                    <option value="Tinggi" {{ request('priority') === 'Tinggi' ? 'selected' : '' }}>Tinggi</option>
                    <option value="Sedang" {{ request('priority') === 'Sedang' ? 'selected' : '' }}>Sedang</option>
                    <option value="Rendah" {{ request('priority') === 'Rendah' ? 'selected' : '' }}>Rendah</option>
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 py-2 px-3 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-xs font-semibold transition flex items-center justify-center gap-1.5">
                    <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                    <span>Terapkan</span>
                </button>
                @if(request()->anyFilled(['search', 'status', 'priority', 'category_id', 'date_from', 'date_to']))
                    <a href="{{ route('reports.index') }}" class="p-2 bg-rose-500/10 border border-rose-500/20 text-rose-400 hover:bg-rose-500/20 rounded-xl text-xs transition" title="Reset Filter">
                        <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Reports Table Card -->
    <div class="rounded-2xl bg-[#1C2330] border border-[#263143] shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#151922] border-b border-[#263143] text-slate-400 uppercase text-[10px] tracking-wider">
                    <tr>
                        <th class="py-3.5 px-4 font-semibold">No. Tiket</th>
                        <th class="py-3.5 px-4 font-semibold">Nama Barang</th>
                        <th class="py-3.5 px-4 font-semibold">Nama Pelapor</th>
                        <th class="py-3.5 px-4 font-semibold">Lokasi</th>
                        <th class="py-3.5 px-4 font-semibold">Jenis Masalah</th>
                        <th class="py-3.5 px-4 font-semibold">Prioritas</th>
                        <th class="py-3.5 px-4 font-semibold">Tanggal Laporan</th>
                        <th class="py-3.5 px-4 font-semibold">Status</th>
                        <th class="py-3.5 px-4 text-right font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#263143]/50">
                    @forelse($reports as $rep)
                        <tr class="hover:bg-[#222B3C]/50 transition group">
                            <!-- Nomor Tiket -->
                            <td class="py-3.5 px-4 font-mono font-medium text-blue-400 whitespace-nowrap">
                                <a href="{{ route('reports.show', $rep) }}" class="hover:underline flex items-center gap-1.5">
                                    <span>{{ $rep->ticket_number }}</span>
                                    @if($rep->photo_path)
                                        <i data-lucide="image" class="w-3 h-3 text-slate-400" title="Ada Foto Bukti"></i>
                                    @endif
                                </a>
                            </td>

                            <!-- Nama Barang -->
                            <td class="py-3.5 px-4 font-semibold text-white max-w-[200px]">
                                <div class="truncate">{{ $rep->item_name }}</div>
                                <div class="text-[10px] text-slate-400 font-normal truncate">
                                    @if($rep->inventory_code)
                                        <span class="font-mono text-blue-400">{{ $rep->inventory_code }}</span> &bull;
                                    @endif
                                    {{ $rep->category->name ?? '-' }} (Jml: {{ $rep->quantity }})
                                </div>
                            </td>

                            <!-- Nama Pelapor -->
                            <td class="py-3.5 px-4 text-slate-300 whitespace-nowrap">
                                {{ $rep->user->name ?? '-' }}
                            </td>

                            <!-- Lokasi -->
                            <td class="py-3.5 px-4 text-slate-300 max-w-[150px]">
                                <div class="truncate">{{ $rep->location }}</div>
                            </td>

                            <!-- Jenis Masalah -->
                            <td class="py-3.5 px-4 text-slate-300 whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded text-[10px] font-medium bg-[#121620] border border-[#263143]">
                                    {{ $rep->problem_type }}
                                </span>
                            </td>

                            <!-- Prioritas -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold {{ $rep->priority_badge_color }}">
                                    {{ $rep->priority }}
                                </span>
                            </td>

                            <!-- Tanggal Laporan -->
                            <td class="py-3.5 px-4 text-slate-400 whitespace-nowrap">
                                <div>{{ $rep->created_at->format('d M Y') }}</div>
                                <div class="text-[10px] text-slate-500">{{ $rep->created_at->format('H:i') }} WIB</div>
                            </td>

                            <!-- Status -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold {{ $rep->status_badge_color }}">
                                    {{ $rep->status }}
                                </span>
                            </td>

                            <!-- Aksi -->
                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('reports.show', $rep) }}" 
                                       class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-[#121620] hover:bg-blue-600 hover:text-white border border-[#263143] text-slate-300 text-xs font-medium transition">
                                        <span>Detail</span>
                                        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                                    </a>

                                    @if(Auth::user()->isAdmin())
                                        <form action="{{ route('reports.destroy', $rep) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus laporan ini?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="p-1.5 rounded-lg bg-[#121620] hover:bg-rose-600 hover:text-white border border-[#263143] text-rose-400 transition" 
                                                    title="Hapus Laporan">
                                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center gap-2.5">
                                    <div class="w-12 h-12 rounded-2xl bg-[#121620] border border-[#263143] flex items-center justify-center text-slate-500">
                                        <i data-lucide="inbox" class="w-6 h-6"></i>
                                    </div>
                                    <p class="text-sm font-bold text-white">Belum ada laporan</p>
                                    <p class="text-xs text-slate-400">
                                        @if(request()->anyFilled(['search', 'status', 'priority', 'category_id', 'date_from', 'date_to']))
                                            Tidak ada laporan yang sesuai dengan filter pencarian Anda.
                                        @else
                                            Anda belum membuat laporan kerusakan atau kekurangan barang.
                                        @endif
                                    </p>
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

        @if($reports->hasPages())
            <div class="p-4 border-t border-[#263143] bg-[#151922]">
                {{ $reports->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

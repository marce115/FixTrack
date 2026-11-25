@extends('layouts.app')

@section('title', 'Kategori Barang')
@section('header-title', 'Kategori Barang')
@section('header-subtitle', 'Kelola daftar klasifikasi dan kategori inventaris barang')

@section('content')
<div class="space-y-6">

    <!-- Top Action Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-white tracking-tight">Kategori Barang</h1>
            <p class="text-xs text-slate-400 mt-1">
                Total {{ $categories->total() }} kategori terdaftar dalam sistem.
            </p>
        </div>
        <a href="{{ route('categories.create') }}" 
           class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold transition shadow-lg shadow-blue-600/20">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Tambah Kategori Baru</span>
        </a>
    </div>

    <!-- Search Bar -->
    <div class="p-4 rounded-2xl bg-[#1C2330] border border-[#263143] shadow-lg">
        <form method="GET" action="{{ route('categories.index') }}" class="flex items-center gap-3">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-500">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </span>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Cari nama atau deskripsi kategori..." 
                       class="w-full pl-9 pr-3 py-2 bg-[#121620] border border-[#263143] rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 transition">
            </div>

            <button type="submit" class="py-2 px-4 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-xs font-semibold transition flex items-center gap-1.5">
                <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                <span>Cari</span>
            </button>
            @if(request()->filled('search'))
                <a href="{{ route('categories.index') }}" class="p-2 bg-rose-500/10 border border-rose-500/20 text-rose-400 hover:bg-rose-500/20 rounded-xl text-xs transition" title="Reset">
                    <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                </a>
            @endif
        </form>
    </div>

    <!-- Categories Table -->
    <div class="rounded-2xl bg-[#1C2330] border border-[#263143] shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#151922] border-b border-[#263143] text-slate-400 uppercase text-[10px] tracking-wider">
                    <tr>
                        <th class="py-3.5 px-4 font-semibold">Nama Kategori</th>
                        <th class="py-3.5 px-4 font-semibold">Deskripsi</th>
                        <th class="py-3.5 px-4 font-semibold text-center">Jumlah Barang</th>
                        <th class="py-3.5 px-4 font-semibold text-center">Jumlah Laporan</th>
                        <th class="py-3.5 px-4 text-right font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#263143]/50">
                    @forelse($categories as $cat)
                        <tr class="hover:bg-[#222B3C]/50 transition">
                            <td class="py-3.5 px-4 font-semibold text-white whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                    <span>{{ $cat->name }}</span>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-slate-300 max-w-[280px]">
                                {{ $cat->description ?: '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-center font-mono font-bold text-slate-300 whitespace-nowrap">
                                {{ $cat->items_count }} Item
                            </td>
                            <td class="py-3.5 px-4 text-center font-mono font-bold text-blue-400 whitespace-nowrap">
                                {{ $cat->reports_count }} Laporan
                            </td>
                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('categories.edit', $cat) }}" 
                                       class="p-1.5 rounded-lg bg-[#121620] hover:bg-blue-600 hover:text-white border border-[#263143] text-slate-300 transition" 
                                       title="Ubah Kategori">
                                        <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                    </a>

                                    <form action="{{ route('categories.destroy', $cat) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="p-1.5 rounded-lg bg-[#121620] hover:bg-rose-600 hover:text-white border border-[#263143] text-rose-400 transition" 
                                                title="Hapus Kategori">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-400">
                                Tidak ada data kategori yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($categories->hasPages())
            <div class="p-4 border-t border-[#263143] bg-[#151922]">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
</div>
@endsection


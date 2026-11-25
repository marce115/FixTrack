@extends('layouts.app')

@section('title', 'Data Barang')
@section('header-title', 'Data Barang & Inventaris')
@section('header-subtitle', 'Kelola inventaris suku cadang, mesin, perkakas, dan peralatan perusahaan')

@section('content')
<div class="space-y-6">

    <!-- Top Action Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-white tracking-tight">Data Barang & Inventaris</h1>
            <p class="text-xs text-slate-400 mt-1">
                Menampilkan total {{ $items->total() }} barang terdata dalam sistem master inventaris.
            </p>
        </div>
        <a href="{{ route('items.create') }}" 
           class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold transition shadow-lg shadow-blue-600/20">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Tambah Barang Baru</span>
        </a>
    </div>

    <!-- Search & Filter Bar -->
    <div class="p-4 rounded-2xl bg-[#1C2330] border border-[#263143] shadow-lg">
        <form method="GET" action="{{ route('items.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <!-- Search -->
            <div class="relative lg:col-span-2">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-500">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </span>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Cari kode barang, nama, lokasi..." 
                       class="w-full pl-9 pr-3 py-2 bg-[#121620] border border-[#263143] rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 transition">
            </div>

            <!-- Category Filter -->
            <div>
                <select name="category_id" class="w-full px-3 py-2 bg-[#121620] border border-[#263143] rounded-xl text-xs text-white focus:outline-none focus:border-blue-500">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Condition Filter -->
            <div>
                <select name="condition" class="w-full px-3 py-2 bg-[#121620] border border-[#263143] rounded-xl text-xs text-white focus:outline-none focus:border-blue-500">
                    <option value="">Semua Kondisi</option>
                    <option value="Baik" {{ request('condition') === 'Baik' ? 'selected' : '' }}>Baik</option>
                    <option value="Rusak Ringan" {{ request('condition') === 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                    <option value="Rusak Berat" {{ request('condition') === 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                </select>
            </div>

            <!-- Submit & Reset -->
            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 py-2 px-3 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-xs font-semibold transition flex items-center justify-center gap-1">
                    <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                    <span>Filter</span>
                </button>
                @if(request()->anyFilled(['search', 'category_id', 'condition']))
                    <a href="{{ route('items.index') }}" class="p-2 bg-rose-500/10 border border-rose-500/20 text-rose-400 hover:bg-rose-500/20 rounded-xl text-xs transition">
                        <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Items Table -->
    <div class="rounded-2xl bg-[#1C2330] border border-[#263143] shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#151922] border-b border-[#263143] text-slate-400 uppercase text-[10px] tracking-wider">
                    <tr>
                        <th class="py-3.5 px-4 font-semibold">Kode Barang</th>
                        <th class="py-3.5 px-4 font-semibold">Nama Barang</th>
                        <th class="py-3.5 px-4 font-semibold">Kategori</th>
                        <th class="py-3.5 px-4 font-semibold">Lokasi</th>
                        <th class="py-3.5 px-4 font-semibold">Jumlah Stok</th>
                        <th class="py-3.5 px-4 font-semibold">Kondisi Barang</th>
                        <th class="py-3.5 px-4 text-right font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#263143]/50">
                    @forelse($items as $item)
                        <tr class="hover:bg-[#222B3C]/50 transition">
                            <td class="py-3.5 px-4 font-mono font-medium text-blue-400 whitespace-nowrap">
                                {{ $item->item_code }}
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-white max-w-[220px]">
                                <div class="truncate">{{ $item->name }}</div>
                                @if($item->description)
                                    <div class="text-[10px] text-slate-400 font-normal truncate">{{ $item->description }}</div>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-slate-300 whitespace-nowrap">
                                {{ $item->category->name ?? '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-slate-300 max-w-[180px]">
                                <div class="truncate">{{ $item->location }}</div>
                            </td>
                            <td class="py-3.5 px-4 font-mono font-bold whitespace-nowrap {{ $item->stock_quantity <= 2 ? 'text-rose-400' : 'text-slate-200' }}">
                                {{ $item->stock_quantity }} Unit
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold 
                                    {{ $item->condition === 'Baik' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 
                                       ($item->condition === 'Rusak Ringan' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : 'bg-rose-500/10 text-rose-400 border border-rose-500/20') }}">
                                    {{ $item->condition }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('items.edit', $item) }}" 
                                       class="p-1.5 rounded-lg bg-[#121620] hover:bg-blue-600 hover:text-white border border-[#263143] text-slate-300 transition" 
                                       title="Ubah Data">
                                        <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                    </a>

                                    <form action="{{ route('items.destroy', $item) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus barang ini?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="p-1.5 rounded-lg bg-[#121620] hover:bg-rose-600 hover:text-white border border-[#263143] text-rose-400 transition" 
                                                title="Hapus Barang">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                Tidak ada data barang yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($items->hasPages())
            <div class="p-4 border-t border-[#263143] bg-[#151922]">
                {{ $items->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

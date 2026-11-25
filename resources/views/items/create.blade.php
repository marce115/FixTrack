@extends('layouts.app')

@section('title', 'Tambah Barang Baru')
@section('header-title', 'Tambah Data Barang')
@section('header-subtitle', 'Daftarkan barang atau aset baru ke dalam database FixTrack')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-white tracking-tight">Formulir Tambah Barang</h1>
            <p class="text-xs text-slate-400 mt-0.5">Lengkapi data barang dan lokasi penempatan.</p>
        </div>
        <a href="{{ route('items.index') }}" 
           class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-[#1C2330] hover:bg-[#263143] text-slate-300 hover:text-white border border-[#263143] text-xs font-semibold transition">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali</span>
        </a>
    </div>

    <div class="p-6 sm:p-8 rounded-2xl bg-[#1C2330] border border-[#263143] shadow-xl">
        <form action="{{ route('items.store') }}" method="POST" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1.5">Kode Barang <span class="text-rose-400">*</span></label>
                    <input type="text" name="item_code" value="{{ old('item_code', $suggestedCode) }}" required placeholder="Contoh: BRG-001" class="w-full px-3.5 py-2.5 bg-[#121620] border border-[#263143] rounded-xl text-xs sm:text-sm text-white focus:outline-none focus:border-blue-500 font-mono">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1.5">Nama Barang <span class="text-rose-400">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Mesin Bor Duduk Bosch" class="w-full px-3.5 py-2.5 bg-[#121620] border border-[#263143] rounded-xl text-xs sm:text-sm text-white focus:outline-none focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1.5">Kategori Barang <span class="text-rose-400">*</span></label>
                    <select name="category_id" required class="w-full px-3.5 py-2.5 bg-[#121620] border border-[#263143] rounded-xl text-xs sm:text-sm text-white focus:outline-none focus:border-blue-500">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1.5">Lokasi <span class="text-rose-400">*</span></label>
                    <input type="text" name="location" value="{{ old('location') }}" required placeholder="Contoh: Gudang B, Rak 04" class="w-full px-3.5 py-2.5 bg-[#121620] border border-[#263143] rounded-xl text-xs sm:text-sm text-white focus:outline-none focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1.5">Jumlah Stok <span class="text-rose-400">*</span></label>
                    <input type="number" name="stock_quantity" value="{{ old('stock_quantity', 1) }}" min="0" required class="w-full px-3.5 py-2.5 bg-[#121620] border border-[#263143] rounded-xl text-xs sm:text-sm text-white focus:outline-none focus:border-blue-500 font-mono">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1.5">Kondisi Barang <span class="text-rose-400">*</span></label>
                    <select name="condition" required class="w-full px-3.5 py-2.5 bg-[#121620] border border-[#263143] rounded-xl text-xs sm:text-sm text-white focus:outline-none focus:border-blue-500">
                        <option value="Baik" {{ old('condition') === 'Baik' ? 'selected' : '' }}>Baik</option>
                        <option value="Rusak Ringan" {{ old('condition') === 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                        <option value="Rusak Berat" {{ old('condition') === 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-300 mb-1.5">Deskripsi / Keterangan</label>
                    <textarea name="description" rows="3" placeholder="Keterangan tambahan barang..." class="w-full px-3.5 py-2.5 bg-[#121620] border border-[#263143] rounded-xl text-xs sm:text-sm text-white focus:outline-none focus:border-blue-500">{{ old('description') }}</textarea>
                </div>
            </div>

            <div class="pt-4 border-t border-[#263143] flex items-center justify-end gap-3">
                <a href="{{ route('items.index') }}" class="px-4 py-2 rounded-xl bg-[#121620] text-slate-300 text-xs font-semibold">Batal</a>
                <button type="submit" class="px-5 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold transition shadow-lg shadow-blue-600/20">
                    Simpan Barang Baru
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

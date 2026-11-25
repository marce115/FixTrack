@extends('layouts.app')

@section('title', 'Ubah Kategori: ' . $category->name)
@section('header-title', 'Ubah Kategori Barang')
@section('header-subtitle', 'Perbarui nama atau deskripsi kategori')

@section('content')
<div class="max-w-xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-white tracking-tight">Edit Kategori: {{ $category->name }}</h1>
            <p class="text-xs text-slate-400 mt-0.5">Perbarui informasi nama atau deskripsi.</p>
        </div>
        <a href="{{ route('categories.index') }}" 
           class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-[#1C2330] hover:bg-[#263143] text-slate-300 hover:text-white border border-[#263143] text-xs font-semibold transition">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali</span>
        </a>
    </div>

    <div class="p-6 sm:p-8 rounded-2xl bg-[#1C2330] border border-[#263143] shadow-xl">
        <form action="{{ route('categories.update', $category) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1.5">Nama Kategori <span class="text-rose-400">*</span></label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}" required class="w-full px-3.5 py-2.5 bg-[#121620] border border-[#263143] rounded-xl text-xs sm:text-sm text-white focus:outline-none focus:border-blue-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1.5">Deskripsi Singkat</label>
                <textarea name="description" rows="3" class="w-full px-3.5 py-2.5 bg-[#121620] border border-[#263143] rounded-xl text-xs sm:text-sm text-white focus:outline-none focus:border-blue-500">{{ old('description', $category->description) }}</textarea>
            </div>

            <div class="pt-4 border-t border-[#263143] flex items-center justify-end gap-3">
                <a href="{{ route('categories.index') }}" class="px-4 py-2 rounded-xl bg-[#121620] text-slate-300 text-xs font-semibold">Batal</a>
                <button type="submit" class="px-5 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold transition shadow-lg shadow-blue-600/20">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection


@extends('layouts.app')

@section('title', 'Buat Laporan Baru')
@section('header-title', 'Formulir Pelaporan Masalah Barang')
@section('header-subtitle', 'Laporkan kerusakan, kehilangan, atau kekurangan stok barang')

@section('content')
<div class="max-w-3xl mx-auto space-y-6"
     x-data="{
         items: {{ Js::from($items) }},
         selectedItemId: '{{ old('item_id') }}',
         itemName: '{{ old('item_name') }}',
         inventoryCode: '{{ old('inventory_code') }}',
         categoryId: '{{ old('category_id') }}',
         location: '{{ old('location') }}',
         priority: '{{ old('priority', 'Sedang') }}',
         photoPreview: null,
         photoFile: null,

         onItemChange() {
             if (!this.selectedItemId) return;
             const found = this.items.find(i => i.id == this.selectedItemId);
             if (found) {
                 this.itemName = found.name;
                 this.inventoryCode = found.item_code;
                 this.categoryId = found.category_id;
                 this.location = found.location;
             }
         },

         handleFile(event) {
             const file = event.target.files[0];
             if (file) {
                 if (file.size > 5 * 1024 * 1024) {
                     alert('Ukuran foto maksimal 5MB!');
                     event.target.value = '';
                     return;
                 }
                 this.photoFile = file;
                 const reader = new FileReader();
                 reader.onload = (e) => {
                     this.photoPreview = e.target.result;
                 };
                 reader.readAsDataURL(file);
             }
         },

         removePhoto() {
             this.photoPreview = null;
             this.photoFile = null;
             document.getElementById('photoInput').value = '';
         }
     }"
     x-init="onItemChange()">

    <!-- Header Banner -->
    <div class="p-6 rounded-2xl bg-[#1C2330] border border-[#263143] shadow-lg flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="text-xs font-mono px-2.5 py-0.5 rounded-full bg-blue-500/10 text-blue-400 border border-blue-500/20 font-bold">
                    No. Tiket: {{ $autoTicket }}
                </span>
                <span class="text-xs text-slate-400">&bull; {{ date('d M Y') }}</span>
            </div>
            <h1 class="text-xl font-bold text-white tracking-tight">Formulir Pelaporan Barang</h1>
            <p class="text-xs text-slate-400 mt-0.5">Isi data laporan dengan jelas agar admin dapat segera menindaklanjuti.</p>
        </div>
        <a href="{{ route('reports.index') }}" 
           class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-[#121620] hover:bg-[#263143] text-slate-300 hover:text-white border border-[#263143] text-xs font-semibold transition">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali</span>
        </a>
    </div>

    <!-- Form Card -->
    <div class="p-6 sm:p-8 rounded-2xl bg-[#1C2330] border border-[#263143] shadow-xl">
        <form action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Section 1: Item & Category -->
            <div class="space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-blue-400 flex items-center gap-2">
                    <i data-lucide="package" class="w-4 h-4"></i>
                    <span>Informasi Barang</span>
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Optional Master Item Picker -->
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-300 mb-1.5">
                            Pilih dari Daftar Barang Terdaftar <span class="text-slate-500 font-normal">(Opsional)</span>
                        </label>
                        <select name="item_id" 
                                x-model="selectedItemId" 
                                @change="onItemChange()"
                                class="w-full px-3.5 py-2.5 bg-[#121620] border border-[#263143] rounded-xl text-xs sm:text-sm text-white focus:outline-none focus:border-blue-500 transition">
                            <option value="">-- Pilih Barang (Atau ketik langsung di bawah) --</option>
                            @foreach($items as $it)
                                <option value="{{ $it->id }}">{{ $it->item_code }} &mdash; {{ $it->name }} ({{ $it->location }})</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Nama Barang / Aset -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1.5">
                            Nama Barang / Aset <span class="text-rose-400">*</span>
                        </label>
                        <input type="text" 
                               name="item_name" 
                               x-model="itemName"
                               required
                               placeholder="Contoh: Laptop Dell Latitude / Switch 24-Port" 
                               class="w-full px-3.5 py-2.5 bg-[#121620] border border-[#263143] rounded-xl text-xs sm:text-sm text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 transition">
                    </div>

                    <!-- Kode Inventaris (Opsional) -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1.5">
                            Kode Inventaris <span class="text-slate-500 font-normal">(Opsional)</span>
                        </label>
                        <input type="text" 
                               name="inventory_code" 
                               x-model="inventoryCode"
                               placeholder="Contoh: IT-HW-001" 
                               class="w-full px-3.5 py-2.5 bg-[#121620] border border-[#263143] rounded-xl text-xs sm:text-sm text-white font-mono placeholder-slate-500 focus:outline-none focus:border-blue-500 transition">
                    </div>

                    <!-- Kategori Aset -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1.5">
                            Kategori Aset <span class="text-rose-400">*</span>
                        </label>
                        <select name="category_id" 
                                x-model="categoryId"
                                required 
                                class="w-full px-3.5 py-2.5 bg-[#121620] border border-[#263143] rounded-xl text-xs sm:text-sm text-white focus:outline-none focus:border-blue-500 transition">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Lokasi / Departemen -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1.5">
                            Lokasi atau Departemen <span class="text-rose-400">*</span>
                        </label>
                        <input type="text" 
                               name="location" 
                               x-model="location"
                               required
                               placeholder="Contoh: Ruang Server / Divisi Finance Lantai 2" 
                               class="w-full px-3.5 py-2.5 bg-[#121620] border border-[#263143] rounded-xl text-xs sm:text-sm text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 transition">
                    </div>
                </div>
            </div>

            <!-- Section 2: Problem Details -->
            <div class="pt-6 border-t border-[#263143]/70 space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-amber-400 flex items-center gap-2">
                    <i data-lucide="alert-circle" class="w-4 h-4"></i>
                    <span>Detail Masalah & Prioritas</span>
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Jenis Masalah -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1.5">
                            Jenis Masalah <span class="text-rose-400">*</span>
                        </label>
                        <select name="problem_type" required class="w-full px-3.5 py-2.5 bg-[#121620] border border-[#263143] rounded-xl text-xs sm:text-sm text-white focus:outline-none focus:border-blue-500 transition">
                            <option value="">-- Pilih Jenis Masalah --</option>
                            @foreach($problemTypes as $type)
                                <option value="{{ $type }}" {{ old('problem_type') === $type ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Jumlah Barang Bermasalah -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1.5">
                            Jumlah Barang Bermasalah <span class="text-rose-400">*</span>
                        </label>
                        <input type="number" 
                               name="quantity" 
                               value="{{ old('quantity', 1) }}" 
                               min="1" 
                               max="9999" 
                               required
                               class="w-full px-3.5 py-2.5 bg-[#121620] border border-[#263143] rounded-xl text-xs sm:text-sm text-white focus:outline-none focus:border-blue-500 transition font-mono">
                    </div>

                    <!-- Prioritas (Rendah, Sedang, Tinggi) -->
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-300 mb-2">
                            Tingkat Prioritas <span class="text-rose-400">*</span>
                        </label>
                        <div class="grid grid-cols-3 gap-3">
                            <!-- Rendah -->
                            <label :class="priority === 'Rendah' ? 'border-blue-500 bg-blue-500/10 text-white' : 'border-[#263143] bg-[#121620] text-slate-400 hover:border-slate-500'" 
                                   class="p-3 rounded-xl border flex flex-col items-center justify-center cursor-pointer transition text-center">
                                <input type="radio" name="priority" value="Rendah" x-model="priority" class="sr-only">
                                <span class="w-2.5 h-2.5 rounded-full bg-blue-400 mb-1.5"></span>
                                <span class="text-xs font-bold text-blue-400">Rendah</span>
                            </label>

                            <!-- Sedang -->
                            <label :class="priority === 'Sedang' ? 'border-amber-500 bg-amber-500/10 text-white' : 'border-[#263143] bg-[#121620] text-slate-400 hover:border-amber-500/50'" 
                                   class="p-3 rounded-xl border flex flex-col items-center justify-center cursor-pointer transition text-center">
                                <input type="radio" name="priority" value="Sedang" x-model="priority" class="sr-only">
                                <span class="w-2.5 h-2.5 rounded-full bg-amber-400 mb-1.5"></span>
                                <span class="text-xs font-bold text-amber-400">Sedang</span>
                            </label>

                            <!-- Tinggi -->
                            <label :class="priority === 'Tinggi' ? 'border-rose-500 bg-rose-500/10 text-white shadow-lg shadow-rose-500/10' : 'border-[#263143] bg-[#121620] text-slate-400 hover:border-rose-500/50'" 
                                   class="p-3 rounded-xl border flex flex-col items-center justify-center cursor-pointer transition text-center">
                                <input type="radio" name="priority" value="Tinggi" x-model="priority" class="sr-only">
                                <span class="w-2.5 h-2.5 rounded-full bg-rose-500 mb-1.5"></span>
                                <span class="text-xs font-bold text-rose-400">Tinggi</span>
                            </label>
                        </div>
                    </div>

                    <!-- Deskripsi Masalah -->
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-300 mb-1.5">
                            Deskripsi Masalah <span class="text-rose-400">*</span>
                        </label>
                        <textarea name="description" 
                                  rows="4" 
                                  required
                                  placeholder="Jelaskan secara ringkas kondisi kerusakan, letak masalah, atau detail kehilangan barang..." 
                                  class="w-full px-3.5 py-2.5 bg-[#121620] border border-[#263143] rounded-xl text-xs sm:text-sm text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 transition">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Section 3: Foto Bukti (Opsional) with Preview -->
            <div class="pt-6 border-t border-[#263143]/70 space-y-3">
                <h3 class="text-xs font-bold uppercase tracking-wider text-emerald-400 flex items-center gap-2">
                    <i data-lucide="camera" class="w-4 h-4"></i>
                    <span>Foto Bukti (Opsional)</span>
                </h3>

                <!-- Dropzone -->
                <div x-show="!photoPreview" class="relative">
                    <label for="photoInput" 
                           class="flex flex-col items-center justify-center w-full h-36 border-2 border-dashed border-[#263143] hover:border-blue-500 rounded-2xl cursor-pointer bg-[#121620]/50 hover:bg-[#121620] transition group">
                        <div class="flex flex-col items-center justify-center text-center px-4">
                            <i data-lucide="upload-cloud" class="w-7 h-7 text-blue-400 mb-2 group-hover:scale-110 transition"></i>
                            <p class="text-xs font-semibold text-slate-300">
                                <span class="text-blue-400">Pilih foto</span> atau seret file ke sini
                            </p>
                            <p class="text-[11px] text-slate-500 mt-0.5">JPG, PNG, WEBP (Maksimal 5MB)</p>
                        </div>
                        <input id="photoInput" 
                               name="photo" 
                               type="file" 
                               accept="image/jpeg,image/png,image/jpg,image/webp" 
                               @change="handleFile($event)" 
                               class="hidden" />
                    </label>
                </div>

                <!-- Preview Area -->
                <div x-show="photoPreview" style="display: none;" class="p-4 rounded-xl bg-[#121620] border border-[#263143] flex items-center gap-4">
                    <div class="relative w-28 h-28 rounded-lg overflow-hidden border border-[#263143] bg-black shrink-0">
                        <img :src="photoPreview" alt="Preview Foto" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1">
                        <h4 class="text-xs font-bold text-white mb-1">Foto Bukti Terpilih</h4>
                        <p class="text-[11px] text-slate-400">Foto siap dilampirkan bersama laporan.</p>
                        <button type="button" 
                                @click="removePhoto()" 
                                class="mt-2 inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 text-xs font-semibold transition border border-rose-500/20">
                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                            <span>Hapus Foto</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="pt-6 border-t border-[#263143] flex items-center justify-end gap-3">
                <a href="{{ route('reports.index') }}" 
                   class="px-4 py-2.5 rounded-xl bg-[#121620] hover:bg-[#263143] text-slate-300 hover:text-white border border-[#263143] text-xs sm:text-sm font-semibold transition">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs sm:text-sm font-semibold transition shadow-lg shadow-blue-600/25 flex items-center gap-2">
                    <i data-lucide="send" class="w-4 h-4"></i>
                    <span>Kirim Laporan</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

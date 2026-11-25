@extends('layouts.app')

@section('title', 'Edit Pengguna: ' . $user->name)
@section('header-title', 'Edit Akun Pengguna')
@section('header-subtitle', 'Perbarui data profil, peran, dan status akun pengguna')

@section('content')
<div class="max-w-xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-white tracking-tight">Edit Akun: {{ $user->name }}</h1>
            <p class="text-xs text-slate-400 mt-0.5">Email: <span class="font-mono text-blue-400">{{ $user->email }}</span></p>
        </div>
        <a href="{{ route('users.index') }}" 
           class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-[#1C2330] hover:bg-[#263143] text-slate-300 hover:text-white border border-[#263143] text-xs font-semibold transition">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali</span>
        </a>
    </div>

    <div class="p-6 sm:p-8 rounded-2xl bg-[#1C2330] border border-[#263143] shadow-xl">
        <form action="{{ route('users.update', $user) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1.5">Nama Lengkap <span class="text-rose-400">*</span></label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-3.5 py-2.5 bg-[#121620] border border-[#263143] rounded-xl text-xs sm:text-sm text-white focus:outline-none focus:border-blue-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1.5">Alamat Email <span class="text-rose-400">*</span></label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-3.5 py-2.5 bg-[#121620] border border-[#263143] rounded-xl text-xs sm:text-sm text-white focus:outline-none focus:border-blue-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1.5">Nomor Handphone / WhatsApp</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full px-3.5 py-2.5 bg-[#121620] border border-[#263143] rounded-xl text-xs sm:text-sm text-white focus:outline-none focus:border-blue-500 font-mono">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1.5">Role / Peran <span class="text-rose-400">*</span></label>
                    <select name="role" required class="w-full px-3.5 py-2.5 bg-[#121620] border border-[#263143] rounded-xl text-xs sm:text-sm text-white focus:outline-none focus:border-blue-500">
                        <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>User (Pelapor)</option>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin (Akses Penuh)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1.5">Status Akun <span class="text-rose-400">*</span></label>
                    <select name="status" required class="w-full px-3.5 py-2.5 bg-[#121620] border border-[#263143] rounded-xl text-xs sm:text-sm text-white focus:outline-none focus:border-blue-500">
                        <option value="active" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ old('status', $user->status) === 'inactive' ? 'selected' : '' }}>Non-Aktif</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1.5">Kata Sandi Baru (Kosongkan jika tidak diubah)</label>
                <input type="password" name="password" minlength="6" placeholder="Biarkan kosong jika tidak diubah" class="w-full px-3.5 py-2.5 bg-[#121620] border border-[#263143] rounded-xl text-xs sm:text-sm text-white focus:outline-none focus:border-blue-500">
            </div>

            <div class="pt-4 border-t border-[#263143] flex items-center justify-end gap-3">
                <a href="{{ route('users.index') }}" class="px-4 py-2 rounded-xl bg-[#121620] text-slate-300 text-xs font-semibold">Batal</a>
                <button type="submit" class="px-5 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold transition shadow-lg shadow-blue-600/20">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

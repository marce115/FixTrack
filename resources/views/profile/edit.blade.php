@extends('layouts.app')

@section('title', 'Pengaturan Akun')
@section('header-title', 'Pengaturan Akun & Profil')
@section('header-subtitle', 'Kelola informasi identitas, nomor kontak, dan keamanan kata sandi Anda')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <!-- Profile Overview Card -->
    <div class="p-6 rounded-2xl bg-[#1C2330] border border-[#263143] shadow-lg flex flex-col sm:flex-row items-center gap-5">
        <div class="w-16 h-16 rounded-2xl bg-blue-600/20 border border-blue-500/30 flex items-center justify-center text-blue-400 font-extrabold text-2xl shadow-lg shadow-blue-500/10">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
        <div class="flex-1 text-center sm:text-left">
            <div class="flex flex-col sm:flex-row sm:items-center gap-2 mb-1">
                <h2 class="text-lg font-bold text-white">{{ $user->name }}</h2>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold uppercase tracking-wider {{ $user->role === 'admin' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : 'bg-blue-500/10 text-blue-400 border border-blue-500/20' }} w-max mx-auto sm:mx-0">
                    {{ $user->role }}
                </span>
            </div>
            <p class="text-xs text-slate-400">
                {{ $user->email }}
            </p>
        </div>
    </div>

    <!-- Edit Profile Form Card -->
    <div class="p-6 sm:p-8 rounded-2xl bg-[#1C2330] border border-[#263143] shadow-xl">
        <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Section 1: Personal Info -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-blue-400 mb-3 flex items-center gap-2">
                    <i data-lucide="user" class="w-4 h-4"></i>
                    <span>Informasi Pribadi & Kontak</span>
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1.5">Nama Lengkap <span class="text-rose-400">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-3.5 py-2.5 bg-[#121620] border border-[#263143] rounded-xl text-xs sm:text-sm text-white focus:outline-none focus:border-blue-500 transition">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1.5">Alamat Email <span class="text-rose-400">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-3.5 py-2.5 bg-[#121620] border border-[#263143] rounded-xl text-xs sm:text-sm text-white focus:outline-none focus:border-blue-500 transition">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1.5">Nomor Handphone / WhatsApp</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="08xxxxxxxxxx" class="w-full px-3.5 py-2.5 bg-[#121620] border border-[#263143] rounded-xl text-xs sm:text-sm text-white focus:outline-none focus:border-blue-500 font-mono transition">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1.5">Role Pengguna</label>
                        <input type="text" value="{{ ucfirst($user->role) }}" disabled class="w-full px-3.5 py-2.5 bg-[#121620]/50 border border-[#263143] rounded-xl text-xs sm:text-sm text-slate-400 uppercase font-mono cursor-not-allowed">
                    </div>
                </div>
            </div>

            <!-- Section 2: Security & Password -->
            <div class="pt-6 border-t border-[#263143]/70">
                <h3 class="text-xs font-bold uppercase tracking-wider text-amber-400 mb-3 flex items-center gap-2">
                    <i data-lucide="shield" class="w-4 h-4"></i>
                    <span>Ubah Kata Sandi (Opsional)</span>
                </h3>
                <p class="text-[11px] text-slate-400 mb-4">Biarkan seluruh kolom sandi kosong jika Anda tidak ingin memperbarui kata sandi.</p>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1.5">Kata Sandi Saat Ini</label>
                        <input type="password" name="current_password" placeholder="Masukkan kata sandi lama Anda" class="w-full px-3.5 py-2.5 bg-[#121620] border border-[#263143] rounded-xl text-xs sm:text-sm text-white focus:outline-none focus:border-blue-500 transition">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1.5">Kata Sandi Baru</label>
                            <input type="password" name="password" minlength="6" placeholder="Minimal 6 karakter" class="w-full px-3.5 py-2.5 bg-[#121620] border border-[#263143] rounded-xl text-xs sm:text-sm text-white focus:outline-none focus:border-blue-500 transition">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1.5">Ulangi Kata Sandi Baru</label>
                            <input type="password" name="password_confirmation" minlength="6" placeholder="Ketik ulang kata sandi baru" class="w-full px-3.5 py-2.5 bg-[#121620] border border-[#263143] rounded-xl text-xs sm:text-sm text-white focus:outline-none focus:border-blue-500 transition">
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-[#263143] flex items-center justify-end">
                <button type="submit" 
                        class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs sm:text-sm font-semibold transition shadow-lg shadow-blue-600/20 flex items-center gap-2">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    <span>Simpan Pengaturan Akun</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection


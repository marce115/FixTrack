@extends('layouts.app')

@section('title', 'Data Pengguna')
@section('header-title', 'Data Pengguna')
@section('header-subtitle', 'Kelola akun pengguna aplikasi FixTrack')

@section('content')
<div class="space-y-6">

    <!-- Top Action Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-white tracking-tight">Daftar Akun Pengguna</h1>
            <p class="text-xs text-slate-400 mt-1">
                Total {{ $users->total() }} akun pengguna terdaftar di sistem.
            </p>
        </div>
        <a href="{{ route('users.create') }}" 
           class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold transition shadow-lg shadow-blue-600/20">
            <i data-lucide="user-plus" class="w-4 h-4"></i>
            <span>Tambah Pengguna Baru</span>
        </a>
    </div>

    <!-- Search & Filter Bar -->
    <div class="p-4 rounded-2xl bg-[#1C2330] border border-[#263143] shadow-lg">
        <form method="GET" action="{{ route('users.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <div class="relative lg:col-span-2">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-500">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </span>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Cari nama, email, nomor HP..." 
                       class="w-full pl-9 pr-3 py-2 bg-[#121620] border border-[#263143] rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 transition">
            </div>

            <!-- Role Filter -->
            <div>
                <select name="role" class="w-full px-3 py-2 bg-[#121620] border border-[#263143] rounded-xl text-xs text-white focus:outline-none focus:border-blue-500">
                    <option value="">Semua Role</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>User (Pelapor)</option>
                </select>
            </div>

            <!-- Submit -->
            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 py-2 px-3 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-xs font-semibold transition flex items-center justify-center gap-1">
                    <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                    <span>Filter</span>
                </button>
                @if(request()->anyFilled(['search', 'role', 'status']))
                    <a href="{{ route('users.index') }}" class="p-2 bg-rose-500/10 border border-rose-500/20 text-rose-400 hover:bg-rose-500/20 rounded-xl text-xs transition">
                        <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="rounded-2xl bg-[#1C2330] border border-[#263143] shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#151922] border-b border-[#263143] text-slate-400 uppercase text-[10px] tracking-wider">
                    <tr>
                        <th class="py-3.5 px-4 font-semibold">Nama Pengguna</th>
                        <th class="py-3.5 px-4 font-semibold">Email</th>
                        <th class="py-3.5 px-4 font-semibold">Role</th>
                        <th class="py-3.5 px-4 font-semibold">Kontak</th>
                        <th class="py-3.5 px-4 font-semibold">Status Akun</th>
                        <th class="py-3.5 px-4 text-right font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#263143]/50">
                    @forelse($users as $u)
                        <tr class="hover:bg-[#222B3C]/50 transition">
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-lg bg-blue-600/20 border border-blue-500/30 flex items-center justify-center text-blue-400 font-bold text-xs">
                                        {{ strtoupper(substr($u->name, 0, 1)) }}
                                    </div>
                                    <span class="font-bold text-white">{{ $u->name }}</span>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-slate-300 whitespace-nowrap font-mono">
                                {{ $u->email }}
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold 
                                    {{ $u->role === 'admin' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : 'bg-blue-500/10 text-blue-400 border border-blue-500/20' }}">
                                    {{ $u->role === 'admin' ? 'Admin' : 'User (Pelapor)' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 font-mono text-slate-400 whitespace-nowrap">
                                {{ $u->phone ?: '-' }}
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $u->status === 'active' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-rose-500/10 text-rose-400 border border-rose-500/20' }}">
                                    {{ $u->status === 'active' ? 'Aktif' : 'Non-Aktif' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('users.edit', $u) }}" 
                                       class="p-1.5 rounded-lg bg-[#121620] hover:bg-blue-600 hover:text-white border border-[#263143] text-slate-300 transition" 
                                       title="Edit Akun">
                                        <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                    </a>

                                    @if($u->id !== Auth::id())
                                        <form action="{{ route('users.destroy', $u) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="p-1.5 rounded-lg bg-[#121620] hover:bg-rose-600 hover:text-white border border-[#263143] text-rose-400 transition" 
                                                    title="Hapus Akun">
                                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                Tidak ada data pengguna.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="p-4 border-t border-[#263143] bg-[#151922]">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
       class="fixed inset-y-0 left-0 z-50 w-64 bg-[#151922] border-r border-[#263143] transition-transform duration-200 ease-in-out flex flex-col justify-between">

    <!-- Top Brand & Nav -->
    <div class="flex-1 overflow-y-auto">
        <!-- Brand Header -->
        <div class="h-16 flex items-center justify-between px-6 border-b border-[#263143]">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                <div class="w-9 h-9 rounded-xl bg-blue-600 flex items-center justify-center text-white shadow-lg shadow-blue-500/20 group-hover:scale-105 transition">
                    <i data-lucide="wrench" class="w-5 h-5"></i>
                </div>
                <div>
                    <span class="text-lg font-extrabold tracking-tight text-white flex items-center gap-1.5">
                        Fix<span class="text-blue-500">Track</span>
                    </span>
                    <span class="text-[9px] uppercase tracking-widest text-slate-400 font-semibold block -mt-1">
                        Inventory & Issue Tracking
                    </span>
                </div>
            </a>
            <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <!-- Role Badge -->
        <div class="px-6 py-4">
            <div class="px-3.5 py-2.5 rounded-xl bg-[#1C2330] border border-[#263143] flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-2 h-2 rounded-full {{ Auth::user()->isAdmin() ? 'bg-amber-400' : 'bg-blue-400' }} animate-pulse"></div>
                    <span class="text-xs font-semibold text-slate-300">
                        {{ Auth::user()->isAdmin() ? 'Administrator' : 'User (Pelapor)' }}
                    </span>
                </div>
                <span class="text-[10px] font-mono px-2 py-0.5 rounded uppercase font-bold {{ Auth::user()->isAdmin() ? 'bg-amber-500/20 text-amber-400 border border-amber-500/30' : 'bg-blue-500/20 text-blue-400 border border-blue-500/30' }}">
                    {{ Auth::user()->role }}
                </span>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="px-3 space-y-1">
            <div class="px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                Menu Utama
            </div>

            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('dashboard') || request()->routeIs('home') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:text-slate-100 hover:bg-[#1C2330]' }}">
                <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                <span>Dashboard</span>
            </a>

            @if(Auth::user()->isAdmin())
                <!-- Menu Admin: Data Laporan -->
                <a href="{{ route('reports.index') }}" 
                   class="flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('reports.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:text-slate-100 hover:bg-[#1C2330]' }}">
                    <div class="flex items-center gap-3">
                        <i data-lucide="clipboard-list" class="w-4 h-4"></i>
                        <span>Data Laporan</span>
                    </div>
                    @php
                        $pendingReportsCount = \App\Models\Report::where('status', 'Pending')->count();
                    @endphp
                    @if($pendingReportsCount > 0)
                        <span class="px-2 py-0.5 text-[11px] font-bold rounded-full {{ request()->routeIs('reports.*') ? 'bg-white/20 text-white' : 'bg-blue-500/10 text-blue-400 border border-blue-500/20' }}">
                            {{ $pendingReportsCount }}
                        </span>
                    @endif
                </a>

                <!-- Master Data Header -->
                <div class="pt-4 px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                    Master Data
                </div>

                <!-- Data Barang -->
                <a href="{{ route('items.index') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('items.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:text-slate-100 hover:bg-[#1C2330]' }}">
                    <i data-lucide="package" class="w-4 h-4"></i>
                    <span>Data Barang</span>
                </a>

                <!-- Kategori Barang -->
                <a href="{{ route('categories.index') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('categories.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:text-slate-100 hover:bg-[#1C2330]' }}">
                    <i data-lucide="tags" class="w-4 h-4"></i>
                    <span>Kategori Barang</span>
                </a>

                <!-- Data Pengguna -->
                <a href="{{ route('users.index') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('users.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:text-slate-100 hover:bg-[#1C2330]' }}">
                    <i data-lucide="users" class="w-4 h-4"></i>
                    <span>Data Pengguna</span>
                </a>

                <!-- Ekspor Rekap (Tambahan Opsional) -->
                <a href="{{ route('exports.index') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('exports.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:text-slate-100 hover:bg-[#1C2330]' }}">
                    <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
                    <span>Export Rekap (PDF/XLS)</span>
                </a>
            @else
                <!-- Menu User: Buat Laporan -->
                <a href="{{ route('reports.create') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('reports.create') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:text-slate-100 hover:bg-[#1C2330]' }}">
                    <i data-lucide="plus-circle" class="w-4 h-4"></i>
                    <span>Buat Laporan</span>
                </a>

                <!-- Menu User: Riwayat Laporan -->
                <a href="{{ route('reports.index') }}" 
                   class="flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('reports.index') || request()->routeIs('reports.show') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:text-slate-100 hover:bg-[#1C2330]' }}">
                    <div class="flex items-center gap-3">
                        <i data-lucide="history" class="w-4 h-4"></i>
                        <span>Riwayat Laporan</span>
                    </div>
                </a>
            @endif

            <!-- Pengaturan Akun -->
            <div class="pt-4 px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                Akun Saya
            </div>
            <a href="{{ route('profile.edit') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('profile.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-400 hover:text-slate-100 hover:bg-[#1C2330]' }}">
                <i data-lucide="settings" class="w-4 h-4"></i>
                <span>Pengaturan Akun</span>
            </a>
        </nav>
    </div>

    <!-- Bottom User Section & Logout -->
    <div class="p-4 border-t border-[#263143] bg-[#11141B]">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-xl bg-blue-600/20 border border-blue-500/30 flex items-center justify-center text-blue-400 font-bold text-base shrink-0">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <div class="text-sm font-semibold text-white truncate">{{ Auth::user()->name }}</div>
                <div class="text-xs text-slate-400 truncate">{{ Auth::user()->email }}</div>
            </div>
        </div>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" 
                    class="w-full flex items-center justify-center gap-2 px-3 py-2 rounded-xl text-xs font-semibold text-rose-400 hover:text-white bg-rose-500/10 hover:bg-rose-600 border border-rose-500/20 hover:border-transparent transition">
                <i data-lucide="log-out" class="w-4 h-4"></i>
                <span>Keluar dari Sistem</span>
            </button>
        </form>
    </div>
</aside>

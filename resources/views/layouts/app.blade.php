<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'FixTrack') - Inventory & Issue Tracking System</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #0F1117;
            color: #F8FAFC;
        }
        .font-mono {
            font-family: 'JetBrains Mono', monospace;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-[#0F1117] text-slate-100 min-h-screen antialiased flex selection:bg-blue-600 selection:text-white" x-data="{ sidebarOpen: false }">

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" 
         x-transition:enter="transition-opacity ease-linear duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false" 
         class="fixed inset-0 bg-black/70 backdrop-blur-sm z-40 lg:hidden" style="display: none;"></div>

    <!-- Sidebar Component -->
    @include('layouts.sidebar')

    <!-- Main Wrapper -->
    <div class="flex-1 flex flex-col min-w-0 min-h-screen lg:pl-64">
        
        <!-- Top Navbar -->
        <header class="sticky top-0 z-30 h-16 bg-[#151922]/90 backdrop-blur-md border-b border-[#263143] px-4 sm:px-6 lg:px-8 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen = true" class="p-2 -ml-2 rounded-lg text-slate-400 hover:text-white hover:bg-[#1C2330] lg:hidden focus:outline-none">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
                <div>
                    <h2 class="text-base font-semibold text-white tracking-tight">@yield('header-title', 'Dashboard')</h2>
                    <p class="text-xs text-slate-400 hidden sm:block">@yield('header-subtitle', 'Sistem Pelaporan Kerusakan & Inventaris Barang')</p>
                </div>
            </div>

            <!-- Right Controls: Notifications & User Profile -->
            <div class="flex items-center gap-3">
                <!-- Notifications Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    @php
                        $unreadCount = Auth::user()->unreadNotificationsCount();
                        $recentNotifications = Auth::user()->notifications()->take(5)->get();
                    @endphp
                    <button @click="open = !open" 
                            class="relative p-2 rounded-xl text-slate-400 hover:text-white hover:bg-[#1C2330] border border-transparent hover:border-[#263143] transition focus:outline-none"
                            title="Notifikasi">
                        <i data-lucide="bell" class="w-5 h-5"></i>
                        @if($unreadCount > 0)
                            <span class="absolute top-1 right-1 flex h-4 min-w-4 px-1 items-center justify-center rounded-full bg-rose-500 text-[10px] font-bold text-white shadow-lg shadow-rose-500/50">
                                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                            </span>
                        @endif
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="open" 
                         @click.away="open = false" 
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-80 sm:w-96 rounded-2xl bg-[#1C2330] border border-[#263143] shadow-2xl z-50 overflow-hidden" 
                         style="display: none;">
                        <div class="p-3.5 bg-[#151922] border-b border-[#263143] flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold text-white uppercase tracking-wider">Notifikasi</span>
                                @if($unreadCount > 0)
                                    <span class="px-2 py-0.5 rounded-full bg-blue-500/10 text-blue-400 border border-blue-500/20 text-[10px] font-bold">
                                        {{ $unreadCount }} baru
                                    </span>
                                @endif
                            </div>
                            @if($unreadCount > 0)
                                <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-[11px] text-blue-400 hover:text-blue-300 font-medium transition">
                                        Tandai semua dibaca
                                    </button>
                                </form>
                            @endif
                        </div>

                        <div class="divide-y divide-[#263143]/60 max-h-80 overflow-y-auto">
                            @forelse($recentNotifications as $notif)
                                <a href="{{ route('notifications.read', $notif) }}" 
                                   class="block p-3.5 hover:bg-[#222B3C] transition {{ !$notif->is_read ? 'bg-blue-600/5' : '' }}">
                                    <div class="flex items-start gap-3">
                                        <div class="p-1.5 rounded-lg {{ $notif->type === 'status_updated' ? 'bg-amber-500/10 text-amber-400' : ($notif->type === 'new_comment' ? 'bg-blue-500/10 text-blue-400' : 'bg-emerald-500/10 text-emerald-400') }} shrink-0 mt-0.5">
                                            @if($notif->type === 'status_updated')
                                                <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                                            @elseif($notif->type === 'new_comment')
                                                <i data-lucide="message-square" class="w-4 h-4"></i>
                                            @else
                                                <i data-lucide="file-plus" class="w-4 h-4"></i>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="text-xs font-semibold {{ !$notif->is_read ? 'text-white' : 'text-slate-300' }} truncate">
                                                {{ $notif->title }}
                                            </div>
                                            <p class="text-[11px] text-slate-400 mt-0.5 line-clamp-2">
                                                {{ $notif->message }}
                                            </p>
                                            <span class="text-[10px] text-slate-500 font-mono mt-1 block">
                                                {{ $notif->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                        @if(!$notif->is_read)
                                            <span class="w-2 h-2 rounded-full bg-blue-500 shrink-0 mt-1.5"></span>
                                        @endif
                                    </div>
                                </a>
                            @empty
                                <div class="p-6 text-center text-xs text-slate-400">
                                    <i data-lucide="bell-off" class="w-8 h-8 text-slate-600 mx-auto mb-2"></i>
                                    Tidak ada notifikasi baru
                                </div>
                            @endforelse
                        </div>

                        <div class="p-2.5 bg-[#151922] border-t border-[#263143] text-center">
                            <a href="{{ route('notifications.index') }}" class="text-xs text-blue-400 hover:text-blue-300 font-semibold transition block py-1">
                                Lihat Semua Notifikasi &rarr;
                            </a>
                        </div>
                    </div>
                </div>

                <!-- User Profile -->
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 p-1.5 rounded-xl hover:bg-[#1C2330] border border-transparent hover:border-[#263143] transition">
                    <div class="w-8 h-8 rounded-lg bg-blue-600/20 border border-blue-500/30 flex items-center justify-center text-blue-400 font-bold text-sm">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="hidden sm:block text-left">
                        <div class="text-xs font-semibold text-white leading-tight">{{ Auth::user()->name }}</div>
                        <div class="text-[10px] text-blue-400 font-medium uppercase tracking-wider">{{ Auth::user()->role }}</div>
                    </div>
                </a>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            <!-- Flash Notifications -->
            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-300 flex items-start gap-3 shadow-lg shadow-emerald-950/20" x-data="{ show: true }" x-show="show">
                    <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-400 shrink-0 mt-0.5"></i>
                    <div class="flex-1 text-sm font-medium">{{ session('success') }}</div>
                    <button @click="show = false" class="text-emerald-400/70 hover:text-emerald-300"><i data-lucide="x" class="w-4 h-4"></i></button>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-300 flex items-start gap-3 shadow-lg shadow-rose-950/20" x-data="{ show: true }" x-show="show">
                    <i data-lucide="alert-triangle" class="w-5 h-5 text-rose-400 shrink-0 mt-0.5"></i>
                    <div class="flex-1 text-sm font-medium">{{ session('error') }}</div>
                    <button @click="show = false" class="text-rose-400/70 hover:text-rose-300"><i data-lucide="x" class="w-4 h-4"></i></button>
                </div>
            @endif

            @if(session('info'))
                <div class="mb-6 p-4 rounded-xl bg-blue-500/10 border border-blue-500/20 text-blue-300 flex items-start gap-3 shadow-lg shadow-blue-950/20" x-data="{ show: true }" x-show="show">
                    <i data-lucide="info" class="w-5 h-5 text-blue-400 shrink-0 mt-0.5"></i>
                    <div class="flex-1 text-sm font-medium">{{ session('info') }}</div>
                    <button @click="show = false" class="text-blue-400/70 hover:text-blue-300"><i data-lucide="x" class="w-4 h-4"></i></button>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-300 shadow-lg shadow-rose-950/20">
                    <div class="flex items-center gap-2 font-semibold text-sm mb-2 text-rose-400">
                        <i data-lucide="alert-circle" class="w-5 h-5"></i>
                        <span>Mohon periksa kesalahan input berikut:</span>
                    </div>
                    <ul class="list-disc list-inside text-xs space-y-1 text-rose-200">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="mt-auto border-t border-[#263143] py-4 px-6 text-center text-xs text-slate-500 flex flex-col sm:flex-row items-center justify-between gap-2">
            <div>
                <span class="font-semibold text-slate-400">FixTrack</span> &mdash; Inventory & Issue Tracking System
            </div>
            <div>
                &copy; {{ date('Y') }} FixTrack. All rights reserved.
            </div>
        </footer>
    </div>

    @stack('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.renderIcons) {
                window.renderIcons();
            }
        });
    </script>
</body>
</html>

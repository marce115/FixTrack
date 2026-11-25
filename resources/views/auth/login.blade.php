<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - FixTrack (Inventory & Issue Tracking)</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #0F1117;
        }
        .font-mono {
            font-family: 'JetBrains Mono', monospace;
        }
    </style>
</head>
<body class="bg-[#0F1117] text-slate-100 min-h-screen flex items-center justify-center p-4 relative overflow-hidden selection:bg-blue-600 selection:text-white"
      x-data="{ 
          email: '{{ old('email') }}', 
          password: '', 
          showPassword: false
      }">

    <!-- Ambient Glow -->
    <div class="absolute -top-40 -left-40 w-96 h-96 bg-blue-600/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-amber-500/5 rounded-full blur-3xl pointer-events-none"></div>

    <div class="w-full max-w-md relative z-10">
        <!-- Logo and Heading -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-blue-600 text-white shadow-xl shadow-blue-500/25 mb-4 border border-blue-400/30">
                <i data-lucide="wrench" class="w-7 h-7"></i>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white flex items-center justify-center gap-1.5">
                Fix<span class="text-blue-500">Track</span>
            </h1>
            <p class="text-xs sm:text-sm text-slate-400 mt-1">
                Sistem Pelaporan Kerusakan & Inventaris Barang
            </p>
        </div>

        <!-- Login Card -->
        <div class="bg-[#1C2330] border border-[#263143] rounded-2xl p-6 sm:p-8 shadow-2xl backdrop-blur-sm">
            <div class="mb-6">
                <h2 class="text-lg font-bold text-white tracking-tight">Masuk ke Akun Anda</h2>
                <p class="text-xs text-slate-400 mt-1">Masukkan email dan password untuk masuk ke sistem.</p>
            </div>

            <!-- Errors Alert -->
            @if($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/25 text-rose-300 text-xs">
                    <div class="flex items-center gap-2 font-semibold mb-1 text-rose-400">
                        <i data-lucide="alert-circle" class="w-4 h-4"></i>
                        <span>Autentikasi Gagal</span>
                    </div>
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('info'))
                <div class="mb-6 p-3 rounded-xl bg-blue-500/10 border border-blue-500/20 text-blue-300 text-xs flex items-center gap-2">
                    <i data-lucide="info" class="w-4 h-4 text-blue-400 shrink-0"></i>
                    <span>{{ session('info') }}</span>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-300 mb-1.5">
                        Alamat Email
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-500">
                            <i data-lucide="mail" class="w-4 h-4"></i>
                        </span>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               x-model="email"
                               required 
                               placeholder="nama@perusahaan.com"
                               class="w-full pl-10 pr-4 py-2.5 bg-[#121620] border border-[#263143] rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
                    </div>
                </div>

                <!-- Password Field with Show/Hide -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-xs font-semibold text-slate-300">
                            Kata Sandi
                        </label>
                    </div>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-500">
                            <i data-lucide="lock" class="w-4 h-4"></i>
                        </span>
                        <input :type="showPassword ? 'text' : 'password'" 
                               id="password" 
                               name="password" 
                               x-model="password"
                               required 
                               placeholder="Masukkan kata sandi"
                               class="w-full pl-10 pr-11 py-2.5 bg-[#121620] border border-[#263143] rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
                        <button type="button" 
                                @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-500 hover:text-slate-300 focus:outline-none">
                            <i data-lucide="eye" x-show="!showPassword" class="w-4 h-4"></i>
                            <i data-lucide="eye-off" x-show="showPassword" class="w-4 h-4" style="display: none;"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded bg-[#121620] border-[#263143] text-blue-600 focus:ring-blue-500 focus:ring-offset-0">
                        <span class="text-xs text-slate-400">Ingat sesi saya</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full py-2.5 px-4 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-semibold text-sm transition shadow-lg shadow-blue-600/25 flex items-center justify-center gap-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-[#1C2330]">
                    <span>Masuk ke Sistem</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </button>
            </form>

            <div class="mt-6 pt-5 border-t border-[#263143] text-center">
                <p class="text-xs text-slate-400">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="text-blue-400 hover:text-blue-300 font-semibold transition">
                        Daftar sebagai Pelapor &rarr;
                    </a>
                </p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.renderIcons) {
                window.renderIcons();
            }
        });
    </script>
</body>
</html>

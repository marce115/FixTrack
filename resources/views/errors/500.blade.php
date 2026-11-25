<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Gangguan Server | FixTrack</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#0F1117] text-slate-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-[#1C2330] border border-[#263143] rounded-2xl p-8 text-center shadow-2xl">
        <div class="w-16 h-16 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-400 flex items-center justify-center mx-auto mb-4">
            <i data-lucide="server-crash" class="w-8 h-8"></i>
        </div>
        <span class="text-xs font-mono font-bold text-rose-400 uppercase tracking-widest">Error 500 &bull; Server Error</span>
        <h1 class="text-xl font-bold text-white mt-1 mb-2">Terjadi Gangguan Sistem</h1>
        <p class="text-xs text-slate-400 mb-6 leading-relaxed">
            Sistem mengalami kendala saat memproses permintaan Anda. Tim teknis sedang menangani kendala ini.
        </p>
        <div class="flex items-center justify-center gap-3">
            <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold transition">
                Kembali ke Dashboard
            </a>
        </div>
    </div>
</body>
</html>


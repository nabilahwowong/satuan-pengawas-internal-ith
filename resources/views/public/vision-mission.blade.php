<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visi, Misi & Profil - SPI ITH</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-800 antialiased font-sans flex flex-col min-h-screen">

    <!-- Navbar Publik -->
    <header class="bg-white/95 backdrop-blur-md border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-6 h-20 flex items-center justify-between">
            <a href="{{ route('public.home') }}" class="flex items-center gap-2">
                <span class="text-2xl font-black tracking-tight text-indigo-700">SPI ITH</span>
                <span class="text-xs text-slate-500 hidden sm:inline-block border-l border-slate-300 pl-2">Satuan Pengawas Intern</span>
            </a>

            <div class="flex items-center gap-6">
                <nav class="hidden md:flex items-center gap-5 text-xs font-semibold">
                    <a href="{{ route('public.home') }}" class="text-slate-600 hover:text-indigo-600 transition">Beranda</a>
                    <a href="{{ route('public.vision-mission') }}" class="text-indigo-600 font-bold">Visi & Misi</a>
                    <a href="{{ route('public.work-programs') }}" class="text-slate-600 hover:text-indigo-600 transition">Program Kerja</a>
                    <a href="{{ route('surveys.index') }}" class="text-slate-600 hover:text-indigo-600 transition">Survei Layanan</a>
                </nav>

                @auth
                    <a href="{{ route(Auth::user()->role . '.dashboard') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-xs font-semibold">
                        Dashboard &rarr;
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-xs font-semibold">
                        Portal Internal &rarr;
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Header Judul -->
    <div class="bg-slate-900 text-white py-14 px-6 text-center">
        <div class="max-w-3xl mx-auto space-y-2">
            <span class="text-xs font-bold text-indigo-400 uppercase tracking-widest">Profil Lembaga</span>
            <h1 class="text-3xl font-extrabold tracking-tight">Visi, Misi & Tentang SPI ITH</h1>
            <p class="text-xs md:text-sm text-slate-400">Landasan filosofis, arah strategis, dan komitmen integritas pengawasan perguruan tinggi.</p>
        </div>
    </div>

    <!-- Konten Visi Misi -->
    <main class="max-w-4xl mx-auto px-6 py-12 flex-1 space-y-8">
        <!-- Profil Singkat -->
        <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm space-y-3">
            <span class="text-xs font-bold text-indigo-600 uppercase tracking-wider">Tentang Organisasi</span>
            <h2 class="text-xl font-bold text-slate-800">Satuan Pengawas Intern ITH</h2>
            <p class="text-sm text-slate-600 leading-relaxed">{{ $profile->about }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Visi -->
            <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm space-y-3">
                <div class="flex items-center gap-2">
                    <span class="text-2xl">🎯</span>
                    <h3 class="text-lg font-bold text-slate-800">Visi SPI</h3>
                </div>
                <p class="text-sm text-slate-600 leading-relaxed">{{ $profile->vision }}</p>
            </div>

            <!-- Misi -->
            <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm space-y-3">
                <div class="flex items-center gap-2">
                    <span class="text-2xl">🚀</span>
                    <h3 class="text-lg font-bold text-slate-800">Misi SPI</h3>
                </div>
                <div class="text-sm text-slate-600 leading-relaxed whitespace-pre-line">{{ $profile->mission }}</div>
            </div>
        </div>
    </main>

    <footer class="bg-slate-900 text-slate-400 text-xs py-8 border-t border-slate-800 text-center mt-auto">
        <p>&copy; {{ date('Y') }} Satuan Pengawas Intern (SPI) - Institut Teknologi Bacharuddin Jusuf Habibie.</p>
    </footer>

</body>
</html>

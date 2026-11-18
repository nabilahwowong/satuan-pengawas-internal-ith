<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Program Kerja Pengawasan - SPI ITH</title>
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
                    <a href="{{ route('public.vision-mission') }}" class="text-slate-600 hover:text-indigo-600 transition">Visi & Misi</a>
                    <a href="{{ route('public.work-programs') }}" class="text-indigo-600 font-bold">Program Kerja</a>
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
            <span class="text-xs font-bold text-indigo-400 uppercase tracking-widest">Akuntabilitas & Kinerja</span>
            <h1 class="text-3xl font-extrabold tracking-tight">10 Program Kerja Pengawasan SPI ITH</h1>
            <p class="text-xs md:text-sm text-slate-400">Rencana kerja pengawasan tahunan berbasis kepatuhan dan risiko untuk tata kelola kampus yang sehat.</p>
        </div>
    </div>

    <!-- Konten Program Kerja -->
    <main class="max-w-6xl mx-auto px-6 py-12 flex-1">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @forelse ($programs as $prog)
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between space-y-3">
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-black text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-md">
                                PROGRAM #{{ $prog->order_num }}
                            </span>
                            <span class="px-2.5 py-0.5 text-[10px] font-bold rounded-full
                                {{ $prog->status === 'terealisasi' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                {{ $prog->status === 'berjalan' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $prog->status === 'terencana' ? 'bg-amber-100 text-amber-700' : '' }}">
                                {{ strtoupper($prog->status) }}
                            </span>
                        </div>
                        <h3 class="text-sm font-bold text-slate-800 leading-snug">{{ $prog->title }}</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">{{ $prog->description }}</p>
                    </div>
                </div>
            @empty
                <div class="col-span-2 p-8 text-center bg-white rounded-2xl border text-xs text-slate-400">
                    Belum ada program kerja yang dipublikasikan.
                </div>
            @endforelse
        </div>
    </main>

    <footer class="bg-slate-900 text-slate-400 text-xs py-8 border-t border-slate-800 text-center mt-auto">
        <p>&copy; {{ date('Y') }} Satuan Pengawas Intern (SPI) - Institut Teknologi Bacharuddin Jusuf Habibie.</p>
    </footer>

</body>
</html>

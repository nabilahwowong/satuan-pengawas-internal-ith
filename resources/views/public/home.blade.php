<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Satuan Pengawas Intern - Institut Teknologi Bacharuddin Jusuf Habibie</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-800 antialiased font-sans">

    <!-- Navbar Publik -->
    <header class="bg-white/95 backdrop-blur-md border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-6 h-20 flex items-center justify-between">
            <a href="{{ route('public.home') }}" class="flex items-center gap-2">
                <span class="text-2xl font-black tracking-tight text-indigo-700">SPI ITH</span>
                <span class="text-xs text-slate-500 hidden sm:inline-block border-l border-slate-300 pl-2">Satuan Pengawas Intern</span>
            </a>

            <!-- Menu Navigasi ke Halaman Masing-masing -->
            <div class="flex items-center gap-6">
                <nav class="hidden md:flex items-center gap-5 text-xs font-semibold text-slate-600">
                    <a href="{{ route('public.home') }}" class="text-indigo-600 font-bold">Beranda</a>
                    <a href="{{ route('public.vision-mission') }}" class="hover:text-indigo-600 transition">Visi & Misi</a>
                    <a href="{{ route('public.work-programs') }}" class="hover:text-indigo-600 transition">Program Kerja</a>
                    <a href="{{ route('surveys.index') }}" class="hover:text-indigo-600 transition">Survei Layanan</a>
                </nav>

                @auth
                    @php
                        $dashboardRoute = route(Auth::user()->role . '.dashboard');
                    @endphp
                    <div class="flex items-center gap-2">
                        <a href="{{ $dashboardRoute }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-semibold shadow-sm transition">
                            Dashboard ({{ strtoupper(Auth::user()->role) }}) &rarr;
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-3 py-2 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg text-xs font-semibold transition cursor-pointer">
                                Logout
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-semibold shadow-sm transition">
                        Portal Internal &rarr;
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Hero Banner -->
    <section class="bg-linear-to-br from-indigo-900 via-slate-900 to-slate-950 text-white py-20 px-6">
        <div class="max-w-4xl mx-auto text-center space-y-4">
            <span class="px-3 py-1 bg-indigo-500/20 text-indigo-300 text-xs font-bold rounded-full border border-indigo-400/30 uppercase tracking-widest">
                Good University Governance
            </span>
            <h1 class="text-3xl md:text-5xl font-extrabold leading-tight tracking-tight">
                Integritas, Akuntabilitas, dan Mutu Pengawasan Kampus
            </h1>
            <p class="text-slate-300 text-sm md:text-base max-w-2xl mx-auto">
                Mendukung perwujudan tata kelola perguruan tinggi yang transparan dan akuntabel di Institut Teknologi Bacharuddin Jusuf Habibie.
            </p>
        </div>
    </section>

    <!-- Konten Berita & Agenda -->
    <main class="max-w-6xl mx-auto px-6 py-16 grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Kolom Berita & Pengumuman -->
        <div class="lg:col-span-2 space-y-6">
            <h2 class="text-lg font-bold text-slate-800">Warta & Pengumuman Pengawasan</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse ($posts as $post)
                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
                        <div>
                            <span class="text-[11px] text-slate-400 font-semibold">{{ $post->created_at->format('d M Y') }}</span>
                            <h3 class="font-bold text-slate-800 text-sm mt-1 leading-snug">{{ $post->title }}</h3>
                            <p class="text-xs text-slate-500 mt-2 line-clamp-3 leading-relaxed">{{ Str::limit(strip_tags($post->content), 120) }}</p>
                        </div>
                        <a href="{{ route('public.post', $post) }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 mt-4 inline-block">
                            Baca Selengkapnya &rarr;
                        </a>
                    </div>
                @empty
                    <div class="col-span-2 p-8 text-center bg-white rounded-2xl border border-slate-200 text-slate-400 text-xs">
                        Belum ada publikasi berita atau pengumuman.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Kolom Agenda Kegiatan SPI -->
        <div class="space-y-6">
            <h2 class="text-lg font-bold text-slate-800">Agenda Kegiatan SPI</h2>
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                @forelse ($schedules as $item)
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 text-xs space-y-1">
                        <span class="font-bold text-indigo-600 block">{{ \Carbon\Carbon::parse($item->start_time)->format('d M Y, H:i') }} WITA</span>
                        <h4 class="font-bold text-slate-800">{{ $item->title }}</h4>
                        <p class="text-slate-500">{{ $item->location ?? 'Auditorium / Ruang Rapat ITH' }}</p>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 italic">Belum ada agenda kegiatan mendatang.</p>
                @endforelse
            </div>
        </div>
    </main>

    <!-- Footer Publik -->
    <footer class="bg-slate-900 text-slate-400 text-xs py-8 border-t border-slate-800 text-center">
        <p>&copy; {{ date('Y') }} Satuan Pengawas Intern (SPI) - Institut Teknologi Bacharuddin Jusuf Habibie.</p>
    </footer>

</body>
</html>

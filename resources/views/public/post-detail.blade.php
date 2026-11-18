<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $post->title }} - SPI ITH</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-800 antialiased font-sans">
    <!-- Navbar -->
    <header class="bg-white border-b border-slate-200">
        <div class="max-w-4xl mx-auto px-6 h-20 flex items-center justify-between">
            <a href="{{ route('public.home') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                &larr; Kembali ke Beranda Utama
            </a>
            <a href="{{ route('login') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-xs font-semibold">
                Portal Masuk
            </a>
        </div>
    </header>

    <main class="max-w-3xl mx-auto px-6 py-12">
        <article class="bg-white p-8 md:p-12 rounded-3xl border border-slate-200 shadow-sm space-y-6">
            <div class="border-b border-slate-100 pb-6 space-y-2">
                <span class="text-xs font-bold text-indigo-600 uppercase tracking-wider">Warta SPI ITH</span>
                <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 leading-tight">{{ $post->title }}</h1>
                <p class="text-xs text-slate-400">Dipublikasikan pada: {{ $post->created_at->translatedFormat('l, d F Y - H:i') }} WITA</p>
            </div>

            <div class="prose prose-slate max-w-none text-sm md:text-base leading-relaxed text-slate-700 whitespace-pre-line">
                {{ $post->content }}
            </div>
        </article>
    </main>

    <footer class="text-center py-8 text-xs text-slate-400 border-t border-slate-200">
        &copy; {{ date('Y') }} Satuan Pengawas Internal - Institut Teknologi Bacharuddin Jusuf Habibie
    </footer>
</body>
</html>

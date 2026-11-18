<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survei Persepsi Kampus - SPI ITH</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-100 text-slate-800 p-4 md:p-10 min-h-screen">
    <div class="max-w-3xl mx-auto bg-white p-6 md:p-10 rounded-2xl border border-slate-200 shadow-sm space-y-6">

        <!-- Header -->
        <div class="border-b border-slate-100 pb-5">
            <a href="{{ route('public.home') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 flex items-center gap-1 mb-3">
                &larr; Kembali ke Beranda SPI
            </a>
            <h1 class="text-xl md:text-2xl font-black text-slate-800">Survei Aspirasi & Persepsi Publik Kampus ITH</h1>
            <p class="text-xs md:text-sm text-slate-500 mt-1.5 leading-relaxed">
                Partisipasi Anda sangat berarti bagi pengawasan internal dalam mendorong perbaikan berkelanjutan, integritas, dan mutu layanan Institut Teknologi Bacharuddin Jusuf Habibie.
            </p>
        </div>

        @if (session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-800 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @forelse ($surveys as $survey)
            <form action="{{ route('surveys.store', $survey) }}" method="POST" class="space-y-6">
                @csrf

                <!-- Profil Pengunjung -->
                <div class="p-5 bg-indigo-50/50 rounded-xl border border-indigo-100 space-y-4">
                    <h3 class="text-xs font-bold text-indigo-950 uppercase tracking-wider">Identitas Responden (Opsional)</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Lengkap</label>
                            <input type="text" name="respondent_name" placeholder="Boleh dikosongkan (Anonim)"
                                   class="w-full px-3 py-2 text-xs bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Kategori Pengunjung <span class="text-rose-500">*</span></label>
                            <select name="visitor_category" required
                                    class="w-full px-3 py-2 text-xs bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600">
                                <option value="">-- Pilih Kategori Anda --</option>
                                <option value="Mahasiswa ITH">Mahasiswa ITH</option>
                                <option value="Dosen / Tenaga Kependidikan">Dosen / Tenaga Kependidikan</option>
                                <option value="Alumni ITH">Alumni ITH</option>
                                <option value="Orang Tua / Wali Mahasiswa">Orang Tua / Wali Mahasiswa</option>
                                <option value="Mitra Kerjasama / Industri">Mitra Kerjasama / Industri</option>
                                <option value="Masyarakat Umum">Masyarakat Umum</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Daftar Pertanyaan Survei -->
                <div class="space-y-5 pt-2">
                    @foreach ($survey->questions as $idx => $q)
                        <div class="p-5 bg-slate-50 rounded-xl border border-slate-200 space-y-3">
                            <p class="text-xs md:text-sm font-bold text-slate-800 leading-snug">
                                {{ $idx + 1 }}. {{ $q->question_text }}
                            </p>

                            @if ($q->question_type === 'rating_5')
                                <div class="flex flex-wrap items-center gap-4 text-xs text-slate-700 pt-1">
                                    <span class="text-[11px] text-slate-400">Sangat Tidak Puas</span>
                                    @for ($i = 1; $i <= 5; $i++)
                                        <label class="flex items-center gap-1.5 cursor-pointer px-3 py-1.5 bg-white border border-slate-200 rounded-lg hover:border-indigo-400 transition">
                                            <input type="radio" name="answers[{{ $q->id }}]" value="{{ $i }}" required class="text-indigo-600 focus:ring-indigo-500">
                                            <span class="font-bold">{{ $i }}</span>
                                        </label>
                                    @endfor
                                    <span class="text-[11px] text-slate-400">Sangat Puas</span>
                                </div>
                            @elseif ($q->question_type === 'text')
                                <textarea name="answers[{{ $q->id }}]" rows="3" placeholder="Tuliskan pandangan, saran, atau harapan Anda..."
                                          class="w-full px-3 py-2 text-xs bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600"></textarea>
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="pt-4 border-t border-slate-100 flex justify-end">
                    <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white rounded-lg text-xs font-bold shadow-md transition cursor-pointer">
                        Kirim Penilaian & Aspirasi
                    </button>
                </div>
            </form>
        @empty
            <div class="p-8 text-center text-slate-400 text-xs bg-slate-50 rounded-xl">
                Saat ini belum ada survei persepsi publik yang dipublikasikan.
            </div>
        @endforelse

    </div>
</body>
</html>

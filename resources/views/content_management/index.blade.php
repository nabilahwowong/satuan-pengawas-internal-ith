@extends('layouts.app')
@section('title', 'Kelola Konten Publik SPI')
@section('page_heading', 'Pengelolaan Profil, Visi-Misi & Program Kerja SPI')

@section('content')
<div class="space-y-10">

    <!-- BAGIAN 1: FORM VISI, MISI & TENTANG SPI -->
    <div class="bg-white p-6 md:p-8 rounded-2xl border border-slate-200 shadow-sm">
        <div class="border-b border-slate-100 pb-4 mb-6">
            <h3 class="text-lg font-bold text-slate-800">🏛️ Profil, Visi & Misi SPI ITH</h3>
            <p class="text-xs text-slate-500 mt-1">Perubahan pada form ini akan langsung ditampilkan di halaman beranda publik/landing page.</p>
        </div>

        <form action="{{ route('content.profile.update') }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Tentang Organisasi SPI ITH</label>
                <textarea name="about" rows="3" required class="w-full px-4 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-600 focus:outline-none">{{ old('about', $profile->about) }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Visi SPI</label>
                    <textarea name="vision" rows="4" required class="w-full px-4 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-600 focus:outline-none">{{ old('vision', $profile->vision) }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Misi SPI (Pisahkan dengan baris baru per poin)</label>
                    <textarea name="mission" rows="4" required class="w-full px-4 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-600 focus:outline-none">{{ old('mission', $profile->mission) }}</textarea>
                </div>
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold transition shadow-sm cursor-pointer">
                    Simpan Perubahan Profil & Visi Misi
                </button>
            </div>
        </form>
    </div>

    <!-- BAGIAN 2: 10 PROGRAM KERJA SPI -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Form Tambah Program Kerja -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm h-fit">
            <h3 class="text-md font-bold text-slate-800 mb-4">+ Tambah Program Kerja</h3>
            <form action="{{ route('content.programs.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Nomor Urutan</label>
                    <input type="number" name="order_num" placeholder="misal: 11"
                           class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-indigo-600">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Judul Program Kerja</label>
                    <input type="text" name="title" required placeholder="misal: Audit Operasional Lab Komputer"
                           class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-indigo-600">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Uraian / Deskripsi Sasaran</label>
                    <textarea name="description" rows="3" placeholder="Uraikan tujuan atau sasaran pengawasan..."
                              class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-indigo-600"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Status Keterlaksanaan</label>
                    <select name="status" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-indigo-600 bg-white">
                        <option value="terencana">Terencana</option>
                        <option value="berjalan">Sedang Berjalan</option>
                        <option value="terealisasi">Terealisasi / Selesai</option>
                    </select>
                </div>
                <button type="submit" class="w-full py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-lg text-xs font-bold transition cursor-pointer">
                    Simpan Program Kerja
                </button>
            </form>
        </div>

        <!-- Tabel Daftar Program Kerja & Form Edit Modal/In-Place -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-200">
                <h3 class="text-md font-bold text-slate-800">Daftar Program Kerja Pengawasan SPI ({{ $programs->count() }})</h3>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse ($programs as $prog)
                    <div class="p-5 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 hover:bg-slate-50 transition">
                        <div class="space-y-1 max-w-lg">
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-0.5 text-xs font-bold rounded bg-slate-200 text-slate-700">#{{ $prog->order_num }}</span>
                                <span class="px-2 py-0.5 text-[10px] font-bold rounded-full
                                    {{ $prog->status === 'terealisasi' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                    {{ $prog->status === 'berjalan' ? 'bg-blue-100 text-blue-700' : '' }}
                                    {{ $prog->status === 'terencana' ? 'bg-amber-100 text-amber-700' : '' }}">
                                    {{ strtoupper($prog->status) }}
                                </span>
                            </div>
                            <h4 class="text-sm font-bold text-slate-800">{{ $prog->title }}</h4>
                            <p class="text-xs text-slate-500 leading-relaxed">{{ $prog->description }}</p>
                        </div>

                        <!-- Aksi Cepat Update Status / Hapus -->
                        <div class="flex items-center gap-2 shrink-0">
                            <form action="{{ route('content.programs.update', $prog) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="order_num" value="{{ $prog->order_num }}">
                                <input type="hidden" name="title" value="{{ $prog->title }}">
                                <input type="hidden" name="description" value="{{ $prog->description }}">
                                <select name="status" onchange="this.form.submit()" class="text-xs px-2 py-1 border border-slate-300 rounded bg-white cursor-pointer">
                                    <option value="terencana" {{ $prog->status === 'terencana' ? 'selected' : '' }}>Terencana</option>
                                    <option value="berjalan" {{ $prog->status === 'berjalan' ? 'selected' : '' }}>Berjalan</option>
                                    <option value="terealisasi" {{ $prog->status === 'terealisasi' ? 'selected' : '' }}>Terealisasi</option>
                                </select>
                            </form>

                            <form action="{{ route('content.programs.destroy', $prog) }}" method="POST" onsubmit="return confirm('Hapus program kerja ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-2.5 py-1 text-xs text-rose-600 hover:bg-rose-50 rounded transition font-semibold cursor-pointer">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-400 text-xs">Belum ada program kerja yang ditambahkan.</div>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection

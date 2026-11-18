@extends('layouts.app')
@section('title', 'Manajemen CMS & Agenda')
@section('page_heading', 'Manajemen Publikasi Berita & Agenda Pengawasan')

@section('content')
<div class="space-y-10">

    <!-- BAGIAN 1: PUBLIKASI WARTA / PENGUMUMAN -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Form Buat Warta Baru -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm h-fit">
            <h3 class="text-md font-bold text-slate-800 mb-4">📰 Terbitkan Berita / Pengumuman</h3>
            <form action="{{ route('admin.cms.posts.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Judul Artikel / Warta</label>
                    <input type="text" name="title" required placeholder="misal: Sosialisasi Pengendalian Gratifikasi Kampus"
                           class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Isi Berita / Rincian</label>
                    <textarea name="content" rows="6" required placeholder="Tuliskan isi pengumuman atau berita secara lengkap..."
                              class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:outline-none"></textarea>
                </div>
                <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-semibold transition cursor-pointer">
                    Publikasikan ke Website
                </button>
            </form>
        </div>

        <!-- Tabel Riwayat Berita -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-200">
                <h3 class="text-md font-bold text-slate-800">Daftar Warta yang Terbit</h3>
            </div>
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-400 text-xs uppercase border-b border-slate-200">
                    <tr>
                        <th class="p-4">Tanggal</th>
                        <th class="p-4">Judul Artikel</th>
                        <th class="p-4">Ringkasan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($posts as $post)
                        <tr class="hover:bg-slate-50">
                            <td class="p-4 text-xs text-slate-400 shrink-0 whitespace-nowrap">
                                {{ $post->created_at->format('d/m/Y') }}
                            </td>
                            <td class="p-4 font-semibold text-slate-800">
                                {{ $post->title }}
                            </td>
                            <td class="p-4 text-xs text-slate-500">
                                {{ Str::limit(strip_tags($post->content), 80) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="p-8 text-center text-slate-400 text-xs">Belum ada artikel warta yang dipublikasikan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4 border-t border-slate-100">
                {{ $posts->links() }}
            </div>
        </div>
    </div>

    <!-- BAGIAN 2: JADWAL & AGENDA KEGIATAN -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 pt-4 border-t border-slate-200">
        <!-- Form Tambah Agenda Baru -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm h-fit">
            <h3 class="text-md font-bold text-slate-800 mb-4">📅 Tambah Agenda Kegiatan SPI</h3>
            <form action="{{ route('admin.cms.schedules.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Agenda</label>
                    <input type="text" name="title" required placeholder="misal: Entry Meeting PKPT Triwulan I"
                           class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Waktu Mulai</label>
                        <input type="datetime-local" name="start_time" required
                               class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Waktu Selesai</label>
                        <input type="datetime-local" name="end_time" required
                               class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Lokasi / Ruangan</label>
                    <input type="text" name="location" placeholder="misal: Ruang Rapat Rektorat Lt. 2 / Zoom Meeting"
                           class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Keterangan Tambahan</label>
                    <textarea name="description" rows="2" placeholder="Catatan peserta atau perlengkapan yang perlu disiapkan..."
                              class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:outline-none"></textarea>
                </div>
                <button type="submit" class="w-full py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-lg text-sm font-semibold transition cursor-pointer">
                    Simpan Agenda
                </button>
            </form>
        </div>

        <!-- Tabel Daftar Agenda -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-200">
                <h3 class="text-md font-bold text-slate-800">Daftar Agenda Kegiatan Pengawasan</h3>
            </div>
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-400 text-xs uppercase border-b border-slate-200">
                    <tr>
                        <th class="p-4">Jadwal Pelaksanaan</th>
                        <th class="p-4">Agenda</th>
                        <th class="p-4">Lokasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($schedules as $sched)
                        <tr class="hover:bg-slate-50">
                            <td class="p-4 text-xs font-medium text-indigo-700 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($sched->start_time)->format('d M Y, H:i') }} WITA
                            </td>
                            <td class="p-4">
                                <p class="font-semibold text-slate-800">{{ $sched->title }}</p>
                                @if ($sched->description)
                                    <p class="text-xs text-slate-400 mt-0.5">{{ $sched->description }}</p>
                                @endif
                            </td>
                            <td class="p-4 text-xs text-slate-500">
                                {{ $sched->location ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="p-8 text-center text-slate-400 text-xs">Belum ada agenda kegiatan yang dicatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4 border-t border-slate-100">
                {{ $schedules->links() }}
            </div>
        </div>
    </div>

</div>
@endsection

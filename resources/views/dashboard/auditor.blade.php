@extends('layouts.app')

@section('page_heading')
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 w-full">
        <div>
            <h1 class="text-lg font-black text-slate-900">Selamat datang, {{ Auth::user()->name }} 👋</h1>
            <p class="text-xs text-slate-500 font-normal mt-0.5">Pusat Kerja Auditor Internal — Pantau progres pekerjaan dan penugasan Anda.</p>
        </div>
    </div>
@endsection

@section('content')
<div class="space-y-8">
    <!-- Kartu Statistik Minimalis & Interaktif -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Kartu 1: Total Audit Saya -->
        <a href="{{ route('assignments.index') }}" class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-2xs hover:border-blue-500 hover:shadow-md transition-all duration-200 block group">
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Total Audit Saya</p>
                <h3 class="text-3xl font-black text-slate-800 mt-2 group-hover:text-blue-600 transition">{{ $stats['my_audits_count'] ?? 0 }}</h3>
            </div>
            <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between text-[11px] font-bold text-blue-600">
                <span>Lihat daftar tugas</span>
                <span class="group-hover:translate-x-1 transition">&rarr;</span>
            </div>
        </a>

        <!-- Kartu 2: Audit Aktif -->
        <a href="{{ route('assignments.index') }}" class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-2xs hover:border-blue-500 hover:shadow-md transition-all duration-200 block group">
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Audit Aktif</p>
                <h3 class="text-3xl font-black text-slate-800 mt-2 group-hover:text-blue-600 transition">{{ $stats['my_active_audits'] ?? 0 }}</h3>
            </div>
            <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between text-[11px] font-bold text-blue-600">
                <span>Pantau berjalan</span>
                <span class="group-hover:translate-x-1 transition">&rarr;</span>
            </div>
        </a>

        <!-- Kartu 3: Total Temuan -->
        <a href="{{ route('assignments.index') }}" class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-2xs hover:border-amber-500 hover:shadow-md transition-all duration-200 block group">
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Total Temuan</p>
                <h3 class="text-3xl font-black text-slate-800 mt-2 group-hover:text-amber-600 transition">{{ $stats['findings_count'] ?? 0 }}</h3>
            </div>
            <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between text-[11px] font-bold text-amber-600">
                <span>Periksa kertas kerja</span>
                <span class="group-hover:translate-x-1 transition">&rarr;</span>
            </div>
        </a>

        <!-- Kartu 4: Perlu Verifikasi TL -->
        <a href="{{ route('assignments.index') }}" class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-2xs hover:border-rose-500 hover:shadow-md transition-all duration-200 block group">
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Perlu Verifikasi TL</p>
                <h3 class="text-3xl font-black text-slate-800 mt-2 group-hover:text-rose-600 transition">{{ $stats['pending_verifications'] ?? 0 }}</h3>
            </div>
            <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between text-[11px] font-bold text-rose-600">
                <span>Validasi bukti</span>
                <span class="group-hover:translate-x-1 transition">&rarr;</span>
            </div>
        </a>
    </div>

    <!-- Daftar Penugasan Pemeriksaan Anda -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-2xs overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-slate-800">Daftar Penugasan Pemeriksaan Anda</h3>
                <p class="text-xs text-slate-400 mt-0.5">Akses ruang kerja audit untuk melakukan pengujian, mencatat temuan, dan memverifikasi dokumen.</p>
            </div>
            <a href="{{ route('assignments.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 transition">Lihat Semua &rarr;</a>
        </div>

        <div class="divide-y divide-slate-100 text-xs">
            @forelse ($stats['active_assignments'] as $item)
                <div class="p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 hover:bg-slate-50/60 transition">
                    <div class="space-y-1.5">
                        <div class="flex items-center gap-2.5">
                            <span class="font-mono font-bold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-lg">{{ $item->assignment_number }}</span>
                            <span class="px-2.5 py-1 text-[10px] font-bold rounded-full uppercase bg-blue-100 text-blue-700">
                                {{ str_replace('_', ' ', $item->status) }}
                            </span>
                        </div>
                        <h4 class="font-bold text-slate-800 text-sm">{{ $item->title }}</h4>
                        <p class="text-slate-400 text-xs">
                            Unit: <strong class="text-slate-600">{{ $item->workUnit->name ?? '-' }}</strong> • Ketua Tim: <strong class="text-slate-600">{{ $item->leader->name ?? '-' }}</strong>
                        </p>
                    </div>
                    <a href="{{ route('assignments.show', $item) }}" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl transition shadow-2xs shrink-0">
                        Buka Ruang Kerja &rarr;
                    </a>
                </div>
            @empty
                <div class="p-12 text-center text-slate-400">
                    <p class="text-sm font-semibold text-slate-600">Belum Ada Penugasan Aktif</p>
                    <p class="text-xs text-slate-400 mt-0.5">Anda saat ini belum terdaftar dalam penugasan pemeriksaan aktif.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

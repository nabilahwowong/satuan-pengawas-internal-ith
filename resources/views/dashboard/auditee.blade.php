@extends('layouts.app')

@section('page_heading')
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 w-full">
        <div>
            <h1 class="text-lg font-black text-slate-900">Selamat datang, {{ Auth::user()->name }} 👋</h1>
            <p class="text-xs text-slate-500 font-normal mt-0.5">Ruang Kerja Unit Kerja — Lengkapi dokumen dan pantau tindak lanjut rekomendasi audit.</p>
        </div>
    </div>
@endsection

@section('content')
<div class="space-y-8">
    <!-- Kartu Statistik Auditee -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <a href="{{ route('auditee.documents') }}" class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-2xs hover:border-blue-500 transition block">
            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Pemeriksaan Aktif</p>
            <h3 class="text-3xl font-black text-slate-800 mt-2">{{ $stats['unit_audits_count'] ?? 0 }}</h3>
        </a>
        <a href="{{ route('auditee.documents') }}" class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-2xs hover:border-amber-500 transition block">
            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Permintaan Dokumen</p>
            <h3 class="text-3xl font-black text-slate-800 mt-2">{{ $stats['pending_documents'] ?? 0 }}</h3>
        </a>
        <a href="{{ route('auditee.followups') }}" class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-2xs hover:border-emerald-500 transition block">
            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Rekomendasi Perlu Aksi</p>
            <h3 class="text-3xl font-black text-slate-800 mt-2">{{ $stats['open_recommendations'] ?? 0 }}</h3>
        </a>
    </div>
</div>
@endsection

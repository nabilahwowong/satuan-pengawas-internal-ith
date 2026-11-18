@extends('layouts.app')

@section('page_heading')
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 w-full">
        <div>
            <h1 class="text-lg font-black text-slate-900">Selamat datang, {{ Auth::user()->name }} 👋</h1>
            <p class="text-xs text-slate-500 font-normal mt-0.5">Pusat Kendali Administrator — Kelola master data, pengguna, dan sistem SPI.</p>
        </div>
    </div>
@endsection

@section('content')
<div class="space-y-8">
    <!-- Kartu Statistik Admin -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-2xs">
            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Total Unit Kerja</p>
            <h3 class="text-3xl font-black text-slate-800 mt-2">{{ $stats['units_count'] ?? 0 }}</h3>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-2xs">
            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Total Pengguna</p>
            <h3 class="text-3xl font-black text-slate-800 mt-2">{{ $stats['users_count'] ?? 0 }}</h3>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-2xs">
            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Agenda Aktif</p>
            <h3 class="text-3xl font-black text-slate-800 mt-2">{{ $stats['active_assignments'] ?? 0 }}</h3>
        </div>
    </div>
</div>
@endsection

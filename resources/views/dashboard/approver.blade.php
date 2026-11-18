@extends('layouts.app')
@section('title', 'Dashboard Pimpinan SPI')
@section('page_heading', 'Pusat Eksekutif Pengawasan SPI ITH')

@section('content')
<div class="space-y-8">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <p class="text-xs font-semibold text-slate-400 uppercase">Menunggu Pengesahan</p>
            <h3 class="text-2xl font-black text-rose-600 mt-1">{{ $stats['pending_approvals'] }}</h3>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <p class="text-xs font-semibold text-slate-400 uppercase">LHP Telah Disahkan</p>
            <h3 class="text-2xl font-black text-emerald-600 mt-1">{{ $stats['approved_reports'] }}</h3>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <p class="text-xs font-semibold text-slate-400 uppercase">Dalam Pemantauan</p>
            <h3 class="text-2xl font-black text-blue-600 mt-1">{{ $stats['audits_in_monitoring'] }}</h3>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <p class="text-xs font-semibold text-slate-400 uppercase">Audit Selesai Penuh</p>
            <h3 class="text-2xl font-black text-slate-800 mt-1">{{ $stats['total_completed_audits'] }}</h3>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-800">Laporan Hasil Pengawasan (LHP) Memerlukan Review</h3>
        </div>
        <div class="divide-y divide-slate-100 text-xs">
            @forelse ($stats['reports_to_review'] as $report)
                <div class="p-5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 hover:bg-slate-50 transition">
                    <div>
                        <span class="font-bold text-rose-600">LHP #{{ $report->report_number }}</span>
                        <h4 class="font-bold text-slate-800 text-sm mt-0.5">{{ $report->assignment->title }}</h4>
                        <p class="text-slate-400 mt-0.5">Unit: {{ $report->assignment->workUnit->name }} | Diajukan oleh: {{ $report->creator->name }}</p>
                    </div>
                    <a href="{{ route('assignments.report', $report->assignment) }}" class="px-3.5 py-1.5 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-lg transition shrink-0">
                        Telaah & Putuskan &rarr;
                    </a>
                </div>
            @empty
                <div class="p-8 text-center text-slate-400">Tidak ada pengajuan laporan audit yang tertunda saat ini.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection

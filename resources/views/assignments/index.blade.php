@extends('layouts.app')
@section('title', Auth::user()->role === 'auditee' ? 'Pemeriksaan Unit Kerja' : 'Penugasan Audit')
@section('page_heading', Auth::user()->role === 'auditee' ? 'Daftar Pemeriksaan Unit Kerja' : 'Daftar Penugasan Pengawasan Internal')

@section('content')
<div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
    <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-base font-bold text-slate-800">
                {{ Auth::user()->role === 'auditee' ? 'Agenda Pemeriksaan & Tindak Lanjut' : 'Agenda Audit & PKPT' }}
            </h2>
            <p class="text-xs text-slate-400 mt-0.5">
                {{ Auth::user()->role === 'auditee'
                    ? 'Pilih pemeriksaan di bawah untuk mengunggah dokumen yang diminta atau menanggapi temuan.'
                    : 'Daftar seluruh pemeriksaan pengawasan internal yang terdaftar.' }}
            </p>
        </div>

        @if(in_array(Auth::user()->role, ['admin', 'auditor']))
            <a href="{{ route('assignments.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl transition">
                + Buat Penugasan Baru
            </a>
        @endif
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider bg-slate-50/50">
                    <th class="py-3.5 px-6">No. Surat Tugas</th>
                    <th class="py-3.5 px-6">Judul Pemeriksaan</th>
                    @if(Auth::user()->role !== 'auditee')
                        <th class="py-3.5 px-6">Unit Kerja (Auditee)</th>
                    @endif
                    <th class="py-3.5 px-6">Tim Auditor</th>
                    <th class="py-3.5 px-6">Status</th>
                    <th class="py-3.5 px-6 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-xs">
                @forelse($assignments as $assignment)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="py-4 px-6 font-mono font-semibold text-slate-700">
                            {{ $assignment->assignment_number }}
                        </td>
                        <td class="py-4 px-6">
                            <p class="font-bold text-slate-800">{{ $assignment->title }}</p>
                            <p class="text-[11px] text-slate-400 mt-0.5">
                                {{ \Carbon\Carbon::parse($assignment->start_date)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($assignment->end_date)->format('d M Y') }}
                            </p>
                        </td>
                        @if(Auth::user()->role !== 'auditee')
                            <td class="py-4 px-6 text-slate-600 font-medium">
                                {{ $assignment->workUnit->name ?? '-' }}
                            </td>
                        @endif
                        <td class="py-4 px-6">
                            <span class="inline-block px-2.5 py-1 bg-indigo-50 text-indigo-700 font-semibold rounded-lg text-[11px]">
                                {{ $assignment->leader->name ?? 'Tim SPI' }} (Ketua)
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            @php
                                $statusLabels = [
                                    'draft' => ['label' => 'Draft', 'class' => 'bg-amber-100 text-amber-700'],
                                    'ongoing' => ['label' => 'Sedang Berjalan', 'class' => 'bg-blue-100 text-blue-700'],
                                    'in_monitoring' => ['label' => 'Masa Tindak Lanjut', 'class' => 'bg-purple-100 text-purple-700'],
                                    'completed' => ['label' => 'Selesai', 'class' => 'bg-emerald-100 text-emerald-700'],
                                ];
                                $currentStatus = $statusLabels[$assignment->status] ?? ['label' => $assignment->status, 'class' => 'bg-slate-100 text-slate-700'];
                            @endphp
                            <span class="px-2.5 py-1 text-[10px] font-bold rounded-full uppercase {{ $currentStatus['class'] }}">
                                {{ $currentStatus['label'] }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-right">
                            <a href="{{ route('assignments.show', $assignment) }}" class="inline-flex items-center gap-1 font-bold text-indigo-600 hover:text-indigo-800">
                                {{ Auth::user()->role === 'auditee' ? 'Buka Ruang Kerja' : 'Detail Audit' }} &rarr;
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-10 text-center text-slate-400">
                            Belum ada data pemeriksaan yang tersedia untuk Anda.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($assignments->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $assignments->links() }}
        </div>
    @endif
</div>
@endsection

@extends('layouts.app')
@section('title', 'Permintaan Dokumen')
@section('page_heading', 'Daftar Permintaan Dokumen Pemeriksaan')

@section('content')
<div class="space-y-6">
    @forelse($assignments as $assignment)
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                    <span class="font-mono text-xs font-bold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-lg">{{ $assignment->assignment_number }}</span>
                    <h3 class="text-sm font-bold text-slate-800 mt-1">{{ $assignment->title }}</h3>
                </div>
                <span class="text-xs text-slate-400">Periode: {{ $assignment->start_date }} s.d {{ $assignment->end_date }}</span>
            </div>

            <div class="space-y-4">
                @forelse($assignment->documentRequests as $docReq)
                    <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        <div>
                            <h4 class="text-xs font-bold text-slate-800">{{ $docReq->document_name }}</h4>
                            <p class="text-[11px] text-slate-500 mt-0.5">{{ $docReq->description ?? '-' }}</p>
                            <span class="inline-block mt-2 px-2 py-0.5 text-[10px] font-bold rounded {{ $docReq->status === 'fulfilled' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                Status: {{ strtoupper($docReq->status) }}
                            </span>
                        </div>

                        <!-- Form Unggah -->
                        <form action="{{ route('documents.submit', $docReq->id) }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2 w-full md:w-auto">
                            @csrf
                            <input type="file" name="file" required class="text-xs file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 cursor-pointer">
                            <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg transition shrink-0 cursor-pointer">
                                Unggah Berkas
                            </button>
                        </form>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 text-center py-4">Belum ada permintaan dokumen untuk penugasan ini.</p>
                @endforelse
            </div>
        </div>
    @empty
        <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center text-slate-400 text-xs">
            Tidak ada penugasan aktif untuk unit kerja Anda.
        </div>
    @endforelse
</div>
@endsection

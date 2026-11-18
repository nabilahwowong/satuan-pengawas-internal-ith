@extends('layouts.app')
@section('title', 'Tindak Lanjut Perbaikan')
@section('page_heading', 'Pemantauan Rekomendasi & Unggah Bukti Perbaikan')

@section('content')
<div class="space-y-6">
    @forelse($assignments as $assignment)
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6 space-y-4">
            <h3 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-3">{{ $assignment->title }}</h3>

            <div class="space-y-4">
                @foreach($assignment->findings as $finding)
                    @foreach($finding->recommendations as $rec)
                        <div class="p-4 rounded-xl border border-slate-200 bg-white space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold uppercase px-2 py-0.5 bg-indigo-100 text-indigo-700 rounded">Rekomendasi</span>
                                <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded {{ $rec->status === 'closed' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                    Status: {{ str_replace('_', ' ', $rec->status) }}
                                </span>
                            </div>
                            <p class="text-xs font-semibold text-slate-800">{{ $rec->recommendation_text ?? $rec->content }}</p>

                            <!-- Form Unggah Bukti -->
                            <form action="{{ route('followups.store', $rec->id) }}" method="POST" enctype="multipart/form-data" class="pt-3 border-t border-slate-100 flex flex-col sm:flex-row items-center gap-3">
                                @csrf
                                <input type="file" name="evidence_file" required class="text-xs w-full sm:w-auto file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 cursor-pointer">
                                <input type="text" name="notes" placeholder="Catatan bukti perbaikan..." class="text-xs p-2 rounded-lg border border-slate-200 w-full focus:outline-indigo-600">
                                <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg transition shrink-0 cursor-pointer w-full sm:w-auto">
                                    Kirim Bukti
                                </button>
                            </form>
                        </div>
                    @endforeach
                @endforeach
            </div>
        </div>
    @empty
        <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center text-slate-400 text-xs">
            Tidak ada penugasan aktif untuk unit kerja Anda.
        </div>
    @endforelse
</div>
@endsection

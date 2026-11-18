@extends('layouts.app')
@section('title', 'Temuan & Tanggapan')
@section('page_heading', 'Temuan Pemeriksaan & Klarifikasi Unit Kerja')

@section('content')
<div class="space-y-6">
    @forelse($assignments as $assignment)
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6 space-y-4">
            <h3 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-3">{{ $assignment->title }} ({{ $assignment->assignment_number }})</h3>

            <div class="space-y-4">
                @forelse($assignment->findings as $finding)
                    <div class="p-4 rounded-xl border border-slate-100 bg-slate-50 space-y-3">
                        <div>
                            <span class="text-[10px] font-bold uppercase px-2 py-0.5 bg-rose-100 text-rose-700 rounded">Temuan Audit</span>
                            <h4 class="text-xs font-bold text-slate-800 mt-1">{{ $finding->description }}</h4>
                        </div>

                        <!-- Form Tanggapan -->
                        <form action="{{ route('findings.response', $finding->id) }}" method="POST" class="space-y-2 pt-2 border-t border-slate-200">
                            @csrf
                            <label class="text-[11px] font-bold text-slate-700">Tanggapan / Klarifikasi Unit Kerja:</label>
                            <textarea name="response" rows="2" placeholder="Tulis tanggapan atau klarifikasi unit kerja..." class="w-full text-xs p-3 rounded-xl border border-slate-200 bg-white focus:outline-indigo-600" required>{{ $finding->auditee_response ?? '' }}</textarea>
                            <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-lg transition cursor-pointer">
                                Kirim Tanggapan
                            </button>
                        </form>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 text-center py-4">Belum ada temuan yang tercatat untuk penugasan ini.</p>
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

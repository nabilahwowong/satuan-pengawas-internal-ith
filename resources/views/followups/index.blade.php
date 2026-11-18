@extends('layouts.app')
@section('title', 'Pemantauan Tindak Lanjut')
@section('page_heading', 'Matriks Pemantauan Tindak Lanjut Rekomendasi')

@section('content')
<div class="space-y-6">
    <!-- Header Kembali & Status Audit -->
    <div class="flex items-center justify-between">
        <a href="{{ route('assignments.show', $assignment) }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 flex items-center gap-1">
            &larr; Kembali ke Detail Penugasan
        </a>
        <div class="flex items-center gap-2">
            <span class="text-xs text-slate-400">Status Pemeriksaan:</span>
            <span class="px-2.5 py-0.5 text-xs font-bold rounded-full bg-slate-100 text-slate-700 uppercase">
                {{ str_replace('_', ' ', $assignment->status) }}
            </span>
        </div>
    </div>

    <!-- Daftar Matriks Temuan & Rekomendasi -->
    <div class="space-y-6">
        @forelse ($assignment->findings as $finding)
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <!-- Header Temuan -->
                <div class="p-6 bg-slate-50 border-b border-slate-200">
                    <span class="text-xs font-bold text-indigo-600 uppercase tracking-wider">Temuan #{{ $finding->finding_number }}</span>
                    <h3 class="text-md font-bold text-slate-800 mt-0.5">{{ $finding->title }}</h3>
                </div>

                <!-- Butir Rekomendasi -->
                <div class="p-6 divide-y divide-slate-100">
                    @foreach ($finding->recommendations as $rec)
                        <div class="py-6 first:pt-0 last:pb-0 space-y-4">
                            <div class="flex flex-col md:flex-row justify-between items-start gap-4">
                                <div class="max-w-2xl">
                                    <span class="text-xs font-bold text-slate-400 uppercase">Rekomendasi SPI:</span>
                                    <p class="text-sm font-semibold text-slate-800 mt-1 leading-relaxed">{{ $rec->recommendation }}</p>
                                </div>
                                <div class="shrink-0">
                                    <span class="px-3 py-1 text-xs font-bold rounded-full
                                        {{ $rec->status === 'open' ? 'bg-rose-100 text-rose-700' : '' }}
                                        {{ $rec->status === 'under_review' ? 'bg-amber-100 text-amber-700' : '' }}
                                        {{ $rec->status === 'in_progress' ? 'bg-blue-100 text-blue-700' : '' }}
                                        {{ $rec->status === 'closed' ? 'bg-emerald-100 text-emerald-700' : '' }}">
                                        STATUS: {{ strtoupper($rec->status) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Riwayat Penyerahan Bukti Tindak Lanjut -->
                            <div class="space-y-3 pt-2">
                                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Bukti & Tindakan yang Telah Dilaporkan:</p>
                                @forelse ($rec->followUpSubmissions as $sub)
                                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                                        <div class="space-y-1">
                                            <p class="text-xs font-semibold text-slate-800">"{{ $sub->action_taken }}"</p>
                                            <div class="flex items-center gap-3 text-[11px] text-slate-400">
                                                <span>Oleh: {{ $sub->submittedBy->name ?? '-' }} ({{ $sub->created_at->format('d/m/Y H:i') }})</span>
                                                <a href="{{ asset('storage/' . $sub->evidence_path) }}" target="_blank" class="text-indigo-600 hover:underline font-bold">
                                                    📄 Lihat Bukti Fisik
                                                </a>
                                            </div>
                                            @if ($sub->verification_notes)
                                                <p class="text-xs text-slate-600 mt-1 italic bg-white p-2 rounded border border-slate-200">
                                                    Catatan Auditor: {{ $sub->verification_notes }}
                                                </p>
                                            @endif
                                        </div>

                                        <!-- Status & Aksi Verifikasi Auditor -->
                                        <div class="flex items-center gap-2 shrink-0">
                                            <span class="px-2 py-0.5 text-xs font-bold rounded
                                                {{ $sub->status === 'verified' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                                {{ $sub->status === 'revision_required' ? 'bg-rose-100 text-rose-700' : '' }}
                                                {{ $sub->status === 'submitted' ? 'bg-amber-100 text-amber-700' : '' }}">
                                                {{ strtoupper(str_replace('_', ' ', $sub->status)) }}
                                            </span>

                                            @if (in_array(Auth::user()->role, ['admin', 'auditor']) && $sub->status === 'submitted')
                                                <form action="{{ route('followups.verify', $sub) }}" method="POST" class="flex items-center gap-1">
                                                    @csrf
                                                    <input type="text" name="verification_notes" placeholder="Catatan auditor (opsional)" class="px-2 py-1 text-xs border rounded w-36">
                                                    <button type="submit" name="status" value="verified" class="px-2.5 py-1 bg-emerald-600 text-white rounded text-xs font-semibold hover:bg-emerald-700">Tuntas</button>
                                                    <button type="submit" name="status" value="revision_required" class="px-2.5 py-1 bg-rose-600 text-white rounded text-xs font-semibold hover:bg-rose-700">Revisi</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-xs text-slate-400 italic">Belum ada bukti penyelesaian yang diserahkan untuk butir rekomendasi ini.</p>
                                @endforelse
                            </div>

                            <!-- Form Pelaporan Khusus Auditee (jika belum closed) -->
                            @if (in_array(Auth::user()->role, ['admin', 'auditee']) && $rec->status !== 'closed')
                                <div class="mt-4 pt-4 border-t border-slate-100">
                                    <form action="{{ route('followups.store', $rec) }}" method="POST" enctype="multipart/form-data" class="p-4 bg-indigo-50/50 rounded-xl border border-indigo-100 space-y-3">
                                        @csrf
                                        <p class="text-xs font-bold text-indigo-900">Serahkan Bukti Perbaikan / Tindak Lanjut:</p>
                                        <textarea name="action_taken" rows="2" required placeholder="Jelaskan tindakan korektif yang telah dilaksanakan oleh unit..."
                                                  class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 focus:outline-none bg-white"></textarea>
                                        <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                                            <input type="file" name="evidence_file" required
                                                   class="text-xs text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-white file:text-indigo-700 hover:file:bg-slate-100 cursor-pointer">
                                            <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-semibold transition cursor-pointer shrink-0">
                                                Kirim Laporan Tindak Lanjut
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="bg-white p-8 rounded-2xl border border-slate-200 text-center text-slate-400 text-sm">
                Belum ada temuan dan rekomendasi yang siap dipantau tindak lanjutnya.
            </div>
        @endforelse
    </div>
</div>
@endsection

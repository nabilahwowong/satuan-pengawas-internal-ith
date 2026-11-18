@extends('layouts.app')
@section('title', 'Laporan Hasil Pengawasan')
@section('page_heading', 'Penyusunan & Pengesahan LHP')

@section('content')
<div class="space-y-6">
    <!-- Header Kembali & Info Penugasan -->
    <div class="flex items-center justify-between">
        <a href="{{ route('assignments.show', $assignment) }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 flex items-center gap-1">
            &larr; Kembali ke Detail Penugasan
        </a>
        <span class="text-xs text-slate-400">Nomor Audit: {{ $assignment->assignment_number }}</span>
    </div>

    <!-- Status Bar LHP -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Status Laporan Saat Ini</span>
            <div class="flex items-center gap-2 mt-1">
                <span class="px-3 py-1 text-xs font-bold rounded-full
                    {{ !$report || $report->status === 'draft' ? 'bg-slate-100 text-slate-700' : '' }}
                    {{ $report && $report->status === 'submitted_for_approval' ? 'bg-amber-100 text-amber-700' : '' }}
                    {{ $report && $report->status === 'approved' ? 'bg-emerald-100 text-emerald-700' : '' }}
                    {{ $report && $report->status === 'rejected' ? 'bg-rose-100 text-rose-700' : '' }}">
                    {{ $report ? strtoupper(str_replace('_', ' ', $report->status)) : 'BELUM DRAFT' }}
                </span>
                @if ($report && $report->report_number)
                    <span class="text-sm font-semibold text-slate-700">No: {{ $report->report_number }}</span>
                @endif
            </div>
        </div>

        <div class="flex items-center gap-3">
            <!-- Tombol Cetak / Unduh Naskah LHP Resmi (PDF) -->
            @if ($report)
                <a href="{{ route('reports.pdf', $assignment) }}"
                   target="_blank"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-xs font-bold shadow-xs transition cursor-pointer">
                    <span>📄</span> Unduh Naskah LHP (PDF)
                </a>
            @endif

            <!-- Tombol Aksi Pengajuan (Auditor/Admin) -->
            @if ($report && in_array($report->status, ['draft', 'rejected']) && in_array(Auth::user()->role, ['admin', 'auditor']))
                <form action="{{ route('reports.submit', $report) }}" method="POST" onsubmit="return confirm('Kirimkan LHP ini ke Kepala SPI?')">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-semibold transition cursor-pointer">
                        Ajukan ke Kepala SPI &rarr;
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Panel Kiri: Form / Info Berkas LHP -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Form Draf (Auditor / Admin saat draf/ditolak) -->
            @if (in_array(Auth::user()->role, ['admin', 'auditor']) && (!$report || in_array($report->status, ['draft', 'rejected'])))
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                    <h3 class="text-md font-bold text-slate-800 mb-4">{{ $report ? 'Perbarui Draf LHP' : 'Buat Draf LHP Baru' }}</h3>
                    <form action="{{ route('reports.store', $assignment) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Nomor LHP</label>
                                <input type="text" name="report_number" value="{{ old('report_number', $report->report_number ?? '') }}" required placeholder="misal: 01/LHP-SPI/ITH/2026"
                                       class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Tanggal Terbit Laporan</label>
                                <input type="date" name="report_date" value="{{ old('report_date', $report->report_date ?? date('Y-m-d')) }}" required
                                       class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Unggah Dokumen LHP (Format PDF)</label>
                            <input type="file" name="report_file" accept=".pdf" {{ $report && $report->file_path ? '' : 'required' }}
                                   class="text-xs text-slate-600 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                            @if ($report && $report->file_path)
                                <p class="text-[11px] text-slate-400 mt-1">*Sudah ada berkas terunggah. Pilih file baru jika ingin menggantinya.</p>
                            @endif
                        </div>

                        <button type="submit" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-lg text-sm font-semibold transition cursor-pointer">
                            Simpan Berkas Draf LHP
                        </button>
                    </form>
                </div>
            @endif

            <!-- Berkas Final / Aktif -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-3">
                <h4 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Dokumen LHP Terunggah</h4>
                @if ($report && $report->file_path)
                    <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="text-2xl">📑</span>
                            <div>
                                <p class="text-sm font-bold text-slate-800">{{ $report->report_number }}</p>
                                <p class="text-xs text-slate-400">Tanggal: {{ \Carbon\Carbon::parse($report->report_date)->format('d F Y') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('reports.pdf', $assignment) }}" target="_blank" class="px-3 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg text-xs font-semibold transition">
                                Cetak Naskah Resmi &rarr;
                            </a>
                            <a href="{{ asset('storage/' . $report->file_path) }}" target="_blank" class="px-4 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-lg text-xs font-semibold transition">
                                File Asli &rarr;
                            </a>
                        </div>
                    </div>
                @else
                    <p class="text-xs text-slate-400 italic">Belum ada dokumen PDF LHP yang diunggah.</p>
                @endif
            </div>

            <!-- Ringkasan Temuan yang Dilaporkan -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                <h4 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Ringkasan Temuan & Rekomendasi Terkait</h4>
                <div class="space-y-3 text-xs">
                    @forelse ($assignment->findings as $finding)
                        <div class="p-3 bg-slate-50 rounded-xl">
                            <p class="font-bold text-slate-800">Temuan #{{ $finding->finding_number }}: {{ $finding->title }}</p>
                            <p class="text-slate-500 mt-1">Rekomendasi: {{ $finding->recommendations->pluck('recommendation')->implode(' | ') }}</p>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 italic">Belum ada temuan yang dimasukkan pada penugasan ini.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Panel Kanan: Keputusan Pengesahan & Riwayat -->
        <div class="space-y-6">
            <!-- Form Pengesahan Kepala SPI -->
            @if (in_array(Auth::user()->role, ['approver', 'admin']) && $report && $report->status === 'submitted_for_approval')
                <div class="bg-white p-6 rounded-2xl border-2 border-indigo-200 shadow-md">
                    <h3 class="text-md font-bold text-slate-800 mb-2">Telaah Kepala SPI</h3>
                    <p class="text-xs text-slate-500 mb-4">Silakan periksa berkas LHP di sebelah kiri sebelum memberikan pengesahan.</p>

                    <form action="{{ route('reports.decide', $report) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Catatan Telaah / Alasan</label>
                            <textarea name="notes" rows="3" placeholder="Tuliskan catatan arahan atau catatan revisi..."
                                      class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 focus:outline-none"></textarea>
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" name="decision" value="approved" class="flex-1 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition cursor-pointer">
                                Setujui (Approve)
                            </button>
                            <button type="submit" name="decision" value="rejected" class="flex-1 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-xs font-bold transition cursor-pointer">
                                Tolak (Revisi)
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <!-- Riwayat Catatan Persetujuan -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                <h4 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Riwayat Pengesahan</h4>
                @if ($report && $report->approvals->isNotEmpty())
                    <div class="space-y-3">
                        @foreach ($report->approvals as $approval)
                            <div class="p-3 rounded-xl text-xs {{ $approval->decision === 'approved' ? 'bg-emerald-50 border border-emerald-200 text-emerald-900' : 'bg-rose-50 border border-rose-200 text-rose-900' }}">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="font-bold">{{ strtoupper($approval->decision) }}</span>
                                    <span class="text-[10px] text-slate-400">{{ \Carbon\Carbon::parse($approval->decided_at)->format('d/m/Y H:i') }}</span>
                                </div>
                                <p class="text-slate-700">Oleh: {{ $approval->approver->name ?? '-' }}</p>
                                @if ($approval->notes)
                                    <p class="mt-1 italic">"{{ $approval->notes }}"</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-slate-400 italic">Belum ada riwayat telaah pengesahan.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

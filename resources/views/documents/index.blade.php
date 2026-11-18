@extends('layouts.app')
@section('title', 'Dokumen Pemeriksaan')
@section('page_heading', 'Permintaan & Pengunggahan Berkas Audit')

@section('content')
<div class="space-y-6">
    <!-- Header Kembali & Info Audit -->
    <div class="flex items-center justify-between">
        <a href="{{ route('assignments.show', $assignment) }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 flex items-center gap-1">
            &larr; Kembali ke Detail Penugasan
        </a>
        <span class="text-xs text-slate-400">Nomor: {{ $assignment->assignment_number }}</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Form Buat Permintaan Dokumen (Khusus Auditor & Admin) -->
        @if (in_array(Auth::user()->role, ['admin', 'auditor']))
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm h-fit">
                <h3 class="text-md font-bold text-slate-800 mb-4">+ Minta Dokumen Baru</h3>
                <form action="{{ route('documents.store', $assignment) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Dokumen yang Diminta</label>
                        <input type="text" name="request_title" placeholder="misal: LPJ Keuangan Triwulan I" required
                               class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Keterangan / Rincian Syarat</label>
                        <textarea name="description" rows="3" placeholder="Sertakan rekap bukti transfer dan kuitansi asli..."
                                  class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:outline-none"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Tenggat Waktu (Deadline)</label>
                        <input type="date" name="deadline" required
                               class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                    </div>
                    <!-- Tombol Terbitkan Permintaan diberi pencegah klik ganda -->
                    <button type="submit"
                            onclick="this.disabled=true; this.form.submit();"
                            class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-semibold transition cursor-pointer disabled:opacity-50">
                        Terbitkan Permintaan
                    </button>
                </form>
            </div>
        @endif

        <!-- Daftar Dokumen yang Diminta & Pengunggahan -->
        <div class="{{ in_array(Auth::user()->role, ['admin', 'auditor']) ? 'lg:col-span-2' : 'lg:col-span-3' }} space-y-4">
            @forelse ($requests as $item)
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                    <div class="flex flex-col sm:flex-row justify-between sm:items-start gap-2 border-b border-slate-100 pb-3">
                        <div>
                            <h4 class="text-md font-bold text-slate-800">{{ $item->request_title }}</h4>
                            <p class="text-xs text-slate-500 mt-0.5">{{ $item->description ?? 'Tidak ada catatan rincian.' }}</p>
                        </div>
                        <div class="text-left sm:text-right shrink-0">
                            <span class="inline-block px-2.5 py-1 text-xs font-semibold rounded-full
                                {{ $item->status === 'requested' ? 'bg-amber-100 text-amber-700' : '' }}
                                {{ $item->status === 'fulfilled' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $item->status === 'revision_needed' ? 'bg-rose-100 text-rose-700' : '' }}">
                                {{ strtoupper(str_replace('_', ' ', $item->status)) }}
                            </span>
                            <p class="text-[11px] text-slate-400 mt-1">Batas: {{ \Carbon\Carbon::parse($item->deadline)->format('d M Y') }}</p>
                        </div>
                    </div>

                    <!-- Riwayat Pengunggahan Berkas -->
                    <div class="space-y-3">
                        <p class="text-xs font-bold text-slate-600 uppercase tracking-wider">Berkas Terunggah:</p>
                        @forelse ($item->submissions as $sub)
                            <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                                <div>
                                    <a href="{{ asset('storage/' . $sub->file_path) }}" target="_blank" class="text-xs font-bold text-indigo-600 hover:underline">
                                        📄 Unduh Berkas Lampiran
                                    </a>
                                    <p class="text-[11px] text-slate-400 mt-0.5">
                                        Oleh: {{ $sub->submitter->name ?? '-' }} ({{ $sub->created_at->format('d/m/Y H:i') }})
                                    </p>
                                    @if ($sub->notes)
                                        <p class="text-xs text-slate-600 mt-1 italic">"{{ $sub->notes }}"</p>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-0.5 text-xs font-semibold rounded
                                        {{ $sub->verification_status === 'accepted' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                        {{ $sub->verification_status === 'rejected' ? 'bg-rose-100 text-rose-700' : '' }}
                                        {{ $sub->verification_status === 'pending' ? 'bg-amber-100 text-amber-700' : '' }}">
                                        {{ ucfirst($sub->verification_status) }}
                                    </span>

                                    <!-- Tombol Verifikasi Auditor -->
                                    @if (in_array(Auth::user()->role, ['admin', 'auditor']) && $sub->verification_status === 'pending')
                                        <form action="{{ route('submissions.verify', $sub) }}" method="POST" class="inline-flex gap-1">
                                            @csrf
                                            <input type="hidden" name="verification_status" value="accepted">
                                            <button type="submit" onclick="this.disabled=true; this.form.submit();" class="px-2 py-1 bg-emerald-600 text-white rounded text-xs font-semibold cursor-pointer disabled:opacity-50">Terima</button>
                                        </form>
                                        <form action="{{ route('submissions.verify', $sub) }}" method="POST" class="inline-flex gap-1">
                                            @csrf
                                            <input type="hidden" name="verification_status" value="rejected">
                                            <button type="submit" onclick="this.disabled=true; this.form.submit();" class="px-2 py-1 bg-rose-600 text-white rounded text-xs font-semibold cursor-pointer disabled:opacity-50">Tolak</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 italic">Belum ada berkas yang diunggah untuk butir ini.</p>
                        @endforelse
                    </div>

                    <!-- Form Unggah Berkas (Khusus Auditee atau jika status butuh revisi) -->
                    @if (in_array(Auth::user()->role, ['admin', 'auditee']))
                        <div class="mt-4 pt-4 border-t border-slate-100">
                            <form action="{{ route('documents.submit', $item) }}" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row gap-3">
                                @csrf
                                <input type="file" name="file" required class="text-xs text-slate-600 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                                <input type="text" name="notes" placeholder="Catatan berkas (opsional)" class="flex-1 px-3 py-1.5 text-xs rounded-lg border border-slate-300 focus:outline-none">
                                <!-- Tombol Unggah Berkas diberi pencegah klik ganda -->
                                <button type="submit"
                                        onclick="this.disabled=true; this.form.submit();"
                                        class="px-4 py-1.5 bg-slate-800 hover:bg-slate-900 text-white rounded-lg text-xs font-semibold shrink-0 cursor-pointer disabled:opacity-50">
                                    Unggah Berkas
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            @empty
                <div class="bg-white p-8 rounded-2xl border border-slate-200 text-center text-slate-400 text-sm">
                    Belum ada permintaan dokumen yang diterbitkan untuk penugasan audit ini.
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

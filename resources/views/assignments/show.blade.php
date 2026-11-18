@extends('layouts.app')
@section('title', 'Ruang Kerja Pemeriksaan')
@section('page_heading', 'Ruang Kerja Pemeriksaan Unit Kerja')

@section('content')
<div class="space-y-6" x-data="{ activeTab: 'documents' }">
    <!-- Informasi Utama Surat Tugas -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="font-mono text-xs font-bold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-lg">{{ $assignment->assignment_number }}</span>
                <span class="text-xs text-slate-400">Unit: <strong class="text-slate-700">{{ $assignment->workUnit->name ?? '-' }}</strong></span>
            </div>
            <h2 class="text-lg font-bold text-slate-800 mt-2">{{ $assignment->title }}</h2>
            <p class="text-xs text-slate-500 mt-0.5">Lingkup: {{ $assignment->scope ?? 'Pemeriksaan operasional dan kepatuhan unit kerja.' }}</p>
        </div>
        <div class="text-right bg-slate-50 p-4 rounded-xl border border-slate-100 shrink-0">
            <p class="text-[10px] uppercase font-bold text-slate-400">Masa Pemeriksaan</p>
            <p class="text-xs font-bold text-slate-700 mt-0.5">
                {{ \Carbon\Carbon::parse($assignment->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($assignment->end_date)->format('d M Y') }}
            </p>
        </div>
    </div>

    <!-- Navigasi Tab Interaktif -->
    <div class="flex border-b border-slate-200 gap-8">
        <button @click="activeTab = 'documents'"
                :class="activeTab === 'documents' ? 'border-indigo-600 text-indigo-600 font-bold' : 'border-transparent text-slate-500 font-medium hover:text-slate-700'"
                class="pb-3 text-xs border-b-2 transition cursor-pointer">
            📁 1. Melengkapi Dokumen Diminta ({{ $assignment->documentRequests->count() }})
        </button>
        <button @click="activeTab = 'findings'"
                :class="activeTab === 'findings' ? 'border-indigo-600 text-indigo-600 font-bold' : 'border-transparent text-slate-500 font-medium hover:text-slate-700'"
                class="pb-3 text-xs border-b-2 transition cursor-pointer">
            🔍 2. Temuan & Tanggapan Unit ({{ $assignment->findings->count() }})
        </button>
        <button @click="activeTab = 'followups'"
                :class="activeTab === 'followups' ? 'border-indigo-600 text-indigo-600 font-bold' : 'border-transparent text-slate-500 font-medium hover:text-slate-700'"
                class="pb-3 text-xs border-b-2 transition cursor-pointer">
            ✅ 3. Rencana Aksi & Bukti Tindak Lanjut
        </button>
    </div>

    <!-- TAB 1: MELENGKAPI DOKUMEN YANG DIMINTA SPI -->
    <div x-show="activeTab === 'documents'" class="space-y-4">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden p-6 space-y-4">
            <h3 class="text-sm font-bold text-slate-800">Daftar Permintaan Dokumen dari Tim Auditor</h3>
            <p class="text-xs text-slate-400">
                @if(Auth::user()->role === 'auditee')
                    Silakan unggah dokumen atau berkas tanggapan sesuai dengan butir permintaan di bawah ini sebelum batas waktu berakhir.
                @else
                    Daftar dokumen yang diminta oleh tim auditor kepada unit kerja beserta berkas yang telah diunggah dan tombol verifikasi.
                @endif
            </p>

            <div class="divide-y divide-slate-100">
                @forelse($assignment->documentRequests as $docReq)
                    <div class="py-4 space-y-3">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                            <div>
                                <div class="flex items-center gap-2">
                                    <h4 class="text-xs font-bold text-slate-800">{{ $docReq->document_name }}</h4>
                                    <span class="px-2 py-0.5 text-[9px] font-black uppercase rounded-full {{ $docReq->status === 'fulfilled' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                        {{ str_replace('_', ' ', $docReq->status) }}
                                    </span>
                                </div>
                                <p class="text-[11px] text-slate-500 mt-0.5">{{ $docReq->description ?? 'Tidak ada catatan tambahan.' }}</p>
                                <p class="text-[10px] text-amber-600 font-semibold mt-1">Batas Waktu: {{ $docReq->deadline }}</p>
                            </div>

                            <!-- Form Unggah Dokumen (Hanya untuk Auditee) -->
                            @if(Auth::user()->role === 'auditee')
                                <form action="{{ route('documents.submit', $docReq->id) }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2 w-full md:w-auto">
                                    @csrf
                                    <input type="file" name="file" required class="text-xs file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                                    <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl transition shrink-0 cursor-pointer">
                                        Unggah Berkas
                                    </button>
                                </form>
                            @endif
                        </div>

                        <!-- Tampilkan Berkas yang Sudah Diunggah oleh Auditee (Lengkap dengan Tombol Verifikasi Auditor) -->
                        @if($docReq->submissions && $docReq->submissions->count() > 0)
                            <div class="bg-slate-50 p-3.5 rounded-2xl border border-slate-100 space-y-3 mt-2">
                                <span class="text-[10px] font-black uppercase text-slate-400 tracking-wider">Berkas Terunggah dari Unit Kerja:</span>
                                @foreach($docReq->submissions as $sub)
                                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 text-xs bg-white p-3 rounded-xl border border-slate-200">
                                        <div class="flex items-center gap-2 truncate">
                                            <span>📄</span>
                                            <div>
                                                <a href="{{ Storage::url($sub->file_path) }}" target="_blank" class="font-bold text-indigo-600 hover:underline">
                                                    {{ $sub->file_name }}
                                                </a>
                                                <p class="text-[10px] text-slate-400">Oleh: {{ $sub->submitter->name ?? 'Unit Kerja' }} • {{ $sub->created_at->format('d M Y H:i') }}</p>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                                            <span class="px-2.5 py-1 text-[9px] font-black uppercase rounded-full
                                                {{ $sub->verification_status === 'accepted' ? 'bg-emerald-100 text-emerald-700' : ($sub->verification_status === 'rejected' ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700') }}">
                                                {{ str_replace('_', ' ', $sub->verification_status) }}
                                            </span>

                                            <!-- Form Aksi Verifikasi (Hanya untuk Auditor/Admin) -->
                                            <!-- Form Aksi Verifikasi (Hanya untuk Auditor/Admin) -->
                                            @if(in_array(Auth::user()->role, ['admin', 'auditor']))
                                                <form action="{{ route('submissions.verify', $sub->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="verification_status" value="accepted">
                                                    <button type="submit" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg transition cursor-pointer text-[10px]">
                                                        Terima
                                                    </button>
                                                </form>
                                                <form action="{{ route('submissions.verify', $sub->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="verification_status" value="rejected">
                                                    <button type="submit" class="px-2.5 py-1 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-lg transition cursor-pointer text-[10px]">
                                                        Revisi
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            @if(Auth::user()->role !== 'auditee')
                                <div class="text-[11px] text-amber-600 font-medium italic">
                                    ⏳ Menunggu unggahan berkas dari unit kerja.
                                </div>
                            @endif
                        @endif
                    </div>
                @empty
                    <p class="text-xs text-slate-400 text-center py-6">Belum ada daftar dokumen yang diminta oleh auditor.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- TAB 2: TEMUAN & TANGGAPAN UNIT -->
    <div x-show="activeTab === 'findings'" class="space-y-4" x-cloak x-data="{ openFindingModal: false }">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden p-6 space-y-4">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-100 pb-4">
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Catatan Temuan Hasil Pemeriksaan</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Kelola unsur temuan (Kondisi, Kriteria, Sebab, Dampak, Rekomendasi) dan tanggapan unit kerja.</p>
                </div>

                <!-- Tombol Tambah Temuan Khusus Auditor / Admin -->
                @if(in_array(Auth::user()->role, ['admin', 'auditor']))
                    <button @click="openFindingModal = true" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl transition shadow-xs cursor-pointer flex items-center gap-2 shrink-0">
                        <span>➕</span> <span>Tambah Temuan Baru</span>
                    </button>
                @endif
            </div>

            <div class="space-y-4">
                @forelse($assignment->findings as $finding)
                    <div class="p-5 rounded-2xl border border-slate-200 bg-slate-50/40 space-y-4">
                        <div class="flex items-center justify-between border-b border-slate-200/60 pb-3">
                            <div>
                                <span class="text-[10px] font-black uppercase px-2.5 py-1 bg-rose-100 text-rose-700 rounded-lg">Temuan Audit</span>
                                <h4 class="text-sm font-bold text-slate-900 mt-1.5">{{ $finding->title ?? 'Temuan Pemeriksaan' }}</h4>
                            </div>
                            <span class="text-[10px] font-semibold text-slate-400">{{ $finding->created_at->format('d M Y') }}</span>
                        </div>

                        <!-- Grid 5 Unsur Temuan -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
                            <div class="p-3 bg-white rounded-xl border border-slate-100 space-y-1">
                                <span class="text-[10px] font-black uppercase text-indigo-600 tracking-wider">1. Kondisi</span>
                                <p class="text-slate-700 leading-relaxed">{{ $finding->condition ?? $finding->description }}</p>
                            </div>
                            <div class="p-3 bg-white rounded-xl border border-slate-100 space-y-1">
                                <span class="text-[10px] font-black uppercase text-indigo-600 tracking-wider">2. Kriteria</span>
                                <p class="text-slate-700 leading-relaxed">{{ $finding->criteria ?? '-' }}</p>
                            </div>
                            <div class="p-3 bg-white rounded-xl border border-slate-100 space-y-1">
                                <span class="text-[10px] font-black uppercase text-amber-600 tracking-wider">3. Sebab</span>
                                <p class="text-slate-700 leading-relaxed">{{ $finding->cause ?? '-' }}</p>
                            </div>
                            <div class="p-3 bg-white rounded-xl border border-slate-100 space-y-1">
                                <span class="text-[10px] font-black uppercase text-rose-600 tracking-wider">4. Dampak</span>
                                <p class="text-slate-700 leading-relaxed">{{ $finding->impact ?? $finding->effect ?? '-' }}</p>
                            </div>
                        </div>

                        <!-- Rekomendasi -->
                        <div class="p-3 bg-emerald-50/50 rounded-xl border border-emerald-100 space-y-1 text-xs">
                            <span class="text-[10px] font-black uppercase text-emerald-700 tracking-wider">5. Rekomendasi Perbaikan</span>
                            <p class="text-emerald-900 font-medium leading-relaxed">
                                @if(isset($finding->recommendations) && $finding->recommendations->count() > 0)
                                    @foreach($finding->recommendations as $rec)
                                        • {{ $rec->recommendation_text ?? $rec->recommendation ?? $rec->content }}<br>
                                    @endforeach
                                @else
                                    {{ $finding->recommendation ?? '-' }}
                                @endif
                            </p>
                        </div>

                        <!-- Tanggapan & Klarifikasi Auditee -->
                        <div class="pt-3 border-t border-slate-200 text-xs space-y-1">
                            <span class="font-bold text-slate-700">Tanggapan Unit Kerja:</span>
                            <p class="text-slate-600 italic">"{{ $finding->auditee_response ?? 'Belum ada tanggapan atau klarifikasi dari unit kerja.' }}"</p>
                        </div>

                        <!-- Form Tanggapan (Jika yang login Auditee) -->
                        @if(Auth::user()->role === 'auditee')
                            <form action="{{ route('findings.response', $finding->id) }}" method="POST" class="pt-2 space-y-2">
                                @csrf
                                <textarea name="response" rows="2" placeholder="Tuliskan klarifikasi atau tanggapan unit kerja..." class="w-full text-xs p-3 rounded-xl border border-slate-200 focus:outline-indigo-600 bg-white" required>{{ $finding->auditee_response ?? '' }}</textarea>
                                <button type="submit" class="px-3.5 py-1.5 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-lg transition cursor-pointer">
                                    Kirim Tanggapan
                                </button>
                            </form>
                        @endif
                    </div>
                @empty
                    <div class="py-12 text-center text-slate-400 space-y-2">
                        <p class="text-2xl">🔍</p>
                        <p class="text-xs font-semibold text-slate-600">Belum ada catatan temuan yang diterbitkan.</p>
                        <p class="text-[11px] text-slate-400">Gunakan tombol "Tambah Temuan Baru" di atas untuk memasukkan butir temuan audit.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Modal Form Tambah Temuan (Alpine.js) dengan 5 Unsur -->
        @if(in_array(Auth::user()->role, ['admin', 'auditor']))
            <div x-show="openFindingModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4 overflow-y-auto">
                <div @click.away="openFindingModal = false" class="bg-white rounded-3xl max-w-2xl w-full p-6 space-y-5 shadow-2xl border border-slate-200 my-8">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <h3 class="text-sm font-black text-slate-900">Tambah Temuan Audit (5 Unsur)</h3>
                        <button @click="openFindingModal = false" class="text-slate-400 hover:text-slate-600 font-bold text-xs cursor-pointer">✕</button>
                    </div>

                    <form action="{{ route('findings.store', $assignment->id) }}" method="POST" class="space-y-4 text-xs">
                        @csrf
                        <div class="space-y-1.5">
                            <label class="font-bold text-slate-700">Judul / Ringkasan Temuan:</label>
                            <input type="text" name="title" placeholder="Contoh: Ketidaksesuaian Prosedur Pengadaan Barang" required class="w-full p-3 rounded-2xl border border-slate-200 focus:outline-indigo-600 bg-slate-50/50">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="font-bold text-slate-700">1. Kondisi (Fakta Lapangan):</label>
                                <textarea name="condition" rows="3" placeholder="Uraikan kondisi nyata di lapangan..." required class="w-full p-3 rounded-2xl border border-slate-200 focus:outline-indigo-600 bg-slate-50/50"></textarea>
                            </div>
                            <div class="space-y-1.5">
                                <label class="font-bold text-slate-700">2. Kriteria (Standar / Regulasi):</label>
                                <textarea name="criteria" rows="3" placeholder="Sebutkan aturan, SOP, atau standar yang berlaku..." required class="w-full p-3 rounded-2xl border border-slate-200 focus:outline-indigo-600 bg-slate-50/50"></textarea>
                            </div>
                            <div class="space-y-1.5">
                                <label class="font-bold text-slate-700">3. Sebab (Penyebab Masalah):</label>
                                <textarea name="cause" rows="3" placeholder="Mengapa kondisi tersebut bisa terjadi..." required class="w-full p-3 rounded-2xl border border-slate-200 focus:outline-indigo-600 bg-slate-50/50"></textarea>
                            </div>
                            <div class="space-y-1.5">
                                <label class="font-bold text-slate-700">4. Dampak (Risiko / Akibat):</label>
                                <textarea name="impact" rows="3" placeholder="Akibat dari penyimpangan tersebut..." required class="w-full p-3 rounded-2xl border border-slate-200 focus:outline-indigo-600 bg-slate-50/50"></textarea>
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="font-bold text-slate-700">5. Rekomendasi Perbaikan:</label>
                            <textarea name="recommendation" rows="2" placeholder="Saran tindakan korektif bagi unit kerja..." required class="w-full p-3 rounded-2xl border border-slate-200 focus:outline-indigo-600 bg-slate-50/50"></textarea>
                        </div>

                        <div class="flex justify-end gap-3 pt-3 border-t border-slate-100">
                            <button type="button" @click="openFindingModal = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-xl transition cursor-pointer">
                                Batal
                            </button>
                            <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition shadow-xs cursor-pointer">
                                Simpan Temuan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>

    <!-- TAB 3: RENCANA AKSI & BUKTI TINDAK LANJUT -->
    <div x-show="activeTab === 'followups'" class="space-y-4" x-cloak>
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden p-6 space-y-4">
            <h3 class="text-sm font-bold text-slate-800">Rencana Aksi & Unggah Bukti Tindak Lanjut Rekomendasi</h3>
            <p class="text-xs text-slate-400">Pantau status rekomendasi dan unggah bukti perbaikan untuk menyelesaikan temuan audit.</p>

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
                            <p class="text-xs font-semibold text-slate-800">{{ $rec->recommendation_text ?? $rec->recommendation ?? $rec->content }}</p>

                            <!-- Form Unggah Bukti Tindak Lanjut (Hanya untuk Auditee) -->
                            @if(Auth::user()->role === 'auditee')
                                <form action="{{ route('followups.store', $rec->id) }}" method="POST" enctype="multipart/form-data" class="pt-3 border-t border-slate-100 flex flex-col sm:flex-row items-center gap-3">
                                    @csrf
                                    <input type="file" name="evidence_file" required class="text-xs w-full sm:w-auto file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                                    <input type="text" name="notes" placeholder="Catatan bukti perbaikan..." class="text-xs p-2 rounded-lg border border-slate-200 w-full focus:outline-indigo-600">
                                    <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg transition shrink-0 cursor-pointer w-full sm:w-auto">
                                        Kirim Bukti
                                    </button>
                                </form>
                            @else
                                <div class="pt-3 border-t border-slate-100 text-xs text-slate-500">
                                    <span class="font-medium text-slate-600">Status Tindak Lanjut: </span>
                                    <span class="italic text-slate-500">Menunggu unggahan bukti perbaikan dari unit kerja.</span>
                                </div>
                            @endif
                        </div>
                    @endforeach
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

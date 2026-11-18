@extends('layouts.app')
@section('title', 'Buat Penugasan Audit')
@section('page_heading', 'Formulir Surat Tugas / Penugasan Audit')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
    <form action="{{ route('assignments.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Nomor Surat Tugas</label>
                <input type="text" name="assignment_number" placeholder="misal: 01/ST-SPI/ITH/2026" required class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Unit Kerja Sasaran (Auditee)</label>
                <select name="work_unit_id" required class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                    <option value="">-- Pilih Unit Kerja --</option>
                    @foreach ($units as $u)
                        <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->unit_code }})</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1">Nama / Agenda Pengawasan</label>
            <input type="text" name="title" placeholder="misal: Audit Kepatuhan Pengelolaan Keuangan Triwulan I" required class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:outline-none">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Tanggal Mulai</label>
                <input type="date" name="start_date" required class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Tanggal Selesai</label>
                <input type="date" name="end_date" required class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:outline-none">
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1">Ruang Lingkup Pemeriksaan</label>
            <textarea name="scope" rows="3" placeholder="Jelaskan batasan atau ruang lingkup pemeriksaan..." class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:outline-none"></textarea>
        </div>

        <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-4">
            <h4 class="text-sm font-bold text-slate-800">Susunan Tim Auditor</h4>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Ketua Tim Auditor</label>
                <select name="leader_id" required class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:outline-none bg-white">
                    <option value="">-- Pilih Ketua Tim --</option>
                    @foreach ($auditors as $auditor)
                        <option value="{{ $auditor->id }}">{{ $auditor->name }} ({{ $auditor->nip ?? 'Tanpa NIP' }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Anggota Tim (Bisa pilih lebih dari satu)</label>
                <select name="member_ids[]" multiple class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:outline-none bg-white h-24">
                    @foreach ($auditors as $auditor)
                        <option value="{{ $auditor->id }}">{{ $auditor->name }}</option>
                    @endforeach
                </select>
                <p class="text-[11px] text-slate-400 mt-1">*Tekan Ctrl (Windows) untuk memilih beberapa auditor sekaligus.</p>
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-4">
            <a href="{{ route('assignments.index') }}" class="px-5 py-2.5 rounded-lg border border-slate-300 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">
                Batal
            </a>
            <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-semibold transition shadow-sm cursor-pointer">
                Simpan Penugasan
            </button>
        </div>
    </form>
</div>
@endsection

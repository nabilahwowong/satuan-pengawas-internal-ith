@extends('layouts.app')
@section('title', 'Rekapitulasi Pemantauan Tindak Lanjut')
@section('page_heading', 'Matriks Pemantauan Tindak Lanjut Rekomendasi')

@section('content')
<div class="space-y-6">
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h3 class="text-sm font-bold text-slate-800">Progres Penyelesaian Rekomendasi Seluruh Unit Kerja</h3>
            <p class="text-xs text-slate-400 mt-0.5">Pemantauan kepatuhan auditee dalam menyelesaikan tindak lanjut hasil pengawasan internal ITH.</p>
        </div>
        <a href="{{ route('monitoring.export') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition shadow-xs">
            <span>📥</span> Unduh Matriks (Excel / CSV)
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 text-slate-400 uppercase border-b border-slate-100">
                <tr>
                    <th class="p-4">Unit Kerja (Auditee)</th>
                    <th class="p-4 text-center">Total Rekomendasi</th>
                    <th class="p-4 text-center">Tuntas (Closed)</th>
                    <th class="p-4 text-center">Verifikasi Auditor</th>
                    <th class="p-4 text-center">Dalam Proses</th>
                    <th class="p-4 text-center">Belum Tindak Lanjut</th>
                    <th class="p-4 text-center">Persentase</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-slate-600">
                @forelse ($units as $u)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="p-4 font-bold text-slate-800">{{ $u['unit_name'] }}</td>
                        <td class="p-4 text-center font-bold">{{ $u['total_recs'] }}</td>
                        <td class="p-4 text-center text-emerald-600 font-bold">{{ $u['closed'] }}</td>
                        <td class="p-4 text-center text-amber-600 font-bold">{{ $u['under_review'] }}</td>
                        <td class="p-4 text-center text-blue-600 font-bold">{{ $u['in_progress'] }}</td>
                        <td class="p-4 text-center text-rose-600 font-bold">{{ $u['open'] }}</td>
                        <td class="p-4 text-center">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-black
                                {{ $u['percentage'] >= 80 ? 'bg-emerald-100 text-emerald-800' : '' }}
                                {{ $u['percentage'] >= 50 && $u['percentage'] < 80 ? 'bg-amber-100 text-amber-800' : '' }}
                                {{ $u['percentage'] < 50 ? 'bg-rose-100 text-rose-800' : '' }}">
                                {{ $u['percentage'] }}%
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-6 text-center text-slate-400">Belum ada data unit kerja.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

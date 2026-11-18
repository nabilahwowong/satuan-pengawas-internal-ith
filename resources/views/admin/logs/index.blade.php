@extends('layouts.app')
@section('title', 'Jejak Audit Sistem')
@section('page_heading', 'Audit Trail & Log Aktivitas Pengguna')

@section('content')
<div class="space-y-6">
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h3 class="text-sm font-bold text-slate-800">Rekam Jejak Aktivitas Pengawasan</h3>
            <p class="text-xs text-slate-400 mt-0.5">Memantau seluruh riwayat perubahan data, pengajuan laporan, dan pengesahan secara kronologis.</p>
        </div>
        <form method="GET" action="{{ route('logs.index') }}" class="flex items-center gap-2">
            <select name="module" onchange="this.form.submit()" class="px-3 py-2 text-xs border border-slate-300 rounded-lg bg-white focus:outline-none">
                <option value="">-- Semua Modul --</option>
                <option value="Penugasan Audit" {{ request('module') === 'Penugasan Audit' ? 'selected' : '' }}>Penugasan Audit</option>
                <option value="Laporan (LHP)" {{ request('module') === 'Laporan (LHP)' ? 'selected' : '' }}>Laporan (LHP)</option>
                <option value="Tindak Lanjut" {{ request('module') === 'Tindak Lanjut' ? 'selected' : '' }}>Tindak Lanjut</option>
            </select>
        </form>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 text-slate-400 uppercase border-b border-slate-100">
                <tr>
                    <th class="p-4">Waktu</th>
                    <th class="p-4">Pengguna</th>
                    <th class="p-4">Modul</th>
                    <th class="p-4">Aksi</th>
                    <th class="p-4">Keterangan Aktivitas</th>
                    <th class="p-4">Alamat IP</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-slate-600">
                @forelse ($logs as $log)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="p-4 whitespace-nowrap font-mono text-[11px] text-slate-500">
                            {{ $log->created_at->format('d/m/Y H:i:s') }}
                        </td>
                        <td class="p-4 font-bold text-slate-800">
                            {{ $log->user?->name ?? 'Sistem' }}
                        </td>
                        <td class="p-4">
                            <span class="px-2 py-0.5 bg-slate-100 text-slate-700 rounded text-[10px] font-semibold">
                                {{ $log->module }}
                            </span>
                        </td>
                        <td class="p-4">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold
                                {{ in_array($log->action, ['APPROVED', 'CREATE']) ? 'bg-emerald-100 text-emerald-700' : '' }}
                                {{ in_array($log->action, ['SUBMIT', 'VERIFY']) ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $log->action === 'REJECTED' ? 'bg-rose-100 text-rose-700' : '' }}">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td class="p-4 text-slate-700">{{ $log->description }}</td>
                        <td class="p-4 font-mono text-[11px] text-slate-400">{{ $log->ip_address ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-6 text-center text-slate-400">Belum ada catatan aktivitas yang terekam.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4 border-t border-slate-100">
            {{ $logs->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')
@section('title', 'Master Unit Kerja')
@section('page_heading', 'Manajemen Unit Kerja (Auditee)')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Form Tambah Unit -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm h-fit">
        <h3 class="text-md font-bold text-slate-800 mb-4">Tambah Unit Kerja Baru</h3>
        <form action="{{ route('admin.units.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Kode Unit</label>
                <input type="text" name="unit_code" placeholder="misal: FTII-01" required
                       class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Unit Kerja</label>
                <input type="text" name="name" placeholder="misal: Bagian Akademik" required
                       class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Pimpinan / Kepala</label>
                <input type="text" name="leader_name" placeholder="misal: Dr. Ir. Budi, M.T."
                       class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">NIP Pimpinan</label>
                <input type="text" name="leader_nip" placeholder="19800101..."
                       class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:outline-none">
            </div>
            <button type="submit" class="w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-semibold transition cursor-pointer">
                Simpan Unit
            </button>
        </form>
    </div>

    <!-- Tabel Daftar Unit -->
    <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-200">
            <h3 class="text-md font-bold text-slate-800">Daftar Unit Kerja Terdaftar</h3>
        </div>
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="bg-slate-50 text-slate-400 text-xs uppercase border-b border-slate-200">
                <tr>
                    <th class="p-4">Kode</th>
                    <th class="p-4">Nama Unit</th>
                    <th class="p-4">Pimpinan</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($units as $unit)
                    <tr class="hover:bg-slate-50">
                        <td class="p-4 font-semibold text-slate-800">{{ $unit->unit_code }}</td>
                        <td class="p-4">{{ $unit->name }}</td>
                        <td class="p-4">
                            <p class="font-medium text-slate-800">{{ $unit->leader_name ?? '-' }}</p>
                            <p class="text-xs text-slate-400">{{ $unit->leader_nip ?? '' }}</p>
                        </td>
                        <td class="p-4 text-center">
                            <form action="{{ route('admin.units.destroy', $unit) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus unit kerja ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-600 hover:text-rose-800 text-xs font-semibold cursor-pointer">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-6 text-center text-slate-400 text-sm">Belum ada unit kerja yang terdaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-slate-100">
            {{ $units->links() }}
        </div>
    </div>
</div>
@endsection

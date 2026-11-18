@extends('layouts.app')
@section('title', 'Kelola Pengguna')
@section('page_heading', 'Manajemen Akun Pengguna SPI')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Form Tambah User -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm h-fit">
        <h3 class="text-md font-bold text-slate-800 mb-4">Tambah Pengguna Baru</h3>
        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Lengkap</label>
                <input type="text" name="name" required class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Email</label>
                <input type="email" name="email" required class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">NIP (Opsional)</label>
                <input type="text" name="nip" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Peran (Role)</label>
                <select name="role" required class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                    <option value="admin">Administrator</option>
                    <option value="auditor">Auditor SPI</option>
                    <option value="auditee">Auditee (Unit Kerja)</option>
                    <option value="approver">Pejabat Penyetuju / Pimpinan</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Unit Kerja (Khusus Auditee)</label>
                <select name="work_unit_id" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                    <option value="">-- Pilih Unit Kerja --</option>
                    @foreach ($units as $u)
                        <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->unit_code }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Kata Sandi Default</label>
                <input type="password" name="password" required placeholder="Minimal 8 karakter" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:outline-none">
            </div>
            <button type="submit" class="w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-semibold transition cursor-pointer">
                Simpan Akun
            </button>
        </form>
    </div>

    <!-- Tabel User -->
    <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-200">
            <h3 class="text-md font-bold text-slate-800">Daftar Akun Pengguna</h3>
        </div>
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="bg-slate-50 text-slate-400 text-xs uppercase border-b border-slate-200">
                <tr>
                    <th class="p-4">Pengguna</th>
                    <th class="p-4">Role</th>
                    <th class="p-4">Unit Kerja</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($users as $user)
                    <tr class="hover:bg-slate-50">
                        <td class="p-4">
                            <p class="font-semibold text-slate-800">{{ $user->name }}</p>
                            <p class="text-xs text-slate-400">{{ $user->email }}</p>
                        </td>
                        <td class="p-4">
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full
                                {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700' : '' }}
                                {{ $user->role === 'auditor' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $user->role === 'auditee' ? 'bg-amber-100 text-amber-700' : '' }}
                                {{ $user->role === 'approver' ? 'bg-emerald-100 text-emerald-700' : '' }}">
                                {{ strtoupper($user->role) }}
                            </span>
                        </td>
                        <td class="p-4 text-xs font-medium text-slate-700">
                            {{ $user->workUnit->name ?? '-' }}
                        </td>
                        <td class="p-4 text-center">
                            @if ($user->id !== Auth::id())
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Hapus akun ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 hover:text-rose-800 text-xs font-semibold cursor-pointer">Hapus</button>
                                </form>
                            @else
                                <span class="text-xs text-slate-400 italic">Akun Anda</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4 border-t border-slate-100">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection

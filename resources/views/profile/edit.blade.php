@extends('layouts.app')
@section('title', 'Pengaturan Profil Akun')
@section('page_heading', 'Profil & Keamanan Akun')

@section('content')
<div class="max-w-4xl space-y-6">
    <!-- Tombol Kembali -->
    <div class="flex items-center justify-between">
        <a href="{{ route(Auth::user()->role . '.dashboard') }}"
           class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-800 transition">
            &larr; Kembali ke Dashboard
        </a>
    </div>

    <!-- Informasi Akun & Role -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-indigo-600 text-white flex items-center justify-center text-xl font-black uppercase">
                {{ substr($user->name, 0, 2) }}
            </div>
            <div>
                <h3 class="text-base font-bold text-slate-800">{{ $user->name }}</h3>
                <p class="text-xs font-mono text-slate-500">{{ $user->email }}</p>
                <div class="flex items-center gap-2 mt-1.5">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-indigo-50 text-indigo-700">
                        Peran: {{ $user->role }}
                    </span>
                    @if ($user->workUnit)
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-600">
                            Unit: {{ $user->workUnit->name }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Form 1: Ubah Identitas Dasar -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs h-fit">
            <h4 class="text-sm font-bold text-slate-800 mb-1">Informasi Identitas</h4>
            <p class="text-xs text-slate-400 mb-4">Perbarui nama lengkap beserta gelar akademik dan nomor induk pegawai.</p>

            <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Lengkap & Gelar</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-1 focus:ring-indigo-600 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">NIP / NIDN</label>
                    <input type="text" name="nip" value="{{ old('nip', $user->nip) }}" placeholder="Nomor Induk Pegawai"
                           class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-1 focus:ring-indigo-600 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Email Resmi Institusi</label>
                    <input type="email" value="{{ $user->email }}" disabled
                           class="w-full px-3 py-2 text-xs border border-slate-200 bg-slate-50 text-slate-400 rounded-lg cursor-not-allowed">
                    <p class="text-[10px] text-slate-400 mt-1">*Email resmi dikelola oleh Administrator SPI.</p>
                </div>

                <button type="submit" class="w-full py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-lg text-xs font-bold transition cursor-pointer">
                    Simpan Perubahan Profil
                </button>
            </form>
        </div>

        <!-- Form 2: Ubah Kata Sandi -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs h-fit">
            <h4 class="text-sm font-bold text-slate-800 mb-1">Keamanan Kata Sandi</h4>
            <p class="text-xs text-slate-400 mb-4">Ganti kata sandi bawaan secara berkala demi menjaga kerahasiaan data audit.</p>

            <form action="{{ route('profile.password') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Kata Sandi Saat Ini</label>
                    <input type="password" name="current_password" required placeholder="Masukkan kata sandi lama"
                           class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-1 focus:ring-indigo-600 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Kata Sandi Baru</label>
                    <input type="password" name="password" required placeholder="Minimal 8 karakter"
                           class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-1 focus:ring-indigo-600 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Konfirmasi Kata Sandi Baru</label>
                    <input type="password" name="password_confirmation" required placeholder="Ulangi kata sandi baru"
                           class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-1 focus:ring-indigo-600 focus:outline-none">
                </div>

                <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold transition cursor-pointer">
                    Perbarui Kata Sandi
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman profil & pengaturan akun pengguna yang sedang login
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Memperbarui informasi dasar profil (Nama & NIP)
     */
    public function update(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nip' => ['nullable', 'string', 'max:50', 'unique:users,nip,' . $user->id],
        ]);

        $user->update($validated);

        ActivityLog::record(
            'UPDATE',
            'Profil Akun',
            "Memperbarui data profil pribadi ({$user->name})."
        );

        return back()->with('success', 'Informasi profil berhasil diperbarui.');
    }

    /**
     * Memperbarui kata sandi pengguna secara aman
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'current_password.current_password' => 'Kata sandi saat ini tidak sesuai.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
            'password.min' => 'Kata sandi baru minimal harus terdiri dari 8 karakter.',
        ]);

        /** @var User $user */
        $user = Auth::user();
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        ActivityLog::record(
            'SECURITY',
            'Profil Akun',
            "Mengubah kata sandi akun ({$user->email})."
        );

        return back()->with('success', 'Kata sandi Anda berhasil diubah.');
    }
}
